<?php

/**
 * Desc
 * @description Hope You Do Good But Not Evil
 */
class mWeixin extends Model
{

    public function getOrderByCode($order_code)
    {
        $status = array(
            'create' => '新创建',
            'receive' => '仓库已接收',
            'ready' => '备货已完成',
            'check' => '对点已完成',
            'send' => '发货已完成',
            'delivery' => '交货已完成',
            'done' => '全部完成'
        );

        $data = $this->Dao->select()
            ->from(VIEW_ORDER)
            ->where("order_code = '$order_code'")
            ->aw("isvalid = 1")
            ->getOneRow();
        $data['statusX'] = $status[$data['status']];
        $data['goods'] = $this->Dao->select()
            ->from(VIEW_ORDER_DETAIL_SUM)
            ->alias('d')
            ->leftJoin(TABLE_GOODS)
            ->alias('g')
            ->on('d.goods_id = g.id')
            ->where("d.order_id = " . $data['id'])
            ->exec();
        $uid = $this->Session->get('uid');
        $data['hasauth'] = $this->Dao->select('f_check_order_byuser(' . $data['id'] . ",$uid)")
            ->getOne();
        return $data;
    }
}
