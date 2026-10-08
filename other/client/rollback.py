#!/usr/bin/env python3
from pathlib import Path
import json, os, sys
import client

if os.geteuid()!=0 or len(sys.argv)!=2:
    raise SystemExit('Usage as root: python3 /opt/zjmf-client/tools/rollback.py /opt/zjmf-client/backups/TIMESTAMP')
folder=Path(sys.argv[1]).resolve()
base=Path('/opt/zjmf-client/backups').resolve()
if folder.parent!=base or not folder.is_dir():raise SystemExit('Unknown backup directory')
rows=json.loads((folder/'files.json').read_text())
allowed={str(client.SO),str(client.OLD_SO),str(client.INI),str(client.PHPINI),str(client.GO),str(client.FRONT),str(client.CACHE),str(client.APP/'extend/other/check_main'),
         str(client.APP/'extend/other/extension'),str(client.KEYS/'public.pem'),str(client.KEYS/'target.pem'),
         str(client.KEYS/'legacy-public.pem')}
for row in rows:
    if row['path'] not in allowed or '/' in row['backup'] or row['backup'] in ('.','..'):
        raise SystemExit('Unexpected restore target')
client.restore(folder,rows)
print('LOCAL_FILES_RESTORED: database and installed plugins were not reverted')
