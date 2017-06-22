<?php

/**
 * 系统控制器
 */
class Back extends ControllerAdmin
{
    /**
     *
     */
    public function getList()
    {
        $pagesize = $this->pGet('pagesize') ? intval($this->pGet('pagesize')) : 20;
        $page = $this->pGet('page');
        $search_text = '%' . $this->pGet('search_text') . '%';
        $where = "(back_code like '$search_text')";
        $list = $this->Dao->select('b.*,v.vendor_code,v.vendor_name')
            ->from(TABLE_BACK)
            ->alias('b')
            ->leftJoin(TABLE_VENDOR)
            ->alias('v')
            ->on('v.id = b.vendor_id')
            ->where($where)
            ->aw("b.isvalid = 1")
            ->orderby('b.id desc')
            ->limit($pagesize * $page, $pagesize)
            ->exec();
        $list_count = $this->Dao->select('count(*)')
            ->from(TABLE_BACK)
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
        $this->loadModel(['mOrder']);
        $data = $this->mOrder->getBackById($id);
        return $this->echoMsg(0, $data);
    }

    public function deleteById()
    {
        $utitle = $this->Session->get('utitle');
        if ($utitle != 4) {  //客服可以删除
            return $this->echoMsg(1, '没有操作权限');
        }
        $id = intval($this->pPost('id'));
        $this->loadModel(['mCommon']);
        try {
            $this->mCommon->deleteById(TABLE_BACK, $id);
            $this->mCommon->deleteByWhere(TABLE_BACK_DETAIL, ['back_id' => $id]);
            return $this->echoMsg(0, '');
        } catch (Exception $ex) {
            return $this->echoMsg(-1, $ex->getMessage());
        }
    }

    public function createBack()
    {
        $postdata = $this->post();
        $backdata = $postdata['backData'];
        $gddata = $postdata['gdData'];

        if (!isset($backdata['back_code']) or $backdata['back_code'] == '') {
            return $this->echoMsg(-1, '单号不能为空');
        }
        if (!isset($backdata['back_date']) or $backdata['back_date'] == '') {
            return $this->echoMsg(-1, '日期不能为空');
        }
        if (!isset($backdata['vendor_id']) or $backdata['vendor_id'] == '') {
            return $this->echoMsg(-1, '供应商不能为空');
        }

        $this->loadModel(['mOrder']);
        $res = $this->mOrder->createBack($backdata, $gddata);
        if ($res['code'] == 0) {
            return $this->echoMsg(0, $res['msg']);
        } else {
            return $this->echoMsg(1, $res['msg']);
        }
    }

    public function confirmBack()
    {
        $utitle = $this->Session->get('utitle');
        if ($utitle != 4) {  //客服可以确认
            return $this->echoMsg(-1, '没有操作权限');
        }
        $id = intval($this->pPost('id'));
        $this->loadModel(['mInventory']);
        try {
            $this->mInventory->confirmBack($id);
            return $this->echoMsg(0, '');
        } catch (Exception $ex) {
            return $this->echoMsg(-1, $ex->getMessage());
        }
    }

    public function getGoodsList()
    {
        $vendor_id = $this->pGet('vendor_id');
        $type = $this->pGet('type');
        if ($type == 2) {
            $goodslist = $this->Dao->select("g.id, CONCAT(g.goods_ccode,'  ',g.goods_name) as text")
                ->from(TABLE_INVENTORY)
                ->alias('i')
                ->leftJoin(TABLE_GOODS)
                ->alias('g')
                ->on('g.id = i.goods_id')
                ->where("i.isvalid = 1")
                ->aw("i.abnormal > 0")
                ->aw("g.vendor_id = $vendor_id")
                ->exec();
        } else {
            $goodslist = $this->Dao->select("id, CONCAT(goods.goods_ccode,'  ',goods.goods_name) as text")
                ->from(TABLE_GOODS)
                ->where("isvalid = 1")
                ->aw("vendor_id = $vendor_id")
                ->exec();
        }
        return $this->echoMsg(0, $goodslist);
    }

    public function getMaxBackNo()
    {
        $date = $this->pGet('date');
        $maxno = $this->Dao->select('max(back_code)')
            ->from(TABLE_BACK)
            ->alias('o')
            ->where("o.back_code like 'R" . $date . "%'")
            ->aw('isvalid = 1')
            ->getOne();
        if ($maxno) {
            $no = intval(substr($maxno, -4));
            $no = $no + 1;
            $no = 'R' . $date . sprintf("%04d", $no);
        } else {
            $no = 'R' . $date . '0001';
        }
        return $this->echoMsg(0, $no);
    }

}