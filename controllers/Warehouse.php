<?php

/**
 * 系统控制器
 */
class Warehouse extends ControllerAdmin
{

    /**
     *
     */
    public function getList()
    {
        $pagesize = $this->pGet('pagesize') ? intval($this->pGet('pagesize')) : 20;
        $page = $this->pGet('page');
        $search_text = '%' . $this->pGet('search_text') . '%';
        $where = "(warehouse_code like '$search_text' or warehouse_name like '$search_text')";
        $list = $this->Dao->select()
            ->from(TABLE_WAREHOUSE)
            ->where($where)
            ->aw("isvalid = 1")
            ->limit($pagesize * $page, $pagesize)
            ->exec();
        $list_count = $this->Dao->select('count(*)')
            ->from(TABLE_WAREHOUSE)
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
            ->from(TABLE_WAREHOUSE)
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
                $this->mCommon->deleteById(TABLE_WAREHOUSE,$id);
                $this->echoMsg(0, '');
            } catch (Exception $ex) {
                return $this->echoMsg(-1, $ex->getMessage());
            }
        } else {
            return $this->echoMsg(-1, '仓储信息不正确');
        }
    }

    public function createOrUpdate()
    {
        $data = $this->post();
        $id = intval($data['id']);

        if (!isset($data['warehouse_code']) or $data['warehouse_code'] == '') {
            return $this->echoMsg(-1, '仓储代码不能为空');
        } else {
            $exsist = $this->Dao->select('count(*)')
                ->from(TABLE_WAREHOUSE)
                ->where("warehouse_code = '" . $data['warehouse_code'] . "'")
                ->aw("isvalid = 1")
                ->aw("id <> $id")
                ->getOne();
            if ($exsist > 0) {
                return $this->echoMsg(-1, '仓储代码重复');
            }
        }
        if (!isset($data['warehouse_name']) or $data['warehouse_name'] == '') {
            return $this->echoMsg(-1, '仓储名称不能为空');
        }

        $this->loadModel(['mCommon']);
        try {
            if ($id > 0) {
                $this->mCommon->updateById(TABLE_WAREHOUSE,$data);
            } else {
                $this->mCommon->create(TABLE_WAREHOUSE,$data);
            }
            return $this->echoMsg(0, '');
        } catch (Exception $ex) {
            return $this->echoMsg(-1, $ex->getMessage());
        }
    }

}