<?php

namespace stlswm\IcbcPay\QR;

use Exception;
use stlswm\IcbcPay\Request\BaseRequest;

/**
 * Class QRGenerator
 * @package stlswm\IcbcPay\QR
 */
class QRGenerator extends BaseRequest
{
    const UrlV2 = "https://gw.open.icbc.com.cn/api/qrcode/V2/generate";
    /**
     * @var string 商户线下档案编号
     */
    public string $mer_id;
    /**
     * @var string e生活档案编号
     */
    public string $store_code;
    /**
     * @var string 商户系统订单号
     */
    public string $out_trade_no;
    /**
     * @var string 订单总金额
     */
    public string $order_amt;
    /**
     * @var string 商户订单生成日期
     */
    public string $trade_date;
    /**
     * @var string 商户订单生成时间
     */
    public string $trade_time;
    /**
     * @var string 商户附加数据
     */
    public string $attach;
    /**
     * @var string 二维码有效期
     */
    public string $pay_expire;
    /**
     * @var string 商户接收支付成功通知消息URL
     */
    public string $notify_url;
    /**
     * @var string 商户订单生成的机器IP
     */
    public string $tporder_create_ip;
    /**
     * @var string 扫码后是否需要跳转分行
     */
    public string $sp_flag;
    /**
     * @var string 商户是否开启通知接口
     */
    public string $notify_flag;

    /**
     * QRGenerator constructor.
     */
    public function __construct()
    {
    }

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
        $mustFields = [
            'mer_id',
            'out_trade_no',
            'order_amt',
            'trade_date',
            'trade_time',
            'pay_expire',
            'tporder_create_ip',
        ];
        foreach ($mustFields as $field) {
            if (empty($this->$field)) {
                throw new Exception("param {$field} must be set");
            }
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