<?php
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller{
	public function index(){
		if(I('ref')){
			session('ref',base64_decode(I('ref')));
		}
		if(session('admininfo')||cookie('admininfo')){
			$this->redirect(U('index/index'));
		}
		$this->display();
	}
	public function action(){
		$do = I('do');
		switch ($do) {
			case 'login':
				$this->_login();
				break;
			case 'logout':
				$this->_logout();
				break;
			default:
				# code...
				break;
		}

	}
	private function _login(){	//name:用户登录
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
					loginLog($row['username'],4,$row['password']);
					$return['error'] = '账号或者密码错误';
				}
				elseif($admin['del']!=1){
					loginLog($row['username'],5,$row['password']);
					$return['error'] = '账号或者密码错误';
				}
				elseif($admin['password']!=md5($row['password'])){
					//密码错误
					loginLog($row['username'],2,$row['password']);
					$return['error'] = '账号或者密码错误';
				}
				elseif($admin['status']==0){
					$return['error'] = '账号已被禁止';
					loginLog($row['username'],3,$row['password']);
				}else{
					loginLog($row['username'],1,$row['password']);
					action_log('用户登录：【用户账号：'.$row['username'].'】');
					session('admininfo',$admin);
					//一周
					cookie('admininfo',$admin,86400*7);
					if(empty(session('ref'))){
						$url = U('index/index');
					}else{
						$url = U(session('ref'));
					}
					if(IS_AJAX)$this->ajaxReturn(array('ok'=>'','url'=>$url));
					$this->redirect($url);
				}

			}
			if(IS_AJAX)$this->ajaxReturn($return);
			$this->error($return['error']);

		}else{
			if(IS_AJAX)$this->ajaxReturn(array('error'=>'请求方式错误'));
			$this->error('请求方式错误');
		}
	}
	private function _logout(){	//name:退出登录
		session('admininfo',null);
		cookie('admininfo',null);
		$url = U('login/index');
		if(IS_AJAX)$this->ajaxReturn(array('ok'=>'','url'=>$url));
		$this->redirect($url);
	}
}