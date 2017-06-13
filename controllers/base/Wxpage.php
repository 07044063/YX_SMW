<?php

if (!defined('APP_PATH')) {
    exit(0);
}

/**
 * 微信页面控制器
 * @description Hope You Do Good But Not Evil
 *
 */
class Wxpage extends ControllerWx
{

    const TPL = './views/';

    /**
     * 构造函数
     * @param string $ControllerName
     * @param string $Action
     * @param string $QueryString
     */
    public function __construct($ControllerName, $Action, $QueryString)
    {
        parent::__construct($ControllerName, $Action, $QueryString);
        $this->Db->cache = false;
        $this->Smarty->caching = false;
        $this->initSettings();
        header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0"); // HTTP/1.1
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
        header("Pragma: no-cache"); // Date in the past
    }

    public function auth()
    {
        //企业号验证通过直接跳转到首页
        $action = 'index';
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
        }
        $this->redirect($this->root . "?/Wxpage/$action/");
    }

    public function test()
    {
        $this->assign('title', '测试页');
        $this->show(self::TPL . 'weixin/wxtest.tpl');
    }

    public function index()
    {
        $this->assign('title', '功能清单');
        $this->show(self::TPL . 'weixin/index.tpl');
    }

    public function order($Query)
    {
        $order_code = $Query->order_code;
        $this->loadModel(['mWeixin']);
        $data = $this->mWeixin->getOrderByCode($order_code);
        $this->assign('order', $data);
        $this->assign('title', '发货单详情-' . $data['order_serial_no']);
        $this->show(self::TPL . 'weixin/order.tpl');
    }

    public function delivery()
    {
        $this->assign('title', '发货装车');
        $this->show(self::TPL . 'weixin/delivery.tpl');
    }

}
