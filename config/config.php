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

$config->wxConfigs = array(
    'token' => 'xYBvTYhVzs9uGGJzveSbBcgkE4',   //填写应用接口的Token
    'encodingaeskey' => 'rP5x7EVqThC66l5yoS18FdU4nuFMH9Vt2TcpoC6Ma3w',//填写加密用的EncodingAESKey
    'appid' => 'wx607bfbe0967add53',  //填写高级调用功能的appid
    'appsecret' => 'ED1lL7zlCm-bxNAfFt1X3-J1iQoZw1OmsoUK3kUo8b9Lsm2JDn1IsPnFhKQrEp7J', //填写高级调用功能的密钥
    'agentid' => '0', //应用的id
    'mch_id' => '', // 微信支付，商户ID（可选）
    'partnerkey' => '', // 微信支付，密钥（可选）
    'ssl_cer' => '', // 微信支付，证书cert的路径（可选，操作退款或打款时必需）
    'ssl_key' => '', // 微信支付，证书key的路径（可选，操作退款或打款时必需）
    'cachepath' => '', // 设置SDK缓存目录（可选，默认位置在./src/Cache下，请保证写权限）
);