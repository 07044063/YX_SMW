<?php

/**
 * Desc
 * @description Hope You Do Good But Not Evil
 */
class mOrder extends Model
{

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
            $emsg = $emsg . "[单单号已存在]";
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
