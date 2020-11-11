<?php

namespace stlswm\IcbcPay\Client;

use Exception;
use stlswm\IcbcPay\Encrypt\ICBCEncrypt;
use stlswm\IcbcPay\Request\BaseRequest;
use stlswm\IcbcPay\Sign\RSAWithSha1;
use stlswm\IcbcPay\Sign\RSAWithSha256;

/**
 * Class DefaultClient
 * @package stlswm\IcbcPay\Client
 */
class DefaultClient
{
    protected string $appId;
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
     * @param  BaseRequest  $request
     * @param  string  $msgId
     * @param  string  $url  请求地址
     * @param  string  $method  请求方式
     * @throws Exception
     */
    public function exec(BaseRequest $request, string $msgId, string $url, string $method = 'post')
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
            $bizContent = ICBCEncrypt::encrypt('AES', $this->encryptKey, $this->encryptIv, $bizContent);
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
                return HttpUtils::get($url, $params);
            case 'post':
                return HttpUtils::post($url, $params);
            default:
                throw new Exception('unknown method:'.$method);
        }
    }
}