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
        $search_text = '%' . $this->pGet('search_text') . '%';
        $where = "(order_code like '$search_text' or order_serial_no like '$search_text')";
        $list = $this->Dao->select()
            ->from(VIEW_ORDER)
            ->limit($pagesize * $page, $pagesize)
            ->exec();
        $list_count = $this->Dao->select('count(*)')
            ->from(VIEW_ORDER)
            ->getOne();
        $data = $this->toJson([
            'total' => $list_count,
            'list' => $list
        ]);

        return $this->echoJsonRaw($data);
    }

    public function getById()
    {
        $id = intval($this->pGet('id'));
        $data['order'] = $this->Dao->select()
            ->from(VIEW_ORDER)
            ->alias('o')
            ->where("o.id = $id")
            ->getOneRow();
        $data['goodslist'] = $this->Dao->select('od.goods_id,od.bar_code,od.needs,od.remark,g.goods_name,g.goods_ccode,g.goods_packing,g.packing_volume,v.vendor_name,s.stock_name')
            ->from(TABLE_ORDER_DETAIL)
            ->alias('od')
            ->leftJoin(TABLE_GOODS)
            ->alias("g")
            ->on("od.goods_id = g.id")
            ->leftJoin(TABLE_VENDOR)
            ->alias("v")
            ->on("g.vendor_id = v.id")
            ->leftJoin(TABLE_STOCK)
            ->alias("s")
            ->on("g.stock_id = s.id")
            ->where("od.order_id = $id")
            ->aw("od.isvalid = 1")
            ->exec();
        return $this->echoMsg(0, $data);

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

}