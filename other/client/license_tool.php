<?php
declare(strict_types=1);
require __DIR__ . '/protocol.php';
try {
    if (PHP_SAPI !== 'cli' || count($argv)!==4) { throw new RuntimeException('Usage: license_tool.php INPUT PUBLIC_KEY OUTPUT'); }
    $record=json_decode(read_license(trim(file_get_contents($argv[1])),file_get_contents($argv[2])),true);
    if(!is_array($record) || ($record['type'] ?? '')!=='cloud' || empty($record['license'])){
        throw new RuntimeException('Authorization identity missing');
    }
    if(file_put_contents($argv[3],json_encode($record,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),LOCK_EX)===false){
        throw new RuntimeException('Cannot save decoded record');
    }
    chmod($argv[3],0600);
    echo "LICENSE_RECORD_VERIFIED\n";
}catch(Throwable $e){fwrite(STDERR,'License decode: '.$e->getMessage()."\n");exit(1);}
