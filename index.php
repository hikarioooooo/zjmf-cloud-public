<?php
declare(strict_types=1);
date_default_timezone_set('Asia/Shanghai');
if(PHP_SAPI==='cli-server')$_SERVER['SCRIPT_NAME']='/index.php';
const IS_MIRROR = true;
const PRIVATE_KEY = '-----BEGIN RSA PRIVATE KEY-----
MIICXAIBAAKBgQDpbVaEEZ4VITRZr/BUHOGa+076M4GrZIlAsZeHB7lTlvLslUtR
TIE33XhCEuVqaxIUUyTUGCEMnzcgbs7c0xwnzAWervSqzZlS85r3mmxNuKDvF0wC
jVoHaQ21lgnMmoqbkfP+f7aqiw/35SZSi2Rl4IAtZucz2nz+f4Sy+1pS1QIDAQAB
AoGAPO9B6m/+6F0moVMAVbTEYATCdSYE74zrF2xEtgcaJev9tiyy4KIsCT1TK0xr
fwA8U/nwXz19QyI87cZ/Ub36VxmGT6TiigvA2lNy9tZ96LuN0gqIMQpfp/xFbqqb
4t+97N4H+IhVQywte/gMa0XF6Cmo93MZPCzcLaZgP0X8fYECQQD8THNkbnzNSee/
28+eXLkRIKjTMajqIOfVMlZV6gz1mD+VJVYbANgXQfgOnD1RtkOFp3KJml1fu9RZ
lYzceelNAkEA7NoDFJ83EJt2oUMaMjfvQMBqSyHl9ZLAOsV7JG7LgE2wYj++1aE9
RaWJALs76zFOtsRc1MpdXIXAOfUMxL0LqQJABnay8hy+h8ff7xNjk0wO1bh/esGn
8S+coOKkQZk4ccZPwrNtLE3uO1JOV5l7HK/NtQvgLFRPFhfKzey96hwZdQJBAKF8
JvuJblbBWEms4ZB5uIMybYZaT1p2ut+XQ1VcwRzyWx8xjSBEde0lZtp7zeeWT6+n
BBAFBVCO1LfvTsxYhDkCQE/5czWCytCwM0OViFjK1trDXwhshbh48BZvnqkwaQLc
6AxQB9ouGLXuqsV12k5opHHf7zr/SMdBzpseqowaADQ=
-----END RSA PRIVATE KEY-----
';
const PUBLIC_KEY = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDpbVaEEZ4VITRZr/BUHOGa+076
M4GrZIlAsZeHB7lTlvLslUtRTIE33XhCEuVqaxIUUyTUGCEMnzcgbs7c0xwnzAWe
rvSqzZlS85r3mmxNuKDvF0wCjVoHaQ21lgnMmoqbkfP+f7aqiw/35SZSi2Rl4IAt
Zucz2nz+f4Sy+1pS1QIDAQAB
-----END PUBLIC KEY-----
';
const NODE_PRIVATE_KEY = '-----BEGIN RSA PRIVATE KEY-----
MIIEogIBAAKCAQEAujw1bL2qBOcYFiLQ5sMZdGkJOq3k60/vWIJD9RPPA9yN7Fei
J8T17Wj5I7uiB/aD/g6d4beZU9lnfe/CFviRPJFpEqrEjCge2j8IEG19lWSRI+Au
/CSt/+7yURRWUxhXwd2Wwoa673tjfFhHVTpjc/ivdCUA/Y/JvUxnHiAg8HMB0E4F
AFKr84edeTiN1o/Lq8sVeq6gBoSgPYLWTaokyKIMcpcfJggDi9DnU82cCEz9Lkbu
T967YfjyTFmOKUpl6ciF/aVxhyNNLw4Y7RJoH+xq4h5j0zhJcdU6+qhNlprtMN9j
FfrTPsNfKPqntHWZi3lyRBmPpm4o1aL4TXF8zwIDAQABAoIBAAXrE+eOFMZtxvOC
d7uy8i7OMl+NrHtYjEqhGSJuAWN7XCc+lwXx2b7fBHaA5/fzeuOqoz8hDoZTn65T
iDTNVrrjWWt2cc3ibdiRrY1CwtLp4eg59O7zsMk0AWSs43Fl5060iVif33ZCKDne
CaZJDLq1acikbxD1GNF4iW5eUa2nwWcrOZvypjwU+P4bJnwv+0SFZvM7d3Ad3B7y
0Ia1JjuYjkV1nlHOTVT5FaGTju1ALIjr51iu4EUsg8GVFw5zcHWiLiHCZnFWHb/b
ImpKbKSsmqh4qBUQns60WPnJWCmrLCBReGqGLDCFt3gcRrnoKiwa4rIBmZ+/wLIv
xOns4gECgYEA6Jw/e46ig8DBAVxA9ovNDB12O92j6yKjTtcFeQ5znQBTZ0UW4Nwa
SapxXmpzgXUpFdZQOG6xor/b1Pg2UqpCpkuz+Hk38cCF1zSP38Wr8EGGAqCADPLA
4Odx4DvSiD3DkdVc0+3+kmEultuCjZm+5moFZ8R8Z8EwhzX1U4nSye8CgYEAzPYx
L7uSY1sG4zEbvRGWPItMjnkEJoTTr4oFdaTIxVB8oiTUBP9H+RFsp2VvrP7DpKsq
y5rrv/YYFV4x53nOhSPR4J5B4P8W2oeo4keVePaAtk0C5roHfEpo91QFD2JDzAOh
m8bOhYhzWXNcDjMbJ/Ps0DUBqtf1yq+uvM/a2yECgYASwSZNK/7maJAnL+z88+Cg
bW+u+/vPZYeNP8DtNcEUk4Yl+WgS0Sw9bESfvC177ppVbGYjZqlj2dw7m5elqpyd
E9V63ysnjsI7y70d6a4nPOE1LQOmB5yNhZuk3K3o1jICfBVz8OhpnPRIrYIlMJQ3
t6yf5TOymdzzyeHxzlb0eQKBgDYLgOLGQg6C1SkZLOhI9+WVEaXL0UVa5vq1mUTx
I6Or2oFi1qlOqyrI5m6pd64VK3+DRvCTNDDU8nrH8L7JxqQi0te4w0RR3zPWa7jn
CUnxLfVkDyzJxumGMXFuLTtmPNxR5M5PuOtLFKd0nMR9w15gmoQ4Re1Hrt84Pgo5
gMshAoGAUIetM01jjideeabJooDoz0UQrSCmaz33GDJc+xmekR5uV+U5GpFu4lSP
kv+hKSOFVBxLnmOj2Ge6eJjSxnAjIsJDtm02DQkfl9CQk+rXTbcGMtG4HAQMUsnq
WiQ8PNWz9zEgPq5Dtrx/KVNRXXI0779ZSmdhKqpSwoNNU5qiv5s=
-----END RSA PRIVATE KEY-----
';
const NODE_PUBLIC_KEY = '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAujw1bL2qBOcYFiLQ5sMZ
dGkJOq3k60/vWIJD9RPPA9yN7FeiJ8T17Wj5I7uiB/aD/g6d4beZU9lnfe/CFviR
PJFpEqrEjCge2j8IEG19lWSRI+Au/CSt/+7yURRWUxhXwd2Wwoa673tjfFhHVTpj
c/ivdCUA/Y/JvUxnHiAg8HMB0E4FAFKr84edeTiN1o/Lq8sVeq6gBoSgPYLWTaok
yKIMcpcfJggDi9DnU82cCEz9LkbuT967YfjyTFmOKUpl6ciF/aVxhyNNLw4Y7RJo
H+xq4h5j0zhJcdU6+qhNlprtMN9jFfrTPsNfKPqntHWZi3lyRBmPpm4o1aL4TXF8
zwIDAQAB
-----END PUBLIC KEY-----
';



function stream_codec(string $input, string $mode, string $secret): string
{
    $root = md5($secret);
    $left = md5(substr($root, 0, 16));
    $right = md5(substr($root, 16, 16));
    $nonce = $mode === 'decode' ? substr($input, 0, 4) : substr(bin2hex(random_bytes(4)), 0, 4);
    $streamKey = $left . md5($left . $nonce);
    if ($mode === 'decode') {
        $input = base64_decode(substr($input, 4));
        if ($input === false) { throw new RuntimeException('Invalid encoded input'); }
    } else {
        $input = '0000000000' . substr(md5($input . $right), 0, 16) . $input;
    }
    $state = range(0, 255);
    $j = 0;
    for ($i = 0; $i < 256; $i++) {
        $j = ($j + $state[$i] + ord($streamKey[$i % strlen($streamKey)])) & 255;
        $temp = $state[$i]; $state[$i] = $state[$j]; $state[$j] = $temp;
    }
    $i = $j = 0; $output = '';
    for ($position = 0; $position < strlen($input); $position++) {
        $i = ($i + 1) & 255; $j = ($j + $state[$i]) & 255;
        $temp = $state[$i]; $state[$i] = $state[$j]; $state[$j] = $temp;
        $output .= chr(ord($input[$position]) ^ $state[($state[$i] + $state[$j]) & 255]);
    }
    if ($mode !== 'decode') { return $nonce . rtrim(base64_encode($output), '='); }
    $expiry = (int)substr($output, 0, 10);
    $plain = substr($output, 26);
    if (($expiry !== 0 && $expiry <= time()) || !hash_equals(substr($output, 10, 16), substr(md5($plain . $right), 0, 16))) {
        throw new RuntimeException('Envelope validation failed');
    }
    return $plain;
}

function sign_license(string $plain, string $privateKey): string
{
    $key = openssl_pkey_get_private($privateKey);
    if ($key === false) { throw new RuntimeException('Invalid signing key'); }
    $pieces = [];
    for ($offset = 0; $offset < strlen($plain); $offset += 100) {
        if (!openssl_private_encrypt(substr($plain, $offset, 100), $signed, $key, OPENSSL_PKCS1_PADDING)) {
            throw new RuntimeException('RSA operation failed');
        }
        $pieces[] = base64_encode($signed);
    }
    return stream_codec(implode('|zjmf|', $pieces), 'encode', 'zjmf_key_strcode');
}

function read_license(string $encoded, string $publicKey): string
{
    $blocks = explode('|zjmf|', stream_codec($encoded, 'decode', 'zjmf_key_strcode'));
    $key = openssl_pkey_get_public($publicKey);
    if ($key === false) { throw new RuntimeException('Invalid verification key'); }
    $plain = '';
    foreach ($blocks as $block) {
        if (!openssl_public_decrypt(base64_decode($block), $piece, $key, OPENSSL_PKCS1_PADDING)) {
            throw new RuntimeException('RSA verification failed');
        }
        $plain .= $piece;
    }
    return $plain;
}

$policy=[
    'version' => '3.9.42',
    'expires' => '2038-12-31 23:59:59',
    'quota' => 9999,


    'apps' => [
        'gpupass', 'float_ip', 'random_port', 'index_msg_show', 'evacuation_setting',
        'cloud_cron_snap', 'security_rule_lock', 'net_queues', 'AbuseManager',
        'SmartBw', 'SmartCpu', 'LocalMigrate', 'abuse_monitor', 'advanced_cpu',
        'advanced_bw', 'AppDatabase', 'app_database', 'mysql_database', 'ceph_storage',
        'AppPreset', 'app_preset', 'CrossMigration', 'cross_migration',
    ],
    'plugins' => [
        'AutoMount', 'BootScript', 'Whitelist', 'DiskIoLimit', 'GoogleAuth',
        'BackupTimeLimit', 'DbRemoteBackup', 'DiskCleaner', 'FlowStatistics', 'Mikrotik',
    ],
    'goods' => [
        ['id' => 140, 'name' => 'Cloud', 'desc' => '魔方云'],
        ['id' => 952, 'name' => 'Service', 'desc' => '节点维护'],
        ['id' => 957, 'name' => 'SmartCpu', 'desc' => '智能CPU'],
        ['id' => 958, 'name' => 'SmartBw', 'desc' => '智能带宽'],
        ['id' => 960, 'name' => 'LocalMigrate', 'desc' => '本地存储热迁移'],
        ['id' => 962, 'name' => 'AbuseManager', 'desc' => '滥用管理'],
        ['id' => 2238, 'name' => 'AppDatabase', 'desc' => 'Mysql数据库'],
        ['id' => 2281, 'name' => 'CephStorage', 'desc' => 'CEPH存储'],
        ['id' => 90001, 'name' => 'AppPreset', 'desc' => '应用预设'],
        ['id' => 90002, 'name' => 'CrossMigration', 'desc' => '跨主控迁移'],
    ],
];
$imageCatalog=json_decode(<<<'CATALOG'
{"images":[{"id":1,"name":"CentOS-7.6.1810-x64.qcow2","version":"3.0.0","support_version":"3.3.8","detailed":"{\"image_group_id\":2,\"name\":\"CentOS-7.6.1810-x64\"}","support_init":1,"status":1,"md5":"e7a1c07403b6b6065dd7bae6164daf5b","is_charge":0},{"id":2,"name":"CentOS-8.1.1911-x64.qcow2","version":"3.0.0","support_version":"3.3.8","detailed":"{\"image_group_id\":2,\"name\":\"CentOS-8.1.1911-x64\"}","support_init":1,"status":1,"md5":"ab10ab98751a98fff2b24b5eab446f14","is_charge":0},{"id":3,"name":"Debian-9.12.1-x64.qcow2","version":"3.0.0","support_version":"3.3.8","detailed":"{\"image_group_id\":4,\"name\":\"Debian-9.12.1-x64\"}","support_init":1,"status":1,"md5":"572884cdf406aaadd4e527948af5f665","is_charge":0},{"id":4,"name":"Debian-10.3.3-x64.qcow2","version":"3.0.0","support_version":"3.3.8","detailed":"{\"image_group_id\":4,\"name\":\"Debian-10.3.3-x64\"}","support_init":1,"status":1,"md5":"04d32e3732b709748351bbf95ac243ca","is_charge":0},{"id":6,"name":"Ubuntu-16.04-x64.qcow2","version":"3.0.0","support_version":"3.3.8","detailed":"{\"image_group_id\":3,\"name\":\"Ubuntu-16.04-x64\"}","support_init":1,"status":1,"md5":"428b90f87675631e6b79d2a2156c8f69","is_charge":0},{"id":7,"name":"Ubuntu-18.04-x64.qcow2","version":"3.0.0","support_version":"3.3.8","detailed":"{\"image_group_id\":3,\"name\":\"Ubuntu-18.04-x64\"}","support_init":1,"status":1,"md5":"b4c2f4e059a04e6034d85ee0bad40b1f","is_charge":0},{"id":8,"name":"Windows-2008R2-Datacenter-cn.qcow2","version":"3.0.1","support_version":"3.3.8","detailed":"{\"image_group_id\":1,\"name\":\"Windows-2008R2-Datacenter-cn\"}","support_init":1,"status":1,"md5":"507e226909679bc6083dc1232b3a3c36","is_charge":0},{"id":9,"name":"Windows-2012R2-Datacenter-cn.qcow2","version":"3.0.1","support_version":"3.3.8","detailed":"{\"image_group_id\":1,\"name\":\"Windows-2012R2-Datacenter-cn\"}","support_init":1,"status":1,"md5":"d40fdf098231a0a2ddd7fe4ae5f78e12","is_charge":0},{"id":10,"name":"Windows-2016-Datacenter-cn.qcow2","version":"3.0.1","support_version":"3.3.8","detailed":"{\"image_group_id\":1,\"name\":\"Windows-2016-Datacenter-cn\"}","support_init":1,"status":1,"md5":"1b7a3f0360d9362ea9cfff98caf38a02","is_charge":0},{"id":11,"name":"Windows-2019-Datacenter-cn.qcow2","version":"3.0.1","support_version":"3.3.8","detailed":"{\"image_group_id\":1,\"name\":\"Windows-2019-Datacenter-cn\"}","support_init":1,"status":1,"md5":"e081cbdf2504e5e83963e68975529c85","is_charge":0},{"id":13,"name":"CentOS-7.0.1406-x64.qcow2","version":"2.0.6","support_version":"2.1.8","detailed":"{\"image_group_id\":2,\"name\":\"CentOS-7.0.1406-x64\"}","support_init":0,"status":1,"md5":"44c35dfeace99d9da3816b507f920676","is_charge":0},{"id":14,"name":"CentOS-7.1.1503-x64.qcow2","version":"3.0.0","support_version":"3.3.8","detailed":"{\"image_group_id\":2,\"name\":\"CentOS-7.1.1503-x64\"}","support_init":1,"status":1,"md5":"52e71010f96be37125b09245652f618f","is_charge":0},{"id":15,"name":"CentOS-7.2.1511-x64.qcow2","version":"3.0.0","support_version":"3.3.8","detailed":"{\"image_group_id\":2,\"name\":\"CentOS-7.2.1511-x64\"}","support_init":1,"status":1,"md5":"ee25444d0ed063d1976198a7f4f03558","is_charge":0},{"id":16,"name":"CentOS-7.3.1611-x64.qcow2","version":"3.0.0","support_version":"3.3.8","detailed":"{\"image_group_id\":2,\"name\":\"CentOS-7.3.1611-x64\"}","support_init":1,"status":1,"md5":"18d34aa16e66e34e895637ce43207a16","is_charge":0},{"id":17,"name":"CentOS-7.4.1708-x64.qcow2","version":"3.0.0","support_version":"3.3.8","detailed":"{\"image_group_id\":2,\"name\":\"CentOS-7.4.1708-x64\"}","support_init":1,"status":1,"md5":"85026119da6455cffd270c5ddc3faad6","is_charge":0},{"id":18,"name":"CentOS-7.5.1804-x64.qcow2","version":"3.0.0","support_version":"3.3.8","detailed":"{\"image_group_id\":2,\"name\":\"CentOS-7.5.1804-x64\"}","support_init":1,"status":1,"md5":"33792f18e2acc9230eb9a4e023ebc8be","is_charge":0},{"id":19,"name":"CentOS-7.7.1908-x64.qcow2","version":"3.0.1","support_version":"3.3.8","detailed":"{\"image_group_id\":2,\"name\":\"CentOS-7.7.1908-x64\"}","support_init":1,"status":1,"md5":"2c8954be31d9203ac9413b8070b1fb3e","is_charge":0},{"id":20,"name":"CentOS-7.8.2003-x64.qcow2","version":"3.0.0","support_version":"3.3.8","detailed":"{\"image_group_id\":2,\"name\":\"CentOS-7.8.2003-x64\"}","support_init":1,"status":1,"md5":"a1c7f4b717dfb846c2728dee9483c3e3","is_charge":0},{"id":21,"name":"CentOS-7.8.2003-x64-BT.qcow2","version":"2.0.6","support_version":"2.1.8","detailed":"{\"image_group_id\":2,\"name\":\"CentOS-7.8.2003-x64-BT\"}","support_init":0,"status":1,"md5":"abe96f59db92b277f02640fb3a7aa190","is_charge":0},{"id":22,"name":"CentOS-8.0.1905-x64.qcow2","version":"2.0.5","support_version":"2.1.8","detailed":"{\"image_group_id\":2,\"name\":\"CentOS-8.0.1905-x64\"}","support_init":0,"status":1,"md5":"2ea85ff647e71282bcfbc8a4c200d69a","is_charge":0},{"id":23,"name":"CentOS-8.2.2004-x64.qcow2","version":"3.0.0","support_version":"3.3.8","detailed":"{\"image_group_id\":2,\"name\":\"CentOS-8.2.2004-x64\"}","support_init":1,"status":1,"md5":"f2cd9d9e25dc9cf1ff49f980ecec3129","is_charge":0},{"id":32,"name":"Linux_Rescue.qcow2","version":"3.0.0","support_version":"3.6.11","detailed":"{\"image_group_id\":2,\"name\":\"Linux_Rescue\"}","support_init":1,"status":1,"md5":"c25ddb07cd1788004277eef0896da438","is_charge":0},{"id":33,"name":"Windows_Rescue.qcow2","version":"3.0.0","support_version":"3.6.11","detailed":"{\"image_group_id\":1,\"name\":\"Windows_Rescue\"}","support_init":1,"status":1,"md5":"15f5f01d514f2e295d6718c69361fde1","is_charge":0},{"id":34,"name":"CentOS-6.8.1607-x64.qcow2","version":"3.0.2","support_version":"3.6.13","detailed":"{\"image_group_id\":2,\"name\":\"CentOS-6.8.1607-x64\"}","support_init":1,"status":1,"md5":"4bdc5b833ef80285899622e026d2a9f6","is_charge":0},{"id":35,"name":"CentOS-6.10.1907-x64.qcow2","version":"3.0.2","support_version":"3.6.13","detailed":"{\"image_group_id\":2,\"name\":\"CentOS-6.10.1907-x64\"}","support_init":1,"status":1,"md5":"4c361567ba7c7a6d8e4beedfe5acfea7","is_charge":0},{"id":36,"name":"Windows-2003-Enterprise-cn.qcow2","version":"3.0.3","support_version":"3.5.11","detailed":"{\"image_group_id\":1,\"name\":\"Windows-2003-Enterprise-cn\"}","support_init":1,"status":1,"md5":"d4627eb139882299c7f3b378c9fc3ad2","is_charge":0},{"id":37,"name":"Windows7_enterprise-cn.qcow2","version":"3.0.1","support_version":"3.3.8","detailed":"{\"image_group_id\":1,\"name\":\"Windows7_enterprise-cn\"}","support_init":1,"status":1,"md5":"7739d62ccc891b449b323052b252b3e0","is_charge":0},{"id":38,"name":"Windows10-cn.qcow2","version":"3.0.2","support_version":"3.3.8","detailed":"{\"image_group_id\":1,\"name\":\"Windows10-cn\"}","support_init":1,"status":1,"md5":"017ae0c8f52df3ba02a905fdfd1656ec","is_charge":0},{"id":39,"name":"Ubuntu-20.04.1-x64.qcow2","version":"3.0.0","support_version":"3.3.8","detailed":"{\"image_group_id\":3,\"name\":\"Ubuntu-20.04.1-x64\"}","support_init":1,"status":1,"md5":"42df79c8d99bf4072408e3873dc4a842","is_charge":0},{"id":40,"name":"CentOS-5.9-x64.qcow2","version":"3.0.0","support_version":"3.5.11","detailed":"{\"image_group_id\":2,\"name\":\"CentOS-5.9-x64\"}","support_init":1,"status":1,"md5":"f4c88eeb6991c95e112963e181a5115f","is_charge":0},{"id":41,"name":"CentOS-6.3.1206-x64.qcow2","version":"3.0.3","support_version":"3.6.13","detailed":"{\"image_group_id\":2,\"name\":\"CentOS-6.3.1206-x64\"}","support_init":1,"status":1,"md5":"155702d65380b6518a5cfadce8b0fc00","is_charge":0},{"id":42,"name":"CentOS-6.7.1504-x64.qcow2","version":"3.0.2","support_version":"3.6.13","detailed":"{\"image_group_id\":2,\"name\":\"CentOS-6.7.1504-x64\"}","support_init":1,"status":1,"md5":"2d8ff47751b0d8b228d357386f0a51c4","is_charge":0},{"id":43,"name":"CentOS-6.9.1704-x64.qcow2","version":"3.0.2","support_version":"3.6.13","detailed":"{\"image_group_id\":2,\"name\":\"CentOS-6.9.1704-x64\"}","support_init":1,"status":1,"md5":"7b08b60064a6ddce2748ad3c69c5d6b2","is_charge":0},{"id":44,"name":"Fedora-25-x64.qcow2","version":"3.0.0","support_version":"3.3.8","detailed":"{\"image_group_id\":8,\"name\":\"Fedora-25-x64\"}","support_init":1,"status":1,"md5":"16332a88de0113377a94f50d319fd650","is_charge":0},{"id":45,"name":"Fedora-26-x64.qcow2","version":"3.0.0","support_version":"3.3.8","detailed":"{\"image_group_id\":8,\"name\":\"Fedora-26-x64\"}","support_init":1,"status":1,"md5":"f61942ee9f9a700a07adf1fbec427654","is_charge":0},{"id":46,"name":"Fedora-27-x64.qcow2","version":"3.0.0","support_version":"3.3.8","detailed":"{\"image_group_id\":8,\"name\":\"Fedora-27-x64\"}","support_init":1,"status":1,"md5":"ffe98da28675b14992dbbcf774984d56","is_charge":0},{"id":47,"name":"Fedora-28-x64.qcow2","version":"3.0.0","support_version":"3.3.8","detailed":"{\"image_group_id\":8,\"name\":\"Fedora-28-x64\"}","support_init":1,"status":1,"md5":"7f58064f19375bcb6ade1bbde917a48f","is_charge":0},{"id":48,"name":"Fedora-29-x64.qcow2","version":"3.0.0","support_version":"3.3.8","detailed":"{\"image_group_id\":8,\"name\":\"Fedora-29-x64\"}","support_init":1,"status":1,"md5":"01ce341003222adf8eb3497f467cf03e","is_charge":0},{"id":49,"name":"Fedora-30-x64.qcow2","version":"3.0.0","support_version":"3.3.8","detailed":"{\"image_group_id\":8,\"name\":\"Fedora-30-x64\"}","support_init":1,"status":1,"md5":"953a4fd2615c0613511da070f2dff1dd","is_charge":0},{"id":50,"name":"Fedora-31-x64.qcow2","version":"3.0.0","support_version":"3.3.8","detailed":"{\"image_group_id\":8,\"name\":\"Fedora-31-x64\"}","support_init":1,"status":1,"md5":"6be7a3c41e0793b6719b28a1c4aa195b","is_charge":0},{"id":51,"name":"Fedora-32-x64.qcow2","version":"1.0.0","support_version":"2.2.0","detailed":"{\"image_group_id\":8,\"name\":\"Fedora-32-x64\"}","support_init":0,"status":1,"md5":"1ac448ceb1c21e93aa1ff347cc4cb0fc","is_charge":0},{"id":52,"name":"CentOS-6.10.1907-x32.qcow2","version":"1.0.1","support_version":"3.6.13","detailed":"{\"image_group_id\":2,\"name\":\"CentOS-6.10.1907-x32\"}","support_init":1,"status":1,"md5":"b4398bc3bccffb7a9a6e1092591f3cba","is_charge":0},{"id":53,"name":"Windows-2008R2-Enterprise-cn.qcow2","version":"1.0.0","support_version":"2.2.0","detailed":"{\"image_group_id\":1,\"name\":\"Windows-2008R2-Enterprise-cn\"}","support_init":0,"status":1,"md5":"31ccabebcbc1864e80d9d59eb04927f6","is_charge":0},{"id":57,"name":"Windows-2008R2-Standard-en.qcow2","version":"1.0.0","support_version":"2.2.0","detailed":"{\"image_group_id\":1,\"name\":\"Windows-2008R2-Standard-en\"}","support_init":0,"status":1,"md5":"ab58671e7c261567aec465b2708e1db3","is_charge":0},{"id":58,"name":"Windows-2008R2-Datacenter-en.qcow2","version":"1.0.0","support_version":"2.2.0","detailed":"{\"image_group_id\":1,\"name\":\"Windows-2008R2-Datacenter-en\"}","support_init":0,"status":1,"md5":"3035c8b29766b7bdd54614342f49f09e","is_charge":0},{"id":60,"name":"Windows-2016-Datacenter-en.qcow2","version":"1.0.1","support_version":"2.2.1","detailed":"{\"image_group_id\":1,\"name\":\"Windows-2016-Datacenter-en\"}","support_init":0,"status":1,"md5":"c7f64463ccd0ae1c72fc04ec9b939f46","is_charge":0},{"id":61,"name":"Windows-2019-Datacenter-en.qcow2","version":"1.0.0","support_version":"2.2.0","detailed":"{\"image_group_id\":1,\"name\":\"Windows-2019-Datacenter-en\"}","support_init":0,"status":1,"md5":"77309972bcc6a8ab4d8d9ac062701076","is_charge":0},{"id":62,"name":"CentOS-8-Stream-x64.qcow2","version":"3.0.0","support_version":"3.3.8","detailed":"{\"image_group_id\":2,\"name\":\"CentOS-8-Stream-x64\"}","support_init":1,"status":1,"md5":"c25ddb07cd1788004277eef0896da438","is_charge":0},{"id":63,"name":"Windows-2022-Datacenter-cn.qcow2","version":"3.0.1","support_version":"3.3.8","detailed":"{\"image_group_id\":1,\"name\":\"Windows-2022-Datacenter-cn\"}","support_init":1,"status":1,"md5":"c40e290f9f9acfc23eab397d1dcfc8ea","is_charge":0},{"id":65,"name":"Debian-11.1-x64.qcow2","version":"3.0.0","support_version":"3.3.8","detailed":"{\"image_group_id\":4,\"name\":\"Debian-11.1-x64\"}","support_init":1,"status":1,"md5":"c725218eb8c8dece33e6e7deb598b8f4","is_charge":0},{"id":76,"name":"CentOS-7.9.2111-x64.qcow2","version":"3.0.0","support_version":"3.3.8","detailed":"{\"image_group_id\":2,\"name\":\"CentOS-7.9.2111-x64\"}","support_init":1,"status":1,"md5":"b1480c9ed948e26e28df2f5300d8172e","is_charge":0},{"id":88,"name":"Ubuntu-22.04-x64.qcow2","version":"3.0.0","support_version":"3.3.8","detailed":"{\"image_group_id\":3,\"name\":\"Ubuntu-22.04-x64\"}","support_init":1,"status":1,"md5":"0f3917a353fd80c379ea7a6ef2e7103d","is_charge":0},{"id":90,"name":"CentOS-8.3.2011-x64.qcow2","version":"3.0.0","support_version":"3.3.8","detailed":"{\"image_group_id\":2,\"name\":\"CentOS-8.3.2011-x64\"}","support_init":1,"status":1,"md5":"ca341f4e5a3e8d563067cc27ff2a4945","is_charge":0},{"id":91,"name":"CentOS-8.4.2105-x64.qcow2","version":"3.0.0","support_version":"3.3.8","detailed":"{\"image_group_id\":2,\"name\":\"CentOS-8.4.2105-x64\"}","support_init":1,"status":1,"md5":"e84a514320fae0bf5ad161445b2f5387","is_charge":0},{"id":92,"name":"CentOS-9-Stream-x64.qcow2","version":"3.0.0","support_version":"3.3.8","detailed":"{\"image_group_id\":2,\"name\":\"CentOS-9-Stream-x64\"}","support_init":1,"status":1,"md5":"343ebfcbcec5148cab0aae1e15754550","is_charge":0},{"id":94,"name":"CentOS-8.5.2111-x64.qcow2","version":"1.0.0","support_version":"3.6.10","detailed":"{\"image_group_id\":2,\"name\":\"CentOS-8.5.2111-x64\"}","support_init":1,"status":1,"md5":"eac98d4698ac7fe7811f02bb8afb4921","is_charge":0},{"id":95,"name":"Windows-2019-Standard-en.qcow2","version":"1.0.0","support_version":"3.3.8","detailed":"{\"image_group_id\":1,\"name\":\"Windows-2019-Standard-en\"}","support_init":1,"status":1,"md5":"7fa77ed9e191c2be9ef1e2c1e7e3b61d","is_charge":0},{"id":96,"name":"Rocky-linux-8.8-x64.qcow2","version":"1.0.0","support_version":"3.6.50","detailed":"{\"image_group_id\":12,\"name\":\"Rocky-linux-8.8-x64\"}","support_init":1,"status":1,"md5":"3a1821f81a162f0b8c6725695e040ff7","is_charge":0},{"id":97,"name":"Rocky-linux-9.2-x64.qcow2","version":"1.0.0","support_version":"3.6.50","detailed":"{\"image_group_id\":12,\"name\":\"Rocky-linux-9.2-x64\"}","support_init":1,"status":1,"md5":"1090cfa307702ee53817552cfeec7275","is_charge":0},{"id":98,"name":"AlmaLinux-8.8-x64.qcow2","version":"1.0.0","support_version":"3.6.54","detailed":"{\"image_group_id\":13,\"name\":\"AlmaLinux-8.8-x64\"}","support_init":1,"status":1,"md5":"fed8b0e10cbe2d4609d1aa78a893e47f","is_charge":0},{"id":99,"name":"AlmaLinux-9.2-x64.qcow2","version":"1.0.0","support_version":"3.6.54","detailed":"{\"image_group_id\":13,\"name\":\"AlmaLinux-9.2-x64\"}","support_init":1,"status":1,"md5":"afa98674ae5476ba90d3822279ec268a","is_charge":0},{"id":100,"name":"OpenEuler-22.03-LTS-SP1-x64.qcow2","version":"1.0.0","support_version":"3.6.54","detailed":"{\"image_group_id\":14,\"name\":\"OpenEuler-22.03-LTS-SP1-x64\"}","support_init":1,"status":1,"md5":"cb9a9280e24cf7c889b31ea9a92558ad","is_charge":0},{"id":101,"name":"Windows_en_Rescue.qcow2","version":"1.0.0","support_version":"3.7.0","detailed":"{\"image_group_id\":10,\"name\":\"Windows_en_Rescue\"}","support_init":1,"status":1,"md5":"eaef636c11395d113b1153d1bfdb37bb","is_charge":0},{"id":102,"name":"Debian-12.0_x64.qcow2  ","version":"1.0.1","support_version":"3.7.0","detailed":"{\"image_group_id\":4,\"name\":\"Debian-12.0_x64\"}","support_init":1,"status":1,"md5":"2e391131cbd6899a05e4c71cb70f67e0","is_charge":0},{"id":103,"name":"en-windwos-2012r2.qcow2","version":"1.0.0","support_version":"3.6.13","detailed":"{\"image_group_id\":1,\"name\":\"Windows-2012R2-Datacenter-en\"}","support_init":1,"status":1,"md5":"bf55f9ade91896b956288e205d8e5218","is_charge":0},{"id":104,"name":"openEuler-22.03-LTS.qcow2","version":"1.0.0","support_version":"3.6.13","detailed":"{\"image_group_id\":14,\"name\":\"openEuler-22.03-LTS-SP2\"}","support_init":1,"status":1,"md5":"861de75ce14027f6688d003cf2ec4cfd","is_charge":0},{"id":105,"name":"openEuler-24.03-LTS.qcow2","version":"1.0.0","support_version":"3.6.13","detailed":"{\"image_group_id\":14,\"name\":\"openEuler-24.03-LTS\"}","support_init":1,"status":1,"md5":"d21e4685ed2ebb735a518c859d287308","is_charge":0},{"id":106,"name":"Ubuntu-24.04.1-x64.qcow2","version":"1.0.0","support_version":"3.6.13","detailed":"{\"image_group_id\":3,\"name\":\"Ubuntu-24.04.1-x64\"}","support_init":1,"status":1,"md5":"3b5dfbc8c57ef555d488948aeeaf6d00","is_charge":0},{"id":107,"name":"TencentOS-Server-2.4-x86_64.qcow2","version":"1.0.0","support_version":"3.9.2","detailed":"{\"image_group_id\":16,\"name\":\"TencentOS-Server-2.4-x86_64\"}","support_init":1,"status":1,"md5":"d9ae2910b71bfb118dcb4c093da04961","is_charge":0},{"id":108,"name":"TencentOS-Server-3.1-x86_64.qcow2","version":"1.0.0","support_version":"3.9.2","detailed":"{\"image_group_id\":16,\"name\":\"TencentOS-Server-3.1-x86_64\"}","support_init":1,"status":1,"md5":"51c98df73ddcf0948289cd0e7aec3780","is_charge":0},{"id":109,"name":"TencentOS-Server-3.3-x86_64.qcow2","version":"1.0.0","support_version":"3.9.2","detailed":"{\"image_group_id\":16,\"name\":\"TencentOS-Server-3.3-x86_64\"}","support_init":1,"status":1,"md5":"f66f34b0ca2433b3561e8e1351f16e35","is_charge":0},{"id":110,"name":"TencentOS-Server-4.0-x86_64.qcow2","version":"1.0.0","support_version":"3.9.2","detailed":"{\"image_group_id\":16,\"name\":\"TencentOS-Server-4.0-x86_64\"}","support_init":1,"status":1,"md5":"89c5f04f5207c361e8d4c3e97656852c","is_charge":0},{"id":111,"name":"TencentOS-Server-4.2-x86_64.qcow2","version":"1.0.0","support_version":"3.9.2","detailed":"{\"image_group_id\":16,\"name\":\"TencentOS-Server-4.2-x86_64\"}","support_init":1,"status":1,"md5":"621bf1208191f396f07db6ce8bf52b0f","is_charge":0},{"id":112,"name":"Windows11-cn.qcow2","version":"1.0.0","support_version":"3.6.13","detailed":"{\"image_group_id\":1,\"name\":\"Windows11-cn\"}","support_init":1,"status":1,"md5":"1b100021c07e76021b3aa638ff7608d9","is_charge":0},{"id":113,"name":"Ubuntu-24.04-deepseek-r1-1.5b.qcow2","version":"1.0.0","support_version":"3.6.13","detailed":"{\"image_group_id\":3,\"name\":\"Ubuntu-24.04-deepseek-r1-1.5b\"}","support_init":1,"status":1,"md5":"059bcdf31c229ca75162b79868630dec","is_charge":0},{"id":114,"name":"Ubuntu24.04.1-vGPU.qcow2","version":"1.0.0","support_version":"3.9.8","detailed":"{\"image_group_id\":3,\"name\":\"Ubuntu24.04.1-vGPU\"}","support_init":1,"status":1,"md5":"56eb0d4435e3121cd3c97722f3ecb774","is_charge":0},{"id":115,"name":"Windows11-vGPU.qcow2","version":"1.0.0","support_version":"3.9.8","detailed":"{\"image_group_id\":1,\"name\":\"Windows11-vGPU\"}","support_init":1,"status":1,"md5":"3aa74675337145a20549c16c33a652d7","is_charge":0},{"id":116,"name":"Windows-2022-Datacenter-GPT-cn.qcow2","version":"1.0.0","support_version":"3.9.10","detailed":"{\"startup_mode\":\"uefi\",\"image_group_id\":1,\"name\":\"Windows-2022-Datacenter-GPT-cn\"}","support_init":1,"status":1,"md5":"ae9bb142e38f101fa9f6a5990bffa6f5","is_charge":0},{"id":117,"name":"Windows-2019-Datacenter-GPT-cn.qcow2","version":"1.0.0","support_version":"3.9.10","detailed":"{\"startup_mode\":\"uefi\",\"image_group_id\":1,\"name\":\"Windows-2019-Datacenter-GPT-cn\"}","support_init":1,"status":1,"md5":"192a815859f86b98a1c329412ebd37be","is_charge":0},{"id":118,"name":"Windows-2016-Datacenter-GPT-cn.qcow2","version":"1.0.0","support_version":"3.9.10","detailed":"{\"startup_mode\":\"uefi\",\"image_group_id\":1,\"name\":\"Windows-2016-Datacenter-GPT-cn\"}","support_init":1,"status":1,"md5":"eae9dff746e041f8a368993555c9a9c6","is_charge":0},{"id":119,"name":"Mysql5.7.qcow2","version":"1.0.0","support_version":"3.9.16","detailed":"{\"image_group_id\":4,\"name\":\"Mysql5.7\",\"app_id\":1,\"app_version\":\"5.7\"}","support_init":1,"status":1,"md5":"147a5248eded98446f561300ff9c7889","is_charge":0},{"id":120,"name":"Rocky-linux-8.10-x64.qcow2","version":"1.0.0","support_version":"3.6.13","detailed":"{\"image_group_id\":12,\"name\":\"Rocky-linux-8.10-x64\"}","support_init":1,"status":1,"md5":"896e490e58c5806eba08d86ba077aa6b","is_charge":0},{"id":121,"name":"Alibaba-Cloud-Linux-3.2104.qcow2","version":"1.0.0","support_version":"3.6.13","detailed":"{\"image_group_id\":2,\"name\":\"Alibaba-Cloud-Linux-3.2104\"}","support_init":1,"status":1,"md5":"2fce52fc2368d801fa27506e239ec175","is_charge":0},{"id":122,"name":"Windows-2025-Datacenter-GPT-cn.qcow2","version":"1.0.0","support_version":"3.9.10","detailed":"{\"image_group_id\":1,\"name\":\"Windows-2025-Datacenter-GPT-cn\",\"startup_mode\":\"uefi\"}","support_init":1,"status":1,"md5":"93bf0fb1b8106b208c9028719b34c787","is_charge":0},{"id":123,"name":"Debian13-x86.qcow2","version":"1.0.1","support_version":"3.6.13","detailed":"{\"image_group_id\":4,\"name\":\"Debian13-x86\"}","support_init":1,"status":1,"md5":"6357001989c8acb886fc2fee2b1e98b4","is_charge":0},{"id":128,"name":"Ubuntu-26.04.1-x64.qcow2","version":"1.0.1","support_version":"3.9.36","detailed":"{\"image_group_id\":3,\"name\":\"Ubuntu-26.04.1-x64\"}","support_init":1,"status":1,"md5":"581544cbb2a1385b9b9eb4d7b26e1a7a","is_charge":0},{"id":129,"name":"MySQL-8.4.qcow2","version":"1.0.0","support_version":"3.9.36","detailed":"{\"image_group_id\":4,\"name\":\"MySQL-8.4\",\"app_id\":1,\"app_version\":\"8.4\"}","support_init":1,"status":1,"md5":"0404a2eeed0c9c0a9955fbc3e7149606","is_charge":0},{"id":130,"name":"Ubuntu-26.04.1-x64-lvm.qcow2","version":"1.0.0","support_version":"3.9.36","detailed":"{\"image_group_id\":3,\"name\":\"Ubuntu-26.04.1-x64-lvm\"}","support_init":1,"status":1,"md5":"803a4f5790e06d5ec28dd236a19e1bf8","is_charge":0}],"versions":[{"name":"CentOS-7.6.1810-x64.qcow2","size":"626851840","support_init":0,"version":"2.0.4"},{"name":"CentOS-8.1.1911-x64.qcow2","size":"736428032","support_init":0,"version":"2.0.4"},{"name":"Debian-9.12.1-x64.qcow2","size":"619249664","support_init":0,"version":"2.0.5"},{"name":"Debian-10.3.3-x64.qcow2","size":"644743168","support_init":0,"version":"2.0.5"},{"name":"Ubuntu-16.04-x64.qcow2","size":"813039616","support_init":0,"version":"2.0.5"},{"name":"Ubuntu-18.04-x64.qcow2","size":"1093599232","support_init":0,"version":"2.0.5"},{"name":"Windows-2008R2-Datacenter-cn.qcow2","size":"7601717248","support_init":0,"version":"2.0.3"},{"name":"Windows-2012R2-Datacenter-cn.qcow2","size":"5547163648","support_init":0,"version":"2.0.3"},{"name":"Windows-2016-Datacenter-cn.qcow2","size":"5320605696","support_init":0,"version":"2.0.4"},{"name":"Windows-2019-Datacenter-cn.qcow2","size":"5367595008","support_init":0,"version":"2.0.3"},{"name":"CentOS-7.0.1406-x64.qcow2","size":"408944640","support_init":0,"version":"2.0.6"},{"name":"CentOS-7.1.1503-x64.qcow2","size":"444596224","support_init":0,"version":"2.0.6"},{"name":"CentOS-7.2.1511-x64.qcow2","size":"471859200","support_init":0,"version":"2.0.5"},{"name":"CentOS-7.3.1611-x64.qcow2","size":"591855616","support_init":0,"version":"2.0.5"},{"name":"CentOS-7.4.1708-x64.qcow2","size":"555679744","support_init":0,"version":"2.0.5"},{"name":"CentOS-7.5.1804-x64.qcow2","size":"623837184","support_init":0,"version":"2.0.5"},{"name":"CentOS-7.7.1908-x64.qcow2","size":"695599104","support_init":0,"version":"2.0.4"},{"name":"CentOS-7.8.2003-x64.qcow2","size":"783876096","support_init":0,"version":"2.0.4"},{"name":"CentOS-7.8.2003-x64-BT.qcow2","size":"791478272","support_init":0,"version":"2.0.6"},{"name":"CentOS-8.0.1905-x64.qcow2","size":"782630912","support_init":0,"version":"2.0.5"},{"name":"CentOS-8.2.2004-x64.qcow2","size":"820646400","support_init":0,"version":"2.0.5"},{"name":"Linux_Rescue.qcow2","size":"783876096","support_init":0,"version":"2.0.2"},{"name":"Windows_Rescue.qcow2","size":"6301483008","support_init":0,"version":"2.0.2"},{"name":"CentOS-6.8.1607-x64.qcow2","size":"606732288","support_init":0,"version":"2.0.5"},{"name":"CentOS-6.10.1907-x64.qcow2","size":"328466432","support_init":0,"version":"2.0.5"},{"name":"Windows-2003-Enterprise-cn.qcow2","size":"941948928","support_init":0,"version":"1.0.1"},{"name":"Windows7_enterprise-cn.qcow2","size":"3627810816","support_init":0,"version":"1.0.0"},{"name":"Windows10-cn.qcow2","size":"5666616832","support_init":0,"version":"1.0.0"},{"name":"Ubuntu-20.04.1-x64.qcow2","size":"3061841920","support_init":0,"version":"1.0.5"},{"name":"CentOS-5.9-x64.qcow2","size":"491716608","support_init":0,"version":"1.0.0"},{"name":"CentOS-6.3.1206-x64.qcow2","size":"272302080","support_init":0,"version":"1.0.1"},{"name":"CentOS-6.7.1504-x64.qcow2","size":"309854208","support_init":0,"version":"1.0.1"},{"name":"CentOS-6.9.1704-x64.qcow2","size":"327942144","support_init":0,"version":"1.0.1"},{"name":"Fedora-25-x64.qcow2","size":"721238528","support_init":0,"version":"1.0.0"},{"name":"Fedora-26-x64.qcow2","size":"783744000","support_init":0,"version":"1.0.0"},{"name":"Fedora-27-x64.qcow2","size":"875881984","support_init":0,"version":"1.0.0"},{"name":"Fedora-28-x64.qcow2","size":"988348416","support_init":0,"version":"1.0.0"},{"name":"Fedora-29-x64.qcow2","size":"987798016","support_init":0,"version":"1.0.0"},{"name":"Fedora-30-x64.qcow2","size":"1036778496","support_init":0,"version":"1.0.0"},{"name":"Fedora-31-x64.qcow2","size":"954148352","support_init":0,"version":"1.0.0"},{"name":"Fedora-32-x64.qcow2","size":"1047527424","support_init":0,"version":"1.0.0"},{"name":"CentOS-6.10.1907-x32.qcow2","size":"319815680","support_init":0,"version":"1.0.0"},{"name":"Windows-2008R2-Enterprise-cn.qcow2","size":"3658350592","support_init":0,"version":"1.0.0"},{"name":"Windows-2008R2-Standard-en.qcow2","size":"3622240256","support_init":0,"version":"1.0.0"},{"name":"Windows-2008R2-Datacenter-en.qcow2","size":"3435069440","support_init":0,"version":"1.0.0"},{"name":"Windows-2016-Datacenter-en.qcow2","size":"5215813632","support_init":0,"version":"1.0.1"},{"name":"Windows-2019-Datacenter-en.qcow2","size":"4779737088","support_init":0,"version":"1.0.0"},{"name":"CentOS-8-Stream-x64.qcow2","size":"1410924544","support_init":0,"version":"1.0.0"},{"name":"Windows-2022-Datacenter-cn.qcow2","size":"6003621888","support_init":0,"version":"1.0.0"},{"name":"Debian-11.1-x64.qcow2","size":"440532992","support_init":0,"version":"1.0.1"},{"name":"CentOS-7.6.1810-x64.qcow2","size":"825491456","support_init":1,"version":"3.0.0"},{"name":"Windows-2016-Datacenter-cn.qcow2","size":"4917304320","support_init":1,"version":"3.0.1"},{"name":"Ubuntu-18.04-x64.qcow2","size":"388431872","support_init":1,"version":"3.0.0"},{"name":"CentOS-7.1.1503-x64.qcow2","size":"812777472","support_init":1,"version":"3.0.0"},{"name":"CentOS-7.2.1511-x64.qcow2","size":"802488320","support_init":1,"version":"3.0.0"},{"name":"CentOS-7.3.1611-x64.qcow2","size":"962330624","support_init":1,"version":"3.0.0"},{"name":"CentOS-7.4.1708-x64.qcow2","size":"773652480","support_init":1,"version":"3.0.0"},{"name":"CentOS-7.5.1804-x64.qcow2","size":"832110592","support_init":1,"version":"3.0.0"},{"name":"CentOS-7.7.1908-x64.qcow2","size":"758029824","support_init":1,"version":"3.0.1"},{"name":"CentOS-7.8.2003-x64.qcow2","size":"791805952","support_init":1,"version":"3.0.0"},{"name":"CentOS-7.9.2111-x64.qcow2","size":"763166720","support_init":1,"version":"3.0.0"},{"name":"CentOS-8.1.1911-x64.qcow2","size":"718340096","support_init":1,"version":"3.0.0"},{"name":"CentOS-8.2.2004-x64.qcow2","size":"1229783040","support_init":1,"version":"3.0.0"},{"name":"CentOS-8.3.2011-x64.qcow2","size":"1323696128","support_init":1,"version":"3.0.0"},{"name":"CentOS-8.4.2105-x64.qcow2","size":"1420558336","support_init":1,"version":"3.0.0"},{"name":"CentOS-8-Stream-x64.qcow2","size":"703991296","support_init":1,"version":"3.0.0"},{"name":"CentOS-9-Stream-x64.qcow2","size":"902561792","support_init":1,"version":"3.0.0"},{"name":"Debian-9.12.1-x64.qcow2","size":"667811840","support_init":1,"version":"3.0.0"},{"name":"Debian-10.3.3-x64.qcow2","size":"569704448","support_init":1,"version":"3.0.0"},{"name":"Debian-11.1-x64.qcow2","size":"253952000","support_init":1,"version":"3.0.0"},{"name":"Ubuntu-16.04-x64.qcow2","size":"316276736","support_init":1,"version":"3.0.0"},{"name":"Ubuntu-20.04.1-x64.qcow2","size":"595984384","support_init":1,"version":"3.0.0"},{"name":"Ubuntu-22.04-x64.qcow2","size":"626458624","support_init":1,"version":"3.0.0"},{"name":"Windows10-cn.qcow2","size":"7653949440","support_init":1,"version":"3.0.2"},{"name":"Windows-2008R2-Datacenter-cn.qcow2","size":"4473480704","support_init":1,"version":"3.0.1"},{"name":"Windows-2012R2-Datacenter-cn.qcow2","size":"3860725760","support_init":1,"version":"3.0.1"},{"name":"Windows-2019-Datacenter-cn.qcow2","size":"4899653632","support_init":1,"version":"3.0.1"},{"name":"Windows-2022-Datacenter-cn.qcow2","size":"5028658688","support_init":1,"version":"3.0.1"},{"name":"Fedora-25-x64.qcow2","size":"528023552","support_init":1,"version":"3.0.0"},{"name":"Fedora-26-x64.qcow2","size":"590151680","support_init":1,"version":"3.0.0"},{"name":"Fedora-27-x64.qcow2","size":"629276672","support_init":1,"version":"3.0.0"},{"name":"Fedora-28-x64.qcow2","size":"701038592","support_init":1,"version":"3.0.0"},{"name":"Fedora-29-x64.qcow2","size":"758317056","support_init":1,"version":"3.0.0"},{"name":"Fedora-30-x64.qcow2","size":"764346368","support_init":1,"version":"3.0.0"},{"name":"Fedora-31-x64.qcow2","size":"779485184","support_init":1,"version":"3.0.0"},{"name":"Windows7_enterprise-cn.qcow2","size":"3289382912","support_init":1,"version":"3.0.1"},{"name":"Windows-2003-Enterprise-cn.qcow2","size":"957022208","support_init":1,"version":"3.0.3"},{"name":"CentOS-5.9-x64.qcow2","size":"492240896","support_init":1,"version":"3.0.0"},{"name":"CentOS-8.5.2111-x64.qcow2","size":"1135476736","support_init":1,"version":"1.0.0"},{"name":"Windows-2019-Standard-en.qcow2","size":"5091688448","support_init":1,"version":"1.0.0"},{"name":"Linux_Rescue.qcow2","size":"703991296","support_init":1,"version":"3.0.0"},{"name":"Windows_Rescue.qcow2","size":"5150277632","support_init":1,"version":"3.0.0"},{"name":"CentOS-6.10.1907-x64.qcow2","size":"393609216","support_init":1,"version":"3.0.0"},{"name":"CentOS-6.3.1206-x64.qcow2","size":"378667008","support_init":1,"version":"3.0.1"},{"name":"CentOS-6.7.1504-x64.qcow2","size":"530579456","support_init":1,"version":"3.0.0"},{"name":"CentOS-6.9.1704-x64.qcow2","size":"485031936","support_init":1,"version":"3.0.0"},{"name":"CentOS-6.8.1607-x64.qcow2","size":"482738176","support_init":1,"version":"3.0.0"},{"name":"Rocky-linux-8.8-x64.qcow2","size":"1117311488","support_init":1,"version":"1.0.0"},{"name":"Rocky-linux-9.2-x64.qcow2","size":"1354027520","support_init":1,"version":"1.0.0"},{"name":"AlmaLinux-8.8-x64.qcow2","size":"1567416832","support_init":1,"version":"1.0.0"},{"name":"AlmaLinux-9.2-x64.qcow2","size":"1756044800","support_init":1,"version":"1.0.0"},{"name":"OpenEuler-22.03-LTS-SP1-x64.qcow2","size":"1558434304","support_init":1,"version":"1.0.0"},{"name":"Windows_en_Rescue.qcow2","size":"6494506496","support_init":1,"version":"1.0.0"},{"name":"Debian-12.0_x64.qcow2  ","size":"777388032","support_init":1,"version":"1.0.0"},{"name":"en-windwos-2012r2.qcow2","size":"5021827072","support_init":1,"version":"1.0.0"},{"name":"openEuler-22.03-LTS.qcow2","size":"1600061440","support_init":1,"version":"1.0.0"},{"name":"openEuler-24.03-LTS.qcow2","size":"940376064","support_init":1,"version":"1.0.0"},{"name":"CentOS-6.10.1907-x64.qcow2","size":"392048640","support_init":1,"version":"3.0.1"},{"name":"CentOS-6.3.1206-x64.qcow2","size":"299182080","support_init":1,"version":"3.0.2"},{"name":"CentOS-6.7.1504-x64.qcow2","size":"368944128","support_init":1,"version":"3.0.1"},{"name":"CentOS-6.8.1607-x64.qcow2","size":"388807680","support_init":1,"version":"3.0.1"},{"name":"CentOS-6.9.1704-x64.qcow2","size":"397556736","support_init":1,"version":"3.0.1"},{"name":"CentOS-6.10.1907-x32.qcow2","size":"373358592","support_init":1,"version":"1.0.1"},{"name":"CentOS-6.10.1907-x64.qcow2","size":"400413696","support_init":1,"version":"3.0.2"},{"name":"CentOS-6.3.1206-x64.qcow2","size":"309042688","support_init":1,"version":"3.0.3"},{"name":"CentOS-6.7.1504-x64.qcow2","size":"442414592","support_init":1,"version":"3.0.2"},{"name":"CentOS-6.8.1607-x64.qcow2","size":"391887872","support_init":1,"version":"3.0.2"},{"name":"CentOS-6.9.1704-x64.qcow2","size":"413138944","support_init":1,"version":"3.0.2"},{"name":"Ubuntu-24.04.1-x64.qcow2","size":"1800863744","support_init":1,"version":"1.0.0"},{"name":"TencentOS-Server-2.4-x86_64.qcow2","size":"2454126592","support_init":1,"version":"1.0.0"},{"name":"TencentOS-Server-3.1-x86_64.qcow2","size":"1262499328","support_init":1,"version":"1.0.0"},{"name":"TencentOS-Server-3.3-x86_64.qcow2","size":"1146895872","support_init":1,"version":"1.0.0"},{"name":"TencentOS-Server-4.0-x86_64.qcow2","size":"753270784","support_init":1,"version":"1.0.0"},{"name":"TencentOS-Server-4.2-x86_64.qcow2","size":"733609984","support_init":1,"version":"1.0.0"},{"name":"Windows11-cn.qcow2","size":"11163202048","support_init":1,"version":"1.0.0"},{"name":"Ubuntu-24.04-deepseek-r1-1.5b.qcow2","size":"5455282176","support_init":1,"version":"1.0.0"},{"name":"Debian-12.0_x64.qcow2  ","size":"660668416","support_init":1,"version":"1.0.1"},{"name":"Windows-2022-Datacenter-GPT-cn.qcow2","size":"6912409600","support_init":1,"version":"1.0.0"},{"name":"Windows-2019-Datacenter-GPT-cn.qcow2","size":"6658719744","support_init":1,"version":"1.0.0"},{"name":"Windows-2016-Datacenter-GPT-cn.qcow2","size":"6030295040","support_init":1,"version":"1.0.0"},{"name":"Mysql5.7.qcow2","size":"1549271040","support_init":1,"version":"1.0.0"},{"name":"Rocky-linux-8.10-x64.qcow2","size":"1344995328","support_init":1,"version":"1.0.0"},{"name":"Alibaba-Cloud-Linux-3.2104.qcow2","size":"1890516992","support_init":1,"version":"1.0.0"},{"name":"Windows-2025-Datacenter-GPT-cn.qcow2","size":"12152993280","support_init":1,"version":"1.0.0"},{"name":"Debian13-x86.qcow2","size":"581632000","support_init":1,"version":"1.0.0"},{"name":"BT_linux.qcow2","size":"1603469312","support_init":1,"version":"1.0.0"},{"name":"1Panel_linux.qcow2","size":"2218065920","support_init":1,"version":"1.0.0"},{"name":"linux_docker.qcow2","size":"1938620416","support_init":1,"version":"1.0.0"},{"name":"Windows-2019-OpenClaw.qcow2","size":"12766543872","support_init":1,"version":"1.0.0"},{"name":"Debian13-x86.qcow2","size":"486080512","support_init":1,"version":"1.0.1"},{"name":"Ubuntu-26.04.1-x64.qcow2","size":"5818941440","support_init":1,"version":"1.0.0"},{"name":"MySQL-8.4.qcow2","size":"1274816512","support_init":1,"version":"1.0.0"},{"name":"Ubuntu-26.04.1-x64-lvm.qcow2","size":"5819203584","support_init":1,"version":"1.0.0"},{"name":"Ubuntu-26.04.1-x64.qcow2","size":"6264651776","support_init":1,"version":"1.0.1"},{"name":"BT_linux.qcow2","size":"1611661312","support_init":1,"version":"1.0.1"}]}
CATALOG
,true);
$authImageDefaults=json_decode('[{"name": "BT_linux", "identifier": "bt_linux", "authrized": true}, {"name": "1Panel_linux", "identifier": "1panel_linux", "authrized": true}, {"name": "linux_docker", "identifier": "linux_docker", "authrized": true}, {"name": "Windows-2019-OpenClaw", "identifier": "win2019openclaw", "authrized": true}]',true);
$authImageMd5Defaults=json_decode('["711eb969125c4515309325b1681c70f6", "b1253d16d332f5fd35a9343072af044c", "ed86f503497f772125155874b95fe957", "26c10d414aa7d9b9d872d0e83f37e01b"]',true);

/* Cloud image metadata API. Catalog is a dated snapshot; version data refreshes upstream. */
function image_normalize_versions(array $rows): array {
    $result=[];
    foreach($rows as $row){
        if(!is_array($row)||!is_string($row['name']??null)||!is_scalar($row['version']??null))continue;
        $name=trim($row['name']);$version=trim((string)$row['version']);
        if(!preg_match('/^[A-Za-z0-9._-]+\.qcow2$/D',$name)||!preg_match('/^[0-9]+(?:\.[0-9]+)*$/D',$version))continue;
        $init=(string)($row['support_init']??0);if($init!=='0'&&$init!=='1')continue;
        $row['name']=$name;$row['version']=$version;$row['support_init']=(int)$init;$result[]=$row;
    }
    return $result;
}
function image_versions(array $fallback, string $cacheDir): array {
    $fallback=image_normalize_versions($fallback);
    $file=$cacheDir.'/image-versions.json';
    if(is_file($file) && time()-filemtime($file)<3600){
        $cached=json_decode(file_get_contents($file),true);
        if(is_array($cached)){$cached=image_normalize_versions($cached);if($cached)return $cached;}
    }
    $context=stream_context_create(['http'=>['timeout'=>8,'header'=>"User-Agent: zjmf/3.9.42\r\n"],
        'ssl'=>['verify_peer'=>true,'verify_peer_name'=>true]]);
    $raw=@file_get_contents('https://license.soft13.idcsmart.com/app/api/get_image_version?version=3.9.42',false,$context,0,2097152);
    $remote=is_string($raw)?json_decode($raw,true):null;
    if(is_array($remote) && ($remote['status']??0)===200 && is_array($remote['data']??null) && $remote['data']){
        $normalized=image_normalize_versions($remote['data']);
        if($normalized){$fallback=$normalized;@file_put_contents($file,json_encode($fallback),LOCK_EX);@chmod($file,0600);}
    }
    if(is_file($file) && !$fallback){$cached=json_decode(file_get_contents($file),true);$fallback=is_array($cached)?image_normalize_versions($cached):[];}
    return $fallback;
}
function image_select_download(string $name,array $images,array $versions): ?array {
    // The catalog identifies the installed image family. Historical and
    // cloud-init variants can share a filename, so filename-only first-match
    // selection can return an obsolete download path.
    $name=trim($name);$catalog=null;
    foreach($images as $row){
        if(is_string($row['name']??null)&&trim($row['name'])===$name){
            if($catalog===null||version_compare((string)($row['version']??'0'),(string)($catalog['version']??'0'),'>'))$catalog=$row;
        }
    }
    if($catalog===null)return null;
    $init=(int)($catalog['support_init']??0);$selected=null;
    foreach($versions as $row){
        if(($row['name']??null)!==$name||(int)($row['support_init']??0)!==$init)continue;
        if(!isset($row['version'])||!is_scalar($row['version']))continue;
        if($selected===null||version_compare((string)$row['version'],(string)$selected['version'],'>'))$selected=$row;
    }
    if($selected!==null)return $selected;
    // Some published catalog entries (currently the two vGPU images) have no
    // history row. Their catalog version and image family remain authoritative.
    $fallback=image_normalize_versions([$catalog]);
    return $fallback?$fallback[0]:null;
}
function image_api(string $path,array $request,array $images,array $versions,string $cacheDir): void {
    $images=image_normalize_versions($images);
    if($path==='/app/api/get_images'){
        respond(['status'=>200,'msg'=>'镜像获取成功','data'=>$images]);
    }
    if($path==='/app/api/get_image_version'){
        respond(['status'=>200,'msg'=>'获取成功','data'=>image_versions($versions,$cacheDir)]);
    }
    if($path==='/app/api/auth_image_download'){
        $name=trim((string)($request['image']??''));
        $image=image_select_download($name,$images,image_versions($versions,$cacheDir));
        if($image!==null){
            respond(['status'=>200,'msg'=>'授权成功',
                'download_server'=>['mirror.cloud.idcsmart.com','hkcloud.idcsmart.com'],
                'version'=>$image['version'],'support_init'=>(int)($image['support_init']??0)]);
        }
        respond(['status'=>400,'msg'=>'未找到与镜像类型匹配的下载版本']);
    }
}

header('Cache-Control: no-store');
function respond(array $data,int $http=200): void {
    http_response_code($http);header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($data,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);exit;
}
function station_base(): string {
    $host=$_SERVER['HTTP_HOST']??'';
    if(strlen($host)>259||!preg_match('/^[A-Za-z0-9.-]+(?::[0-9]{1,5})?$/D',$host))respond(['status'=>400,'msg'=>'Invalid Host'],400);
    $https=(!empty($_SERVER['HTTPS'])&&$_SERVER['HTTPS']!=='off')||($_SERVER['SERVER_PORT']??'')==='443';
    $prefix=str_replace('\\','/',dirname($_SERVER['SCRIPT_NAME']??'/index.php'));
    if(!preg_match('~^/[A-Za-z0-9/_-]*$~D',$prefix))$prefix='/';
    return ($https?'https://':'http://').$host.rtrim($prefix,'/').'/';
}
function state_directory(): string {
    $directory=__DIR__.'/.zjmf-data';
    if(!is_dir($directory)&&!mkdir($directory,0700,true)&&!is_dir($directory))throw new RuntimeException('Site directory must be writable by PHP');
    return $directory;
}
function client_file(array $request): string {
    return state_directory().'/'.hash('sha256',(string)($request['license']??'')).'.php';
}
function store_client(string $file,array $record): void {
    $guard="<?php http_response_code(404); exit; ?>\n";
    if(file_put_contents($file,$guard.json_encode($record,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES),LOCK_EX)===false)throw new RuntimeException('Client state cannot be saved');
    chmod($file,0600);
}
function current_record(array $request,array $policy): array {
    global $authImageDefaults,$authImageMd5Defaults;
    $file=client_file($request);$record=[];
    if(is_file($file))$record=json_decode(substr(file_get_contents($file),strlen("<?php http_response_code(404); exit; ?>\n")),true);
    if(!is_array($record))$record=[];
    $defaults=['id'=>1,'ip'=>'','domain'=>'','system_token'=>'','install_version'=>$policy['version'],
        'license'=>'','type'=>'cloud','installation_path'=>'','version_type'=>'stable','node_num'=>0,
        'node_ip'=>'','hyperv_num'=>0,'facetoken'=>'','truthkey'=>'','secure_password'=>'',
        'suspend_reason'=>'','image'=>[],'image_md5'=>$authImageMd5Defaults,'auth_image_list'=>$authImageDefaults,'auth_image_md5'=>[],
        'business_version'=>0,'is_test'=>0];
    $record=array_replace($defaults,$record);
    if(!$record['auth_image_list'])$record['auth_image_list']=$authImageDefaults;
    $record['image_md5']=array_values(array_unique(array_merge($authImageMd5Defaults,$record['image_md5'])));
    foreach(['ip','domain','system_token','install_version','license','installation_path','node_ip','facetoken','truthkey','secure_password'] as $field){
        if(isset($request[$field])&&is_scalar($request[$field]))$record[$field]=(string)$request[$field];
    }
    $record['type']='cloud';$record['edition']=1;$record['status']='Active';
    $record['due_time']=$record['auth_due_time']=$policy['expires'];
    $record['max_node']=$record['hyperv_max']=$policy['quota'];
    foreach(['smart_cpu','smart_bw','abuse_manager','local_migrate','mysql_database','ceph_storage','app_database','app_preset','cross_migration'] as $module){
        $record[$module.'_node_max']=$policy['quota'];if(!isset($record[$module.'_node_num']))$record[$module.'_node_num']=0;
    }
    $record['node_instance_limit']=$policy['quota'];$record['high_availability']=1;
    $record['auth_time']=$record['update_time']=date('Y-m-d H:i:s');$record['last_license_time']=time();
    if(!isset($record['create_time']))$record['create_time']=$record['auth_time'];
    $record['app']=array_values(array_unique(array_merge($record['app']??[],$policy['apps'])));
    $record['plugin']=array_map(function(string $name):array{return ['name'=>$name];},$policy['plugins']);
    foreach($record['auth_image_list'] as &$image)$image['authrized']=true;unset($image);
    global $imageCatalog;
    $record['image_md5']=array_values(array_unique(array_merge($record['image_md5'],array_column($imageCatalog['images'],'md5'))));
    $record['auth_image_md5']=$record['image_md5'];
    store_client($file,$record);return $record;
}
function node_authorization(array $request,array $policy): array {
    $license='';
    foreach(['license','license_code','auth_code'] as $field){
        if(isset($request[$field])&&is_string($request[$field])&&$request[$field]!==''){$license=$request[$field];break;}
    }
    $licenseHash=isset($request['license_hash'])&&is_string($request['license_hash'])?strtolower($request['license_hash']):'';
    if($license!=='')$licenseHash=hash('sha256',$license);
    if(!preg_match('/^[a-f0-9]{64}$/D',$licenseHash))respond(['status'=>400,'msg'=>'Missing controller license identity','error_code'=>'node_authorization_request_invalid'],400);
    $nodeIp=$request['node_ip']??'';$machine=$request['machine_id']??'';
    if(!is_string($nodeIp)||!filter_var($nodeIp,FILTER_VALIDATE_IP)||!is_string($machine)||!preg_match('/^[A-Za-z0-9._:-]{1,128}$/D',$machine))respond(['status'=>400,'msg'=>'Invalid node identity','error_code'=>'node_authorization_request_invalid'],400);
    $timestamp=time();
    $claims=['v'=>1,'license_id'=>1,'license_hash'=>$licenseHash,'node_ip'=>$nodeIp,'machine_id'=>$machine,'last_license_time'=>$timestamp];
    $payload=json_encode($claims,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    $signature='';
    if(!openssl_sign($payload,$signature,NODE_PRIVATE_KEY,OPENSSL_ALGO_SHA256))throw new RuntimeException('Node authorization signature failed');
    $encode=function(string $value):string{return rtrim(strtr(base64_encode($value),'+/','-_'),'=');};
    return ['authorization'=>$encode($payload).'.'.$encode($signature),'last_license_time'=>$timestamp];
}
try {
    $path=parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH);
    $prefix=rtrim(str_replace('\\','/',dirname($_SERVER['SCRIPT_NAME']??'/index.php')),'/');
    if($prefix!==''&&strpos($path,$prefix.'/')===0)$path=substr($path,strlen($prefix));
    if(strpos($path,'/index.php')===0)$path=substr($path,10)?:'/';
    if(IS_MIRROR&&in_array($path,['/install-zjmf-cloud_new','/other/authorize-cloud'],true)){
        $script=file_get_contents(__DIR__.'/install-zjmf-cloud_new');
        $script=str_replace('SOURCE=__SITE_URL__','SOURCE='.escapeshellarg(station_base()),$script);
        if($path==='/other/authorize-cloud')$script=str_replace('EXISTING=0','EXISTING=1',$script);
        header('Content-Type: text/plain; charset=UTF-8');echo $script;exit;
    }
    if(IS_MIRROR&&$path==='/site.json'){
        respond(['product_version'=>'3.9.42','authorization_station_required'=>true,'client_package'=>'other/client-3.9.42.tar.gz','client_sha256'=>'bf7ac9a5bee2d68133fe067095476a242d7e8a9cf1bc4424446407191b1dc6bd']);
    }
    if($path==='/'){
        if(!IS_MIRROR)respond(['status'=>200,'service'=>'zjmf-cloud-auth','version'=>'3.9.42']);
        $base=station_base();$command='wget '.escapeshellarg($base.'install-zjmf-cloud_new').' -O install-zjmf-cloud_new && chmod +x install-zjmf-cloud_new && ./install-zjmf-cloud_new';
        $nodeCommand='wget '.escapeshellarg($base.'install-zjmf-cloud_new').' -O zjmf-node-repair.sh && bash zjmf-node-repair.sh --node-only';
        header('Content-Type: text/html; charset=UTF-8');
        echo '<!doctype html><html lang="zh-CN"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>魔方云安装教程</title><style>body{font:16px/1.8 system-ui;margin:40px auto;padding:0 24px;max-width:880px;color:#202124}h1{margin-bottom:4px}h2{font-size:22px;margin-top:30px}p{margin:10px 0}pre{padding:18px;background:#f3f4f6;border-radius:6px;white-space:pre-wrap;overflow-wrap:anywhere;font:14px/1.7 monospace}a{color:#1760b5}summary{cursor:pointer;font-weight:600;padding:12px 0}details{border-top:1px solid #e5e7eb}li{margin:6px 0}.intro{color:#666}footer{margin-top:28px;color:#666;font-size:14px}@media(max-width:600px){body{margin:24px auto;padding:0 18px}h1{font-size:28px}}</style></head><body><main><h1>魔方云 3.9.42 安装教程</h1><p class="intro">先搭好自己的授权站，再复制命令安装。</p><h2>1. 搭建授权站</h2><p><a href="zjmf_auth_api.zip">下载授权站源码</a>，解压后把 <code>index.php</code> 上传到你的 PHP 网站根目录，再把包里的伪静态填到网站设置中。</p><p>记下这个网站的完整地址，比如 <code>https://auth.example.com/</code>，下一步要用。</p><h2>2. 运行安装命令</h2><p>SSH 登录要安装魔方云的服务器，复制下面这条命令执行：</p><pre>'.htmlspecialchars($command,ENT_QUOTES,'UTF-8').'</pre><p>按提示输入你刚搭好的<strong>授权站地址</strong>，再选择安装类型：</p><ul><li><strong>主控：</strong>安装管理后台。</li><li><strong>仅计算节点：</strong>安装运行虚拟机的节点。</li><li><strong>合并安装：</strong>同一台服务器同时做主控和节点。</li></ul><p>主控和节点都用上面这条命令，填写<strong>同一个授权站地址</strong>。</p><h2>3. 安装完成后</h2><p>保存最后显示的后台地址、账号和密码，登录后台。单独安装的计算节点，还需要在主控里添加，确认节点显示已连接。</p><p>正常情况下，后续授权会自动续签，不用每周手动操作。</p><h2>遇到问题再看这里</h2><details><summary>安装断线了，或者节点没有授权</summary><p>先查看 <code>/var/log/zjmf-bootstrap/</code> 里的安装日志。安装还在运行就等它结束，不要同时重复执行。</p><p>确认节点已经装好、安装进程已经结束，但授权接入没完成，再到<strong>节点服务器</strong>执行：</p><pre>'.htmlspecialchars($nodeCommand,ENT_QUOTES,'UTF-8').'</pre><p>填写与主控相同的授权站地址。完成后检查节点连接；仍未获得授权时，可在主控点击“下发授权”。<a href="node-recovery.md">查看详细处理步骤</a></p></details><details><summary>为什么提示只有七天授权？</summary><p>节点每次拿到的凭据有效七天，主控会提前自动续签。保持主控、节点和授权站正常通信即可。<a href="node-renewal.md">查看续签说明和测试记录</a></p></details><details><summary>已经装过，或者想换授权站地址</summary><p>已安装 3.9.42 的服务器可以重跑上面的安装命令，按提示填写授权站地址。更换地址时，主控和节点都要处理。其他版本不要直接当作升级命令使用。</p></details><footer>本套适配：专业版、9999 节点配额及 10 个随附插件。</footer></main></body></html>';exit;
    }
    if($path==='/health'||$path==='/client-config'){
        $data=['status'=>200,'service'=>'zjmf-cloud-auth','version'=>$policy['version'],'expires'=>$policy['expires'],'quota'=>$policy['quota'],'plugins'=>$policy['plugins']];
        if($path==='/client-config'){$data['public_key']=PUBLIC_KEY;$data['public_key_sha256']=hash('sha256',PUBLIC_KEY);$data['node_authorization_version']=1;$data['node_public_key']=NODE_PUBLIC_KEY;$data['node_public_key_sha256']=hash('sha256',NODE_PUBLIC_KEY);}
        respond($data);
    }
    if((int)($_SERVER['CONTENT_LENGTH']??0)>262144)respond(['status'=>413,'msg'=>'Request too large'],413);
    $request=$_REQUEST;$raw=file_get_contents('php://input');
    if(strlen($raw)>262144)respond(['status'=>413,'msg'=>'Request too large'],413);
    if($raw!==''){$json=json_decode($raw,true);if(is_array($json))$request=array_replace($request,$json);}
    if($path==='/app/api/node_authorize')respond(['status'=>200,'msg'=>'节点授权成功','data'=>node_authorization($request,$policy)]);
    if($path==='/app/api/bootstrap'){
        $baseline=$request['baseline']??null;
        if(!is_array($baseline)||empty($baseline['license'])||($baseline['install_version']??'')!=='3.9.42'||($baseline['license']??'')!==($request['license']??''))respond(['status'=>400,'msg'=>'Invalid client identity'],400);
        store_client(client_file($request),$baseline);respond(['status'=>200,'msg'=>'Client registered']);
    }
    if(in_array($path,['/app/api/auth_complete','/app/api/auth_update'],true)){
        if(empty($request['license']))respond(['status'=>400,'msg'=>'Missing license identity'],400);
        $record=current_record($request,$policy);
        respond(['status'=>200,'msg'=>'授权成功','data'=>sign_license(json_encode($record,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES),PRIVATE_KEY),'ip'=>sign_license($record['ip'],PRIVATE_KEY)]);
    }
    if(in_array($path,['/app/api/auth','/app/api/toggle_version'],true))respond(['status'=>200,'msg'=>'授权成功','professional'=>true,'version'=>'3.9.42','last_version'=>'3.9.42','release_version'=>'3.9.42','remote_ip'=>$request['ip']??'']);
    if($path==='/market/index'){
        $apps=[];foreach($policy['goods'] as $good)$apps[]=['hostid'=>1,'id'=>$good['id'],'qty'=>9999,'uuid'=>$good['name'],'nextduedate'=>strtotime($policy['expires'])];
        respond(['status'=>200,'msg'=>'获取成功','jwt'=>'','hostid'=>1,'productid'=>140,'son_host'=>[],'apps'=>$apps,'goods'=>$policy['goods'],'data'=>['bind'=>1]]);
    }
    if($path==='/app/api/ip')respond(['status'=>200,'msg'=>'获取成功','ip'=>$request['ip']??$_SERVER['REMOTE_ADDR'],'country_code'=>'']);
    image_api($path,$request,$imageCatalog['images'],$imageCatalog['versions'],state_directory());
    if($path==='/app/api/get_new_version')respond(['status'=>200,'msg'=>'当前适配版本','data'=>[]]);
    if($path==='/app/api/get_version')respond(['status'=>200,'msg'=>'获取成功','data'=>['version'=>'3.9.42','description'=>'自建授权站适配版本']]);
    if($path==='/app/api/sync_authorize')respond(['status'=>200,'msg'=>'获取成功','data'=>[]]);
    respond(['status'=>404,'msg'=>'未实现的接口'],404);
} catch(Throwable $e){error_log('Cloud auth: '.$e->getMessage());respond(['status'=>500,'msg'=>'授权站内部错误，请检查 PHP 日志和目录写入权限'],500);}
