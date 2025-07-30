<?php 
namespace app\util;

class Security {
    public static function checkRequestCode($requestCode, $tag, $iv): bool
    {
        $code = env('REQUEST_CODE');
        $key = base64_decode($code);
        $tag = base64_decode($tag);
        $iv = base64_decode($iv);
        $cipherCode = base64_decode($requestCode);
        $method = 'aes-128-gcm';
        $plainCode = openssl_decrypt($cipherCode, $method, $key, OPENSSL_RAW_DATA, $iv, $tag);
        $plainCodeBase64 = base64_encode($plainCode);
        $ret = strcmp($plainCodeBase64, $code);
        return $ret == 0;
    }

    public static function checkIpLimit(string $ip, int $max = 20): bool
    {
        $key = 'ip_limit_' . $ip;
        $count = cache($key) ?? 0;

        if ($count >= $max) {
            return false;
        }

        // 每次 +1（设置 24 小时过期）
        cache($key, $count + 1, 86400);
        return true;
    }

    public static function rsaDecrypt($cipherText)
    {
        $code = base64_decode(env('RSA_PRIVATE_KEY_BASE64'));

        $key = openssl_pkey_get_private($code);
        $ret = openssl_private_decrypt(base64_decode($cipherText), $plainText, $key);
        if (!$ret)
            return false;
        return $plainText;
    }

    public static function aesDecrypt($cipherText, $key) 
    {
        $method = 'aes-128-ecb';
        return openssl_decrypt(base64_decode($cipherText), $method, $key, OPENSSL_RAW_DATA);
    }

    public static function aesEncrypt($plainText, $key): string
    {
        $method = 'aes-128-ecb';
        $result = openssl_encrypt($plainText, $method, $key, OPENSSL_RAW_DATA);
        return base64_encode($result);
    }
}