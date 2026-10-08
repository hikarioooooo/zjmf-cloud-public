"""Pin one official installer build and redirect its license-check call only.

The resulting ELF reserves a 1024-byte URL field. The bootstrap fills it from
its own source site before execution. The optional test variant exits on the
successful license-check return, before any master installation can start.
"""
import hashlib,json,pathlib,struct,sys

ORIGINAL_SHA256='00c54d2034b7021e626cc809f0048c57e7edfd01e039e6a7e4d0024b0ac355e1'
REQUEST_CALL=0xbeea78
POST_FORM=0xbdb020
SUCCESS_EPILOGUE=0xbefb69
CAPACITY=1024

def relative(opcode,address,target):
    delta=target-address-5
    if not -(1<<31)<=delta<(1<<31):raise ValueError('Branch out of range')
    return bytes([opcode])+struct.pack('<i',delta)

def build(original):
    if hashlib.sha256(original).hexdigest()!=ORIGINAL_SHA256:raise ValueError('Unsupported official installer build')
    assert original[:6]==b'\x7fELF\x02\x01'
    data=bytearray(original)
    phoff=struct.unpack_from('<Q',data,32)[0]
    phsize,count=struct.unpack_from('<HH',data,54)
    assert phsize==56
    headers=[list(struct.unpack_from('<IIQQQQQQ',data,phoff+i*phsize)) for i in range(count)]
    def offset(address):
        for p in headers:
            if p[0]==1 and p[3]<=address<p[3]+p[5]:return p[2]+address-p[3]
        raise ValueError('Address outside file')
    request_offset=offset(REQUEST_CALL)
    assert data[request_offset:request_offset+5]==relative(0xe8,REQUEST_CALL,POST_FORM)
    epilogue_offset=offset(SUCCESS_EPILOGUE)
    assert data[epilogue_offset:epilogue_offset+9]==bytes.fromhex('4881c4b00300005dc3')
    align=lambda n:(n+4095)&~4095
    file_base=align(len(data))
    virtual_base=align(max(p[3]+p[6] for p in headers if p[0]==1))
    code_address=virtual_base+0x200
    exit_address=virtual_base+0x240
    message_address=virtual_base+0x280
    url_address=virtual_base+0x400
    segment=bytearray(0x800)
    # Preserve RCX (the form-data map). Replace only the URL pointer and length.
    code=bytearray(b'\x48\x8d\x05'+struct.pack('<i',url_address-code_address-7))
    code+=b'\x31\xdb'                     # xor ebx,ebx
    code+=b'\x80\x3c\x18\x00'           # cmp byte [rax+rbx],0
    code+=b'\x74\x04\xff\xc3\xeb\xf6'  # count until NUL
    code+=relative(0xe9,code_address+len(code),POST_FORM)
    segment[0x200:0x200+len(code)]=code
    message=b'LICENSE_PREFLIGHT_PASSED_STOPPED_BEFORE_INSTALLATION\n'
    stop=bytearray(bytes.fromhex('b801000000bf01000000'))
    lea_address=exit_address+len(stop)
    stop+=b'\x48\x8d\x35'+struct.pack('<i',message_address-lea_address-7)
    stop+=b'\xba'+struct.pack('<I',len(message))+b'\x0f\x05'
    stop+=bytes.fromhex('b8e700000031ff0f050f0b')
    segment[0x240:0x240+len(stop)]=stop
    segment[0x280:0x280+len(message)]=message
    placeholder=b'__ZJMF_INSTALL_AUTH_URL__'
    segment[0x400:0x400+len(placeholder)]=placeholder
    table_size=(count+1)*phsize
    assert table_size<=0x200
    for p in headers:
        if p[0]==6:p[2]=file_base;p[3]=p[4]=virtual_base;p[5]=p[6]=table_size
    headers.append([1,5,file_base,virtual_base,virtual_base,len(segment),len(segment),4096])
    for i,p in enumerate(headers):struct.pack_into('<IIQQQQQQ',segment,i*phsize,*p)
    struct.pack_into('<Q',data,32,file_base)
    struct.pack_into('<H',data,56,count+1)
    data[request_offset:request_offset+5]=relative(0xe8,REQUEST_CALL,code_address)
    data.extend(bytes(file_base-len(data)));data.extend(segment)
    test_patch=relative(0xe9,SUCCESS_EPILOGUE,exit_address)+b'\x90\x90'
    metadata={
        'original_unpacked_sha256':ORIGINAL_SHA256,
        'template_sha256':hashlib.sha256(data).hexdigest(),
        'url_offset':file_base+0x400,'url_capacity':CAPACITY,
        'test_offset':epilogue_offset,
        'test_patch_octal':''.join('\\%03o'%b for b in test_patch),
        'request_call_virtual_address':hex(REQUEST_CALL),
        'successful_return_virtual_address':hex(SUCCESS_EPILOGUE),
    }
    return bytes(data),metadata

if __name__=='__main__':
    destination=pathlib.Path(sys.argv[2])
    result,metadata=build(pathlib.Path(sys.argv[1]).read_bytes())
    destination.write_bytes(result)
    pathlib.Path(sys.argv[3]).write_text(json.dumps(metadata,indent=2),encoding='utf-8')
    print(json.dumps(metadata,indent=2))
