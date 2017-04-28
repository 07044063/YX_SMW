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
        $this->Dao->update($table)
            ->set($data)
            ->where("id = " . $data['id'])
            ->exec();
    }

    public function create($table, $data)
    {
        $data['create_by'] = $this->Session->get('uid');
        $this->Dao->insert($table, array_keys($data))
            ->values(array_values($data))
            ->exec();
    }

}
