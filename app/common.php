<?php
// 应用公共文件

use app\model\UserModel;
use think\facade\Cache;
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
 * AES encode
 *
 * @param  string $plaintext 
 * @return string
 */
function encrypt($plaintext = '')
{
    return base64_encode(rc4(hash('sha256', Env::get('aes.password', '123456')), $plaintext));
}

/**
 * AES decode
 *
 * @param  string $ciphertext 
 * @return string|array
 */
function decrypt($ciphertext = '', $json = false)
{
    $decoded = rc4(hash('sha256', Env::get('aes.password', '123456')), base64_decode($ciphertext));
    if ($json) {
        if ($ret = json_decode($decoded, true)) {
            return $ret;
        }

        if (strpos($decoded, '=') !== false && strpos($decoded, '&') !== false) {
            $ret = [];
            $arr = explode('&', $decoded);
            foreach ($arr as &$v) {
                list($key, $val) = explode('=', $v);
                $ret[$key] = $val;
            }
            if (is_array($ret) && count($ret) > 0) {
                return $ret;
            }
        }
    }
    return $decoded;
}

/**
 * create salt str
 *
 * @return string
 */
function salt()
{
    return md5(uniqid());
}

function rc4($pwd, $data)
{
    $cipher      = '';
    $key[]       = "";
    $box[]       = "";
    $pwd_length  = strlen($pwd);
    $data_length = strlen($data);
    for ($i = 0; $i < 256; $i++) {
        $key[$i] = ord($pwd[$i % $pwd_length]);
        $box[$i] = $i;
    }
    for ($j = $i = 0; $i < 256; $i++) {
        $j       = ($j + $box[$i] + $key[$i]) % 256;
        $tmp     = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }
    for ($a = $j = $i = 0; $i < $data_length; $i++) {
        $a       = ($a + 1) % 256;
        $j       = ($j + $box[$a]) % 256;
        $tmp     = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $k       = $box[(($box[$a] + $box[$j]) % 256)];
        $cipher .= chr(ord($data[$i]) ^ $k);
    }
    return $cipher;
}
