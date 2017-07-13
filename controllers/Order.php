<?php

/**
 * 系统控制器
 */
class Order extends ControllerAdmin
{

    /**
     *
     */
    public function getList()
    {
        $pagesize = $this->pGet('pagesize') ? intval($this->pGet('pagesize')) : 20;
        $page = $this->pGet('page');
        $search_data = [];
        $search_data['search_text'] = $this->pGet('search_text');
        $search_data['order_address'] = $this->pGet('order_address');
        $search_data['order_type'] = $this->pGet('order_type');
        $search_data['order_status'] = $this->pGet('order_status');
        $search_data['order_vendor'] = intval($this->pGet('order_vendor'));
        $search_data['pagesize'] = $pagesize;
        $search_data['page'] = $page;
        $this->loadModel(['mOrder']);
        $res = $this->mOrder->getList($search_data);
        $data = $this->toJson($res);
        $this->echoJsonRaw($data);
    }

    public function getById()
    {
        $this->loadModel(['mOrder']);
        $id = intval($this->pGet('id'));
        $data = $this->mOrder->getById($id);
        return $this->echoMsg(0, $data);
    }

    public function deleteById()
    {
        $this->loadModel(['mOrder']);
        $id = intval($this->pPost('id'));
        $this->mOrder->deleteById($id);
        return $this->echoMsg(0, '');
    }

    public function getStatusById()
    {
        $id = intval($this->pGet('id'));
        $data['order'] = $this->Dao->select("id,status")
            ->from(TABLE_ORDER)
            ->alias('o')
            ->where("o.id = $id")
            ->aw("o.isvalid = 1")
            ->getOneRow();
        $data['statuslist'] = $this->Dao->select('os.*,p.person_name')
            ->from(TABLE_ORDER_STATUS)
            ->alias('os')
            ->leftJoin(TABLE_PERSON)
            ->alias("p")
            ->on("os.create_by = p.id")
            ->where("os.order_id = $id")
            ->aw("os.isvalid = 1")
            ->exec();
        return $this->echoMsg(0, $data);

    }

    public function modifyOrderStatus()
    {
        $id = $this->pPost('orderId');
        $newstatus = $this->pPost('newstatus');
        $oldstatus = $this->pPost('oldstatus');
        $uid = $this->Session->get('uid');
        $data = $this->Db->query("call p_update_order_status($id,'$oldstatus','$newstatus',$uid);");
        return $this->echoMsg((int)$data[0]['res'], '');
    }

    public function getVendorSelect()
    {
        $vendorlist = $this->Dao->select("id,concat(vendor_code,' ',vendor_shortname) as text")
            ->from(TABLE_VENDOR)
            ->where("isvalid = 1")
            ->orderby('vendor_code asc')
            ->exec();
        return $this->echoMsg(0, $vendorlist);
    }

    public function getGoodsList()
    {
        $vendor_id = $this->pGet('vendor_id');
        $goodslist = $this->Dao->select("id, CONCAT(goods.goods_ccode,'  ',goods.goods_name) as text")
            ->from(TABLE_GOODS)
            ->where("isvalid = 1")
            ->aw("vendor_id = $vendor_id")
            ->exec();
        return $this->echoMsg(0, $goodslist);
    }

    public function createOrder()
    {
        $postdata = $this->post();
        $orderdata = $postdata['orderData'];
        $gddata = $postdata['gdData'];

        if (!isset($orderdata['order_code']) or $orderdata['order_code'] == '') {
            return $this->echoMsg(-1, '发货单号不能为空');
        }
        if (!isset($orderdata['order_date']) or $orderdata['order_date'] == '') {
            return $this->echoMsg(-1, '需求日期不能为空');
        }
        if (!isset($orderdata['vendor_id']) or $orderdata['vendor_id'] == '') {
            return $this->echoMsg(-1, '供应商不能为空');
        }
        if (!isset($orderdata['address']) or $orderdata['address'] == '') {
            return $this->echoMsg(-1, '收货单位不能为空');
        }

        $this->loadModel(['mOrder']);
        $res = $this->mOrder->createOrder($orderdata, $gddata);
        if ($res['code'] == 0) {
            return $this->echoMsg(0, $res['msg']);
        } else {
            return $this->echoMsg(1, $res['msg']);
        }
    }

    public function updateSingelOrderDetail()
    {
        $data = $this->post();
        $this->loadModel(['mCommon']);
        try {
            $this->mCommon->updateById(TABLE_ORDER_DETAIL, $data);
            return $this->echoMsg(0, '');
        } catch (Exception $ex) {
            return $this->echoMsg(-1, $ex->getMessage());
        }
    }


    public function getMaxOrderNo()
    {
        $date = $this->pGet('date');
        $maxno = $this->Dao->select('max(order_code)')
            ->from(TABLE_ORDER)
            ->alias('o')
            ->where("o.order_code like '$date%'")
            ->aw('isvalid = 1')
            ->getOne();
        if ($maxno) {
            $no = intval($maxno) + 1;
        } else {
            $no = $date . '0001';
        }
        return $this->echoMsg(0, $no);
    }

}