<?php
namespace Admin\Model;
use Think\Model;
class UserModel extends Model{

    protected $tableName = 'users';

    public $col = 'username,password,pay_password,nickname,openid,headimgUrl,from,regtime,regip,create_time,mobile';

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
    public function getList(){
        $where['del'] = 1;
        if(I('subscribe')>-1){
            $where['subscribe'] = I('subscribe');
        }
        if(I('keywords')){
            $where['CONCAT(username,nickname)'] = array('like','%'.I('keywords').'%');
        }
        $this->where($where);
        $this->limit(I('start',0).','.I('length',10));
        return $this->order('uid desc')->select();
    }
    public function getCount(){
        $where['del'] = 1;
        if($row['subscribe']>-1){
            $where['subscribe'] = $row['subscribe'];
        }
        $this->where($where);
        return $this->count();        
    }
}