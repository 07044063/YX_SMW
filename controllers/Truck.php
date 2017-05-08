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
class Truck extends ControllerAdmin
{

    /**
     *
     */
    public function getList()
    {
        $pagesize = $this->pGet('pagesize') ? intval($this->pGet('pagesize')) : 20;
        $page = $this->pGet('page');
        $search_text = '%' . $this->pGet('search_text') . '%';
        $where = "(truck_code like '$search_text' or truck_desc like '$search_text')";
        $list = $this->Dao->select()
            ->from(TABLE_TRUCK)
            ->where($where)
            ->aw("isvalid = 1")
            ->limit($pagesize * $page, $pagesize)
            ->exec();
        $list_count = $this->Dao->select('count(*)')
            ->from(TABLE_TRUCK)
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
            ->from(TABLE_TRUCK)
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
                $this->mCommon->deleteById(TABLE_TRUCK,$id);
                $this->echoMsg(0, '');
            } catch (Exception $ex) {
                return $this->echoMsg(-1, $ex->getMessage());
            }
        } else {
            return $this->echoMsg(-1, '车辆信息不正确');
        }
    }

    public function createOrUpdate()
    {
        $data = $this->post();
        $id = intval($data['id']);

        if (!isset($data['truck_code']) or $data['truck_code'] == '') {
            return $this->echoMsg(-1, '车牌号不能为空');
        } else {
            $exsist = $this->Dao->select('count(*)')
                ->from(TABLE_VENDOR)
                ->where("truck_code = '" . $data['truck_code'] . "'")
                ->aw("isvalid = 1")
                ->aw("id <> $id")
                ->getOne();
            if ($exsist > 0) {
                return $this->echoMsg(-1, '车牌号重复');
            }
        }
//        if (!isset($data['vendor_name']) or $data['vendor_name'] == '') {
//            return $this->echoMsg(-1, '供货商名称不能为空');
//        }

        $this->loadModel(['mCommon']);
        try {
            if ($id > 0) {
                $this->mCommon->updateById(TABLE_TRUCK,$data);
            } else {
                $this->mCommon->create(TABLE_TRUCK,$data);
            }
            return $this->echoMsg(0, '');
        } catch (Exception $ex) {
            return $this->echoMsg(-1, $ex->getMessage());
        }
    }

}