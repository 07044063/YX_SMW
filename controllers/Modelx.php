<?php

/**
 * 系统控制器
 */
class Modelx extends ControllerAdmin
{

    /**
     *获取model列表
     */
    public function getList()
    {
        $pagesize = $this->pGet('pagesize') ? intval($this->pGet('pagesize')) : 20;
        $page = $this->pGet('page');
        $search_text = '%' . $this->pGet('search_text') . '%';
        $where = "(model_code like '$search_text' or model_name like '$search_text' or model_alias like '$search_text')";
        $list = $this->Dao->select('m.*,c.customer_code,c.customer_name')
            ->from(TABLE_MODEL)
            ->alias('m')
            ->leftJoin(TABLE_CUSTOMER)
            ->alias('c')
            ->on("m.customer_id = c.id")
            ->where($where)
            ->aw("m.isvalid = 1")
            ->limit($pagesize * $page, $pagesize)
            ->exec();
        $list_count = $this->Dao->select('count(*)')
            ->from(TABLE_MODEL)
            ->where($where)
            ->aw("isvalid = 1")
            ->getOne();
        $data = $this->toJson([
            'total' => $list_count,
            'list' => $list
        ]);

        return $this->echoJsonRaw($data);
    }

    /**
     *获取单个model信息
     */
    public function getById()
    {
        $id = intval($this->pGet('id'));
        $dataone = $this->Dao->select('m.*,c.customer_code,c.customer_name')
            ->from(TABLE_MODEL)
            ->alias('m')
            ->leftJoin(TABLE_CUSTOMER)
            ->alias('c')
            ->on("m.customer_id = c.id")
            ->where("m.id = $id")
            ->aw("m.isvalid = 1")
            ->getOneRow();
        return $this->echoMsg(0, $dataone);
    }

    /**
     *删除单个model信息
     */
    public function deleteById()
    {
        $data = $this->post();
        $id = intval($data['id']);
        $this->loadModel(['mCommon']);
        if ($id > 0) {
            try {
                $this->mCommon->deleteById(TABLE_MODEL, $id);
                $this->mCommon->deleteByWhere(TABLE_MODEL_GOODS, ['model_id' => $id]);
                $this->echoMsg(0, '');
            } catch (Exception $ex) {
                return $this->echoMsg(-1, $ex->getMessage());
            }
        } else {
            return $this->echoMsg(-1, '车型信息不正确');
        }
    }

    /**
     *编辑model信息
     */
    public function createOrUpdate()
    {
        $data = $this->post();
        $id = intval($data['id']);

        if (!isset($data['customer_id']) or $data['customer_id'] == '') {
            return $this->echoMsg(-1, '主机厂不能为空');
        }
        if (!isset($data['model_code']) or $data['model_code'] == '') {
            return $this->echoMsg(-1, '车型代码不能为空');
        } else {
            $exsist = $this->Dao->select('count(*)')
                ->from(TABLE_MODEL)
                ->where("model_code = '" . $data['model_code'] . "'")
                ->aw("isvalid = 1")
                ->aw("id <> $id")
                ->getOne();
            if ($exsist > 0) {
                return $this->echoMsg(-1, '车型代码重复');
            }
        }
        if (!isset($data['model_name']) or $data['model_name'] == '') {
            return $this->echoMsg(-1, '车型名称不能为空');
        }

        $this->loadModel(['mCommon']);
        try {
            if ($id > 0) {
                $this->mCommon->updateById(TABLE_MODEL, $data);
            } else {
                $this->mCommon->create(TABLE_MODEL, $data);
            }
            return $this->echoMsg(0, '');
        } catch (Exception $ex) {
            return $this->echoMsg(-1, $ex->getMessage());
        }
    }

    /**
     *下拉菜单信息抓取
     */
    public function getSelectOption()
    {
        $customerlist = $this->Dao->select("id,customer_name as text")
            ->from(TABLE_CUSTOMER)
            ->where("isvalid = 1")
            ->exec();
        return $this->echoMsg(0, $customerlist);
    }

    /**
     *获取model对应物料列表
     */
    public function getMgList()
    {
        $pagesize = $this->pGet('pagesize') ? intval($this->pGet('pagesize')) : 20;
        $page = $this->pGet('page');
        $model_id = $this->pGet('id');
        $search_text = '%' . $this->pGet('search_text') . '%';
        $where = "(goods_ccode like '$search_text' or goods_vcode like '$search_text' or goods_name like '$search_text')";
        $list = $this->Dao->select('mg.*,g.goods_vcode,g.goods_ccode,g.goods_name')
            ->from(TABLE_MODEL_GOODS)
            ->alias('mg')
            ->leftJoin(TABLE_GOODS)
            ->alias('g')
            ->on("mg.goods_id = g.id")
            ->where($where)
            ->aw("mg.isvalid = 1 and mg.model_id = $model_id")
            ->limit($pagesize * $page, $pagesize)
            ->exec();
        $list_count = $this->Dao->select('count(*)')
            ->from(TABLE_MODEL_GOODS)
            ->where($where)
            ->aw("mg.isvalid = 1 and mg.model_id = $model_id")
            ->getOne();
        $data = $this->toJson([
            'total' => $list_count,
            'list' => $list
        ]);

        return $this->echoJsonRaw($data);
    }

    /**
     *获取单个model对应物料信息
     */
    public function getMgById()
    {
        $id = intval($this->pGet('id'));
        $dataone = $this->Dao->select()
            ->from(TABLE_MODEL_GOODS)
            ->where("id = $id")
            ->aw("isvalid = 1")
            ->getOneRow();
        return $this->echoMsg(0, $dataone);

    }

    /**
     *删除单个model对应物料信息
     */
    public function deleteMgById()
    {
        $data = $this->post();
        $id = intval($data['id']);
        $this->loadModel(['mCommon']);
        if ($id > 0) {
            try {
                $this->mCommon->deleteById(TABLE_MODEL_GOODS, $id);
                $this->echoMsg(0, '');
            } catch (Exception $ex) {
                return $this->echoMsg(-1, $ex->getMessage());
            }
        } else {
            return $this->echoMsg(-1, '物料明细信息不正确');
        }
    }

    /**
     *编辑model对应物料信息
     */
    public function createOrUpdateMg()
    {
        $data = $this->post();
        $id = intval($data['id']);
        if (!isset($data['model_id']) or $data['model_id'] == '') {
            return $this->echoMsg(-1, '车型信息不正确');
        }
        if (!isset($data['goods_id']) or $data['goods_id'] == '') {
            return $this->echoMsg(-1, '物料信息不能为空');
        } else {
            $exsist = $this->Dao->select('count(*)')
                ->from(TABLE_MODEL_GOODS)
                ->where("goods_id = '" . $data['goods_id'] . "'")
                ->aw("isvalid = 1 and model_id = " . $data['model_id'])
                ->aw("id <> $id")
                ->getOne();
            if ($exsist > 0) {
                return $this->echoMsg(-1, '物料信息重复');
            }
        }
        if (!isset($data['goods_count']) or $data['goods_count'] == '') {
            return $this->echoMsg(-1, '物料数量不能为空');
        }

        $this->loadModel(['mCommon']);
        try {
            if ($id > 0) {
                $this->mCommon->updateById(TABLE_MODEL_GOODS, $data);
            } else {
                $this->mCommon->create(TABLE_MODEL_GOODS, $data);
            }
            return $this->echoMsg(0, '');
        } catch (Exception $ex) {
            return $this->echoMsg(-1, $ex->getMessage());
        }
    }


    public function getGoodsSelectOption()
    {
        $goodslist = $this->Dao->select("id,CONCAT(goods_ccode,'  ',goods_name) as text")
            ->from(TABLE_GOODS)
            ->where("isvalid = 1")
            ->exec();
        return $this->echoMsg(0, $goodslist);
    }

}