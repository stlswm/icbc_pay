<?php

namespace stlswm\IcbcPay\Client;

use Exception;
use stlswm\IcbcPay\Encrypt\ICBCEncrypt;
use stlswm\IcbcPay\Request\BaseRequest;
use stlswm\IcbcPay\Response\ICBCResponse;
use stlswm\IcbcPay\Sign\RSAWithSha1;
use stlswm\IcbcPay\Sign\RSAWithSha256;

/**
 * Class DefaultClient
 * @package stlswm\IcbcPay\Client
 */
class DefaultClient
{
    protected string $appId;
    protected string $merId;
    protected string $myPrivateKey;
    protected string $icbcPubicKey;
    protected string $signType;
    protected string $charset;
    protected string $encryptType;
    protected string $encryptKey;
    protected string $encryptIv;

    /**
     * DefaultClient constructor.
     * @param  string  $appId
     * @param  string  $myPrivateKey
     * @param  string  $icbcPubicKey
     * @param  string  $signType
     * @param  string  $encryptType
     * @param  string  $encryptKey
     * @param  string  $charset
     */
    public function __construct(
        string $appId,
        string $myPrivateKey,
        string $icbcPubicKey,
        string $signType,
        string $encryptType = '',
        string $encryptKey = '',
        string $charset = 'UTF-8'
    ) {
        $this->appId = $appId;
        $this->myPrivateKey = $myPrivateKey;
        $this->icbcPubicKey = $icbcPubicKey;
        $this->signType = $signType;
        $this->charset = $charset;
        $this->encryptType = $encryptType;
        $this->encryptKey = $encryptKey;
        $this->encryptIv = str_repeat("\0", 16);
        $this->charset = $charset;
    }

    /**
     * 设置商户号
     * @param  string  $merId
     */
    public function setMerId(string $merId)
    {
        $this->merId = $merId;
    }

    /**
     * 获取商户号
     * @return string
     */
    public function getMerId(): string
    {
        return $this->merId;
    }

    /**
     * 获取工行网关公钥
     * @return string
     */
    public function getIcbcPubicKey(): string
    {
        return $this->icbcPubicKey;
    }


    /**
     * 验证工行返回数据
     * @param  string  $json
     * @return bool
     */
    public function icbcDataVerify(string $json): bool
    {
        $indexOfRootStart = strpos($json, "response_biz_content") + strlen("response_biz_content") + 2;
        $indexOfRootEnd = strrpos($json, ",\"sign\":\"");
        $indexOfSignStart = $indexOfRootEnd + strlen("sign") + 5;
        $indexOfSignEnd = strrpos($json, "\"");
        $respBizContentStr = substr($json, $indexOfRootStart, ($indexOfRootEnd - $indexOfRootStart));
        $sign = substr($json, $indexOfSignStart, ($indexOfSignEnd - $indexOfSignStart));
        return RSAWithSha1::verifySign($respBizContentStr, $sign, $this->icbcPubicKey);
    }

    /**
     * 异步通知签名
     * @param  string  $path
     * @param  array  $param
     * @return bool
     * @throws Exception
     */
    public function icbcNotifyDatVerify(string $path, array $param): bool
    {
        if (!isset($param['sign'])) {
            throw new Exception('param sign is required');
        }
        $sign = $param['sign'];
        unset($param['sign']);
        $path = parse_url($path, PHP_URL_PATH);
        $signStr = $path.'?';
        ksort($param);
        foreach ($param as $key => $value) {
            if (null == $key || "" == $key || null == $value || "" == $value) {
                continue;
            }
            $signStr .= $key.'='.$value.'&';
        }
        $signStr = substr($signStr, 0, -1);
        return RSAWithSha1::verifySign($signStr, $sign, $this->icbcPubicKey);
    }


    /**
     * @param  BaseRequest  $request
     * @param  string  $msgId
     * @param  string  $url  请求地址
     * @param  string  $method  请求方式
     * @return ICBCResponse
     * @throws Exception
     */
    public function exec(BaseRequest $request, string $msgId, string $url, string $method = 'post'): ICBCResponse
    {
        $method = strtolower($method);
        //公共参数
        $params = [];
        $params['app_id'] = $this->appId;
        $params['msg_id'] = $msgId;
        $params['format'] = "json";
        $params['charset'] = $this->charset;
        $params['sign_type'] = $this->signType;
        $params['timestamp'] = date('Y-m-d H:i:s');
        $bizContent = json_encode($request->exportBusinessParam());
        if ($request->isReqEncrypt()) {
            $params['encrypt_type'] = $this->encryptType;
            $bizContent = ICBCEncrypt::encrypt($this->encryptType, $this->encryptKey, $this->encryptIv, $bizContent);
        }
        $params['biz_content'] = $bizContent;
        //参数验证
        if (!$request->isValid()) {
            throw new Exception('参数验证失败');
        }
        //签名
        $signExceptList = $request->signExceptParamList();
        if ($signExceptList) {
            foreach ($signExceptList as $name) {
                unset($params[$name]);
            }
        }
        switch ($this->signType) {
            case 'RSA':
                $params['sign'] = RSAWithSha1::sign($url, $params, $this->myPrivateKey);
                break;
            case 'RSA2':
                $params['sign'] = RSAWithSha256::sign($url, $params, $this->myPrivateKey);
                break;
            default:
                throw new Exception('Signature method not supported:'.$this->signType);
        }
        switch ($method) {
            case 'get':
                $apiRes = HttpUtils::get($url, $params);
                break;
            case 'post':
                $apiRes = HttpUtils::post($url, $params);
                break;
            default:
                throw new Exception('unknown method:'.$method);
        }
        if (!$this->icbcDataVerify($apiRes)) {
            throw new Exception('icbc sign verify not passed');
        }
        $apiRes = json_decode($apiRes, true);
        if ($request->isReqEncrypt() && is_string($apiRes['response_biz_content'])) {
            $responseBizContent = ICBCEncrypt::decrypt('AES', $this->encryptKey, $this->encryptIv,
                $apiRes['response_biz_content']);
            $apiRes['response_biz_content'] = json_decode($responseBizContent, true);
        }
        return new ICBCResponse($apiRes['response_biz_content']);
    }
}