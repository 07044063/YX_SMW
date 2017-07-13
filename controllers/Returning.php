<?php

/**
 * 系统控制器
 */
class Returning extends ControllerAdmin
{

    /**
     *
     */
    public function getList()
    {
        $pagesize = $this->pGet('pagesize') ? intval($this->pGet('pagesize')) : 20;
        $page = $this->pGet('page');
        $search_text = '%' . $this->pGet('search_text') . '%';
        $where = "returning_code like '$search_text'";
        $list = $this->Dao->select("r.*,p.person_name")
            ->from(TABLE_RETURNING)
            ->alias('r')
            ->leftJoin(TABLE_PERSON)
            ->alias('p')
            ->on("r.create_by = p.id")
            ->where($where)
            ->aw("r.isvalid = 1")
            ->orderby('r.id desc')
            ->limit($pagesize * $page, $pagesize)
            ->exec();
        $list_count = $this->Dao->select('count(*)')
            ->from(TABLE_RETURNING)
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
        $data = $this->Dao->select("concat(g.goods_ccode,' ',g.goods_name) as goods_name,rd.returning_type,rd.returning_date,rd.counts,rd.remark")
            ->from(TABLE_RETURNING_DETAIL)
            ->alias('rd')
            ->leftJoin(TABLE_GOODS)
            ->alias('g')
            ->on("rd.goods_id = g.id")
            ->where("rd.isvalid = 1")
            ->aw("rd.returning_id = $id")
            ->exec();
        return $this->echoMsg(0, $data);

    }

    public function createDetail()
    {
        $data = $this->post();
        $id = intval($data['id']);
        $detail = $data['data'];
        if ($detail == []) {
            return $this->echoMsg(-1, "物料清单为空");
        }
        $this->loadModel(['mInventory']);
        try {
            $this->mInventory->returningDetail($id, $detail);
            return $this->echoMsg(0, '');
        } catch (Exception $ex) {
            return $this->echoMsg(-1, $ex->getMessage());
        }
    }

    public function create()
    {
        $data = $this->post();
        $data['status'] = 'create';
        if (!$data['returning_code']) {
            return $this->echoMsg(-1, "退货单号不能为空");
        }
        $isExist = $this->Dao->select('count(1)')
            ->from(TABLE_RETURNING)
            ->where("returning_code = '" . $data["returning_code"] . "'")
            ->aw('isvalid = 1')
            ->getOne();
        if ($isExist > 0) {
            return $this->echoMsg(-1, "单号已存在");
        }
        //补齐六位数
        $data['returning_code'] = str_pad($data['returning_code'], 6, "0", STR_PAD_LEFT);

        if (!$data['pic_url']) {
            return $this->echoMsg(-1, "图片信息不正确");
        }
        $this->loadModel(['mCommon']);
        $rid = $this->mCommon->create(TABLE_RETURNING, $data);
        if ($rid > 0) {
            return $this->echoMsg(0, '');
        } else {
            return $this->echoMsg(-1, '保存失败');
        }
    }

}