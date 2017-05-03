<?php

/**
 * Desc
 * @description Hope You Do Good But Not Evil
 */
class mStock extends Model
{

    public function deleteById($id)
    {
        $this->Dao->update(TABLE_STOCK_ADMIN)
            ->set(array(
                'isvalid' => 0,
                'update_by' => $this->Session->get('uid')
            ))
            ->where("stock_id = $id")
            ->exec();

        $this->Dao->update(TABLE_STOCK)
            ->set(array(
                'isvalid' => 0,
                'update_by' => $this->Session->get('uid')
            ))
            ->where("id = $id")
            ->exec();
    }

    public function updateById($data)
    {
        $data_m['stock_code'] = $data['stock_code'];
        $data_m['stock_name'] = $data['stock_name'];
        $data_m['stock_area'] = $data['stock_area'];
        $data_m['warehouse_id'] = $data['warehouse_id'];
        $data_m['update_by'] = $this->Session->get('uid');
        $data_admin = $data['admin_id'];
        $data_clerk_ids = $data['clerk_ids'];
        $this->Dao->update(TABLE_STOCK)
            ->set($data_m)
            ->where("id = " . $data['id'])
            ->exec();
        $this->insert_admin($data['id'], $data_admin, $data_clerk_ids);
    }

    public function create($data)
    {
        $data_m['stock_code'] = $data['stock_code'];
        $data_m['stock_name'] = $data['stock_name'];
        $data_m['stock_area'] = $data['stock_area'];
        $data_m['warehouse_id'] = $data['warehouse_id'];
        $data_m['create_by'] = $this->Session->get('uid');
        $data_admin = $data['admin_id'];
        $data_clerk_ids = $data['clerk_ids'];
        //插入主表数据
        $stock_id = $this->Dao->insert(TABLE_STOCK, array_keys($data_m))
            ->values(array_values($data_m))
            ->exec();
        if ($stock_id > 0) {
            $this->insert_admin($stock_id, $data_admin, $data_clerk_ids);
        }
    }

    function insert_admin($id, $admin_id, $clerk_ids)
    {
        //作废管理员数据
        $this->Dao->Update(TABLE_STOCK_ADMIN)
            ->set([
                "isvalid" => 0
            ])
            ->where("stock_id = $id")
            ->exec();

        //插入管理员数据
        $this->Dao->insert(TABLE_STOCK_ADMIN, ["stock_id", "person_id", "type"])
            ->values([$id, $admin_id, '1'])
            ->exec();
        //插入理货员数据
        foreach ($clerk_ids as $clerk_id) {
            $this->Dao->insert(TABLE_STOCK_ADMIN, ["stock_id", "person_id", "type"])
                ->values([$id, $clerk_id, '2'])
                ->exec();
        }
    }
}
