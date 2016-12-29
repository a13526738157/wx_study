<?php
namespace Common\Controller;
use Think\Controller;
use Think\Auth;
class AdminController extends Controller {

    public function _initialize(){
    	$admininfo = session('admininfo');
    	if(empty($admininfo)){
    		$admininfo = cookie('admininfo');
    		if(empty($admininfo)){
    			$this->redirect(U('login/index',array('ref'=>base64_encode(CONTROLLER_NAME.'/'.ACTION_NAME))));
    		}else{
    			session('admininfo',$admininfo);
    		}
    	}
    	//TOOD:AUTH认证
        $cur = CONTROLLER_NAME."/".ACTION_NAME;
		//权限验证
		if(!authCheck($cur,$admininfo['id'])){
			session('admininfo',null);
            cookie('admininfo',null);
            $this->error('您的权限不足',U('login/index'));
        }
		$this->nav = getAdminMenu();
	}

}