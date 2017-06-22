<?php

/**
 * Desc
 * @description Hope You Do Good But Not Evil
 */
class mCommon extends Model
{

    public function deleteById($table, $id)
    {
        $this->Dao->update($table)
            ->set(array(
                'isvalid' => 0,
                'update_by' => $this->Session->get('uid')
            ))
            ->where("id = $id")
            ->exec();
    }

    public function updateById($table, $data)
    {
        $data['update_by'] = $this->Session->get('uid');
        unset($data['update_at']);
        $this->Dao->update($table)
            ->set($data)
            ->where("id = " . $data['id'])
            ->exec();
    }

    public function create($table, $data)
    {
        $data['create_by'] = $this->Session->get('uid');
        $id = $this->Dao->insert($table, array_keys($data))
            ->values(array_values($data))
            ->exec();
        if (!$id > 0) {
            throw new Exception('新建资料失败');
        }
        return $id;
    }

    public function deleteByWhere($table, $wheredata)
    {
        $where = "1 = 1";
        foreach ($wheredata as $k => $v) {
            $where = $where . " and `$k` = '$v'";
        }
        if ($where == "1 = 1") {
            return false;
        }
        $this->Dao->update($table)
            ->set(array(
                'isvalid' => 0,
                'update_by' => $this->Session->get('uid')
            ))
            ->where($where)
            ->exec();
    }

}
