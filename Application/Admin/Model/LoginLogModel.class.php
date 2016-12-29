<?php
namespace Admin\Model;
use Think\Model;
class LoginLogModel extends Model{

    protected $tableName = 'loginlist';

    public $col = 'ip,username,password,create_time,type,del';

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