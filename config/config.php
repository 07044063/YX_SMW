<?php

// 请注意: 安装时，请将此文件重命名为 config.php
// 并填写您的数据库（config_database.php）
// OSS信息（config_oss.php）

!defined('APP_PATH') && define('APP_PATH', __DIR__ . '/../');

// 系统配置
include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'sys_config.php';

// 数据库表
include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'sys_tables.php';

// debug开关
$config->debug = true;

// 数据库
$config->db['host'] = '127.0.0.1';
$config->db['db'] = 'wms';
$config->db['user'] = 'root';
$config->db['pass'] = 'root';

// 系统根目录
$config->webroot = '/WMS/';

// 系统根域名 /结尾
$config->domain = 'http://127.0.0.1/WMS/';

$config->imagesSuffix50 = '_x50';

$config->imagesSuffix100 = '_x100';

$config->imagesSuffix500 = '_x500';

$config->cssversion = '1.5.1';

// log目录设置
$config->logdir = APP_PATH . 'logs' . DIRECTORY_SEPARATOR;

// 是否已经通过微信认证
$config->wechatVerifyed = true;

// 微信公众号AppId
define("APPID", "wxb8a1832b22d36fdb");

// 微信公众号AppSecret
define("APPSECRET", "387edcad5efabe7249c89b5c0f181912");

// 微信公众号通讯AESKey
define('EncodingAESKey', 'C5mKA9e061g9VsnWHoBqbv64RQ99wKRPyWJBAJKpM5v');

// 微信公众号验证TOKEN
define("TOKEN", "ABCDEF2016");

// <微信支付> 商户ID(partnerId)
define("PARTNER", "X");

// <微信支付> 商户通加密串(partnerKey)
define("PARTNERKEY", "X");

// <微信支付> CA证书 .pem文件
define('CERT_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR . "apiclient_cert.pem");

// <微信支付> CA证书 .pem文件
define('CERT_KEY_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR . "apiclient_key.pem");

// <微信支付> CA证书 .pem文件
define('CERT_ROOTCA', dirname(__FILE__) . DIRECTORY_SEPARATOR . "rootca.pem");