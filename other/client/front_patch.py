#!/usr/bin/env python3
import hashlib,pathlib,sys
EXPECTED='d2e78de6e21e8cc0f701293d3a457738d551f153bcd730ddeb2f778abef65882'
BEFORE=[b'b.interceptors.response.use((function(e){return l.finish()',b'p.interceptors.response.use((function(e){m.finish();']
ADD=b'if(204===e.status&&/\\/(?:nodes|clouds)(?:\\?|$)/.test(e.config.url))e.data={data:[],meta:{total:0,total_page:0,page:1,per_page:20}};'
AFTER=[BEFORE[0].replace(b'{return',b'{'+ADD+b'return'),BEFORE[1].replace(b'{m.finish',b'{'+ADD+b'm.finish')]
def patch(data):
    original=data
    for old,new in zip(BEFORE,AFTER):original=original.replace(new,old)
    if hashlib.sha256(original).hexdigest()!=EXPECTED:raise RuntimeError('Unsupported frontend; nothing changed')
    for old,new in zip(BEFORE,AFTER):
        if original.count(old)!=1:raise RuntimeError('Unexpected response adapter')
        original=original.replace(old,new)
    return original
if __name__=='__main__':
    p=pathlib.Path(sys.argv[1]);pathlib.Path(sys.argv[2]).write_bytes(patch(p.read_bytes()));print('EMPTY_COLLECTION_FRONTEND_FIXED')
