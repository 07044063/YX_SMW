<?php

/**
 * Desc
 * @description Hope You Do Good But Not Evil
 */
class mImport extends Model
{

    public function importExcel($data, $action)
    {
        if ($action == null) {
            return ['code' => 1, 'msg' => 'action不正确'];
        }
        //订单导入
        if ($action == 'Order') {
            $rowCount = count($data);
            //$columnCount = count($data[1]);
            $saveCount = 0;
            $order = [];
            $order_detail = [];
            $emsg = '';
            $this->Db->transtart();
            for ($i = 2; $i <= $rowCount; $i++) {
                if ($data[$i]["M"] <> null) {
                    //收货数量不为空 说明有收货数据 不做处理
                    continue;
                }
                //新订单
                if ($order["order_code"] <> $data[$i]["P"]) {
                    unset($order);
                    unset($order_detail);
                    $order["order_code"] = $data[$i]["P"];
                    $order["order_type"] = $data[$i]["D"]; //订单类型
                    $order["vendor_code"] = $data[$i]["E"];  //供应商代码
                    $order["dock"] = $data[$i]["H"];   //道口
                    $order["order_serial_no"] = $data[$i]["O"];  //流水号
                    $order["order_date"] = $data[$i]["I"];  //流水号
                    $order["status"] = 'create';
                    $order["customer_id"] = '3';  //设置收货方ID
                    $isExist = $this->Dao->select('count(1)')
                        ->from(TABLE_ORDER)
                        ->where("order_code = '" . $order["order_code"] . "'")
                        ->aw('isvalid = 1')
                        ->getOne();
                    if ($isExist > 0) {
                        $emsg = $emsg . "[发货单" . $order["order_serial_no"] . "已存在]";
                        break;
                    }
                    $order["vendor_id"] = $this->Dao->select('id')
                        ->from(TABLE_VENDOR)
                        ->where("vendor_code = '" . $order["vendor_code"] . "'")
                        ->aw("isvalid = 1")
                        ->getOne();
                    if (!$order["vendor_id"] > 0) {
                        $emsg = $emsg . "[发货单" . $order["order_serial_no"] . "供应商信息不正确]";
                        break;
                    }
                    $order_insert = $order;
                    unset($order_insert["vendor_code"]);
                    $order_insert['create_by'] = $this->Session->get('uid');
                    $order['id'] = $this->Dao->insert(TABLE_ORDER, array_keys($order_insert))
                        ->values(array_values($order_insert))
                        ->exec();
                    //插入订单状态资料
                    if ($order['id'] > 0) {
                        $saveCount = $saveCount + 1;
                        $order_status_id = $this->Dao->insert(TABLE_ORDER_STATUS, ['order_id', 'status', 'create_by'])
                            ->values([$order['id'], 'create', $order_insert['create_by']])
                            ->exec();
                    }
                    if ($order['id'] > 0 && $order_status_id > 0) {
                        //保存成功
                        unset($order_insert);
                    } else {
                        $emsg = $emsg . "[收货单保存失败]";
                        break;
                    }
                }
                //订单明细
                if ($order["order_code"] == $data[$i]["P"]) {
                    if ($order["order_type"] <> $data[$i]["D"]) {
                        $emsg = $emsg . "[发货单" . $order["order_serial_no"] . "类型不一致]";
                        break;
                    }
                    if ($order["vendor_code"] <> $data[$i]["E"]) {
                        $emsg = $emsg . "[发货单" . $order["order_serial_no"] . "供应商不一致]";
                        break;
                    }
                    if ($order["dock"] <> $data[$i]["H"]) {
                        $emsg = $emsg . "[发货单" . $order["order_serial_no"] . "道口不一致]";
                        break;
                    }
                    unset($order_detail);
                    $order_detail["order_id"] = $order['id'];  //订单ID
                    $order_detail["bar_code"] = $data[$i]["L"]; //条形码
                    $order_detail["needs"] = $data[$i]["F"];   //需求数量
                    $order_detail["goods_ccode"] = $data[$i]["B"];  //物料图号
                    $goods_info = $this->Dao->select('id,vendor_id')
                        ->from(TABLE_GOODS)
                        ->where("goods_ccode = '" . $order_detail["goods_ccode"] . "'")
                        ->aw("isvalid = 1")
                        ->getOneRow();
                    if (!$goods_info["id"] > 0) {
                        $emsg = $emsg . "[物料代码" . $order_detail["goods_ccode"] . "不正确]";
                        break;
                    }
                    if (!$goods_info["vendor_id"] > 0 || $goods_info["vendor_id"] <> $order["vendor_id"]) {
                        $emsg = $emsg . "[供应商代码" . $order["vendor_code"] . "不正确或与系统内不一致]";
                        break;
                    }
                    $order_detail["goods_id"] = $goods_info["id"];
                    $order_detail["create_by"] = $this->Session->get('uid');
                    unset($order_detail["goods_ccode"]);
                    $order_detail['id'] = $this->Dao->insert(TABLE_ORDER_DETAIL, array_keys($order_detail))
                        ->values(array_values($order_detail))
                        ->exec();
                    if (!$order_detail['id'] > 0) {
                        $emsg = $emsg . "[收货单明细信息保存失败]";
                        break;
                    }
                }
            }
            if ($emsg == '') {
                $this->Db->transcommit();
                return ['code' => 0, 'msg' => "保存成功，导入" . $saveCount . "笔发货单"];
            } else {
                $this->Db->transrollback();
                return ['code' => 1, 'msg' => $emsg];
            }
        }

    }
}
