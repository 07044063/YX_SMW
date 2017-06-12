<?php

/**
 * 系统控制器
 */
class Person extends ControllerAdmin
{

    /**
     *
     */
    public function getList()
    {
        $pagesize = $this->pGet('pagesize') ? intval($this->pGet('pagesize')) : 20;
        $page = $this->pGet('page');
        $search_text = '%' . $this->pGet('search_text') . '%';
        $where = "(person_code like '$search_text' or person_name like '$search_text' or person_phone like '$search_text' or person_dept like '$search_text')";
        $list = $this->Dao->select()
            ->from(VIEW_PERSON)
            ->where($where)
            ->limit($pagesize * $page, $pagesize)
            ->exec();
        $list_count = $this->Dao->select('count(*)')
            ->from(VIEW_PERSON)
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
        $dataone = $this->Dao->select('id,person_code,person_type,org_id,person_name,person_phone,person_email,person_dept,person_title')
            ->from(TABLE_PERSON)
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
                $this->mCommon->deleteById(TABLE_PERSON, $id);
                $this->echoMsg(0, '');
            } catch (Exception $ex) {
                return $this->echoMsg(-1, $ex->getMessage());
            }
        } else {
            return $this->echoMsg(-1, '人员信息不正确');
        }
    }

    public function createOrUpdate()
    {
        $data = $this->post();
        $id = intval($data['id']);

        if (!isset($data['person_type']) or $data['person_type'] == '') {
            return $this->echoMsg(-1, '人员类型不能为空');
        }
        if (!isset($data['org_id']) or $data['org_id'] == '') {
            return $this->echoMsg(-1, '所属组织不能为空');
        }
        if (!isset($data['person_name']) or $data['person_name'] == '') {
            return $this->echoMsg(-1, '人员姓名不能为空');
        }

        if (!isset($data['person_phone']) or $data['person_phone'] == '') {
            return $this->echoMsg(-1, '手机号码不能为空');
        } else {
            $exsist = $this->Dao->select('count(*)')
                ->from(TABLE_PERSON)
                ->where("person_phone = '" . $data['person_phone'] . "'")
                ->aw("isvalid = 1")
                ->aw("id <> $id")
                ->getOne();
            if ($exsist > 0) {
                return $this->echoMsg(-1, '手机号码不能重复');
            }
        }

        if (!$id > 0) {
            //创建初始密码
            $this->loadModel(['mAdmin']);
            $data['person_password'] = $this->mAdmin->encryptPassword(substr($data['person_phone'], -6));
        }

        $this->loadModel(['mCommon']);
        try {
            if ($id > 0) {
                $this->mCommon->updateById(TABLE_PERSON, $data);
            } else {
                $this->mCommon->create(TABLE_PERSON, $data);
            }
            return $this->echoMsg(0, '');
        } catch (Exception $ex) {
            return $this->echoMsg(-1, $ex->getMessage());
        }
    }

    public function getTitleOption()
    {
        $title = $this->Dao->select("id,title_name as text")
            ->from(TABLE_TITLE)
            ->where("isvalid = 1")
            ->exec();
        return $this->echoMsg(0, $title);
    }


    public function getOrgOption()
    {
        $type = $this->pGet('type');
        if ($type == 1) {
            $options = $this->Dao->select("id,vendor_name as name")
                ->from(TABLE_VENDOR)
                ->where("isvalid = 1")
                ->exec();
        } elseif ($type == 2) {
            $options = $this->Dao->select("id,customer_name as name")
                ->from(TABLE_CUSTOMER)
                ->where("isvalid = 1")
                ->exec();
        } elseif ($type == 3) {
            $options = $this->Dao->select("id,warehouse_name as name")
                ->from(TABLE_WAREHOUSE)
                ->where("isvalid = 1")
                ->exec();
        }
        return $this->echoMsg(0, $options);
    }

}