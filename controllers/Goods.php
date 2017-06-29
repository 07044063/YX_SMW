<?php

/**
 * 系统控制器
 */
class Goods extends ControllerAdmin
{

    /**
     *
     */
    public function getList()
    {
        $pagesize = $this->pGet('pagesize') ? intval($this->pGet('pagesize')) : 20;
        $page = $this->pGet('page');
        $search_text = '%' . $this->pGet('search_text') . '%';
        $where = "(goods_vcode like '$search_text' or goods_ccode like '$search_text' or goods_name like '$search_text' or vendor_name like '$search_text')";
        $list = $this->Dao->select()
            ->from(VIEW_GOODS)
            ->where($where)
            ->aw("isvalid = 1")
            ->limit($pagesize * $page, $pagesize)
            ->exec();
        $list_count = $this->Dao->select('count(*)')
            ->from(VIEW_GOODS)
            ->where($where)
            ->aw("isvalid = 1")
            ->getOne();
        $data = $this->toJson([
            'total' => $list_count,
            'list' => $list
        ]);

        return $this->echoJsonRaw($data);
    }

    public function getById()
    {
        $id = intval($this->pGet('id'));
        $dataone = $this->Dao->select()
            ->from(TABLE_GOODS)
            ->where("id = $id")
            ->aw("isvalid = 1")
            ->getOneRow();
        return $this->echoMsg(0, $dataone);

    }


    public function deleteById()
    {
        $data = $this->post();
        $id = intval($data['id']);
        $this->loadModel(['mCommon']);
        if ($id > 0) {
            try {
                $this->mCommon->deleteById(TABLE_GOODS, $id);
                $this->echoMsg(0, '');
            } catch (Exception $ex) {
                return $this->echoMsg(-1, $ex->getMessage());
            }
        } else {
            return $this->echoMsg(-1, '物料信息不正确');
        }
    }

    public function createOrUpdate()
    {
        $data = $this->post();
        $id = intval($data['id']);

        if (!isset($data['goods_ccode']) or $data['goods_ccode'] == '') {
            return $this->echoMsg(-1, '客户图号不能为空');
        } else {
            $exsist = $this->Dao->select('count(*)')
                ->from(TABLE_GOODS)
                ->where("goods_ccode = '" . $data['goods_ccode'] . "'")
                ->aw("isvalid = 1")
                ->aw("vendor_id = " . $data['vendor_id'])
                ->aw("id <> $id")
                ->getOne();
            if ($exsist > 0) {
                return $this->echoMsg(-1, '同一供应商的已存在相同图号的物料');
            }
        }
        if (!isset($data['goods_name']) or $data['goods_name'] == '') {
            return $this->echoMsg(-1, '物料名称不能为空');
        }
        if (!isset($data['vendor_id']) or $data['vendor_id'] == '') {
            return $this->echoMsg(-1, '供应商信息不能为空');
        }
        if (!isset($data['stock_id']) or $data['stock_id'] == '') {
            return $this->echoMsg(-1, '库区信息不能为空');
        }
        $this->loadModel(['mCommon']);
        try {
            if ($id > 0) {
                $this->mCommon->updateById(TABLE_GOODS, $data);
            } else {
                $this->mCommon->create(TABLE_GOODS, $data);
            }
            return $this->echoMsg(0, '');
        } catch (Exception $ex) {
            return $this->echoMsg(-1, $ex->getMessage());
        }
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

}