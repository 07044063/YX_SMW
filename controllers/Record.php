<?php

/**
 * 系统控制器
 */
class Record extends ControllerAdmin
{


    public function getList()
    {
        $pagesize = $this->pGet('pagesize') ? intval($this->pGet('pagesize')) : 20;
        $page = $this->pGet('page');
        $vendor_id = $this->pGet('vendor_id');
        $stock_id = $this->pGet('stock_id');
        $goods_id = $this->pGet('goods_id');
        $from_date = $this->pGet('from_date');
        $to_date = $this->pGet('to_date');
        $rtype = $this->pGet('rtype');
        $where = '1=1';
        if (isset($vendor_id) and $vendor_id > 0) {
            $where .= " and vendor_id = '$vendor_id'";
        }
        if (isset($stock_id) and $stock_id > 0) {
            $where .= " and stock_id = '$stock_id'";
        }
        if (isset($goods_id) and $goods_id > 0) {
            $where .= " and goods_id = '$goods_id'";
        }
        if ((isset($from_date) and $to_date != '') and (isset($from_date) and $to_date != '')) {
            $where .= " and gdate between date_format('$from_date','%Y-%m-%d') and date_format('$to_date','%Y-%m-%d') ";
        }
        if (isset($rtype) and $rtype != '') {
            $where .= " and gtype like '%$rtype%'";
        }

        $list = $this->Dao->select()
            ->from(VIEW_RECORD)
            ->where($where)
            ->limit($pagesize * $page, $pagesize)
            ->exec();
        $list_count = $this->Dao->select('count(*)')
            ->from(VIEW_RECORD)
            ->where($where)
            ->getOne();
        $data = $this->toJson([
            'total' => $list_count,
            'list' => $list
        ]);

        return $this->echoJsonRaw($data);
    }

    public function export()
    {
        //导出数据
        $vendor_id = $this->pGet('vendor_id');
        $stock_id = $this->pGet('stock_id');
        $goods_id = $this->pGet('goods_id');
        $from_date = $this->pGet('from_date');
        $to_date = $this->pGet('to_date');
        $rtype = $this->pGet('rtype');
        $where = '1=1';
        if (isset($vendor_id) and $vendor_id > 0) {
            $where .= " and vendor_id = '$vendor_id'";
        }
        if (isset($stock_id) and $stock_id > 0) {
            $where .= " and stock_id = '$stock_id'";
        }
        if (isset($goods_id) and $goods_id > 0) {
            $where .= " and goods_id = '$goods_id'";
        }
        if ((isset($from_date) and $to_date != '') and (isset($from_date) and $to_date != '')) {
            $where .= " and gdate between date_format('$from_date','%Y-%m-%d') and date_format('$to_date','%Y-%m-%d') ";
        }
        if (isset($rtype) and $rtype != '') {
            $where .= " and gtype like '%$rtype%'";
        }

        $list = $this->Dao->select()
            ->from(VIEW_RECORD_EXPORT)
            ->where($where)
            ->exec();

        $this->loadModel(['mExport']);
        $res = $this->mExport->exportExcel($list, 'record.xlsx');
        return $this->echoMsg($res['code'], $res['msg']);
    }

}