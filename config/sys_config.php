<?php

/**
 * Conifg Object
 */
$config = new stdClass();

/**
 * 系统初始化自动加载模块
 */
$config->preload = array('Smarty', 'Db', 'Util', 'Dao', 'Load', 'Auth', 'Session');

/**
 * 单独设置控制器默认方法，未设置则默认为index
 */
$config->defaultAction = array(
    'Controller1' => 'index'
);

/**
 * 默认视图文件目录
 */
$config->tpl_dir = "views";

/**
 * Smarty配置
 */
$config->Smarty = [
    'cache_lifetime' => 30,
    'cache_dir' => APP_PATH . 'tmp/tpl_cache/',
    'compile_dir' => APP_PATH . 'tmp/tpl_compile/',
    'view_dir' => APP_PATH . 'views/',
    'cached' => false
];

/**
 * 默认Controller
 */
$config->default_controller = "Index";

/**
 * config -> shoproot
 * 微信支付发起路径
 */
$config->wxpayroot = 'wxpay.php';

/**
 * config -> admin_salt
 * 管理后台加密盐
 */
$config->admin_salt = '1akjx99k';

/**
 * 系统版本号
 */
$config->system_version = 'v1.0';

/**
 * 微信消息使用AES密文模式[推荐]
 * @see http://mp.weixin.qq.com/wiki/11/2d2d1df945b75605e7fea9ea2573b667.html
 */
$config->wechat_aes_open = true;

/**
 * 微信消息处理回调选项
 * 可以添加多个类名, 必须继承 WechatHandler 这个类哦
 */
$config->wechat_msg_handler = [
    'text' => ['TextHandler'],
    'event' => ['EventHandler'],
    'voice' => ['VoiceHandler']
];

/**
 * 是否检查微信ip来路
 * 如果对于安全性要求很高,请打开此选项,但是会牺牲一定的性能
 */
$config->wechat_check_ip = false;

if (is_file(__DIR__ . DIRECTORY_SEPARATOR . 'config_oss.php')) {
    include_once __DIR__ . DIRECTORY_SEPARATOR . 'config_oss.php';
}

if (is_file(__DIR__ . DIRECTORY_SEPARATOR . 'config_redis.php')) {
    include_once __DIR__ . DIRECTORY_SEPARATOR . 'config_redis.php';
}