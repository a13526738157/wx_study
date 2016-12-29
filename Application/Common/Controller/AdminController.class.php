<?php
namespace Common\Controller;
use Think\Controller;
use Think\Auth;
class AdminController extends Controller {

    public function _initialize(){
    	$admininfo = session('admininfo');
    	if(empty($admininfo)){
    		$this->redirect(U('login/index',array('ref'=>base64_encode(CONTROLLER_NAME.'/'.ACTION_NAME))));
    	}
	}

}