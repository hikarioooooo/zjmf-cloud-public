#!/usr/bin/env python3
"""Prepare the native 3.9.42 compute service for its controller's node issuer."""
import argparse, hashlib, json, os, pathlib, shutil, subprocess, sys, tempfile, time, urllib.request, urllib.parse
from node_go_patch import integrate

PROGRAM=pathlib.Path('/usr/local/zjmf/bin/cloud-control')
VERSION=pathlib.Path('/usr/local/zjmf/php/version.txt')
STATE=pathlib.Path('/opt/zjmf-node-client')

def run(argv):
    result=subprocess.run(argv,stdout=subprocess.PIPE,stderr=subprocess.PIPE)
    if result.returncode:raise RuntimeError('Command failed: '+argv[0])
    return result.stdout

def station(url):
    parsed=urllib.parse.urlsplit(url)
    if parsed.scheme not in ('http','https') or not parsed.hostname or parsed.username or parsed.password or parsed.query or parsed.fragment:
        raise RuntimeError('Invalid authorization station address')
    return url.rstrip('/')+'/'

def write(path,data,mode=0o600,owner=None):
    path.parent.mkdir(mode=0o700,parents=True,exist_ok=True)
    fd,name=tempfile.mkstemp(prefix='.node-station-',dir=str(path.parent))
    try:
        with os.fdopen(fd,'wb') as output:output.write(data);output.flush();os.fsync(output.fileno())
        os.chmod(name,mode)
        if owner:os.chown(name,owner[0],owner[1])
        os.replace(name,str(path))
    finally:
        if os.path.exists(name):os.unlink(name)

def install(args):
    if os.geteuid()!=0:raise RuntimeError('Run as root')
    if not PROGRAM.is_file() or not VERSION.is_file() or VERSION.read_text().strip()!='3.9.42':
        raise RuntimeError('Only the native 3.9.42 compute service is supported')
    url=station(args.auth_url)
    with urllib.request.urlopen(url+'client-config',timeout=25) as response:config=json.loads(response.read(262144))
    public=config.get('node_public_key','').encode()
    if config.get('version')!='3.9.42' or config.get('node_authorization_version')!=1 or hashlib.sha256(public).hexdigest()!=config.get('node_public_key_sha256'):
        raise RuntimeError('请先更新自建授权站 index.php，使其支持 3.9.42 独立节点授权')
    patched=integrate(PROGRAM.read_bytes(),public,url,'node')
    run(['systemctl','is-active','--quiet','cloud-control'])
    if args.check:
        print('NODE_PREFLIGHT_PASSED: compute version / service / station / pinned integration verified; no files changed')
        return
    STATE.mkdir(mode=0o700,parents=True,exist_ok=True);os.chmod(str(STATE),0o700)
    backup=STATE/'backups'/('node-auth-'+time.strftime('%Y%m%d-%H%M%S'))
    backup.mkdir(mode=0o700,parents=True,exist_ok=False)
    shutil.copy2(str(PROGRAM),str(backup/'cloud-control'))
    stat=PROGRAM.stat()
    report={'version':'3.9.42','station':url,'node_public_key_sha256':config['node_public_key_sha256'],'license_mode':'controller_proxy','backup':str(backup),'sha256':hashlib.sha256(patched).hexdigest()}
    try:
        write(PROGRAM,patched,stat.st_mode&0o777,(stat.st_uid,stat.st_gid))
        run(['systemctl','restart','cloud-control'])
        run(['systemctl','is-active','--quiet','cloud-control'])
    except Exception:
        write(PROGRAM,(backup/'cloud-control').read_bytes(),stat.st_mode&0o777,(stat.st_uid,stat.st_gid))
        run(['systemctl','restart','cloud-control'])
        raise
    write(STATE/'node-public.pem',public)
    write(STATE/'last-install.json',json.dumps(report,ensure_ascii=False,indent=2).encode())
    print('NODE_CLIENT_PREPARED: 计算服务已适配你的自建授权站；加入使用同一授权站的主控后，由主控下发绑定授权。')

def main():
    parser=argparse.ArgumentParser();parser.add_argument('--auth-url',required=True);parser.add_argument('--check',action='store_true')
    try:install(parser.parse_args())
    except Exception as error:print('STOPPED: '+str(error),file=sys.stderr);sys.exit(1)

if __name__=='__main__':main()
