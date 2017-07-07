<?php
/**
 * Created by PhpStorm.
 * User: conghu
 * Date: 2017/5/4
 * Time: 22:38
 */

/**
 * 系统控制器
 */
class Stockloan extends ControllerAdmin
{

    /**
     *
     */
    public function getList()
    {
        $pagesize = $this->pGet('pagesize') ? intval($this->pGet('pagesize')) : 20;
        $page = $this->pGet('page');
        $search_text = '%' . $this->pGet('search_text') . '%';
        $where = "(stock_code like '$search_text' or stock_name like '$search_text' or vendor_name like '$search_text' or vendor_code like '$search_text')";
        $list = $this->Dao->select()
            ->from(VIEW_STOCK_LOAN)
            ->where($where)
            ->limit($pagesize * $page, $pagesize)
            ->exec();
        $list_count = $this->Dao->select('count(*)')
            ->from(VIEW_STOCK_LOAN)
            ->where($where)
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
            ->from(TABLE_STOCK_LOAN)
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
                $this->mCommon->deleteById(TABLE_STOCK_LOAN, $id);
                $this->echoMsg(0, '');
            } catch (Exception $ex) {
                return $this->echoMsg(-1, $ex->getMessage());
            }
        } else {
            return $this->echoMsg(-1, '输入信息不正确');
        }
    }

    public function createOrUpdate()
    {
        $data = $this->post();
        $id = intval($data['id']);

        if (!isset($data['stock_id']) or $data['stock_id'] == '') {
            return $this->echoMsg(-1, '库区不能为空');
        }
        if (!isset($data['vendor_id']) or $data['vendor_id'] == '') {
            return $this->echoMsg(-1, '供货商不能为空');
        }
        if (!intval($data['price']) > 0) {
            return $this->echoMsg(-1, '单价不正确');
        }
        $exsist = $this->Dao->select('count(*)')
            ->from(TABLE_STOCK_LOAN)
            ->where("stock_id = " . $data['stock_id'])
            ->aw("vendor_id = " . $data['vendor_id'])
            ->aw("isvalid = 1")
            ->aw("id <> $id")
            ->getOne();
        if ($exsist > 0) {
            return $this->echoMsg(-1, '已有此供应商和库区的租赁数据');
        }

        $this->loadModel(['mCommon']);
        try {
            if ($id > 0) {
                $this->mCommon->updateById(TABLE_STOCK_LOAN, $data);
            } else {
                $this->mCommon->create(TABLE_STOCK_LOAN, $data);
            }
            return $this->echoMsg(0, '');
        } catch (Exception $ex) {
            return $this->echoMsg(-1, $ex->getMessage());
        }
    }

    public function getStockList()
    {
        $options = $this->Dao->select("id,stock_name as name")
            ->from(TABLE_STOCK)
            ->where("isvalid = 1")
            ->exec();
        return $this->echoMsg(0, $options);
    }

    public function getVendorList()
    {
        $options = $this->Dao->select("id,CONCAT(vendor_code,' ',vendor_shortname) as name")
            ->from(TABLE_VENDOR)
            ->where("isvalid = 1 order by vendor_code")
            ->exec();
        return $this->echoMsg(0, $options);
    }

}