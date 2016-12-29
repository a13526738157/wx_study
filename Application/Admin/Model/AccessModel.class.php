<?php
namespace Admin\Model;
use Think\Model;
class AccessModel extends Model{

    protected $tableName = 'auth_group_access'; 

    public function insertData($row){
        $data['uid'] = $row['uid'];
        $data['group_id'] = $row['group'];
        $this->add($data);
    }
    public function getGroupId($uid)
    {
    	return $this->where(array('uid'=>$uid))->getField('group_id');
    }
}