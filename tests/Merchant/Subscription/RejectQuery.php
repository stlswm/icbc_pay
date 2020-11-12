<?php

namespace Merchant\Subscription;

use Exception;
use PHPUnit\Framework\TestCase;
use stlswm\IcbcPay\Client\DefaultClient;

/**
 * Class RejectQuery
 * @package Merchant\Subscription
 */
class RejectQuery extends TestCase
{
    /**
     * @throws Exception
     */
    public function testRejectQuery()
    {
        $config = json_decode(file_get_contents(__DIR__.'/../../Config/config.json'), true);
        $myPrivateKey = file_get_contents(__DIR__.'/../../Config/yourname.pri');
        $icbcPubicKey = file_get_contents(__DIR__.'/../../Config/icbc.pub');
        $cli = new DefaultClient($config['app_id'], $myPrivateKey, $icbcPubicKey, 'RSA2', 'AES',
            $config['encrypt_key']);
        $req = new \stlswm\IcbcPay\Merchant\Subscription\RejectQuery();
        $req->setBusinessParam('mer_id', $config['mer_id']);
        $req->setBusinessParam('out_trade_no', "0123456789");
        $req->setBusinessParam('order_id', "1234567890");
        $req->setBusinessParam('reject_no', "1234567890");
        $req->setReqEncrypt(true);
        $res = $cli->exec($req, date('YmdHis').mt_rand(1000, 9999),
            \stlswm\IcbcPay\Merchant\Subscription\RejectQuery::UrlV3);
        $this->assertSame(true, $res->isSuccess());
    }
}