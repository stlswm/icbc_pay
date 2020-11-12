<?php

namespace Merchant\Subscription;

use Exception;
use PHPUnit\Framework\TestCase;
use stlswm\IcbcPay\Client\DefaultClient;

/**
 * Class Reject
 * @package Merchant\Subscription
 */
class Reject extends TestCase
{
    /**
     * @throws Exception
     */
    public function testReject()
    {
        $config = json_decode(file_get_contents(__DIR__.'/../../Config/config.json'), true);
        $myPrivateKey = file_get_contents(__DIR__.'/../../Config/yourname.pri');
        $icbcPubicKey = file_get_contents(__DIR__.'/../../Config/icbc.pub');
        $cli = new DefaultClient($config['app_id'], $myPrivateKey, $icbcPubicKey, 'RSA2', 'AES',
            $config['encrypt_key']);
        $req = new \stlswm\IcbcPay\Merchant\Subscription\Reject();
        $req->setBusinessParam('mer_id', $config['mer_id']);
        $req->setBusinessParam('out_trade_no', "0123456789");
        $req->setBusinessParam('reject_no', "1234567890");
        $req->setBusinessParam('reject_amt', "1");
        $req->setReqEncrypt(true);
        $res = $cli->exec($req, date('YmdHis').mt_rand(1000, 9999),
            \stlswm\IcbcPay\Merchant\Subscription\Reject::UrlV2);
        $this->assertSame(true, $res->isSuccess());
    }
}