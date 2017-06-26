<?php

/**
 * 系统控制器
 */
class Weixin extends ControllerWx
{
    /**
     *
     */
    public function getAccessToken()
    {
        $weObj = new Wechat($this->config->wxConfigs);
//        $weObj->valid(); //注意, 企业号与普通公众号不同，必须打开验证，不要注释掉
//        echo $weObj->checkAuth();
        return $this->echoMsg(0, $weObj->checkAuth());
    }


    public function getSignPackage()
    {
        $url = $this->pGet('url');
        $weObj = new Wechat($this->config->wxConfigs);
        $signPackage = $weObj->getJsSign($url);
        //Util::log($this->toJson($signPackage));
        return $this->echoMsg(0, $signPackage);
    }

    //变更退回单状态调用的方法
    public function changeBackStatus()
    {
        $postdata = $this->post();
        $back_id = intval($postdata['back_id']);
        $this->loadModel(['mInventory']);
        try {
            $this->mInventory->updateBackStatus($back_id);
            return $this->echoMsg(0, '');
        } catch (Exception $ex) {
            Util::log($ex->getMessage());
            return $this->echoMsg(-1, $ex->getMessage());
        }
    }

    //发货流程中，变更发货单状态调用的方法
    public function changeOrderStatus()
    {
        $postdata = $this->post();
        $order_id = intval($postdata['order_id']);
        $oldstatus = $postdata['oldstatus'];
        $status = array(
            'create' => 'receive',
            'receive' => 'ready',
            'ready' => 'check',
            'check' => 'send',
            'send' => 'delivery',
            'delivery' => 'done'
        );
        $newstatus = $status[$oldstatus];
        $uid = $this->Session->get('uid');
        $data = $this->Db->query("call p_update_order_status($order_id,'$oldstatus','$newstatus',$uid);");
        return $this->echoMsg((int)$data[0]['res'], '');
    }

    //发货操作时，扫描发货单之后，获取发货单的数据
    public function getOrderInfoByList()
    {
        $postdata = $this->post();
        $orderlist = $postdata['orderlist'];
        $data = $this->Dao->select("id,order_type,order_serial_no,order_code")
            ->from(TABLE_ORDER)
            ->alias('o')
            ->where("o.order_code in ($orderlist)")
            ->aw('o.isvalid = 1')
            ->aw("status = 'check'")
            ->exec();
        return $this->echoMsg(0, $data);
    }

    //发货操作时，获取选择车辆的选项
    public function getTruckList()
    {
        $data = $this->Dao->select("id as `value`,truck_code as `title`")
            ->from(TABLE_TRUCK)
            ->alias('t')
            ->where("t.isvalid = 1")
            ->exec();
        return $this->echoMsg(0, $data);
    }

    //确认退货单已收货调用的方法
    public function confirmReturningReceive()
    {
        $postdata = $this->post();
        $data['id'] = intval($postdata['id']);
        $data['status'] = 'receive';
        $this->loadModel(['mCommon']);
        try {
            $this->mCommon->updateById(TABLE_RETURNING, $data);
            return $this->echoMsg(0, '');
        } catch (Exception $ex) {
            Util::log($ex->getMessage());
            return $this->echoMsg(-1, $ex->getMessage());
        }
    }

    //发货操作
    public function orderSend()
    {
        $postdata = $this->post();
        $truckid = intval($postdata['truckid']);
        $odlist = $postdata['odlist'];
        $uid = $this->Session->get('uid');
        $data = $this->Db->query("call p_send_order('$odlist',$truckid,$uid);");
        return $this->echoMsg((int)$data[0]['res'], '');
    }

    //查看发货单清单
    public function getOrderListByStatus($Query)
    {
        $this->Db->cache = false;
        $this->Smarty->caching = false;

        $status = array(
            'create' => "新创建",
            'receive' => "仓库已接收",
            'ready' => "备货已完成",
            'check' => "对点已完成",
            'send' => "发货已完成",
            'delivery' => "交货已完成",
            'done' => "全部完成"
        );

        !isset($Query->page) && $Query->page = 0;
        $limit = (15 * $Query->page) . ",15";

        if ($Query->status == '' || !$Query->status) {
            $where = '1 = 1';
        } else {
            if ($Query->status == 'readying') {
                $where = "( status = 'receive' or status = 'ready' or status = 'check' )";
            } else {
                $where = "status = '" . $Query->status . "'";
            }
        }
        if (!$this->isCached()) {
            $orders = $this->Dao->select()
                ->from(VIEW_ORDER)
                ->alias('o')
                ->where('o.isvalid = 1')
                ->aw($where)
                ->orderby('order_date desc')
                ->limit($limit)
                ->exec();
            foreach ($orders as $i => $od) {
                $orders[$i]['statusX'] = $status[$orders[$i]['status']];
            }
        }
        $this->assign('orders', $orders);
        $this->show('./views/weixin/orderlisttemp.tpl');
    }

    //查看发货单清单
    public function getReturningListByStatus($Query)
    {
        $this->Db->cache = false;
        $this->Smarty->caching = false;

        $status = array(
            'create' => "新创建",
            'receive' => "仓库已接收",
            'done' => "全部完成"
        );

        !isset($Query->page) && $Query->page = 0;
        $limit = (15 * $Query->page) . ",15";

        if ($Query->status == '' || !$Query->status) {
            $where = '1 = 1';
        } else {
            $where = "status = '" . $Query->status . "'";
        }
        if (!$this->isCached()) {
            $data = $this->Dao->select("r.*,p.person_name")
                ->from(TABLE_RETURNING)
                ->alias('r')
                ->leftJoin(TABLE_PERSON)
                ->alias('p')
                ->on("r.create_by = p.id")
                ->where('r.isvalid = 1')
                ->aw($where)
                ->orderby('r.id desc')
                ->limit($limit)
                ->exec();
            foreach ($data as $i => $od) {
                $data[$i]['statusX'] = $status[$data[$i]['status']];
            }
        }
        $this->assign('returnings', $data);
        $utitle = $this->Session->get('utitle');
        if ($utitle == 5) {  //库管可以操作退货单入库确认
            $this->assign('auth', 1);
        } else {
            $this->assign('auth', 0);
        }
        $this->show('./views/weixin/returninglisttemp.tpl');
    }

}