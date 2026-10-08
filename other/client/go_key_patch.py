#!/usr/bin/env python3
"""Replace only the Cloud 3.9.42 Go license verifier's trusted public key."""
import base64,hashlib,pathlib,struct,sys
ORIGINAL_SHA='bba4c3291a4772f2c7a25307c28b0144a87dc8bc77c7756bd790de93d3217093'
CODE_OFFSET=15949180
DATA_OFFSET=29357235
CODE_ORIGINAL=base64.b64decode('SIs1fSyBAUiLdkBIiz5IidC7EQEAAEiJ2UiJ8v/X')
DATA_ORIGINAL=base64.b64decode('bkVh7/iiXzI9YNd5G732kNNyfr1J8QRvkEjKxUkbbfBH16hms68qYNI2SSq0OJU/VhAWxIAR/P4CecLjlf8T+I1HlsIy1W2A4YrrM5BvSxoo4v1VEXTNZTn13x937tVkdnPMhS3tqdKrNnzs9W7B+WVAI+30MqBzTVPYrqbpjVG9hVo3wCMa12aFmpnpPjELbTq5VZUyHMbR/AnVf3/6uTb5VhjS5pk5U5uDxFgF7sKnw8CiixKBQhrt3x7xHb2ilxeQgnVOa10HO/v35I3rE4asLH6lKabSlM11w6YX8339Iaqck8rronrad0UOmrzOy4MND9C1D0clYPExqVxdgGK20l98i2s52dNRwEDW/un4')
CODE_VA=0x1335d7c
DATA_VA=0x1fff4b3
def patch(data,public):
    original=bytearray(data)
    original[CODE_OFFSET:CODE_OFFSET+30]=CODE_ORIGINAL
    original[DATA_OFFSET:DATA_OFFSET+273]=DATA_ORIGINAL
    if hashlib.sha256(original).hexdigest()!=ORIGINAL_SHA:
        raise RuntimeError('Unsupported cloudgo binary; nothing changed')
    public=public.strip()+b'\n'
    if len(public)!=272 or not public.startswith(b'-----BEGIN PUBLIC KEY-----\n'):
        raise RuntimeError('Expected a 1024-bit SPKI PEM public key')
    code=b'\x48\x8d\x05'+struct.pack('<i',DATA_VA-(CODE_VA+7))+b'\xbb'+struct.pack('<I',len(public))
    original[CODE_OFFSET:CODE_OFFSET+30]=code+b'\x90'*(30-len(code))
    original[DATA_OFFSET:DATA_OFFSET+273]=public+b'\n'
    return bytes(original)
if __name__=='__main__':
    if len(sys.argv)!=4: raise SystemExit('Usage: go_key_patch.py ORIGINAL PUBLIC_KEY OUTPUT')
    result=patch(pathlib.Path(sys.argv[1]).read_bytes(),pathlib.Path(sys.argv[2]).read_bytes())
    pathlib.Path(sys.argv[3]).write_bytes(result)
    print('GO_KEY_PATCH_VERIFIED '+hashlib.sha256(result).hexdigest())
