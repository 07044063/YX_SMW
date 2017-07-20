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

    public function sendMessage($data)
    {
        //发送微信企业号消息通用接口
        //Post参数说明
        //{type:'text',content:'要发送的文本消息内容',touser:'userA|userB',totag}
        //文本支持\n换行，和<a>标签 "消息\n点击查看<a href='http://www.baidu.com'>点我</a>哈哈"
        if (!$data['agentid']) {
            $data['agentid'] = 0; //默认发送消息的ID
        }
        $sendData = [
            "touser" => $data['touser'],
            "toparty" => "",
            "totag" => "",
            "msgtype" => $data['type'],
            "agentid" => $data['agentid'],
            "text" => ["content" => $data['content']],
            "safe" => 0
        ];
        $weObj = new Wechat($this->config->wxConfigs);
        try {
            $res = $weObj->sendMessage($sendData);
        } catch (Exception $e) {
        }
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
        //ORDER_STATUS_Z
        $status = array(
            'create' => 'receive',
            'receive' => 'ready',
            'ready' => 'check',
            'check' => 'send',
            'send' => 'arrive',
            'arrive' => 'delivery',
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

    //发货单查询时，获取选择供应商的选项
    public function getVendorList()
    {
        $data = $this->Dao->select("id as `value`,concat(vendor_code,' ',vendor_shortname) as `title`")
            ->from(TABLE_VENDOR)
            ->where("isvalid = 1")
            ->orderby('vendor_code asc')
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
        if ((int)$data[0]['res'] == 0) {
            $this->orderSendMsg($uid, $truckid, $odlist);
        }
        return $this->echoMsg((int)$data[0]['res'], '');
    }

    //发送发货的微信消息
    public function orderSendMsg($uid, $truckid, $odlist)
    {
        $userPhone = $this->Dao->select("person_phone")
            ->from(TABLE_PERSON)
            ->where("id = $uid")
            ->aw("isvalid = 1")
            ->getOne();
        $truckCode = $this->Dao->select("truck_code")
            ->from(TABLE_TRUCK)
            ->where("id = $truckid")
            ->aw("isvalid = 1")
            ->getOne();
        $goodsList = $this->Dao->select("o.order_type,o.order_date,g.goods_name")
            ->from(VIEW_ORDER_CHECK)
            ->alias("v")
            ->leftJoin(TABLE_GOODS)
            ->alias('g')
            ->on("v.goods_id = g.id")
            ->leftJoin(TABLE_ORDER)
            ->alias('o')
            ->on("v.order_id = o.id")
            ->where("order_id in ($odlist)")
            ->exec();
        $msg = date('n月j日') . "\n亿翔物流\n";
        $msg = $msg . "\n";
        $msg = $msg . "车牌号码：$truckCode" . "\n";
        $msg = $msg . "发车时间：" . date('H:i') . "\n";
        $msg = $msg . "配送物料：\n";
        foreach ($goodsList as $go) {
            if ($go['order_type'] == '外部序列') {
                $timeStr = substr($go['order_date'], 11, 5);
            } else {
                $timeStr = '';
            }
            $msg = $msg . $go['goods_name'] . " " . $timeStr . "\n";
        }
        $this->sendMessage([
            'type' => 'text',
            'content' => $msg,
            'touser' => $userPhone
        ]);
    }

    //查看发货单清单
    public function getOrderListByStatus($Query)
    {
        $this->Db->cache = false;
        $this->Smarty->caching = false;

        //ORDER_STATUS_Z
        $status = array(
            'create' => "新创建",
            'receive' => "仓库已接收",
            'ready' => "备货已完成",
            'check' => "对点已完成",
            'send' => "发车已完成",
            'arrive' => "货已到达",
            'delivery' => "交货已完成",
            'done' => "全部完成"
        );

        if (!$this->isCached()) {
            $search_data = [];
            $search_data['search_text'] = $this->pGet('search_text');
            $search_data['order_address'] = $this->pGet('order_address');
            $search_data['order_type'] = $this->pGet('order_type');
            $search_data['order_status'] = $this->pGet('order_status');
            $search_data['order_vendor'] = intval($this->pGet('order_vendor'));
            $search_data['pagesize'] = 15;
            $search_data['page'] = $this->pGet('page');
            $this->loadModel(['mOrder']);
            $res = $this->mOrder->getList($search_data);
            $orders = $res['list'];
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
        $this->show('./views/weixin/returninglisttemp.tpl');
    }

    //查看发货单清单
    public function getInventoryList($Query)
    {
        $this->Db->cache = false;
        $this->Smarty->caching = false;

        !isset($Query->page) && $Query->page = 0;
        $limit = (15 * $Query->page) . ",15";

        if ($Query->goods_code == '' || !$Query->goods_code) {
            $where = "1 = 1 ";
        } else {
            $where = "goods_ccode like '%" . $Query->goods_code . "%'";
        }
        if (!$this->isCached()) {
            $inventorylist = $this->Dao->select()
                ->from(VIEW_INVENTORY)
                ->where($where)
                ->orderby('goods_ccode asc')
                ->limit($limit)
                ->exec();
        }
        $this->assign('inventorylist', $inventorylist);
        $this->show('./views/weixin/inventorychecktemp.tpl');
    }


}