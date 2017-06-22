<?php

/**
 * Desc
 * @description Hope You Do Good But Not Evil
 */
class mQuery extends Model
{
    //根据发货单条形码获取发货单信息，以及用户是否有操作权限
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

    //获取概览数据用于首页显示
    public function getOverviewData()
    {
        $data = [];
        $data['order_delivery'] = $this->Dao->select('count(1)')
            ->from(TABLE_ORDER)
            ->alias('o')
            ->where('o.isvalid = 1')
            ->aw('o.create_at > date_sub(curdate(),interval 2 day)')
            ->aw("o.status in ('done','delivery')")
            ->getOne();
        $data['order_send'] = $this->Dao->select('count(1)')
            ->from(TABLE_ORDER)
            ->alias('o')
            ->where('o.isvalid = 1')
            ->aw('o.create_at > date_sub(curdate(),interval 2 day)')
            ->aw("o.status = 'send'")
            ->getOne();
        $data['order_check'] = $this->Dao->select('count(1)')
            ->from(TABLE_ORDER)
            ->alias('o')
            ->where('o.isvalid = 1')
            ->aw('o.create_at > date_sub(curdate(),interval 2 day)')
            ->aw("o.status = 'check'")
            ->getOne();
        $data['order_receive'] = $this->Dao->select('count(1)')
            ->from(TABLE_ORDER)
            ->alias('o')
            ->where('o.isvalid = 1')
            ->aw('o.create_at > date_sub(curdate(),interval 2 day)')
            ->aw("o.status in ('receive','ready')")
            ->getOne();
        $data['order_create'] = $this->Dao->select('count(1)')
            ->from(TABLE_ORDER)
            ->alias('o')
            ->where('o.isvalid = 1')
            ->aw('o.create_at > date_sub(curdate(),interval 2 day)')
            ->aw("o.status = 'create'")
            ->getOne();
        $data['today'] = $this->Dao->select('count(1)')
            ->from(TABLE_ORDER)
            ->alias('o')
            ->where('o.isvalid = 1')
            ->aw('o.create_at > curdate()')
            ->getOne();
        $data['today_done'] = $this->Dao->select('count(1)')
            ->from(TABLE_ORDER)
            ->alias('o')
            ->where('o.isvalid = 1')
            ->aw("o.status = 'done'")
            ->aw('o.create_at > curdate()')
            ->getOne();
        $data['today_check'] = $this->Dao->select('count(1)')
            ->from(TABLE_ORDER)
            ->alias('o')
            ->where('o.isvalid = 1')
            ->aw('o.create_at > curdate()')
            ->aw("o.status = 'check'")
            ->getOne();
        $data['today_create'] = $this->Dao->select('count(1)')
            ->from(TABLE_ORDER)
            ->alias('o')
            ->where('o.isvalid = 1')
            ->aw("o.status in ('receive','ready','create')")
            ->getOne();
        return $data;
    }

}
