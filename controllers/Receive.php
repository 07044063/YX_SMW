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

}