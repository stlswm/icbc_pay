# icbc_pay
中国工商银行支付接口SDK(PHP版本)

工商银行在线文档地址

https://open.icbc.com.cn/icbc/apip/service_detail.html?service_id=P0067&from=singlemessage&isappinstalled=0#


安装

```
composer require stlswm/icbc-pay
```

下单demo

```
<?php
use stlswm\IcbcPay\Client\DefaultClient;

//----------------------
//请求流程（所有接口都是这个套路）
//----实例化cli对象
//----实例化请求对象
//----设置请求参数
//----使用cli对象exce发起请求
//----获取返回对象
//----------------------
//实例化cli对象
$cli = new DefaultClient($config['app_id'], $myPrivateKey, $icbcPubicKey, 'RSA2', 'AES',
            $config['encrypt_key']);
//实例化请求对象
$req = new \stlswm\IcbcPay\Merchant\Subscription\HidePayRequest();
//设置请求参数
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
$req->setReqEncrypt(true);//当请求需要加密时设置
//发起请求
$res = $cli->exec($req, date('YmdHis').mt_rand(1000, 9999),\stlswm\IcbcPay\Merchant\Subscription\HidePayRequest::UrlV1);
//获取返回
var_dump($res);
var_dump($res->isSuccess());
```

异步通知校验demo

```
<?php
use stlswm\IcbcPay\Client\DefaultClient;

$cli = new DefaultClient($payAccount['app_id'], file_get_contents($payAccount['my_pri_key']),
            file_get_contents($payAccount['their_pub_key']), 'RSA2', 'AES', $payAccount['encrypt_key']);
$cli->setMerId($payAccount['mch_id']);
var_dump($cli->icbcNotifyDatVerify('你服务器的异步通知地址', $_POST));
```

单元测试运行说明：

tests/Config目录添加以下文件

```
config.json 内容参见config.json.example
icbc.pub 工行网关公钥
yourname.pri 调起接口的私钥
```
