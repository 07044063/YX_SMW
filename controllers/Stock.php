<?php

/**
 * 系统控制器
 */
class Stock extends ControllerAdmin
{

    /**
     *
     */
    public function getList()
    {
        $pagesize = $this->pGet('pagesize') ? intval($this->pGet('pagesize')) : 20;
        $page = $this->pGet('page');
        $search_text = '%' . $this->pGet('search_text') . '%';
        $where = "(stock_code like '$search_text' or stock_name like '$search_text')";
        $list = $this->Dao->select('s.*,w.warehouse_code,w.warehouse_name')
            ->from(TABLE_STOCK)
            ->alias('s')
            ->leftJoin(TABLE_WAREHOUSE)
            ->alias('w')
            ->on("s.warehouse_id = w.id")
            ->where($where)
            ->aw("s.isvalid = 1")
            ->limit($pagesize * $page, $pagesize)
            ->exec();
        $list_count = $this->Dao->select('count(*)')
            ->from(TABLE_STOCK)
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
            ->from(TABLE_STOCK)
            ->where("id = $id")
            ->aw("isvalid = 1")
            ->getOneRow();
        $dataone['admin_id'] = $this->Dao->select('person_id')
            ->from(TABLE_STOCK_ADMIN)
            ->where("stock_id = $id")
            ->aw("type = 1")
            ->aw("isvalid = 1")
            ->getOne();
        $clerk_ids = $this->Dao->select("GROUP_CONCAT(person_id)")
            ->from(TABLE_STOCK_ADMIN)
            ->where("stock_id = $id")
            ->aw("type = 2")
            ->aw("isvalid = 1")
            ->getOne();
        $dataone['clerk_ids'] = explode(',', $clerk_ids);
        return $this->echoMsg(0, $dataone);

    }


    public function deleteById()
    {
        $data = $this->post();
        $id = intval($data['id']);
        $this->loadModel(['mStock']);
        if ($id > 0) {
            try {
                $this->mStock->deleteById($id);
                $this->echoMsg(0, '');
            } catch (Exception $ex) {
                return $this->echoMsg(-1, $ex->getMessage());
            }
        } else {
            return $this->echoMsg(-1, '库区信息不正确');
        }
    }

    public function createOrUpdate()
    {
        $data = $this->post();
        $id = intval($data['id']);
        if (!isset($data['warehouse_id']) or $data['warehouse_id'] == '') {
            return $this->echoMsg(-1, '所属仓储不能为空');
        }
        if (!isset($data['stock_area']) or $data['stock_area'] == '') {
            return $this->echoMsg(-1, '库区面积不能为空');
        }
        if (!isset($data['stock_code']) or $data['stock_code'] == '') {
            return $this->echoMsg(-1, '库区代码不能为空');
        } else {
            $exsist = $this->Dao->select('count(*)')
                ->from(TABLE_STOCK)
                ->where("stock_code = '" . $data['stock_code'] . "'")
                ->aw("isvalid = 1")
                ->aw("id <> $id")
                ->getOne();
            if ($exsist > 0) {
                return $this->echoMsg(-1, '库区代码重复');
            }
        }
        if (!isset($data['stock_name']) or $data['stock_name'] == '') {
            return $this->echoMsg(-1, '库区名称不能为空');
        }

        $this->loadModel(['mStock']);
        try {
            if ($id > 0) {
                $this->mStock->updateById($data);
            } else {
                $this->mStock->create($data);
            }
            return $this->echoMsg(0, '');
        } catch (Exception $ex) {
            return $this->echoMsg(-1, $ex->getMessage());
        }
    }

    public function getSelectOption()
    {
        $wh = $this->Dao->select("id,warehouse_name as text")
            ->from(TABLE_WAREHOUSE)
            ->where("isvalid = 1")
            ->exec();
        $per = $this->Dao->select("id,person_name as text")
            ->from(TABLE_PERSON)
            ->where("isvalid = 1")
            ->exec();
        $options['wh'] = $wh;
        $options['per'] = $per;
        return $this->echoMsg(0, $options);
    }

}