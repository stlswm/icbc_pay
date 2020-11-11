<?php

namespace stlswm\IcbcPay\Client;

use Exception;

/**
 * Class HttpUtils
 * @package stlswm\IcbcPay\Client
 */
class HttpUtils
{
    /**
     * @var string
     */
    const  VersionHeaderName = 'APIGW-VERSION';
    const  Version           = "v2_20170324";

    /**
     * @param $strUrl
     * @param $params
     * @return string
     */
    public static function buildGetUrl($strUrl, $params)
    {
        if ($params == null || count($params) == 0) {
            return $strUrl;
        }
        $buildUrlParams = http_build_query($params);
        if (strrpos($strUrl, '?', 0) != (strlen($strUrl) + 1)) {
            //最后是否以？结尾
            return $strUrl.'?'.$buildUrlParams;
        }
        return $strUrl.$buildUrlParams;
    }

    /**
     * @param $url
     * @param $params
     * @return bool|string
     * @throws Exception
     */
    public static function get($url, $params)
    {
        $headers = [];
        $headers[self::VersionHeaderName] = self::Version;
        $getUrl = self::buildGetUrl($url, $params);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $getUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 8000);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 30000);
        $response = curl_exec($ch);
        $resInfo = curl_getinfo($ch);
        curl_close($ch);
        if ($resInfo["http_code"] != 200) {
            throw new Exception("response status code is not valid. status code: ".$resInfo["http_code"]);
        }
        return $response;
    }

    /**
     * @param $url
     * @param $params
     * @return bool|string
     * @throws Exception
     */
    public static function post($url, $params)
    {
        $headers = [];
        $headers[] = 'Expect:';
        $headers[self::VersionHeaderName] = self::Version;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 8000);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 30000);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $response = curl_exec($ch);
        $resInfo = curl_getinfo($ch);
        curl_close($ch);
        if ($resInfo["http_code"] != 200) {
            throw new Exception("response status code is not valid. status code: ".$resInfo["http_code"]);
        }
        return $response;
    }
}