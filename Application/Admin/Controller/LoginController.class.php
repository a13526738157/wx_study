<?php
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller{
	public function index(){
		if(I('ref')){
			session('ref',base64_decode(I('ref')));
		}
		$this->display();
	}
	public function action(){
		$do = I('do');
		if(IS_POST){
			$row = I('post.');
			unset($return);
			if(isset($row['verify'])){
				//TODO:验证码
			}
			elseif(empty($row['username'])){
				$return['error'] = '用户名不能为空';
			}
			elseif(empty($row['password'])){
				$return['error'] = '密码不能为空';
			}else{//基本验证通过
				$admin = D('Admin')->getData($row);
				if(!$admin){
					//账号不存在
					$return['error'] = '账号或者密码错误';
				}
				elseif($admin['password']!=md5($row['password'])){
					//密码错误
					$return['error'] = '账号或者密码错误';
				}

			}
			if(IS_AJAX)$this->ajaxReturn($return);
			$this->error($return['error']);

		}else{
			if(IS_AJAX)$this->ajaxReturn(array('error'=>'请求方式错误'));
			$this->error('请求方式错误');
		}
	}
}