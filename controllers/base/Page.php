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

    public function blank()
    {
        $this->show(self::TPL . 'blank.tpl');
    }

    public function index()
    {
        $this->loadModel(['mQuery']);
        $datas = $this->mQuery->getOverviewData();
        $this->Smarty->assign('datas', $datas);
        $this->show(self::TPL . 'home/index.tpl');
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

    public function stockedit($Query)
    {
        $id = $Query->id;
        $this->Smarty->assign('stock_id', $id);
        $this->show('./views/mdata/modify_stock.tpl');
    }

    public function goods()
    {
        $this->show(self::TPL . 'mdata/goods_list.tpl');
    }

    public function modelx()
    {
        $this->show(self::TPL . 'mdata/model_list.tpl');
    }

    /**
     *model对应的物料清单
     */
    public function modelxdetail($Query)
    {
        $id = $Query->id;
        $this->Smarty->assign('model_id', $id);
        $this->show('./views/mdata/model_goods_list.tpl');
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

    public function receivecheck()
    {
        $this->show(self::TPL . 'receive/receive_list.tpl');
    }

    public function order($Query)
    {
        $code = $Query->code;
        $this->Smarty->assign('code', $code);
        $this->show(self::TPL . 'order/order_list.tpl');
    }

    public function ordercreate()
    {
        $this->show(self::TPL . 'order/order_create.tpl');
    }

    public function backcreate()
    {
        $this->show(self::TPL . 'order/back_create.tpl');
    }

    public function back($Query)
    {
        $code = $Query->code;
        $this->Smarty->assign('code', $code);
        $this->show(self::TPL . 'order/back_list.tpl');
    }

    public function orderprint($Query)
    {
        $orderid = intval($Query->orderid);
        $backid = intval($Query->backid);
        if ($orderid > 0) {
            $this->loadModel(['mOrder']);
            $data = $this->mOrder->getById($orderid);
            $this->Smarty->assign('order', $data['order']);
            $this->Smarty->assign('gds', $data['goodslist']);
        } else {
            $this->loadModel(['mOrder']);
            $data = $this->mOrder->getBackById($backid);
            $this->Smarty->assign('back', $data['back']);
            $this->Smarty->assign('gds', $data['goodslist']);
        }
        $this->show(self::TPL . 'order/order_print.tpl');
    }

    public function orderdetail($Query)
    {
        $id = $Query->id;
        $this->assign('title', '发货单-明细信息');
        $this->Smarty->assign('order_id', $id);
        $this->show('./views/order/order_detail.tpl');
    }

    public function orderedit($Query)
    {
        $id = $Query->id;
        $this->assign('title', '发货单-发货数量修改');
        $this->Smarty->assign('order_id', $id);
        $this->show('./views/order/order_edit.tpl');
    }

    public function orderconfirm($Query)
    {
        $id = $Query->id;
        $this->assign('title', '发货单-发货确认');
        $this->Smarty->assign('order_id', $id);
        $this->show('./views/order/order_confirm.tpl');
    }

    public function returning($Query)
    {
        $code = $Query->code;
        $this->Smarty->assign('code', $code);
        $this->show(self::TPL . 'receive/returning_list.tpl');
    }

    public function inventory()
    {
        $this->show(self::TPL . 'report/inventory.tpl');
    }

    public function record($Query)
    {
        $goods_id = $Query->goods_id;
        $this->assign('q_goods_id', $goods_id);
        $this->show(self::TPL . 'report/record.tpl');
    }

    public function setting()
    {
        $this->show(self::TPL . 'setting/setting.tpl');
    }

    public function password()
    {
        $this->show(self::TPL . 'setting/password.tpl');
    }

    public function returnpic($Query)
    {
        $id = $Query->id;
        //$this->assign('title', '发货单-发货确认');
        //$this->Smarty->assign('returnpic_url', 'http://wms.yixiangscm.cn/uploads/pic/149924116664722.jpg');
        $this->Smarty->assign('returnpic_id', $id);
        $this->show('./views/receive/return_pic.tpl');
    }
}
