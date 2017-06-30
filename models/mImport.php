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
            return ['success' => 0, 'msg' => "action不正确"];
        }

        //订单导入
        if ($action == 'Order') {
            $rowCount = count($data);
            //判断是几厂的模版
            $otype = 0;
            if ($data[1]["A"] == '序号') {
                $otype = 2;
            } else if ($data[1]["R"] == '工厂') {
                $otype = 1;
            } else {
                $otype = 3;
            }
            //检查是否设置了导入的默认客户编码
            $default_customer_id = intval($this->Dao->select('value')
                ->from(TABLE_SETTINGS)
                ->where("`key` = 'default_customer_id'")
                ->aw("isvalid = 1")
                ->getOne());
            if (!$default_customer_id > 0) {
                return ['success' => 0, 'msg' => "未设置默认的收货方信息"];
            }

            $saveCount = 0;
            $order = [];
            $order_detail = [];
            $emsg = '';
            $thiscode = '';
            $thisid = '';

            for ($i = 2; $i <= $rowCount; $i++) {
                $raw = [];
                if ($otype == 2) {
                    //2厂模版
                    $raw["otype"] = 2;
                    $raw["order_code"] = $data[$i]["P"];
                    $raw["order_type"] = $data[$i]["D"];
                    $raw["vendor_code"] = strtoupper($data[$i]["E"]);
                    $raw["address"] = '江淮二工厂' . $data[$i]["G"];
                    $raw["dock"] = $data[$i]["H"];
                    $raw["order_serial_no"] = $data[$i]["O"];
                    $raw["order_date"] = $data[$i]["I"];
                    $raw["status"] = 'create';
                    $raw["customer_id"] = $default_customer_id;
                    $raw["goods_ccode"] = strtoupper($data[$i]["B"]);
                    $raw["bar_code"] = $data[$i]["L"];
                    $raw["needs"] = intval($data[$i]["F"]);
                    $raw["sends"] = $raw["needs"];
                    $raw["receives"] = $raw["needs"];
                    $raw["rnum"] = $data[$i]["M"];  //收货数量
                } else if ($otype == 1) {
                    //1厂模版
                    $raw["otype"] = 1;
                    $raw["order_code"] = $data[$i]["N"];
                    $raw["order_type"] = $data[$i]["C"];
                    $raw["vendor_code"] = strtoupper($data[$i]["D"]);
                    $raw["address"] = '江淮一工厂' . $data[$i]["P"];
                    $raw["dock"] = $data[$i]["G"];
                    $raw["order_serial_no"] = $data[$i]["M"];
                    $raw["order_date"] = $data[$i]["H"];
                    $raw["status"] = 'create';
                    $raw["customer_id"] = $default_customer_id;
                    $raw["goods_ccode"] = strtoupper($data[$i]["A"]);
                    $raw["bar_code"] = $data[$i]["K"];
                    $raw["needs"] = intval($data[$i]["E"]);
                    $raw["sends"] = $raw["needs"];
                    $raw["receives"] = $raw["needs"];
                    $raw["rnum"] = $data[$i]["L"];  //收货数量
                } else {
                    $raw["otype"] = 3;
                    $raw["order_code"] = $data[$i]["M"];
                    $raw["order_type"] = $data[$i]["C"];
                    $raw["vendor_code"] = strtoupper($data[$i]["D"]);
                    $raw["address"] = '江淮二工厂' . $data[$i]["O"];
                    $raw["dock"] = $data[$i]["F"];
                    $raw["order_serial_no"] = $data[$i]["L"];
                    $raw["order_date"] = $data[$i]["G"];
                    $raw["status"] = 'create';
                    $raw["customer_id"] = $default_customer_id;
                    $raw["goods_ccode"] = strtoupper($data[$i]["A"]);
                    $raw["bar_code"] = $data[$i]["J"];
                    $raw["needs"] = intval($data[$i]["E"]);
                    $raw["sends"] = $raw["needs"];
                    $raw["receives"] = $raw["needs"];
                    $raw["rnum"] = $data[$i]["K"];  //收货数量
                }
                if ($raw["rnum"] <> null) {
                    //收货数量不为空 说明有收货数据 不做处理
                    continue;
                }
                if ($raw["order_serial_no"] < '17000') {
                    //排除掉历史的未收货的单据
                    continue;
                }
                //新订单
                if ($raw["order_code"] <> $thiscode) {
                    if ($thisid) {
                        $saveCount = $saveCount + 1;
                        $this->Db->transcommit();
                    }
                    $thiscode = $raw["order_code"];
                    $thisid = '';
                    unset($order);
                    unset($order_detail);
                    $order["order_code"] = $raw["order_code"];
                    $order["order_type"] = $raw["order_type"]; //订单类型
                    $order["vendor_code"] = $raw["vendor_code"];  //供应商代码
                    $order["address"] = $raw["address"];  //生产线
                    $order["dock"] = $raw["dock"];   //道口
                    $order["order_serial_no"] = $raw["order_serial_no"];  //流水号
                    $order["order_date"] = $raw["order_date"];  //需求时间
                    $order["status"] = $raw["status"];
                    $order["customer_id"] = $raw["customer_id"];  //设置收货方ID

                    $isExist = $this->Dao->select('count(1)')
                        ->from(TABLE_ORDER)
                        ->where("order_code = '" . $order["order_code"] . "'")
                        ->aw('isvalid = 1')
                        ->getOne();
                    if ($isExist > 0) {
                        //单子如果已经存在则跳过
                        slog($raw["order_code"]);
                        slog($order["order_code"]);
                        $emsg = $emsg . "[行$i ：发货单" . $order["order_serial_no"] . "已经存在]<br>";
                        continue;
                    } else {
                        //先获取供应商ID
                        $count_v = $this->Dao->select('count(1)')
                            ->from(TABLE_VENDOR)
                            ->where("vendor_code = '" . $order["vendor_code"] . "'")
                            ->aw("isvalid = 1")
                            ->getOne();
                        if ($count_v == 0) {
                            $order["vendor_id"] = '';
                        } else if ($count_v == 1) {
                            $order["vendor_id"] = $this->Dao->select('id')
                                ->from(TABLE_VENDOR)
                                ->where("vendor_code = '" . $order["vendor_code"] . "'")
                                ->aw("isvalid = 1")
                                ->getOne();
                        } else {
                            //同一个供应商代码有多个
                            $newcode = $this->getGoodsCode($raw);
                            $order["vendor_id"] = $this->Dao->select('g.vendor_id')
                                ->from(TABLE_GOODS)
                                ->alias('g')
                                ->leftJoin(TABLE_VENDOR)
                                ->alias('v')
                                ->on("g.vendor_id = v.id")
                                ->where("v.vendor_code = '" . $order["vendor_code"] . "'")
                                ->aw("g.isvalid = 1")
                                ->aw("v.isvalid = 1")
                                ->aw("g.goods_ccode = '" . $newcode . "'")
                                ->getOne();
                        }
                        //对未找到供应商信息处理
                        if (!$order["vendor_id"]) {
                            $emsg = $emsg . "[行$i ：发货单" . $order["order_serial_no"] . "供应商代码不正确]<br>";
                            continue;
                        }

                        //开启事务
                        $this->Db->transtart();

                        //插入订单主数据
                        $order_insert = $order;
                        unset($order_insert["vendor_code"]);
                        $order_insert['create_by'] = $this->Session->get('uid');
                        $thisid = $this->Dao->insert(TABLE_ORDER, array_keys($order_insert))
                            ->values(array_values($order_insert))
                            ->exec();
                        //插入订单状态资料
                        if ($thisid > 0) {
                            $order_status_id = $this->Dao->insert(TABLE_ORDER_STATUS, ['order_id', 'status', 'create_by'])
                                ->values([$thisid, 'create', $order_insert['create_by']])
                                ->exec();
                        }
                        if ($thisid > 0 && $order_status_id > 0) {
                            //保存成功
                            unset($order_insert);
                        } else {
                            $emsg = $emsg . "[行$i ：发货单" . $order["order_serial_no"] . "保存失败]<br>";
                            $thisid = '';
                            $this->Db->transrollback();
                            continue;
                        }
                    }
                }  //一笔新的订单主数据处理结束

                //开始处理订单明细
                if ($raw["order_code"] == $thiscode && $thisid > 0) {
                    if ($raw["order_type"] <> $order["order_type"]) {
                        $emsg = $emsg . "[行$i ：发货单" . $order["order_serial_no"] . "类型不一致]<br>";
                        $thisid = 0;
                    }
                    if ($raw["vendor_code"] <> $order["vendor_code"]) {
                        $emsg = $emsg . "[行$i ：发货单" . $order["order_serial_no"] . "供应商不一致]<br>";
                        $thisid = 0;
                    }
                    if ($raw["dock"] <> $order["dock"]) {
                        $emsg = $emsg . "[行$i ：发货单" . $order["order_serial_no"] . "道口不一致]<br>";
                        $thisid = 0;
                    }
                    if (!$raw["needs"] > 0) {
                        $emsg = $emsg . "[行$i ：发货单" . $order["order_serial_no"] . "需求数量不正确]<br>";
                        $thisid = 0;
                    }
                    unset($order_detail);
                    $order_detail["goods_id"] = $this->getGoodsId($raw);
                    if (!$order_detail["goods_id"]) {
                        $emsg = $emsg . "[行$i ：发货单" . $order["order_serial_no"] . "物料代码不正确]<br>";
                        $thisid = 0;
                    }
                    if (!$thisid) {
                        //如果thisid为0 则回滚，跳出
                        $this->Db->transrollback();
                        continue;
                    }
                    $order_detail["order_id"] = $thisid;  //订单ID
                    $order_detail["bar_code"] = $raw["bar_code"]; //条形码
                    $order_detail["needs"] = intval($raw["needs"]);   //需求数量
                    $order_detail["sends"] = $order_detail["needs"];
                    $order_detail["receives"] = $order_detail["needs"];
                    $order_detail["create_by"] = $this->Session->get('uid');
                    $order_detail['id'] = $this->Dao->insert(TABLE_ORDER_DETAIL, array_keys($order_detail))
                        ->values(array_values($order_detail))
                        ->exec();
                    if (!$order_detail['id']) {
                        $emsg = $emsg . "[行$i ：发货单" . $order["order_serial_no"] . "明细信息保存失败]<br>";
                        $thisid = 0;
                        $this->Db->transrollback();
                        continue;
                    }
                }
            }
            //最后一次的订单数据还没提交，提交一次
            if ($thisid) {
                $saveCount = $saveCount + 1;
                $this->Db->transcommit();
            }
            return ['success' => $saveCount, 'msg' => $emsg];
        }
    }

    private function getGoodsId($raw)
    {
        $newcode = $this->getGoodsCode($raw);
        $goods_id = $this->Dao->select('g.id')
            ->from(TABLE_GOODS)
            ->alias('g')
            ->leftJoin(TABLE_VENDOR)
            ->alias('v')
            ->on("g.vendor_id = v.id")
            ->where("g.isvalid = 1")
            ->aw("v.isvalid = 1")
            ->aw("g.goods_ccode = '" . $newcode . "'")
            ->aw("v.vendor_code = '" . $raw["vendor_code"] . "'")
            ->getOne();
        return $goods_id;
    }

    private function getGoodsCode($raw)
    {
        $res = $raw["goods_ccode"];
        $mapdata = $this->Dao->select()
            ->from(TABLE_GOODS_CODE_MAP)
            ->where("input_code = '" . $raw["goods_ccode"] . "'")
            ->aw("isvalid = 1")
            ->getOneRow();
        if ($mapdata) {
            if ($mapdata["type"] == 'PLANT') {
                if ($raw["otype"] == 2) {
                    $res = $mapdata["value2"];
                } else if ($raw["otype"] == 3) {
                    $res = $mapdata["value3"];
                }
            } else if ($mapdata["type"] == 'KD') {
                if (strstr($raw["dock"], 'KD')) {
                    $res = $mapdata["value2"];
                } else {
                    $res = $mapdata["value1"];
                }
            }
        }
        return $res;
    }

}
