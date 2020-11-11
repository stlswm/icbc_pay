<?php

namespace Sign;

use PHPUnit\Framework\TestCase;

/**
 * Class RSAWithSha256
 * @package tests\Sign
 */
class RSAWithSha256 extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testSHA1withRSA()
    {
        $str = '/api/preciousmetal/V1/purchase?app_id=2014072300007148&biz_content={"id":"stud ent_id","name":"student_name"}&charset=GBK&sign_type=RSA&timestamp=2014-07-24 03:07:50&trade_id=123456';
        //开放平台公钥示例（非生产密钥）
        $privateCertC = join("\n", [
            '-----BEGIN RSA PRIVATE KEY-----',
            'MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBALAWAcPiTMRU906PTdy0ozspX7XptZ',
            'nkEw2C8R64RDB9BiRFXAj0cU4aTA1MyfmGIlceeVdgJf7OnmvpHnYxjQ7sGxMItPtodrGwA2y8j0AE',
            'bHc5pNWU8Hn0zoY9smHS5e+KjSbWv+VNbdnrRFTpDeiJ3+s2E/cKI2CDRbo7cAarAgMBAAECgYABiA',
            '933q4APyTvf/uTYdbRmuiEMoYr0nn/8hWayMt/CHdXNWs5gLbDkSL8MqDHFM2TqGYxxlpOPwnNsndb',
            'W874QIEKmtH/SSHuVUJSPyDW4B6MazA+/e6Hy0TZg2VAYwkB1IwGJox+OyfWzmbqpQGgs3FvuH9q25',
            'cDxkWntWbDcQJBAP2RDXlqx7UKsLfM17uu+ol9UvpdGoNEed+5cpScjFcsB0XzdVdCpp7JLlxR+UZN',
            'wr9Wf1V6FbD2kDflqZRBuV8CQQCxxpq7CJUaLHfm2kjmVtaQwDDw1ZKRb/Dm+5MZ67bQbvbXFHCRKk',
            'GI4qqNRlKwGhqIAUN8Ynp+9WhrEe0lnxo1AkEA0flSDR9tbPADUtDgPN0zPrN3CTgcAmOsAKXSylmw',
            'pWciRrzKiI366DZ0m6KOJ7ew8z0viJrmZ3pmBsO537llRQJAZLrRxZRRV6lGrwmUMN+XaCFeGbgJ+l',
            'phN5/oc9F5npShTLEKL1awF23HkZD9HUdNLS76HCp4miNXbQOVSbHi2QJAUw7KSaWENXbCl5c7M43E',
            'So9paHHXHT+/5bmzebq2eoBofn+IFsyJB8Lz5L7WciDK7WvrGC2JEbqwpFhWwCOl/w==',
            '-----END RSA PRIVATE KEY-----'
        ]);
        $sign = \stlswm\IcbcPay\Sign\RSAWithSha256::signStr($str, $privateCertC);
        $this->assertSame('Z0/o9PAcs+PbKm+wwbjrUHzI3qsEzMAFJfivSkBbvsG+L50IrjOEZBp8O4CclE4Ne7bqRLTt91yEXN4z5GeB6yAgNKO9aAqTQLKb07ggP9baZLHkNyKNAz0wqNjiKluOj4w140EYyMSyLu45RmIbLv2hEg/Y+4kzl2fKTCUJY/w=',
            $sign);
    }
}