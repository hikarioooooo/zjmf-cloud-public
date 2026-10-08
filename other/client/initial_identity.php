<?php
declare(strict_types=1);
require __DIR__.'/protocol.php';
try {
    if(PHP_SAPI!=='cli'||count($argv)!==3)throw new RuntimeException('Usage: initial_identity.php CACHE ORIGINAL_PUBLIC_KEY');
    $outer=json_decode(stream_codec(trim(file_get_contents($argv[1])),'decode','default_key'),true);
    if(!is_array($outer)||!is_string($outer['license']??null))throw new RuntimeException('Invalid installer identity envelope');
    $identity=$outer['license'];
    // The native installer preserves the entered identity; it can be longer
    // than an MD5 token. Retain that identity and validate its signed IP below.
    // Ignore retained terminal backspace bytes for validation only. Signing
    // must use the exact identity that the original application has stored.
    $visible=str_replace(["\x08","\x7f"],'',$identity);
    if(strlen($identity)<1||strlen($identity)>256||$visible===''||preg_match('/[\x00-\x20\x7f]/',$visible))throw new RuntimeException('Invalid installer license identity: expected a non-empty token without whitespace or unsupported control characters (up to 256 bytes)');
    if(array_key_exists('zjmf_authorize',$outer))throw new RuntimeException('Existing signed authorization is unreadable; refusing a fresh-install fallback');
    $ip=read_license((string)($outer['authsystemip']??''),file_get_contents($argv[2]));
    if(filter_var($ip,FILTER_VALIDATE_IP)===false)throw new RuntimeException('Installer system IP signature is invalid');
    echo json_encode(['license'=>$outer['license'],'ip'=>$ip,'domain'=>'','type'=>'cloud','install_version'=>'3.9.42'],JSON_UNESCAPED_SLASHES),"\n";
} catch(Throwable $error) {
    fwrite(STDERR,'Initial identity: '.$error->getMessage()."\n");exit(1);
}
