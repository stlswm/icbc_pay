<?php

namespace stlswm\IcbcPay\Merchant\Subscription;

use Exception;
use stlswm\IcbcPay\Request\BaseRequest;

/**
 * Class RejectQuery
 * @package stlswm\IcbcPay\Merchant\Subscription
 */
class RejectQuery extends BaseRequest
{
    const UrlV3 = 'https://gw.open.icbc.com.cn/api/qrcode/reject/query/V3';
    /**
     * @var string   商户线下档案编号(特约商户12位，特约部门15位)
     */
    protected string $mer_id;
    /**
     * @var string 支付时工行返回的用户唯一标识
     */
    protected string $cust_id;
    /**
     * @var string 商户系统订单号
     */
    protected string $out_trade_no;
    /**
     * @var string 完行内系统订单号(特约商户27位，特约部门30位)
     */
    protected string $order_id;
    /**
     * @var string 商户系统生成的退款编号
     */
    protected string $reject_no;

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
        if (empty($this->out_trade_no)) {
            throw new Exception("param out_trade_no must be set");
        }
        if (empty($this->order_id)) {
            throw new Exception("param order_id must be set");
        }
        if (empty($this->reject_no)) {
            throw new Exception("param reject_no must be set");
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