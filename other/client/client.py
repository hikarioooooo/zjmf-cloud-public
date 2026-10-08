#!/usr/bin/env python3
"""Cloud 3.9.42 / PHP 7.2.24 NTS client deployment for an independent station."""
import argparse, configparser, getpass, hashlib, json, os, pathlib, pwd, re
import shutil, stat, subprocess, sys, tempfile, time, urllib.error, urllib.parse, urllib.request
from node_go_patch import integrate as integrate_go
from front_patch import patch as patch_front

HERE = pathlib.Path(__file__).resolve().parent
APP = pathlib.Path('/home/zjmf/dashboard/www')
CACHE = pathlib.Path('/home/zjmf/share/zjmf-web/license')
STATE = pathlib.Path('/opt/zjmf-client')
KEYS = pathlib.Path('/opt/zjmf-license/keys')
SO = pathlib.Path('/usr/lib64/php/modules/zjmf_cloud.so')
OLD_SO = SO.with_name('idcsmart.so')
PHPINI = pathlib.Path('/etc/php.ini')
GO = APP/'cloudgo'
FRONT = APP/'public/js/app.b7d31b0c.js'
INI = pathlib.Path('/etc/php.d/40-idcsmart.ini')
CHECK_MD5 = 'bc8cfd735124c54215c5afeeb6dac644'
EXT_MD5 = '277e61f87c5b280ed2a036cd3bdefcad'
OFFSET = 765315
SUCCESS = b"#!/bin/sh\nprintf 'success\\n'\n"
PLUGINS = ['AutoMount','BackupTimeLimit','BootScript','DbRemoteBackup','DiskCleaner',
           'DiskIoLimit','FlowStatistics','GoogleAuth','Mikrotik','Whitelist']

def run(args, env=None, capture=False):
    result = subprocess.run([str(x) for x in args], env=env,
                            stdout=subprocess.PIPE if capture else None,
                            stderr=subprocess.PIPE if capture else None)
    if result.returncode:
        detail = ''
        if capture and result.stderr:
            detail = result.stderr.decode('utf-8', errors='replace').strip()[-2000:]
            for name, value in (env or os.environ).items():
                if value and re.search(r'PASSWORD|TOKEN|SECRET', name, re.I):
                    detail = detail.replace(value, '[REDACTED]')
        raise RuntimeError('Command failed: ' + str(args[0]) + ' (exit ' + str(result.returncode) + ')' + (': ' + detail if detail else ''))
    return result.stdout.decode('utf-8', errors='replace') if capture else ''

def request(url, data=None, headers=None, allow_unlicensed_login=False):
    if not url.startswith(('https://','http://')):
        raise RuntimeError('Only HTTP(S) URLs are supported')
    body = None if data is None else json.dumps(data, ensure_ascii=False).encode()
    h = {'Content-Type':'application/json','think-lang':'zh-cn'}
    h.update(headers or {})
    try:
        with urllib.request.urlopen(urllib.request.Request(url, data=body, headers=h), timeout=45) as response:
            raw = response.read(2 * 1024 * 1024)
            return json.loads(raw.decode()) if raw else None
    except urllib.error.HTTPError as error:
        # A fresh 3.9.42 master issues a valid login token with HTTP 405 while
        # its first signed license is missing. Other login errors remain fatal.
        if not allow_unlicensed_login or error.code != 405:
            raise
        try:
            result = json.loads(error.read(2 * 1024 * 1024).decode())
        except (ValueError, UnicodeError):
            raise RuntimeError('Invalid unlicensed login response')
        if not isinstance(result, dict) or result.get('error') != '授权错误' or not isinstance(result.get('token'), str) or not result['token']:
            raise RuntimeError('Backend login did not issue an authenticated token')
        return result

def station_url(url):
    p=urllib.parse.urlparse(url)
    if p.scheme not in ('http','https') or not p.netloc or p.username or p.password or p.query or p.fragment:
        raise RuntimeError('Invalid authorization station URL')
    return url.rstrip('/')+'/'

def native_license_identity_valid(value):
    # The native installer stores the entered identity verbatim. Preserve it;
    # its authenticated cache envelope does not require an MD5-shaped token.
    if not isinstance(value, str) or not 0 < len(value.encode('utf-8')) <= 256:
        return False
    # Its line reader can retain terminal backspace bytes. Ignore these only
    # when validating the token; keep the exact identity for all signed data.
    visible = value.replace('\x08', '').replace('\x7f', '')
    return bool(visible) and re.search(r'[\x00-\x20\x7f]', visible) is None

def preflight():
    if os.geteuid()!=0: raise RuntimeError('Run as root')
    for name in ['php','php-fpm','systemctl']:
        if not shutil.which(name): raise RuntimeError('Required command missing: '+name)
    if os.uname().machine!='x86_64': raise RuntimeError('Only x86_64 is supported')
    release=(APP/'.build-meta/release.env').read_text()
    if not re.search(r'^VERSION=3\.9\.42\s*$',release,re.M):
        raise RuntimeError('This package only supports Cloud 3.9.42; no local files changed')
    php=run(['php','-r','echo PHP_VERSION," ",PHP_ZTS," ",PHP_INT_SIZE;'],capture=True).strip()
    if php!='7.2.24 0 8': raise RuntimeError('Requires PHP 7.2.24 NTS x86_64')
    checker=APP/'extend/other/check_main'; ext=APP/'extend/other/extension'
    data=checker.read_bytes()
    if len(data)<OFFSET+5: raise RuntimeError('Unsupported check_main')
    original=bytearray(data)
    if original[OFFSET:OFFSET+5]==b'\x90'*5: original[OFFSET:OFFSET+5]=b'\xe8\x18\x05\x00\x00'
    if hashlib.md5(original).hexdigest()!=CHECK_MD5 or original[OFFSET:OFFSET+5]!=b'\xe8\x18\x05\x00\x00':
        raise RuntimeError('check_main version mismatch; no local files changed')
    contents=ext.read_bytes()
    if contents!=SUCCESS and hashlib.md5(contents).hexdigest()!=EXT_MD5:
        raise RuntimeError('extension version mismatch; no local files changed')
    if not CACHE.is_file(): raise RuntimeError('Complete original installation first; authorization cache missing')
    return checker,ext

def isolated_php(stage):
    scans=stage/'php.d';scans.mkdir()
    for p in pathlib.Path('/etc/php.d').glob('*.ini'):
        text=p.read_text(errors='replace')
        text='\n'.join(line for line in text.splitlines() if not re.search(r'^\s*(zend_)?extension\s*=.*(idcsmart|zjmf_cloud|zjmf_license|zjmf_probe)',line,re.I))
        (scans/p.name).write_text(text+'\n')
    original=pathlib.Path('/etc/php.ini').read_text(errors='replace')
    original='\n'.join(line for line in original.splitlines() if not re.search(r'^\s*(zend_)?extension\s*=.*(idcsmart|zjmf_cloud|zjmf_license|zjmf_probe)',line,re.I))
    ini=stage/'php.ini';ini.write_text(original+'\n')
    env=dict(os.environ);env['PHP_INI_SCAN_DIR']=str(scans)
    return ['php','-c',str(ini)],env

def atomic_write(path,data,mode=0o644,owner=None):
    path.parent.mkdir(parents=True,exist_ok=True)
    fd,tmp=tempfile.mkstemp(prefix='.zjmf-new-',dir=str(path.parent))
    try:
        with os.fdopen(fd,'wb') as f:f.write(data)
        os.chmod(tmp,mode)
        if owner is not None:os.chown(tmp,owner[0],owner[1])
        os.replace(tmp,str(path))
    finally:
        if os.path.exists(tmp):os.unlink(tmp)

def snapshot(paths):
    directory=STATE/'backups'/time.strftime('%Y%m%d-%H%M%S')
    directory.mkdir(parents=True,mode=0o700,exist_ok=False)
    rows=[]
    for index,path in enumerate(paths):
        row={'path':str(path),'exists':path.exists(),'backup':str(index)}
        if path.exists():
            st=path.stat();row.update(mode=stat.S_IMODE(st.st_mode),uid=st.st_uid,gid=st.st_gid)
            shutil.copyfile(str(path),str(directory/str(index)));os.chmod(str(directory/str(index)),0o600)
        rows.append(row)
    (directory/'files.json').write_text(json.dumps(rows,indent=2));os.chmod(str(directory/'files.json'),0o600)
    return directory,rows

def restore(directory,rows):
    for row in rows:
        path=pathlib.Path(row['path'])
        if row['exists']:atomic_write(path,(directory/row['backup']).read_bytes(),row['mode'],(row['uid'],row['gid']))
        elif path.is_file():path.unlink()
    run(['systemctl','restart','php-fpm','cloudgo','cloudgo-worker'])

def database_backup(directory):
    c=configparser.ConfigParser(interpolation=None)
    c.read_string('[app]\n'+(APP/'.env').read_text())
    section=next((c[s] for s in c.sections() if s.lower()=='database'),None)
    if section is None:raise RuntimeError('Database configuration missing')
    def get(*names,**kw):
        for name in names:
            if name in section:return section[name].strip().strip('\"\'')
        return kw.get('default','')
    dump=pathlib.Path('/home/zjmf/mariadb-5.5/bin/mysqldump')
    if not dump.is_file():raise RuntimeError('Verified mysqldump path unavailable')
    args=[str(dump),'--single-transaction','--routines','--triggers',
          '--host='+get('hostname','host',default='localhost'),
          '--port='+get('hostport','port',default='3306'),
          '--user='+get('username','user',default='root'),get('database','dbname')]
    env=dict(os.environ);env['MYSQL_PWD']=get('password')
    with (directory/'database.sql').open('wb') as f:
        result=subprocess.run(args,env=env,stdout=f,stderr=subprocess.PIPE)
    if result.returncode:raise RuntimeError('Database backup failed; installation stopped')
    os.chmod(str(directory/'database.sql'),0o600)

def installer_credentials(log):
    if not log:
        return None
    path=pathlib.Path(log)
    info=path.lstat()
    if not stat.S_ISREG(info.st_mode) or info.st_uid != 0 or stat.S_IMODE(info.st_mode) & 0o077:
        raise RuntimeError('Installer credential log must be a private root-owned file')
    text=re.sub(r'\x1b\[[0-?]*[ -/]*[@-~]', '', path.read_text(errors='replace'))
    users=re.findall(r'^\s*#?\s*Username\s*:\s*(\S+)\s*$', text, re.M)
    passwords=re.findall(r'^\s*#?\s*Password\s*:\s*(\S+)\s*$', text, re.M)
    if users and passwords:
        return users[-1],passwords[-1]
    return None

def backend_login(backend, installer_log=''):
    username=os.environ.get('ZJMF_BACKEND_USER','admin')
    password=os.environ.get('ZJMF_BACKEND_PASSWORD')
    google=os.environ.get('ZJMF_BACKEND_GOOGLE','')
    if password is None:
        automatic=installer_credentials(installer_log)
        if automatic:
            username,password=automatic
            print('已读取本次安装生成的后台账号，继续授权初始化。')
    if not backend:
        adminpath=pathlib.Path('/home/zjmf/share/zjmf-web/admin_path').read_text().strip().strip('/')
        if not re.fullmatch('[A-Za-z0-9_-]+',adminpath):raise RuntimeError('Invalid backend path')
        backend='http://127.0.0.1/'+adminpath+'/'
    if password is None:
        with open('/dev/tty','r+') as tty:
            tty.write('后台账号 [admin]: ');tty.flush();username=tty.readline().strip() or 'admin'
            password=getpass.getpass('后台密码: ',stream=tty)
            tty.write('谷歌验证码（未绑定直接回车）: ');tty.flush();google=tty.readline().strip()
    base=station_url(backend)+'v1'
    result=request(base+'/login',{'username':username,'password':password,'customfield':{'google_code':google}},allow_unlicensed_login=True)
    password=None
    token=result if isinstance(result,str) else result.get('token') if isinstance(result,dict) else None
    if not token and isinstance(result,dict):token=(result.get('data') or {}).get('token')
    if not token:raise RuntimeError('Backend login failed; rerun with --existing to retry')
    headers={'access-token':token}
    return base,headers

def original_identity(base,headers,php,env):
    try:
        response=request(base+'/authorize_info',headers=headers)
        original=response['data']
        if not isinstance(original,dict) or not original.get('license'):
            raise RuntimeError('Original backend did not return a license identity')
        return original
    except urllib.error.HTTPError as error:
        if error.code not in (400,405):
            raise
        try:result=json.loads(error.read(2 * 1024 * 1024).decode())
        except (ValueError,UnicodeError):raise RuntimeError('Invalid initial authorization response')
        if not isinstance(result,dict) or result.get('error') != '授权错误':
            raise RuntimeError('Unexpected initial authorization error')
    # Obtain the actual installer-generated identity from its validated outer
    # envelope. The first signed license does not exist yet on a fresh master.
    raw=run(php+[str(HERE/'initial_identity.php'),str(CACHE),str(HERE/'target.pem')],env=env,capture=True)
    original=json.loads(raw)
    if not isinstance(original,dict) or not native_license_identity_valid(original.get('license')):
        raise RuntimeError('Installer-generated license identity is missing or invalid')
    print('FRESH_INSTALLATION_IDENTITY_FOUND: first signed authorization will be initialized')
    return original

def plugin_setup(base, headers, report, skip):
    auth=request(base+'/authorize_info',headers=headers)['data']
    if auth.get('edition')!=1 or auth.get('max_node')!=9999 or auth.get('hyperv_max')!=9999:
        raise RuntimeError('Backend entitlement verification failed')
    if auth.get('auth_due_time')!='2038-12-31 23:59:59':raise RuntimeError('Backend expiry verification failed')
    report['backend_verified']=True
    if skip:
        report['plugins_skipped']=True
        print('CLIENT_AND_AUTH_VERIFIED: plugin installation skipped')
        return
    installed=request(base+'/plugin',headers=headers)['data']
    found={p['name']:p for p in installed}
    if set(PLUGINS)-set(found):raise RuntimeError('Some expected plugins are missing from original installation')
    for name in PLUGINS:
        if found[name]['status']==3:request(base+'/plugin/'+name,{},headers)
        elif found[name]['status']!=1:raise RuntimeError('Plugin is disabled; enable it in backend: '+name)
    plugins=request(base+'/plugin',headers=headers)['data']
    if not all(any(p['name']==name and p['status']==1 for p in plugins) for name in PLUGINS):
        raise RuntimeError('Backend plugin verification failed')
    report['backend_verified']=True
    report['plugins']=[{'name':p['name'],'status':p['status']} for p in plugins if p['name'] in PLUGINS]

def install(args):
    checker,ext=preflight();url=station_url(args.auth_url)
    health=request(url+'client-config')
    if health.get('status')!=200 or health.get('version')!='3.9.42' or health.get('quota')!=9999:
        raise RuntimeError('Authorization station policy mismatch')
    public=health['public_key'].encode()
    if hashlib.sha256(public).hexdigest()!=health['public_key_sha256']:raise RuntimeError('Public key transfer mismatch')
    node_public=health.get('node_public_key','').encode()
    if health.get('node_authorization_version')!=1 or hashlib.sha256(node_public).hexdigest()!=health.get('node_public_key_sha256'):
        raise RuntimeError('请先将自建授权站 index.php 更新到支持 3.9.42 节点授权的配套版本')
    with tempfile.TemporaryDirectory(prefix='zjmf-client-') as temp:
        stage=pathlib.Path(temp);php,env=isolated_php(stage)
        pub=stage/'public.pem';pub.write_bytes(public)
        base,headers=backend_login(args.backend_url,args.installer_log)
        original=original_identity(base,headers,php,env)
        common=request(base+'/common_config',headers=headers)
        if not original.get('license'):raise RuntimeError('Original backend did not return a license identity')
        baseline=dict(original)
        baseline.update(type='cloud',install_version='3.9.42',domain='',installation_path=str(APP),app=common.get('auth_app',[]))
        baselineFile=stage/'baseline.json'
        baselineFile.write_text(json.dumps(baseline));os.chmod(str(baselineFile),0o600)
        # Both keys exist before loading the extension into this isolated CLI process.
        testargs=php+['-d','extension='+str(HERE/'idcsmart.so'),'-d','idcsmart.url='+url,
                      '-d','zjmf_license.public_key='+str(pub),'-d','zjmf_license.target_key='+str(HERE/'target.pem'),
                      str(HERE/'selftest.php'),str(baselineFile)]
        run(testargs,env=env)
        go_data=integrate_go(GO.read_bytes(),node_public,url,'master',public)
        front_data=patch_front(FRONT.read_bytes())
        if args.check:
            print('PREFLIGHT_PASSED: no installed files or backend plugins changed')
            return
        seed={'license':baseline['license'],'domain':baseline.get('domain',''),'baseline':baseline}
        if request(url+'app/api/bootstrap',seed).get('status')!=200:raise RuntimeError('Baseline registration failed')
        signed=request(url+'app/api/auth_update',baseline)
        if signed.get('status')!=200:raise RuntimeError('Authorization issue failed')
        newcache=stage/'issued-protocol';newcache.write_text(signed['data'])
        verified=stage/'verified.json'
        run(php+[str(HERE/'license_tool.php'),str(newcache),str(pub),str(verified)],env=env,capture=True)
        record=json.loads(verified.read_text())
        if record.get('max_node')!=9999 or record.get('license')!=baseline['license']:
            raise RuntimeError('Issued record failed verification')
        STATE.mkdir(mode=0o700,parents=True,exist_ok=True);os.chmod(str(STATE),0o700)
        tools=STATE/'tools';tools.mkdir(mode=0o700,exist_ok=True)
        for source in HERE.iterdir():
            if source.is_file():atomic_write(tools/source.name,source.read_bytes(),0o600)
        paths=[SO,OLD_SO,INI,PHPINI,GO,FRONT,checker,ext,CACHE,KEYS/'public.pem',KEYS/'target.pem',KEYS/'legacy-public.pem']
        backup,rows=snapshot(paths);database_backup(backup)
        account=pwd.getpwnam('nginx')
        try:
            if not KEYS.parent.exists():
                KEYS.parent.mkdir(mode=0o750,parents=True)
                os.chown(str(KEYS.parent),0,account.pw_gid)
            if not KEYS.exists():
                KEYS.mkdir(mode=0o750)
                os.chown(str(KEYS),0,account.pw_gid)
            legacy=KEYS/'public.pem'
            legacyLine=''
            if legacy.exists() and legacy.read_bytes()!=public:
                atomic_write(KEYS/'legacy-public.pem',legacy.read_bytes())
                legacyLine='zjmf_license.legacy_public_key='+str(KEYS/'legacy-public.pem')+'\n'
            elif (KEYS/'legacy-public.pem').exists():
                legacyLine='zjmf_license.legacy_public_key='+str(KEYS/'legacy-public.pem')+'\n'
            atomic_write(KEYS/'public.pem',public)
            atomic_write(KEYS/'target.pem',(HERE/'target.pem').read_bytes())
            atomic_write(SO,(HERE/'idcsmart.so').read_bytes())
            if OLD_SO.exists():OLD_SO.unlink()
            go_stat=GO.stat();atomic_write(GO,go_data,stat.S_IMODE(go_stat.st_mode),(go_stat.st_uid,go_stat.st_gid))
            front_stat=FRONT.stat();atomic_write(FRONT,front_data,stat.S_IMODE(front_stat.st_mode),(front_stat.st_uid,front_stat.st_gid))
            phpconfig=PHPINI.read_text()
            match=re.search(r'^disable_functions\s*=([^\n]*)$',phpconfig,re.M)
            if not match:raise RuntimeError('PHP disable_functions setting missing')
            # CloudController sleeps when phpinfo is disabled (Issue #127).
            # Keep every other disabled function and leave phpinfo available.
            functions=[v.strip() for v in match.group(1).split(',') if v.strip() and v.strip().lower()!='phpinfo']
            phpconfig=phpconfig[:match.start()]+'disable_functions = '+','.join(functions)+phpconfig[match.end():]
            phpconfig=re.sub(r'^\s*idcsmart\.url\s*=.*$', '',phpconfig,flags=re.M|re.I)
            phpconfig=phpconfig.rstrip()+'\n\nidcsmart.url='+url+'\n'
            atomic_write(PHPINI,phpconfig.encode())
            data=bytearray(checker.read_bytes());data[OFFSET:OFFSET+5]=b'\x90'*5
            atomic_write(checker,bytes(data),0o755)
            atomic_write(ext,SUCCESS,0o755)
            atomic_write(INI,('extension=zjmf_cloud.so\n'+legacyLine).encode())
            run(['php-fpm','-t']);run(['systemctl','restart','php-fpm','cloudgo','cloudgo-worker'])
            request(base+'/authorize',headers=headers)
            auth=request(base+'/authorize_info',headers=headers)['data']
            if auth.get('edition')!=1 or auth.get('max_node')!=9999 or auth.get('hyperv_max')!=9999 or auth.get('auth_due_time')!='2038-12-31 23:59:59':
                raise RuntimeError('Original backend cache refresh failed')
            run(['php',str(HERE/'selftest.php'),str(baselineFile)])
            stores=request(base+'/stores?type=ceph&usage=disk',headers=headers)
            if not isinstance(stores,dict) or not isinstance(stores.get('data'),list):
                raise RuntimeError('Go service stores verification failed')
            print('GO_SERVICE_AND_EMPTY_COLLECTION_FRONTEND_VERIFIED')
        except Exception:
            restore(backup,rows)
            print('Local files restored. Backup: '+str(backup),file=sys.stderr)
            raise
        report={'version':'3.9.42','quota':9999,'expires':'2038-12-31 23:59:59',
                'station':url,'backup':str(backup),'backend_verified':False}
        reportFile=STATE/'last-install.json'
        try:plugin_setup(base,headers,report,args.skip_plugins)
        finally:reportFile.write_text(json.dumps(report,ensure_ascii=False,indent=2));os.chmod(str(reportFile),0o600)
        if args.skip_plugins:return
        print('INSTALLATION_VERIFIED: professional edition / 2038-12-31 / quota 9999 / ten enabled plugins')

def main():
    parser=argparse.ArgumentParser()
    parser.add_argument('--auth-url',required=True)
    parser.add_argument('--check',action='store_true')
    parser.add_argument('--skip-plugins',action='store_true')
    parser.add_argument('--backend-url',default='')
    parser.add_argument('--installer-log',default='')
    args=parser.parse_args()
    try:install(args)
    except Exception as e:
        print('STOPPED: '+str(e),file=sys.stderr)
        sys.exit(1)

if __name__=='__main__':main()
