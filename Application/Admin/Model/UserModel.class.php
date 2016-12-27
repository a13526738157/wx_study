<?php
/**
 * @Author: Sincez
 * @Date:   2015-12-14 22:32:23
 * @Last Modified by:   Sincez
 * @Last Modified time: 2016-12-01 14:22:31
 */
namespace Admin\Model;
use Think\Model;
class UserModel extends Model{

    protected $tableName = 'users';

    public $col = 'company,username,password,mobile,nicename,status,regtime,regip,lasttime,lastip,email,from,qq,pay_password,m_code';

    public function getUserinfo($row){
        if($row['uid']){
            $this->where(array('sj_users.uid'=>$row['uid']));
        }
        if($row['username']){
            $this->where(array('username'=>$row['username']));
        }
        $data = $this->field('sj_users.*,sj_auth_group_access.group_id')
            ->join('sj_auth_group_access on sj_users.uid = sj_auth_group_access.uid','left')
            ->find();
        return $data;
    }

    public function getData($row){
        if($row['not_uids']){
            $where['sj_users.uid'] = array('not in',$row['not_uids']);
        }
        if($row['group_id']){
            $where['group_id'] = $row['group_id'];
        }
        if($row['ischeck']){
            $where['ischeck'] = $row['ischeck'];
        }
        if($row['del']>-1){
            $where['del'] = $row['del'];
        }
        $data = $this->field('sj_users.*,sj_auth_group_access.group_id')
            ->join('sj_auth_group_access on sj_users.uid=sj_auth_group_access.uid','left')
            ->where($where)
            ->select();
        return $data;
    }

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

    public function login($row){
        $username = $row['username'];
        $password = $row['password'];
        $ip = $row['ip'];
        $verifycode = $row['verify'];
        $verify = new \Think\Verify();
        if(isset($row['verify']) && !$verify->check($verifycode)){
            return array('state' => 2 , 'msg'=>'验证码错误！');
        }
        if(!$username){
            D('loginlist')->insertData(array('username'=>$username,'password'=>$password,'state'=>2,'ip'=>$ip));
            return array('state' => 3 , 'msg'=>'用户名不能为空！');
        }
        if(!$password){
            D('loginlist')->insertData(array('username'=>$username,'password'=>$password,'state'=>3,'ip'=>$ip));
            return array('state' => 4 , 'msg'=>'密码不能为空！');
        }
        #判断登录次数
        $time = time();
        $logins = F('login');
        if($logins[$username]['count']>5 && $logins[$username]['locktime'] && (($time-$logins[$username]['locktime'])<=1800)){
            return array('state' => 4 , 'msg'=>'用户名或者密码错误达到最大次数，稍后再试！');
        }
        unset($map);
        $map['username']  =   $username;
        // $map['mobile']  =   $username;
        // $map['email']   =   $username;
        // $map['_logic']  =   'or';   
        $rs = $this->where($map)->find();
        if($rs['del'] == 1){
            return array('state' => 4 , 'msg'=>'用户无效！');
        }
        if($rs['password']!=md5($password.C('AUTH_kEY'))){
            D('loginlist')->insertData(array('username'=>$username,'password'=>$password,'state'=>4,'ip'=>$ip));
            if(!$logins){
                $logins[$username]['time'] = time();
                $logins[$username]['count'] = 1;
            }
            // print_r($logins);die;
            if($logins && ($time-$logins[$username]['time'])<=1800){
                $logins[$username]['time'] = time();
                $logins[$username]['count'] = $logins[$username]['count']+1;
            }

            if($logins[$username]['count'] >=5){
                $logins[$username]['locktime'] = time();
            }
            if($logins){
                F('login',$logins);
            }
            return array('state' => 5 , 'msg'=>'用户名或者密码错误！');
        }

        if($rs && $rs['status'] == 1){
            D('loginlist')->insertData(array('username'=>$username,'password'=>$password,'state'=>5,'ip'=>$ip));
            return array('state' => 6 , 'msg'=>'该用户已被禁止登录！');
        }
        $d = D('Access')->where(array('uid'=>$rs['uid']))->find();
        $rs['groupid'] = $d['group_id'];
        $gp = D('Group')->find($d['group_id']);
        if($gp && $gp['status'] == 0){
            D('loginlist')->insertData(array('username'=>$username,'password'=>$password,'state'=>6,'ip'=>$ip));
            return array('state' => 7 , 'msg'=>'该用户组已被禁止登录！');
        }
        cookie('PHPSESSID',null);
        session(null);
        cookie('PHPSESSID',session_id());
        
        session('userinfo',$rs);
        D('loginlist')->insertData(array('username'=>$username,'password'=>'******','state'=>1,'ip'=>$ip));
        $this->where(array('uid'=>$rs['uid']))
            ->save(array('lasttime'=>time(),'lastip'=>$ip));
        return array('state' => 1);
    }

    public function reg($row)
    {
        $verify = new \Think\Verify();
        if(isset($row['verify']) && !$verify->check($row['verify'])){
            //return array('state' => 2 , 'msg'=>l('verify_code').l('wrong'));
        }
        if(!$row['username']){
            return array('state' => 3 , 'msg'=>l('user').l('no_empty'));
        }
        if(!$row['password']){
            return array('state' => 4 , 'msg'=>l('pwd').l('no_empty'));
        }
        if(!$row['mobile']){
            return array('state' => 4 , 'msg'=>l('mobile').l('no_empty'));
        }
        if(!$row['email']){
            return array('state' => 4 , 'msg'=>l('email').l('no_empty'));
        }
        if(D('User')->where(array('username'=>$row['username']))->find())
        {
            return array('state' => 4 , 'msg'=>l('username').l('is_exits'));
        }
        if(D('User')->where(array('mobile'=>$row['mobile']))->find())
        {
            return array('state' => 4 , 'msg'=>l('mobile').l('is_exits'));
        }
        if(D('User')->where(array('email'=>$row['email']))->find())
        {
            return array('state' => 4 , 'msg'=>l('email').l('is_exits'));
        }
        unset($row['verify']);
        $row['regtime'] = time();
        $row['groups'] = $row['groups']?$row['groups']:array(2);
        $row['nicename'] = '';
        $row['password'] = md5($row['password'].C('AUTH_kEY'));
        $result = $this->insertData($row);
        return array('state' => 1);
    }
}