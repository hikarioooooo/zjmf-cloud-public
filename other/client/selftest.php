<?php
declare(strict_types=1);
require __DIR__.'/protocol.php';
function check(bool $ok,string $label):void{if(!$ok){throw new RuntimeException('FAIL '.$label);}echo 'PASS '.$label."\n";}
$baseline=json_decode(file_get_contents($argv[1]),true);
$target=file_get_contents(ini_get('zjmf_license.target_key'));
$source='https://license.soft13.idcsmart.com/app/api/auth_update';
foreach([false,true] as $arrayMode){
    $h=curl_init();$options=[CURLOPT_URL=>$source,CURLOPT_RETURNTRANSFER=>true,CURLOPT_POST=>true,
        CURLOPT_POSTFIELDS=>http_build_query($baseline),CURLOPT_TIMEOUT=>30];
    if($arrayMode){curl_setopt_array($h,$options);}else{foreach($options as $k=>$v){curl_setopt($h,$k,$v);}}
    $raw=curl_exec($h);check(is_string($raw),'external station response');
    check(curl_getinfo($h,CURLINFO_EFFECTIVE_URL)===$source,'original effective URL retained');
    check(curl_getinfo($h)['url']===$source,'original info URL retained');
    $response=json_decode($raw,true);check(($response['status'] ?? 0)===200,'external station status');
    $record=json_decode(read_license($response['data'],$target),true);
    check($record['max_node']===9999 && $record['hyperv_max']===9999,'9999 node limits');
    check($record['auth_due_time']==='2038-12-31 23:59:59','expiry');
    check(count($record['plugin'])===10,'ten plugin entitlements');
    check($record['license']===$baseline['license'] && $record['domain']===$baseline['domain'],'client identity');
    curl_close($h);
}
$key=openssl_pkey_get_public($target);
check(@openssl_public_decrypt(str_repeat('!',128),$bad,$key)===false,'invalid RSA rejected');
$unrelated=openssl_pkey_new(['private_key_bits'=>1024,'private_key_type'=>OPENSSL_KEYTYPE_RSA]);
$details=openssl_pkey_get_details($unrelated);openssl_private_encrypt('ordinary-key',$block,$unrelated);
check(openssl_public_decrypt($block,$plain,openssl_pkey_get_public($details['key'])) && $plain==='ordinary-key','unrelated key preserved');
echo "CENTRAL_AUTH_SELFTESTS_PASSED\n";
