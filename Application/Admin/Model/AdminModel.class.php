<?php
namespace Admin\Model;
use Think\Model;
class AdminModel extends Model{

    protected $tableName = 'users';

    public $col = 'username,nickname,password,code,from,curip,cur_time,lastip,lasttime,createip,create_time,status,del';

    public function insertData($row){
        $data = array_bykeys($row,$this->col);
        if($row['uid']){
            $this->where(array('uid'=>$row['uid']))->save($data);
            $uid = $row['uid'];
            D('Admin/access')->where(array('uid'=>$row['uid']))->delete();
            foreach(array_filter($row['groups']) as $v){
                D('Admin/Access')->insertData(array('uid'=>$row['uid'],'group'=>$v));
            }
        } else {
            $uid = $this->add($data);
            D('Admin/Access')->where(array('uid'=>$uid))->delete();
            foreach(array_filter($row['groups']) as $v){
                D('Admin/Access')->insertData(array('uid'=>$uid,'group'=>$v));
            }
        }
        return $uid;
    }
    /**
    *获取用户信息
    *@param array $row 查询条件
    *@param mode 1单条 多条
    *@return 
    */
    public function getData($row=array(),$mode=0){
    	if(isset($row['username']))
    		$where['username'] = $row['username'];
    	if(isset($where))
    		$this->where($where);
    	if($mode == 0){
    		return $this->find();
    	}else{
    		return $this->select();
    	}

    }
}    