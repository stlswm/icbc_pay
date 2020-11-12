<?php

namespace Merchant\QR;

use Exception;
use PHPUnit\Framework\TestCase;
use stlswm\IcbcPay\Client\DefaultClient;

/**
 * Class QRGenerator
 * @package stlswm\IcbcPay\QR
 */
class QRGenerator extends TestCase
{
    /**
     * @throws Exception
     */
    public function testGenerator()
    {
        $config = json_decode(file_get_contents(__DIR__.'/../../Config/config.json'), true);
        $myPrivateKey = file_get_contents(__DIR__.'/../../Config/yourname.pri');
        $icbcPubicKey = file_get_contents(__DIR__.'/../../Config/icbc.pub');
        $cli = new DefaultClient($config['app_id'], $myPrivateKey, $icbcPubicKey, 'RSA2', 'AES',
            $config['encrypt_key']);
        $req = new \stlswm\IcbcPay\Merchant\QR\QRGenerator();
        $req->setBusinessParam('mer_id', $config['mer_id']);
        $req->setBusinessParam('store_code', $config['store_code']);
        $req->setBusinessParam('out_trade_no', 'ZHL777O15002039');
        $req->setBusinessParam('order_amt', '7370');
        $req->setBusinessParam('trade_date', date('Ymd'));
        $req->setBusinessParam('trade_time', date('His'));
        $req->setBusinessParam('attach', 'abcdefg');
        $req->setBusinessParam('pay_expire', '1200');
        $req->setBusinessParam('notify_url', '127.0.0.1');
        $req->setBusinessParam('tporder_create_ip', '127.0.0.1');
        $req->setBusinessParam('sp_flag', '0');
        $req->setBusinessParam('notify_flag', '1');
        $back = $cli->exec($req, date('YmdHis').mt_rand(1000, 9999),
            \stlswm\IcbcPay\Merchant\QR\QRGenerator::UrlV2);
        $this->assertSame(true, $back->isSuccess());
    }
}