<?php

namespace stlswm\IcbcPay\Request;

/**
 * Class BaseRequest
 * @package stlswm\IcbcPay\Request
 */
abstract class BaseRequest
{
    /**
     * @var string[] 请求参数列表
     */
    protected array $biz_list;

    /**
     * @var bool 是否要对请求进行加密
     */
    protected bool $reqEncrypt = false;

    /**
     * 设置请求体参数键值
     * @param  string  $name
     * @param  mixed  $value
     * @return mixed
     */
    public abstract function setBusinessParam(string $name, $value);

    /**
     * 验证业务参数
     * @return bool
     */
    public abstract function isValid(): bool;

    /**
     * 导出请求业务参数
     * @return array
     */
    public abstract function exportBusinessParam(): array;

    /**
     * 不参与签名的参数名称列表
     * @return array
     */
    public abstract function signExceptParamList(): array;

    /**
     * 是否需要对请求加密
     * @param  bool  $isEncrypt
     */
    public function setReqEncrypt(bool $isEncrypt = true)
    {
        $this->reqEncrypt = $isEncrypt;
    }

    /**
     * 请求是否要求加密
     * @return bool
     */
    public function isReqEncrypt(): bool
    {
        return $this->reqEncrypt;
    }
}