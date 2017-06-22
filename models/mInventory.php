<?php

/**
 * Desc
 * @description Hope You Do Good But Not Evil
 */
class mInventory extends Model
{

    public function receive($data)
    {
        //库存表是否有数据
        $isInventoryExist = $this->Dao->select("count(1)")
            ->from(TABLE_INVENTORY)
            ->where("goods_id = " . $data['goods_id'])
            ->aw("isvalid = 1")
            ->getOne();
        //如果还没有库存数据就先插入一条
        if ($isInventoryExist == 0) {
            $inventory = array(
                'goods_id' => $data['goods_id']
            );
            $inventoryid = mCommon::create(TABLE_INVENTORY, $inventory);
        }
        if ($inventoryid > 0 || $isInventoryExist == 1) {
            //插入收货数据
            $receiveid = mCommon::create(TABLE_RECEIVE, $data);
        } else {
            throw  new Exception('库存信息不存在');
        }
        //插入收货数据
        if ($receiveid > 0) {
            //更新库存数据
            $sql = "UPDATE " . TABLE_INVENTORY . " set quantity = quantity + " . $data['count'] . " where goods_id = " . $data['goods_id'] . " and isvalid = 1";
            $this->Db->query($sql);
        } else {
            throw  new Exception('创建收货信息失败');
        }
    }

    public function returningDetail($rid, $data)
    {
        $isReturningExist = $this->Dao->select("count(1)")
            ->from(TABLE_RETURNING)
            ->where("id = $rid")
            ->aw("isvalid = 1")
            ->getOne();
        if ($isReturningExist == 0) {
            throw  new Exception('退货单不存在');
        }
        $this->Db->transtart();
        try {
            foreach ($data as $gd) {
                if ($gd['returning_type'] == 2) {
                    $filed = 'abnormal';
                } else {
                    $filed = 'quantity';
                }
                unset($gd['goods_name']);
                unset($gd['returning_typeX']);
                unset($gd['$$hashKey']);
                $flag = mCommon::create(TABLE_RETURNING_DETAIL, $gd);
                if ($flag > 0) {
                    $sql = "UPDATE " . TABLE_INVENTORY . " set $filed = $filed + " . $gd['counts'] . " where isvalid = 1 and goods_id = " . $gd['goods_id'];
                    $this->Db->query($sql);
                } else {
                    throw  new Exception('退货明细信息保存失败');
                }
            }
            $this->Dao->update(TABLE_RETURNING)
                ->set(['status' => 'done'])
                ->where("id = $rid")
                ->aw("isvalid = 1")
                ->exec();
            $this->Db->transcommit();
        } catch (Exception $ex) {
            $this->Db->transrollback();
            throw  new Exception($ex->getMessage());
        }
    }

    public function confirmBack($id)
    {
        //查找退回单明细数据
        $back = $this->Dao->select()
            ->from(TABLE_BACK)
            ->where("id = $id")
            ->aw("isvalid = 1")
            ->getOneRow();

        if ($back['back_type'] == 2) {
            $filed = 'abnormal';
        } else {
            $filed = 'quantity';
        }

        $this->Db->transtart();

        $gdlist = $this->Dao->select()
            ->from(TABLE_BACK_DETAIL)
            ->where("back_id = $id")
            ->aw("isvalid = 1")
            ->exec();
        try {
            foreach ($gdlist as $gd) {
                $existCount = 0;
                $existCount = $this->Dao->select($filed)
                    ->from(TABLE_INVENTORY)
                    ->where("goods_id = " . $gd['goods_id'])
                    ->aw("isvalid = 1")
                    ->getOne();
                if ($existCount < $gd['needs']) {
                    throw  new Exception('退回数量大于库存数量');
                }
                $sql = "UPDATE " . TABLE_INVENTORY . " set $filed = $filed - " . $gd['needs'] . " where isvalid = 1 and goods_id = " . $gd['goods_id'];
                $this->Db->query($sql);
            }
            $this->Dao->update(TABLE_BACK)
                ->set(['status' => 'done'])
                ->where("id = $id")
                ->aw("isvalid = 1")
                ->exec();
            $this->Db->transcommit();
        } catch (Exception $ex) {
            $this->Db->transrollback();
            throw  new Exception($ex->getMessage());
        }
    }
}
