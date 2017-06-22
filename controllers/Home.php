<?php

/**
 * 系统控制器
 */
class Home extends ControllerAdmin
{

    //获取概览数据用于首页显示
    public function getCatSummary()
    {
        $res = $this->Dao->select('v.vendor_shortname as `name`, count(o.id) as `count`')
            ->from(TABLE_ORDER)
            ->alias('o')
            ->leftJoin(TABLE_VENDOR)
            ->alias('v')
            ->on('o.vendor_id = v.id')
            ->where('o.isvalid = 1')
            ->groupby('o.vendor_id')
            ->exec();
        $r = array();
        foreach ($res as $index => $s) {
            if ($s['name'] != '' && !empty($s['name'])) {
                $t = array(
                    $s['name'],
                    intval($s['count'])
                );
                $r[] = $t;
            }
        }
        $this->echoJson($r);
    }

}