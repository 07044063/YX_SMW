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
        $this->assign('uname', $this->Session->get('uname'));
        $this->assign('title', '功能清单');
        $this->show(self::TPL . 'weixin/index.tpl');
    }

    public function order($Query)
    {
        $code = $Query->order_code;
        $this->loadModel(['mQuery']);
        if (substr($code, 0, 1) == 'R') {
            $data = $this->mQuery->getBackByCode($code);
            $this->assign('back', $data);
            $this->assign('code', $code);
            $this->assign('title', '退回单详情-' . $data['back_code']);
            $this->show(self::TPL . 'weixin/back.tpl');
        } else {
            $data = $this->mQuery->getOrderByCode($code);
            $this->assign('order', $data);
            $this->assign('code', $code);
            $this->assign('title', '发货单详情-' . $data['order_serial_no']);
            $this->show(self::TPL . 'weixin/order.tpl');
        }
    }

    public function send()
    {
        //权限判断
        $utitle = intval($this->Session->get('utitle'));
        if ($utitle != 7) {
            $this->show(self::TPL . 'wxnoauth.tpl');
            return;
        }
        $this->assign('title', '发货装车');
        $this->show(self::TPL . 'weixin/send.tpl');
    }

    public function orderlist()
    {
        $status = $this->pGet('status');
        $this->assign('status', $status);
        $this->assign('title', '发货单清单');
        $this->show(self::TPL . 'weixin/orderlist.tpl');
    }

    public function returningcreate()
    {
        $utitle = intval($this->Session->get('utitle'));
        if ($utitle != 8) {  //跟单员
            $this->show(self::TPL . 'wxnoauth.tpl');
            return;
        }
        $this->assign('title', '创建退货单');
        $this->show(self::TPL . 'weixin/returningcreate.tpl');
    }

    public function returninglist()
    {
        $this->assign('title', '退货单清单');
        $utitle = intval($this->Session->get('utitle'));
        if ($utitle == 5) {  //库管
            $this->assign('auth', 1);
        }
        $this->show(self::TPL . 'weixin/returninglist.tpl');
    }

}
