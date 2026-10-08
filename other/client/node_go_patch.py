"""Pinned 3.9.42 controller/node integration with an independent node issuer."""
import base64, hashlib, json, struct, urllib.parse
from go_key_patch import CODE_OFFSET, DATA_OFFSET, CODE_ORIGINAL, DATA_ORIGINAL, patch as master_key_patch

MAGIC = b'ZJMF_NODE_STATION_V1\x00'
ORIGINAL = {
    'master': 'bba4c3291a4772f2c7a25307c28b0144a87dc8bc77c7756bd790de93d3217093',
    'node': '7b108403c61bc44e29a730d3754857878fede793b79ddf28e14fc6bd7964180e',
}

def original_binary(data, role):
    if role not in ORIGINAL:
        raise RuntimeError('Unsupported integration role')
    if data.endswith(MAGIC):
        trailer = len(data) - len(MAGIC)
        length = struct.unpack_from('<I', data, trailer - 4)[0]
        if not 0 < length < 20000:
            raise RuntimeError('Invalid integration metadata')
        metadata = json.loads(data[trailer - 4 - length:trailer - 4])
        if metadata.get('role') != role:
            raise RuntimeError('Integration role mismatch')
        restored = bytearray(data[:metadata['original_size']])
        for item in metadata['spans']:
            value = base64.b64decode(item['original'])
            restored[item['offset']:item['offset'] + len(value)] = value
        data = bytes(restored)
    if role == 'master':
        restored = bytearray(data)
        restored[CODE_OFFSET:CODE_OFFSET + 30] = CODE_ORIGINAL
        restored[DATA_OFFSET:DATA_OFFSET + 273] = DATA_ORIGINAL
        data = bytes(restored)
    if hashlib.sha256(data).hexdigest() != ORIGINAL[role]:
        raise RuntimeError('Unsupported %s executable; no files changed' % role)
    return data

def endpoint(station):
    parsed = urllib.parse.urlsplit(station)
    if parsed.scheme not in ('http', 'https') or not parsed.hostname or parsed.username or parsed.password or parsed.query or parsed.fragment:
        raise RuntimeError('Invalid authorization station URL')
    value = (station.rstrip('/') + '/app/api/node_authorize').encode('ascii')
    if len(value) > 1024:
        raise RuntimeError('Authorization endpoint is too long')
    return value

def integrate(data, public, station, role, master_public=None):
    original = original_binary(data, role)
    data = bytearray(master_key_patch(original, master_public) if role == 'master' else original)
    public = public.strip() + b'\n'
    if not public.startswith(b'-----BEGIN PUBLIC KEY-----\n') or len(public) != 451:
        raise RuntimeError('Expected the station 2048-bit node public key')
    url = endpoint(station)
    phoff = struct.unpack_from('<Q', data, 32)[0]
    phsize, count = struct.unpack_from('<HH', data, 54)
    if phsize != 56 or data[:6] != b'\x7fELF\x02\x01':
        raise RuntimeError('Unsupported ELF program header')
    next_header = phoff + phsize * count
    if any(data[next_header:next_header + phsize]):
        raise RuntimeError('No unused ELF program header slot')
    headers = [struct.unpack_from('<IIQQQQQQ', data, phoff + i * phsize) for i in range(count)]
    maximum = max(row[3] + row[6] for row in headers if row[0] == 1)
    address = (maximum + 4095) & ~4095
    offset = (len(data) + 4095) & ~4095
    payload = public + b'\x00' + url + b'\x00'
    public_address = address
    url_address = address + len(public) + 1
    spans = []

    def change(position, value):
        spans.append({'offset': position, 'original': base64.b64encode(data[position:position + len(value)]).decode()})
        data[position:position + len(value)] = value

    def file_offset(virtual):
        for row in headers:
            if row[0] == 1 and row[3] <= virtual < row[3] + row[5]:
                return row[2] + virtual - row[3]
        raise RuntimeError('Unmapped integration location')

    change(56, struct.pack('<H', count + 1))
    change(next_header, struct.pack('<IIQQQQQQ', 1, 4, offset, address, address, len(payload), len(payload), 4096))
    for index, row in enumerate(headers):
        if row[0] == 6:
            change(phoff + index * phsize + 32, struct.pack('<QQ', (count + 1) * phsize, (count + 1) * phsize))
    if role == 'node':
        location = 0x1345300
        code = b'\x48\x8d\x05' + struct.pack('<i', public_address - location - 7) + b'\xbb' + struct.pack('<I', len(public)) + b'\xc3'
        change(file_offset(location), code)
    else:
        location = 0x1340f94
        code = b'\x48\x8d\x05' + struct.pack('<i', public_address - location - 7) + b'\xbb' + struct.pack('<I', len(public))
        change(file_offset(location), code + b'\x90' * (16 - len(code)))
        location = 0x115650f
        code = b'\x48\x8d\x35' + struct.pack('<i', url_address - location - 7) + b'\x41\xb8' + struct.pack('<I', len(url))
        change(file_offset(location), code + b'\x90' * (16 - len(code)))
    data.extend(b'\x00' * (offset - len(data)))
    data.extend(payload)
    metadata = json.dumps({'role': role, 'original_size': len(original), 'spans': spans}, separators=(',', ':')).encode()
    data.extend(metadata + struct.pack('<I', len(metadata)) + MAGIC)
    if original_binary(bytes(data), role) != original:
        raise RuntimeError('Integration reversibility verification failed')
    return bytes(data)
