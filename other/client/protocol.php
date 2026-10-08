<?php
declare(strict_types=1);

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
