<?php

namespace Merchant\Subscription;

use Exception;
use PHPUnit\Framework\TestCase;
use stlswm\IcbcPay\Client\DefaultClient;

/**
 * Class PayRequest
 * @package Merchant\Subscription
 */
class PayRequest extends TestCase
{
    /**
     * 支付下单测试
     * @throws Exception
     */
    public function testPay()
    {
        $config = json_decode(file_get_contents(__DIR__.'/../../Config/config.json'), true);
        $myPrivateKey = file_get_contents(__DIR__.'/../../Config/yourname.pri');
        $icbcPubicKey = file_get_contents(__DIR__.'/../../Config/icbc.pub');
        $cli = new DefaultClient($config['app_id'], $myPrivateKey, $icbcPubicKey, 'RSA2', 'AES',
            $config['encrypt_key']);
        $req = new \stlswm\IcbcPay\Merchant\Subscription\PayRequest();
        $req->setBusinessParam('mer_id', $config['mer_id']);
        $req->setBusinessParam('tp_app_id', $config['tp_app_id']);
        $req->setBusinessParam('tp_open_id', $config['tp_open_id']);
        $req->setBusinessParam('out_trade_no', date('YmdHis').mt_rand(1000, 9999));
        $req->setBusinessParam('tran_type', 'OfflinePay');
        $req->setBusinessParam('order_date', date('YmdHis'));
        $req->setBusinessParam('end_time', date('YmdHis', strtotime("+1 hour")));
        $req->setBusinessParam('goods_body', '芬达');
        $req->setBusinessParam('goods_detail', '{"good_name":"芬达橙味300ml罐装","good_id":"1001","good_num":1}');
        $req->setBusinessParam('order_amount', 1);
        $req->setBusinessParam('spbill_create_ip', '127.0.0.1');
        $req->setBusinessParam('install_times', 1);
        $req->setBusinessParam('return_url', 'http://localhost');
        $req->setBusinessParam('notify_url', 'http://localhost');
        $req->setBusinessParam('notify_type', 'AG');
        $req->setReqEncrypt(true);
        $res = $cli->exec($req, date('YmdHis').mt_rand(1000, 9999),
            \stlswm\IcbcPay\Merchant\Subscription\PayRequest::UrlV2);
        $this->assertSame(true, $res->isSuccess());
    }
}