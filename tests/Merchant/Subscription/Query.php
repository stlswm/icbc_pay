<?php

namespace Merchant\Subscription;

use Exception;
use PHPUnit\Framework\TestCase;
use stlswm\IcbcPay\Client\DefaultClient;

/**
 * Class Query
 * @package Merchant\Subscription
 */
class Query extends TestCase
{
    /**
     * 支付下单测试
     * @throws Exception
     */
    public function testQuery()
    {
        $config = json_decode(file_get_contents(__DIR__.'/../../Config/config.json'), true);
        $myPrivateKey = file_get_contents(__DIR__.'/../../Config/yourname.pri');
        $icbcPubicKey = file_get_contents(__DIR__.'/../../Config/icbc.pub');
        $cli = new DefaultClient($config['app_id'], $myPrivateKey, $icbcPubicKey, 'RSA2', 'AES',
            $config['encrypt_key']);
        $req = new \stlswm\IcbcPay\Merchant\Subscription\Query();
        $req->setBusinessParam('mer_id', $config['mer_id']);
        $req->setBusinessParam('out_trade_no', "0123456789");
        $req->setReqEncrypt(true);
        $res = $cli->exec($req, date('YmdHis').mt_rand(1000, 9999),
            \stlswm\IcbcPay\Merchant\Subscription\Query::UrlV2);
        $this->assertSame(true, $res->isSuccess());
    }
}