<?php

namespace stlswm\IcbcPay\Response;

/**
 * Class ICBCResponse
 * @package stlswm\IcbcPay\Response
 */
class ICBCResponse
{
    /**
     * @var array
     */
    protected array $_apiResContent;
    /**
     * @var string
     */
    public string $msgId;
    /**
     * @var string
     */
    public string $returnCode;
    /**
     * @var string
     */
    public string $returnMsg;

    /**
     * ICBCResponse constructor.
     * @param  array  $apiResContent
     */
    public function __construct(array $apiResContent)
    {
        $this->msgId = $apiResContent['msg_id'];
        $this->returnCode = $apiResContent['return_code'];
        $this->returnMsg = $apiResContent['return_msg'];
        unset($apiResContent['msg_id']);
        unset($apiResContent['return_code']);
        unset($apiResContent['return_msg']);
        $this->_apiResContent = $apiResContent;
    }

    /**
     * 验证请求是否成功
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->returnCode === '0';
    }

    /**
     * 获取api返回内容
     * @return array
     */
    public function getBody(): array
    {
        return $this->_apiResContent;
    }
}
