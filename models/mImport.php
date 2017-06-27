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

            //判断是几厂的模版
            $otype = 0;
            if ($data[1]["A"] == '序号') {
                $otype = 2;
            } else if ($data[1]["R"] == '工厂') {
                $otype = 1;
            } else {
                $otype = 3;
            }

            $saveCount = 0;
            $order = [];
            $order_detail = [];
            $emsg = '';

            //检查是否设置了导入的默认客户编码
            $default_customer_id = intval($this->Dao->select('value')
                ->from(TABLE_SETTINGS)
                ->where("`key` = 'default_customer_id'")
                ->aw("isvalid = 1")
                ->getOne());
            if (!$default_customer_id > 0) {
                return ['code' => 1, "无效的客户ID:$default_customer_id"];
            }

            $this->Db->transtart();
            for ($i = 2; $i <= $rowCount; $i++) {
                if ($otype == 2) {
                    //2厂模版导入开始
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
                        $order["address"] = '江淮二工厂' . $data[$i]["G"];  //生产线
                        $order["dock"] = $data[$i]["H"];   //道口
                        $order["order_serial_no"] = $data[$i]["O"];  //流水号
                        $order["order_date"] = $data[$i]["I"];  //流水号
                        $order["status"] = 'create';
                        $order["customer_id"] = $default_customer_id;  //设置收货方ID
                        $isExist = $this->Dao->select('count(1)')
                            ->from(TABLE_ORDER)
                            ->where("order_code = '" . $order["order_code"] . "'")
                            ->aw('isvalid = 1')
                            ->getOne();
                        if ($isExist > 0) {
                            $emsg = $emsg . "[行$i ：发货单" . $order["order_serial_no"] . "已存在]";
                            break;
                        }
                        $order["vendor_id"] = $this->Dao->select('id')
                            ->from(TABLE_VENDOR)
                            ->where("vendor_code = '" . $order["vendor_code"] . "'")
                            ->aw("isvalid = 1")
                            ->getOne();
                        if (!$order["vendor_id"]) {
                            $emsg = $emsg . "[行$i ：发货单" . $order["order_serial_no"] . "供应商信息不正确]";
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
                            $emsg = $emsg . "[发货单保存失败]";
                            break;
                        }
                    }
                    //订单明细
                    if ($order["order_code"] == $data[$i]["P"]) {
                        if ($order["order_type"] <> $data[$i]["D"]) {
                            $emsg = $emsg . "[行$i ：发货单" . $order["order_serial_no"] . "类型不一致]";
                            break;
                        }
                        if ($order["vendor_code"] <> $data[$i]["E"]) {
                            $emsg = $emsg . "[行$i ：发货单" . $order["order_serial_no"] . "供应商不一致]";
                            break;
                        }
                        if ($order["dock"] <> $data[$i]["H"]) {
                            $emsg = $emsg . "[行$i ：发货单" . $order["order_serial_no"] . "道口不一致]";
                            break;
                        }
                        unset($order_detail);
                        $order_detail["order_id"] = $order['id'];  //订单ID
                        $order_detail["bar_code"] = $data[$i]["L"]; //条形码
                        $order_detail["needs"] = intval($data[$i]["F"]);   //需求数量
                        $order_detail["sends"] = $order_detail["needs"];   //需求数量
                        $order_detail["receives"] = $order_detail["needs"];   //需求数量
                        if (!$order_detail["needs"] > 0) {
                            $emsg = $emsg . "[行$i ：需求数量不正确]";
                            break;
                        }
                        $order_detail["goods_ccode"] = $data[$i]["B"];  //物料图号
                        $goods_info = $this->Dao->select('id,vendor_id')
                            ->from(TABLE_GOODS)
                            ->where("goods_ccode = '" . $order_detail["goods_ccode"] . "'")
                            ->aw("isvalid = 1")
                            ->getOneRow();
                        if (!$goods_info["id"] > 0) {
                            $emsg = $emsg . "[行$i ：物料代码" . $order_detail["goods_ccode"] . "不正确]";
                            break;
                        }
                        if (!$goods_info["vendor_id"] > 0 || $goods_info["vendor_id"] <> $order["vendor_id"]) {
                            $emsg = $emsg . "[行$i ：供应商代码" . $order["vendor_code"] . "不正确或与系统内不一致]";
                            break;
                        }
                        $order_detail["goods_id"] = $goods_info["id"];
                        $order_detail["create_by"] = $this->Session->get('uid');
                        unset($order_detail["goods_ccode"]);
                        $order_detail['id'] = $this->Dao->insert(TABLE_ORDER_DETAIL, array_keys($order_detail))
                            ->values(array_values($order_detail))
                            ->exec();
                        if (!$order_detail['id'] > 0) {
                            $emsg = $emsg . "[发货单明细信息保存失败]";
                            break;
                        }
                    }
                } else if ($otype == 1) {
                    //1厂模版导入开始
                    if ($data[$i]["L"] <> null) {
                        //收货数量不为空 说明有收货数据 不做处理
                        continue;
                    }
                    //新订单
                    if ($order["order_code"] <> $data[$i]["N"]) {
                        unset($order);
                        unset($order_detail);
                        $order["order_code"] = $data[$i]["N"];
                        $order["order_type"] = $data[$i]["C"]; //订单类型
                        $order["vendor_code"] = $data[$i]["D"];  //供应商代码
                        $order["address"] = '江淮一工厂' . $data[$i]["P"];  //生产线
                        $order["dock"] = $data[$i]["G"];   //道口
                        $order["order_serial_no"] = $data[$i]["M"];  //流水号
                        $order["order_date"] = $data[$i]["H"];  //
                        $order["status"] = 'create';
                        $order["customer_id"] = $default_customer_id;  //设置收货方ID
                        $isExist = $this->Dao->select('count(1)')
                            ->from(TABLE_ORDER)
                            ->where("order_code = '" . $order["order_code"] . "'")
                            ->aw('isvalid = 1')
                            ->getOne();
                        if ($isExist > 0) {
                            $emsg = $emsg . "[行$i ：发货单" . $order["order_serial_no"] . "已存在]";
                            break;
                        }
                        $order["vendor_id"] = $this->Dao->select('id')
                            ->from(TABLE_VENDOR)
                            ->where("vendor_code = '" . $order["vendor_code"] . "'")
                            ->aw("isvalid = 1")
                            ->getOne();
                        if (!$order["vendor_id"] > 0) {
                            $emsg = $emsg . "[行$i ：发货单" . $order["order_serial_no"] . "供应商信息不正确]";
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
                            $emsg = $emsg . "[发货单保存失败]";
                            break;
                        }
                    }
                    //订单明细
                    if ($order["order_code"] == $data[$i]["N"]) {
                        if ($order["order_type"] <> $data[$i]["C"]) {
                            $emsg = $emsg . "[行$i ：发货单" . $order["order_serial_no"] . "类型不一致]";
                            break;
                        }
                        if ($order["vendor_code"] <> $data[$i]["D"]) {
                            $emsg = $emsg . "[行$i ：发货单" . $order["order_serial_no"] . "供应商不一致]";
                            break;
                        }
                        if ($order["dock"] <> $data[$i]["G"]) {
                            $emsg = $emsg . "[行$i ：发货单" . $order["order_serial_no"] . "道口不一致]";
                            break;
                        }
                        unset($order_detail);
                        $order_detail["order_id"] = $order['id'];  //订单ID
                        $order_detail["bar_code"] = $data[$i]["K"]; //条形码
                        $order_detail["needs"] = intval($data[$i]["E"]);   //需求数量
                        $order_detail["sends"] = $order_detail["needs"];   //需求数量
                        $order_detail["receives"] = $order_detail["needs"];   //需求数量
                        if (!$order_detail["needs"]) {
                            $emsg = $emsg . "[行$i ：需求数量不正确]";
                            break;
                        }
                        $order_detail["goods_ccode"] = $data[$i]["A"];  //物料图号
                        $goods_info = $this->Dao->select('id,vendor_id')
                            ->from(TABLE_GOODS)
                            ->where("goods_ccode = '" . $order_detail["goods_ccode"] . "'")
                            ->aw("isvalid = 1")
                            ->getOneRow();
                        if (!$goods_info["id"] > 0) {
                            $emsg = $emsg . "[行$i ：物料代码" . $order_detail["goods_ccode"] . "不正确]";
                            break;
                        }
                        if (!$goods_info["vendor_id"] > 0 || $goods_info["vendor_id"] <> $order["vendor_id"]) {
                            $emsg = $emsg . "[行$i ：供应商代码" . $order["vendor_code"] . "不正确或与系统内不一致]";
                            break;
                        }
                        $order_detail["goods_id"] = $goods_info["id"];
                        $order_detail["create_by"] = $this->Session->get('uid');
                        unset($order_detail["goods_ccode"]);
                        $order_detail['id'] = $this->Dao->insert(TABLE_ORDER_DETAIL, array_keys($order_detail))
                            ->values(array_values($order_detail))
                            ->exec();
                        if (!$order_detail['id'] > 0) {
                            $emsg = $emsg . "[发货单明细信息保存失败]";
                            break;
                        }
                    }
                } else {
                    //3厂模版导入开始
                    if ($data[$i]["K"] <> null) {
                        //收货数量不为空 说明有收货数据 不做处理
                        continue;
                    }
                    //新订单
                    if ($order["order_code"] <> $data[$i]["M"]) {
                        unset($order);
                        unset($order_detail);
                        $order["order_code"] = $data[$i]["M"];
                        $order["order_type"] = $data[$i]["C"]; //订单类型
                        $order["vendor_code"] = $data[$i]["D"];  //供应商代码
                        $order["address"] = '江淮三工厂' . $data[$i]["O"];  //生产线
                        $order["dock"] = $data[$i]["F"];   //道口
                        $order["order_serial_no"] = $data[$i]["L"];  //流水号
                        $order["order_date"] = $data[$i]["G"];  //
                        $order["status"] = 'create';
                        $order["customer_id"] = $default_customer_id;  //设置收货方ID
                        $isExist = $this->Dao->select('count(1)')
                            ->from(TABLE_ORDER)
                            ->where("order_code = '" . $order["order_code"] . "'")
                            ->aw('isvalid = 1')
                            ->getOne();
                        if ($isExist > 0) {
                            $emsg = $emsg . "[行$i ：发货单" . $order["order_serial_no"] . "已存在]";
                            break;
                        }
                        $order["vendor_id"] = $this->Dao->select('id')
                            ->from(TABLE_VENDOR)
                            ->where("vendor_code = '" . $order["vendor_code"] . "'")
                            ->aw("isvalid = 1")
                            ->getOne();
                        if (!$order["vendor_id"] > 0) {
                            $emsg = $emsg . "[行$i ：发货单" . $order["order_serial_no"] . "供应商信息不正确]";
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
                            $emsg = $emsg . "[发货单保存失败]";
                            break;
                        }
                    }
                    //订单明细
                    if ($order["order_code"] == $data[$i]["M"]) {
                        if ($order["order_type"] <> $data[$i]["C"]) {
                            $emsg = $emsg . "[行$i ：发货单" . $order["order_serial_no"] . "类型不一致]";
                            break;
                        }
                        if ($order["vendor_code"] <> $data[$i]["D"]) {
                            $emsg = $emsg . "[行$i ：发货单" . $order["order_serial_no"] . "供应商不一致]";
                            break;
                        }
                        if ($order["dock"] <> $data[$i]["F"]) {
                            $emsg = $emsg . "[行$i ：发货单" . $order["order_serial_no"] . "道口不一致]";
                            break;
                        }
                        unset($order_detail);
                        $order_detail["order_id"] = $order['id'];  //订单ID
                        $order_detail["bar_code"] = $data[$i]["J"]; //条形码
                        $order_detail["needs"] = intval($data[$i]["E"]);   //需求数量
                        $order_detail["sends"] = $order_detail["needs"];   //需求数量
                        $order_detail["receives"] = $order_detail["needs"];   //需求数量
                        if (!$order_detail["needs"]) {
                            $emsg = $emsg . "[行$i ：需求数量不正确]";
                            break;
                        }
                        $order_detail["goods_ccode"] = $data[$i]["A"];  //物料图号
                        $goods_info = $this->Dao->select('id,vendor_id')
                            ->from(TABLE_GOODS)
                            ->where("goods_ccode = '" . $order_detail["goods_ccode"] . "'")
                            ->aw("isvalid = 1")
                            ->getOneRow();
                        if (!$goods_info["id"] > 0) {
                            $emsg = $emsg . "[行$i ：物料代码" . $order_detail["goods_ccode"] . "不正确]";
                            break;
                        }
                        if (!$goods_info["vendor_id"] > 0 || $goods_info["vendor_id"] <> $order["vendor_id"]) {
                            $emsg = $emsg . "[行$i ：供应商代码" . $order["vendor_code"] . "不正确或与系统内不一致]";
                            break;
                        }
                        $order_detail["goods_id"] = $goods_info["id"];
                        $order_detail["create_by"] = $this->Session->get('uid');
                        unset($order_detail["goods_ccode"]);
                        $order_detail['id'] = $this->Dao->insert(TABLE_ORDER_DETAIL, array_keys($order_detail))
                            ->values(array_values($order_detail))
                            ->exec();
                        if (!$order_detail['id'] > 0) {
                            $emsg = $emsg . "[发货单明细信息保存失败]";
                            break;
                        }
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
