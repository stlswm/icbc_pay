<?php

namespace stlswm\IcbcPay\Merchant\Subscription;

use Exception;
use stlswm\IcbcPay\Request\BaseRequest;

/**
 * Class Query
 * @package stlswm\IcbcPay\Merchant\Subscription
 */
class Query extends BaseRequest
{
    const UrlV2 = 'https://gw.open.icbc.com.cn/api/qrcode/V2/query';

    /**
     * @var string
     */
    protected string $mer_id;
    /**
     * @var string
     */
    protected string $cust_id;
    /**
     * @var string
     */
    protected string $out_trade_no;
    /**
     * @var string
     */
    protected string $order_id;

    /**
     * 设置业务参数
     * @param  string  $name
     * @param  mixed  $value
     * @throws Exception
     */
    public function setBusinessParam(string $name, $value)
    {
        if (!property_exists($this, $name)) {
            throw new Exception('无效参数：'.$name);
        }
        $this->biz_list[] = $name;
        $this->$name = $value;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function isValid(): bool
    {
        if (empty($this->mer_id)) {
            throw new Exception("param mer_id must be set");
        }
        if (empty($this->out_trade_no) && empty($this->order_id)) {
            throw new Exception("param out_trade_no and order_id must be set at least one");
        }
        return true;
    }

    /**
     * @return array
     */
    public function exportBusinessParam(): array
    {
        $params = [];
        foreach ($this->biz_list as $column) {
            $params[$column] = $this->$column;
        }
        return $params;
    }

    /**
     * @return array
     */
    public function signExceptParamList(): array
    {
        return [];
    }
}