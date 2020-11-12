<?php

namespace stlswm\IcbcPay\Merchant\Subscription;

use \Exception;
use stlswm\IcbcPay\Request\BaseRequest;

/**
 * Class PayRequest
 * @package stlswm\IcbcPay\Merchant\Subscription
 */
class PayRequest extends BaseRequest
{
    const UrlV1 = 'https://gw.open.icbc.com.cn/ui/aggregate/payment/request/V1';
    const UrlV2 = 'https://gw.open.icbc.com.cn/ui/aggregate/payment/request/V2';
    //接口号，目前仅支持上送1.1.0.0
    protected string $interface_version;
    /**
     * @var string 商户号
     */
    protected string $mer_id;
    /**
     * @var string 渠道商号；商户通过渠道商接入时必送。目前暂不支持上送。
     */
    protected string $channel_id;
    /**
     * @var string 第三方应用ID；商户在微信公众号内接入时必送，上送微信分配的公众账号ID；商户通过支付宝生活号接入时必送，上送支付宝分配的应用ID。目前暂不支持上送。
     */
    protected string $tp_app_id;
    /**
     * @var string 第三方用户标识；商户在微信公众号/支付宝生活号内接入时必送，上送用户在商户appid下的唯一标识。 目前暂不支持上送。
     */
    protected string $tp_open_id;
    /**
     * @var string 商户订单号；需保证商户系统唯一
     */
    protected string $out_trade_no;
    /**
     * @var string 交易类型。用于区分交易场景为线上支付还是线下支付，对应数据字典：OfflinePay-线下支付，OnlinePay-线上支付。商户需按实际交易场景上送，如上送错误可能影响后续交易的进行；比如线上支付场景，上送OfflinePay-线下支付，使用微信支付时，微信会对实际交易场景进行检查，一旦发现不符，微信侧会拒绝对应交易请求
     */
    protected string $tran_type;
    /**
     * @var string 交易提交时间， 格式为：YYYYMMDDHHmmss
     */
    protected string $order_date;
    /**
     * @var string 交易过期时间，格式为：YYYYMMDDHHmmss。建议上送为order_date之后的五分钟或者固定为每晚11点这种形式。
     */
    protected string $end_time;
    /**
     * @var string 商品描述
     */
    protected string $goods_body;
    /**
     * @var string 商品详情
     */
    protected string $goods_detail;
    /**
     * @var string 附加数据。商户可上送定制信息（如商户会话ID、终端设备编号等），在支付结束后的支付结果通知报文中该字段原样返回,该字样可以在对账单中体现
     */
    protected string $attach;
    /**
     * @var string 总金额（单位：分）
     */
    protected string $order_amount;
    /**
     * @var string 终端ip
     */
    protected string $spbill_create_ip;
    /**
     * @var string 分期期数。目前仅支持1-不分期
     */
    protected string $install_times;
    /**
     * @var string 商家提示。目前暂无处理，后续可用于在交易页面回显给客户
     */
    protected string $mer_hint;
    /**
     * @var string 支付成功回显页面。支付成功后，客户端引导跳转至该页面显示
     */
    protected string $return_url;
    /**
     * @var string 支付方式限定；上送”no_credit“表示不支持信用卡支付；不上送或上送空表示无限制；上送“no_balance”表示仅支持银行卡支付（需要微信审批通过后可以接入）
     */
    protected string $pay_limit;
    /**
     * @var string 支付结果通知地址；上送互联网可访问的完整URL地址（必须包含协议）；应支持受理同一笔订单的多次通知场景
     */
    protected string $notify_url;
    /**
     * @var string 通知类型，表示在交易处理完成后把交易结果通知商户的处理模式。 取值“HS”：在交易完成后将通知信息，主动发送给商户，发送地址为notify_url指定地址； 取值“AG”：在交易完成后不通知商户
     */
    protected string $notify_type;
    /**
     * @var string 结果发送类型，通知方式为HS时有效。取值“0”：无论支付成功或者失败，银行都向商户发送交易通知信息；取值“1”，银行只向商户发送交易成功的通知信息
     */
    protected string $result_type;
    /**
     * @var string 备用字段1，后续扩展使用
     */
    protected string $backup1;
    /**
     * @var string 备用字段2，后续扩展使用
     */
    protected string $backup2;
    /**
     * @var string 备用字段3，后续扩展使用
     */
    protected string $backup3;
    /**
     * @var string 备用字段4，后续扩展使用
     */
    protected string $backup4;
    
    /**
     * PayRequest constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $this->setBusinessParam('interface_version', '1.0.0.0');
        $this->setBusinessParam('install_times', '1');
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
            'tran_type',
            'order_date',
            'end_time',
            'goods_body',
            'goods_detail',
            'order_amount',
            'spbill_create_ip',
            'install_times',
            'return_url',
            'notify_url',
            'notify_type',
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