<?php

namespace stlswm\IcbcPay\Merchant\Subscription;

use Exception;
use stlswm\IcbcPay\Request\BaseRequest;

/**
 * Class Reject
 * @package stlswm\IcbcPay\Merchant\Subscription
 */
class Reject extends BaseRequest
{
    const UrlV2 = 'https://gw.open.icbc.com.cn/api/qrcode/V2/reject';
    /**
     * @var string   商户线下档案编号(特约商户12位，特约部门15位)
     */
    protected string $mer_id;
    /**
     * @var string 支付时工行返回的用户唯一标识
     */
    protected string $cust_id;
    /**
     * @var string 商户系统订单号，商户订单号或行内订单号必须其中一个不为空
     */
    protected string $out_trade_no;
    /**
     * @var string 完整行内系统订单号(特约商户27位，特约部门30位)或其后15位，商户订单号或行内订单号必须其中一个不为空
     */
    protected string $order_id;
    /**
     * @var string 商户系统生成的退款编号，每次部分退款需生成不同的退款编号
     */
    protected string $reject_no;
    /**
     * @var string 退款金额，单位：分
     */
    protected string $reject_amt;
    /**
     * @var string 操作人员ID
     */
    protected string $oper_id;

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
        if (empty($this->reject_no)) {
            throw new Exception("param reject_no must be set");
        }
        if (empty($this->reject_amt)) {
            throw new Exception("param reject_amt must be set");
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