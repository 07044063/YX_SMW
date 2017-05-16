<?php

/**
 * Desc
 * @description Hope You Do Good But Not Evil
 */
class mReceive extends Model
{

    public function create($data)
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
            mCommon::create(TABLE_INVENTORY, $inventory);
        }
        //插入收货数据
        mCommon::create(TABLE_RECEIVE, $data);
        //更新库存数据
        $sql = "update " . TABLE_INVENTORY . " set quantity = quantity + " . $data['count'] . " where goods_id = " . $data['goods_id'] . " and isvalid = 1";
        $this->Db->query($sql);
    }

}
