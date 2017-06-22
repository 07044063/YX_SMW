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
        $list = $this->Dao->select("r.*")
            ->from(TABLE_RETURNING)
            ->alias('r')
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

}