<?php

/**
 * Desc
 * @description Hope You Do Good But Not Evil
 */
class mOrder extends Model
{
    public function getList($search_data)
    {

        $search_text = $search_data['search_text'];
        $order_address = $search_data['order_address'];
        $order_vendor = $search_data['order_vendor'];
        $order_type = $search_data['order_type'];
        $order_status = $search_data['order_status'];
        $pagesize = $search_data['pagesize'];
        $page = $search_data['page'];

        $where = "1=1";
        $orderby = "id desc";
        if (isset($search_text) and $search_text != '') {
            $where .= " and (order_code like '%$search_text%' or order_serial_no like '%$search_text%')";
        }
        if (isset($order_address) and $order_address != '') {
            if ($order_address != "所有收货单位") {
                $where .= " and address like '$order_address%'";
            }
        }
        if ($order_vendor > 0) {
            $where .= " and vendor_id = $order_vendor";
        }
        if (isset($order_type) and $order_type != '') {
            if ($order_type != "所有类型") {
                $where .= " and order_type = '$order_type'";
            }
        }
        if (isset($order_status) and $order_status != '') {
            //ORDER_STATUS_Z
            if ($order_status == "all") {
            } elseif ($order_status == "notsend") {
                $where .= " and status in ('create','receive','ready','check')";
            } elseif ($order_status == "notdone") {
                $where .= " and status <> 'done'";
            } else {
                $where .= " and status = '$order_status'";
            }
            if ($order_status == "all" || $order_status == "done") {
            } else {
                $orderby = "order_date asc";
            }
        }
        $list = $this->Dao->select()
            ->from(VIEW_ORDER)
            ->where($where)
            ->orderby($orderby)
            ->limit($pagesize * $page, $pagesize)
            ->exec();
        $list_count = $this->Dao->select('count(*)')
            ->from(VIEW_ORDER)
            ->where($where)
            ->getOne();
        $data = [
            'total' => $list_count,
            'list' => $list
        ];
        return $data;
    }

    public function createOrder($orderdata, $gddata)
    {
        unset($orderdata['address_txt']);
        $orderdata['order_serial_no'] = $orderdata['order_code'];
        $orderdata['status'] = 'create';
        $orderdata['create_by'] = $this->Session->get('uid');
        $emsg = '';
        $this->Db->transtart();
        $isExist = $this->Dao->select('count(1)')
            ->from(TABLE_ORDER)
            ->where("order_code = '" . $orderdata["order_code"] . "'")
            ->aw('isvalid = 1')
            ->getOne();
        if ($isExist > 0) {
            $emsg = $emsg . "[发货单单号已存在]";
        }
        if ($emsg == '') {
            //插入主表数据
            $orderdata['id'] = $this->Dao->insert(TABLE_ORDER, array_keys($orderdata))
                ->values(array_values($orderdata))
                ->exec();
            //插入订单状态资料
            if ($orderdata['id'] > 0) {
                $order_status_id = $this->Dao->insert(TABLE_ORDER_STATUS, ['order_id', 'status', 'create_by'])
                    ->values([$orderdata['id'], 'create', $orderdata['create_by']])
                    ->exec();
            }
            if (!$orderdata['id'] > 0 || !$order_status_id > 0) {
                $emsg = $emsg . "[发货单保存失败]";
            }
        }
        if ($emsg == '') {
            //插入订单明细资料
            foreach ($gddata as $gd) {
                unset($gd['goods_name']);
                unset($gd['$$hashKey']);
                $gd['order_id'] = $orderdata['id'];
                $gd['create_by'] = $this->Session->get('uid');
                $gd['id'] = $this->Dao->insert(TABLE_ORDER_DETAIL, array_keys($gd))
                    ->values(array_values($gd))
                    ->exec();
                if (!$gd['id'] > 0) {
                    $emsg = $emsg . "[发货单明细信息保存失败]";
                    break;
                }
            }
        }
        if ($emsg == '') {
            $this->Db->transcommit();
            return ['code' => 0, 'msg' => $orderdata['id']];
        } else {
            $this->Db->transrollback();
            return ['code' => 1, 'msg' => $emsg];
        }
    }

    public function createBack($backdata, $gddata)
    {
        $backdata['status'] = 'create';
        $backdata['create_by'] = $this->Session->get('uid');
        $emsg = '';
        $this->Db->transtart();
        $isExist = $this->Dao->select('count(1)')
            ->from(TABLE_BACK)
            ->where("back_code = '" . $backdata["back_code"] . "'")
            ->aw('isvalid = 1')
            ->getOne();
        if ($isExist > 0) {
            $emsg = $emsg . "[单号已存在]";
        }
        if ($emsg == '') {
            //插入主表数据
            $backdata['id'] = $this->Dao->insert(TABLE_BACK, array_keys($backdata))
                ->values(array_values($backdata))
                ->exec();
            if (!$backdata['id'] > 0) {
                $emsg = $emsg . "[退回单保存失败]";
            }
        }
        if ($emsg == '') {
            //插入订单明细资料
            foreach ($gddata as $gd) {
                unset($gd['goods_name']);
                unset($gd['$$hashKey']);
                if ($backdata['back_type'] == 2) {
                    $existCount = $this->Dao->select('abnormal')
                        ->from(TABLE_INVENTORY)
                        ->where("goods_id = " . $gd['goods_id'])
                        ->aw("isvalid = 1")
                        ->getOne();
                    if ($existCount < $gd['needs']) {
                        $emsg = $emsg . "[退回数量超出不良品总数]";
                        break;
                    }
                }
                $gd['back_id'] = $backdata['id'];
                $gd['create_by'] = $this->Session->get('uid');
                $gd['id'] = $this->Dao->insert(TABLE_BACK_DETAIL, array_keys($gd))
                    ->values(array_values($gd))
                    ->exec();
                if (!$gd['id'] > 0) {
                    $emsg = $emsg . "[退回单明细信息保存失败]";
                    break;
                }
            }
        }
        if ($emsg == '') {
            $this->Db->transcommit();
            return ['code' => 0, 'msg' => $backdata['id']];
        } else {
            $this->Db->transrollback();
            return ['code' => 1, 'msg' => $emsg];
        }
    }

    public function getById($id)
    {
        $data['order'] = $this->Dao->select()
            ->from(VIEW_ORDER)
            ->alias('o')
            ->where("o.id = $id")
            ->getOneRow();
        $data['goodslist'] = $this->Dao->select('od.id,od.goods_id,od.bar_code,od.needs,od.sends,od.receives,od.remark,g.goods_name,g.goods_ccode,g.goods_packing,g.using_count,g.packing_volume,v.vendor_name,s.stock_name')
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
        return $data;
    }

    public function getBackById($id)
    {
        $data['back'] = $this->Dao->select("b.*,v.vendor_code,v.vendor_name")
            ->from(TABLE_BACK)
            ->alias('b')
            ->leftJoin(TABLE_VENDOR)
            ->alias('v')
            ->on("b.vendor_id = v.id")
            ->where("b.id = $id")
            ->aw("b.isvalid = 1")
            ->getOneRow();
        $data['goodslist'] = $this->Dao->select("g.goods_ccode,g.goods_name,bd.needs,bd.remark")
            ->from(TABLE_BACK_DETAIL)
            ->alias('bd')
            ->leftJoin(TABLE_GOODS)
            ->alias('g')
            ->on("bd.goods_id = g.id")
            ->where("bd.isvalid = 1")
            ->aw("bd.back_id = $id")
            ->exec();
        return $data;
    }

    public function deleteById($id)
    {
        $this->Dao->update(TABLE_ORDER)
            ->set(array(
                'isvalid' => 0,
                'update_by' => $this->Session->get('uid')
            ))
            ->where("id = $id")
            ->exec();
        $this->Dao->update(TABLE_ORDER_DETAIL)
            ->set(array(
                'isvalid' => 0,
                'update_by' => $this->Session->get('uid')
            ))
            ->where("order_id = $id")
            ->exec();
    }

}
