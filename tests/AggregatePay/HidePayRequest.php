<?php

namespace AggregatePay;

use Exception;
use PHPUnit\Framework\TestCase;
use stlswm\IcbcPay\Client\DefaultClient;

/**
 * Class HidePayRequest
 * @package AggregatePay
 */
class HidePayRequest extends TestCase
{
    /**
     * 支付下单测试
     * @throws Exception
     */
    public function testPay()
    {
        $config = json_decode(file_get_contents(__DIR__.'/../Config/config.json'), true);
        $myPrivateKey = file_get_contents(__DIR__.'/../Config/yourname.pri');
        $icbcPubicKey = file_get_contents(__DIR__.'/../Config/icbc.pub');
        $cli = new DefaultClient($config['app_id'], $myPrivateKey, $icbcPubicKey, 'RSA2', 'AES',
            $config['encrypt_key']);
        $req = new \stlswm\IcbcPay\AggregatePay\HidePayRequest();
        $req->setBusinessParam('mer_id', $config['mer_id']);
        $req->setBusinessParam('tp_app_id', $config['tp_app_id']);
        $req->setBusinessParam('tp_open_id', $config['tp_open_id']);
        $req->setBusinessParam('out_trade_no', date('YmdHis').mt_rand(1000, 9999));
        $req->setBusinessParam('tran_type', 'OfflinePay');
        $req->setBusinessParam('order_date', date('YmdHis'));
        $req->setBusinessParam('end_time', date('YmdHis', strtotime("+1 hour")));
        $req->setBusinessParam('goods_body', '测试');
        $req->setBusinessParam('order_amount', 1);
        $req->setBusinessParam('spbill_create_ip', '127.0.0.1');
        $req->setBusinessParam('install_times', 1);
        $req->setBusinessParam('return_url', 'https://www.baidu.com');
        $req->setBusinessParam('notify_url', 'https://www.baidu.com');
        $req->setBusinessParam('notify_type', 'AG');
        $req->setBusinessParam('order_channel', '101');
        $req->setReqEncrypt(true);
        $res = $cli->exec($req, date('YmdHis').mt_rand(1000, 9999),
            \stlswm\IcbcPay\AggregatePay\HidePayRequest::UrlV1);
        $this->assertSame(true, $res->isSuccess());
    }
}