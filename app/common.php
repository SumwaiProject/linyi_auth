<?php
// 应用公共文件

use think\facade\Env;

/**
 * expired -> 检查时间是否过期
 *
 * @param  string $time 
 * @return bool
 */
function expired(string $time = '')
{
    $now = time();
    if (strtotime($time)) {
        $time = strtotime($time);
    }
    return $time <= $now;
}

/**
 * AES iv
 *
 * @return string
 */
function iv()
{
    $result = '';
    for ($i = 0; $i < 16; $i++) {
        $result .= chr(0x0);
    }
    return $result;
}

/**
 * AES encode
 *
 * @param  string $plaintext 
 * @return string
 */
function encrypt($plaintext = '')
{
    $method = Env::get('aes.method', "AES-256-CBC");
    $key = hash('sha256', Env::get('aes.password', '123456'), true);
    $ciphertext = openssl_encrypt($plaintext, $method, $key, OPENSSL_RAW_DATA, iv());
    return base64_encode($ciphertext);
}

/**
 * AES decode
 *
 * @param  string $ciphertext 
 * @return string|array
 */
function decrypt($ciphertext = '', $json = false)
{
    $method = Env::get('aes.method', "AES-256-CBC");
    $ciphertext = base64_decode($ciphertext);
    $key = hash('sha256', Env::get('aes.password', '123456'), true);
    $decoded = openssl_decrypt($ciphertext, $method, $key, OPENSSL_RAW_DATA, iv());
    if ($json) {
        return json_decode($decoded, true);
    }
    return $decoded;
}