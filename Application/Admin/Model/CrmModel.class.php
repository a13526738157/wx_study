<?php
/**
 * Created by Whb
 * Auher: Whb
 * Date: 2016/11/19
 * Time: 11:03
 */

namespace Admin\Model;
use Think\Model;


class CrmModel extends Model
{
    protected $tableName = 'Crm';

    protected $col = 'id,name,tel,sheng,city,area,address,favorite,create_time,sale_id,status,del';

    public function insertData($row){
        $data = array_bykeys($row,$this->col);
        if($row['id']){
            $this->where(array('id'=>$row['id']))->save($data);
        } else {
            $this->add($data);
        }
    }

    public function getData($row){
        if($row['status']>-1){
            $this->where(array('status'=>$row['status']));
        }
        return $this->select();
    }
}