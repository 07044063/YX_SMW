<?php

/**
 * 系统控制器
 */
class Receive extends ControllerAdmin
{

    /**
     *
     */
    public function createRecords()
    {
        $data = $this->pPost('receiveData');
        $this->loadModel(['mCommon']);
        try {
            foreach ($data as $receive) {
                unset($receive['goods_name']);
                unset($receive['$$hashKey']);
                $this->mCommon->create(TABLE_RECEIVE, $receive);
            }
        } catch (Exception $ex) {
            return $this->echoMsg(-1, $ex->getMessage());
        }
        return $this->echoMsg(0, "保存成功");
    }

    public function getSelectOption()
    {
        $vendorlist = $this->Dao->select("id,vendor_name as text")
            ->from(TABLE_VENDOR)
            ->where("isvalid = 1")
            ->exec();
        $stocklist = $this->Dao->select("id,stock_name as text")
            ->from(TABLE_STOCK)
            ->where("isvalid = 1")
            ->exec();
        $options['vendorlist'] = $vendorlist;
        $options['stocklist'] = $stocklist;
        return $this->echoMsg(0, $options);
    }

    public function getGoodsList()
    {
        $vendor_id = $this->pGet('vendor_id');
        $stock_id = $this->pGet('stock_id');
        $goodslist = $this->Dao->select("id, CONCAT(goods.goods_ccode,'  ',goods.goods_name) as text")
            ->from(TABLE_GOODS)
            ->where("isvalid = 1")
            ->aw("vendor_id = $vendor_id")
            ->aw("stock_id = $stock_id")
            ->exec();
        return $this->echoMsg(0, $goodslist);
    }

    public function getList()
    {
        $pagesize = $this->pGet('pagesize') ? intval($this->pGet('pagesize')) : 20;
        $page = $this->pGet('page');
        $vendor_id =$this->pGet('vendor_id');
        $stock_id= $this->pGet('stock_id');
        $goods_id=$this->pGet('goods_id');
        $receiveFrom_date= $this->pGet('receiveFrom_date');
        $receiveTo_date=$this->pGet('receiveTo_date');
        $where ='1=1';
        if (isset($vendor_id) and $vendor_id != '')
        {
            $where.=" and vendor_id = '$vendor_id'";
        }
        if (isset($stock_id) and $stock_id != '')
        {
            $where.=" and stock_id = '$stock_id'";
        }
        if (isset($goods_id) and $goods_id != '')
        {
            $where.=" and goods_id = '$goods_id'";
        }
        if ((isset($receiveFrom_date) and $receiveFrom_date != '') and (isset($receiveTo_date) and $receiveTo_date != ''))
        {
            $where.=" and receive_date between date_format('$receiveFrom_date','%Y-%m-%d %h:%i:%s') and date_format('$receiveTo_date','%Y-%m-%d %h:%i:%s')\"";
        }

        $list = $this->Dao->select()
            ->from(VIEW_RECEIVE)
            ->where($where)
            ->limit($pagesize * $page, $pagesize)
            ->exec();
        $list_count = $this->Dao->select('count(*)')
            ->from(VIEW_RECEIVE)
            ->where($where)
            ->getOne();
        $data = $this->toJson([
            'total' => $list_count,
            'list' => $list
        ]);

        return $this->echoJsonRaw($data);
    }

    public function getVendorList()
    {
        $stock_id = $this->pGet('stock_id');
        $vendorlist = $this->Dao->select("v.id,v.vendor_name as text")
            ->from(TABLE_VENDOR)
            ->alias('v')
            ->leftJoin(TABLE_STOCK_LOAN)
            ->alias('s')
            ->on("s.vendor_id = v.id")
            ->where("s.isvalid = 1 and v.isvalid = 1")
            ->aw(" s.stock_id='$stock_id'")
            ->exec();
        return $this->echoMsg(0, $vendorlist);
    }

}