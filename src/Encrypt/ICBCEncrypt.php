<?php

namespace stlswm\IcbcPay\Encrypt;

use Exception;
use stlswm\PHPEncrypt\AES;

/**
 * Class AES
 * @package stlswm\IcbcPay\Encrypt
 */
class ICBCEncrypt
{
    /**
     * 加密
     * @param  string  $type
     * @param  string  $key  秘钥
     * @param  string  $iv  向量
     * @param  string  $content  加密内容
     * @return string
     * @throws Exception
     */
    public static function encrypt(string $type, string $key, string $iv, string $content): string
    {
        switch ($type) {
            case "AES":
                $aes = new AES(base64_decode($key), 'aes-128-cbc', $iv, OPENSSL_RAW_DATA);
                return base64_encode($aes->encrypt($content));
            default:
                throw new Exception('encrypt type:'.$type.' is not supported');
        }
    }

    /**
     * 解密
     * @param  string  $type
     * @param  string  $key  秘钥
     * @param  string  $iv  向量
     * @param  string  $content  解密内容
     * @return string
     * @throws Exception
     */
    public static function decrypt(string $type, string $key, string $iv, string $content): string
    {
        switch ($type) {
            case "AES":
                $aes = new AES(base64_decode($key), 'aes-128-cbc', $iv, OPENSSL_RAW_DATA);
                return $aes->decrypt($content);
            default:
                throw new Exception('encrypt type:'.$type.' is not supported');
        }
    }
}