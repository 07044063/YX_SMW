<?php

if (!defined('APP_PATH')) {
    exit(0);
}

/**
 * 管理后台页面控制器
 * @description Hope You Do Good But Not Evil
 *
 */
class Page extends ControllerAdmin
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

    /*
     * 未完页
     * */
    public function index()
    {
        $this->show(self::TPL . 'blank.tpl');
    }

    //系统日志查看
    public function logs()
    {
        $this->show(self::TPL . 'system/logs.tpl');
    }

    public function vendor()
    {
        $this->show(self::TPL . 'mdata/vendor_list.tpl');
    }

    public function customer()
    {
        $this->show(self::TPL . 'mdata/customer_list.tpl');
    }

    public function warehouse()
    {
        $this->show(self::TPL . 'mdata/warehouse_list.tpl');
    }

    public function person()
    {
        $this->show(self::TPL . 'mdata/person_list.tpl');
    }

    public function stock()
    {
        $this->show(self::TPL . 'mdata/stock_list.tpl');
    }

    public function goods()
    {
        $this->show(self::TPL . 'mdata/goods_list.tpl');
    }

    public function modelx()
    {
        $this->show(self::TPL . 'mdata/model_list.tpl');
    }

    public function truck()
    {
        $this->show(self::TPL . 'mdata/truck_list.tpl');
    }

    public function stockloan()
    {
        $this->show(self::TPL . 'mdata/stockloan_list.tpl');
    }

    public function receive()
    {
        $this->show(self::TPL . 'receive/receive.tpl');
    }

    public function receiveCheck()
    {
        $this->show(self::TPL . 'receive/receiveCheck.tpl');
    }
}
