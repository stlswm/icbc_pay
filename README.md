# icbc_pay
中国工商银行支付接口SDK(PHP版本)

工商银行在线文档地址

https://open.icbc.com.cn/icbc/apip/service_detail.html?service_id=P0067&from=singlemessage&isappinstalled=0#


安装

```
composer require stlswm/icbc-pay
```

单元测试运行说明：

```
tests/Config目录添加文件

config.json 内容参见config.json.example

icbc.pub 工行网关公钥

yourname.pri 调起接口的私钥
```
