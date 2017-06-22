<?php

/**
 * 系统控制器
 */
class Customer extends ControllerAdmin
{

    /**
     *
     */
    public function getList()
    {
        $pagesize = $this->pGet('pagesize') ? intval($this->pGet('pagesize')) : 20;
        $page = $this->pGet('page');
        $search_text = '%' . $this->pGet('search_text') . '%';
        $where = "(customer_code like '$search_text' or customer_name like '$search_text')";
        $list = $this->Dao->select()
            ->from(TABLE_CUSTOMER)
            ->where($where)
            ->aw("isvalid = 1")
            ->limit($pagesize * $page, $pagesize)
            ->exec();
        $list_count = $this->Dao->select('count(*)')
            ->from(TABLE_CUSTOMER)
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
            ->from(TABLE_CUSTOMER)
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
                $this->mCommon->deleteById(TABLE_CUSTOMER, $id);
                $this->echoMsg(0, '');
            } catch (Exception $ex) {
                return $this->echoMsg(-1, $ex->getMessage());
            }
        } else {
            return $this->echoMsg(-1, '需求方信息不正确');
        }
    }

    public function createOrUpdate()
    {
        $data = $this->post();
        $id = intval($data['id']);
        if (!isset($data['customer_code']) or $data['customer_code'] == '') {
            return $this->echoMsg(-1, '需求方代码不能为空');
        } else {
            $exsist = $this->Dao->select('count(*)')
                ->from(TABLE_CUSTOMER)
                ->where("customer_code = '" . $data['customer_code'] . "'")
                ->aw("isvalid = 1")
                ->aw("id <> $id")
                ->getOne();
            if ($exsist > 0) {
                return $this->echoMsg(-1, '需求方代码重复');
            }
        }
        if (!isset($data['customer_name']) or $data['customer_name'] == '') {
            return $this->echoMsg(-1, '需求方名称不能为空');
        }

        $this->loadModel(['mCommon']);
        try {
            if ($id > 0) {
                $this->mCommon->updateById(TABLE_CUSTOMER, $data);
            } else {
                $this->mCommon->create(TABLE_CUSTOMER, $data);
            }
            return $this->echoMsg(0, '');
        } catch (Exception $ex) {
            return $this->echoMsg(-1, $ex->getMessage());
        }
    }

}