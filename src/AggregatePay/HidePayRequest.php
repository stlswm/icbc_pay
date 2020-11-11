<?php

namespace stlswm\IcbcPay\AggregatePay;

use \Exception;
use stlswm\IcbcPay\Request\BaseRequest;

/**
 * Class HidePayRequest
 * 平台公众号及小程序埋名聚合支付
 * https://open.icbc.com.cn/icbc/apip/api_detail.html?apiId=10000000000000046000&baseUrl=%2Fmybank%2Fpay%2Faggregatepay&resUrl=%2Fhidepayrequest&version=V1&apiName=%E5%B9%B3%E5%8F%B0%E5%85%AC%E4%BC%97%E5%8F%B7%E5%8F%8A%E5%B0%8F%E7%A8%8B%E5%BA%8F%E5%9F%8B%E5%90%8D%E8%81%9A%E5%90%88%E6%94%AF%E4%BB%98&serviceId=P0067&resourceId=10000000000000004420
 * @package stlswm\IcbcPay\AggregatePay
 */
class HidePayRequest extends BaseRequest
{
    const UrlV1 = 'https://gw.open.icbc.com.cn/api/mybank/pay/aggregatepay/hidepayrequest/V1';
    //接口号，目前仅支持上送1.1.0.0
    protected string $interface_version;
    //商户号
    protected string $mer_id;
    //渠道商号；商户通过渠道商接入时必送。目前暂不支持上送。
    protected string $channel_id;
    //第三方应用ID；商户在微信公众号内或微信小程序内接入时必送，上送微信分配的公众账号ID；商户通过支付宝生活号接入时不送。
    protected string $tp_app_id;
    //第三方用户标识；商户在微信公众号内或微信小程序内接入时必送，上送用户在商户appid下的唯一标识；商户通过支付宝生活号接入时不送。
    protected string $tp_open_id;
    //第三方用户标识；商户在支付宝生活号接入时必送，上送用户的唯一标识；商户在微信公众号内或微信小程序内选送，上送用户唯一标识。
    protected string $union_id;
    //商户订单号；需保证商户系统唯一
    protected string $out_trade_no;
    //交易类型。用于区分交易场景为线上支付还是线下支付，对应数据字典：OfflinePay-线下支付，OnlinePay-线上支付。商户需按实际交易场景上送，如上送错误可能影响后续交易的进行；比如线上支付场景，上送OfflinePay-线下支付，使用微信支付时，微信会对实际交易场景进行检查，一旦发现不符，微信侧会拒绝对应交易请求
    protected string $tran_type;
    //交易提交时间， 格式为：YYYYMMDDHHmmss
    protected string $order_date;
    //交易过期时间，格式为：YYYYMMDDHHmmss
    protected string $end_time;
    //商品描述
    protected string $goods_body;
    //商品详情
    protected string $goods_detail;
    //附加数据。商户可上送定制信息（如商户会话ID、终端设备编号等），在支付结束后的支付结果通知报文中该字段原样返回,该字样可以在对账单中体现
    protected string $attach;
    //总金额（单位：分）
    protected string $order_amount;
    //请求发起终端ip（商户后台发起请求的服务器IP地址，如果获取不到上送127.0.0.1即可）
    protected string $spbill_create_ip;
    //分期期数。目前仅支持1-不分期
    protected string $install_times;
    //商家提示。目前暂无处理，可以不送
    protected string $mer_hint;
    //支付成功回显页面。支付成功后，客户端引导跳转至该页面显示
    protected string $return_url;
    //支付方式限定；上送”no_credit“表示不支持信用卡支付；不上送或上送空表示无限制；上送“no_balance”表示仅支持银行卡支付
    protected string $pay_limit;
    //支付结果通知地址；上送互联网可访问的完整URL地址（必须包含协议）；应支持受理同一笔订单的多次通知场景
    protected string $notify_url;
    //通知类型，表示在交易处理完成后把交易结果通知商户的处理模式。 取值“HS”：在交易完成后将通知信息，主动发送给商户，发送地址为notify_url指定地址； 取值“AG”：在交易完成后不通知商户
    protected string $notify_type;
    //结果发送类型，通知方式为HS时有效。取值“0”：无论支付成功或者失败，银行都向商户发送交易通知信息；取值“1”，银行只向商户发送交易成功的通知信息
    protected string $result_type;
    //下单发起渠道，100对应微信小程序，101对应微信公众号，102对应支付宝生活号
    protected string $order_channel;
    //备用字段1，后续扩展使用
    protected string $backup1;
    //备用字段2，后续扩展使用
    protected string $backup2;
    //备用字段3，后续扩展使用
    protected string $backup3;
    //备用字段4，后续扩展使用
    protected string $backup4;

    /**
     * HidePayRequest constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $this->setBusinessParam('interface_version', '1.1.0.0');
        $this->setBusinessParam('result_type', '0');
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
            'order_amount',
            'spbill_create_ip',
            'install_times',
            'return_url',
            'notify_url',
            'notify_type',
            'order_channel',
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