<?php
namespace Admin\Model;
use Think\Model;
class ActionLogModel extends Model{

    protected $tableName = 'action_log';

    public $col = 'ip,uid,username,remark,create_time,url,del';

    public function insertData($row){
        $data = array_bykeys($row,$this->col);
        if($row['id']){
            $r = $this->where(array('id'=>$row['id']))->save($data);
        } else {
            $r = $this->add($data);
        }
        return $r;
    }
}    