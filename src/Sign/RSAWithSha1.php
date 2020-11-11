<?php

namespace stlswm\IcbcPay\Sign;

/**
 * Class RSAWithSha1
 * @package stlswm\IcbcPay\Sign
 */
class RSAWithSha1
{
    /**
     * @param  string  $url
     * @param  array  $body
     * @param  string  $privateKey
     * @return string
     */
    public static function sign(string $url, array $body, string $privateKey): string
    {
        unset($body['sign']);
        ksort($body);
        $url = parse_url($url, PHP_URL_PATH);
        $signStr = $url.'?';
        foreach ($body as $key => $value) {
            if (null == $key || "" == $key || null == $value || "" == $value) {
                continue;
            }
            $signStr .= $key.'='.$value.'&';
        }
        $signStr = substr($signStr, 0, -1);
        return self::signStr($signStr, $privateKey);
    }

    /**
     * @param  string  $toSign
     * @param  string  $privateKey
     * @return string
     */
    public static function signStr(string $toSign, string $privateKey): string
    {
        $key = openssl_pkey_get_private($privateKey);
        openssl_sign($toSign, $signature, $key, OPENSSL_ALGO_SHA1);
        openssl_free_key($key);
        return base64_encode($signature);
    }

    /**
     * @param  string  $data
     * @param  string  $sign
     * @param  string  $pubKey
     * @return bool
     */
    public static function verifySign(string $data, string $sign, string $pubKey): bool
    {
        return (bool)openssl_verify($data, base64_decode($sign), $pubKey, OPENSSL_ALGO_SHA1);
    }
}