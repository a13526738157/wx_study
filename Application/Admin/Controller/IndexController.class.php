<?php
namespace Admin\Controller;
use Common\Controller\AdminController;
class IndexController extends AdminController
{
    public function _initialize(){
    	parent::_initialize();
    	//面包屑
    	$this->breads = array(
    		array('title' => '欢迎页','key'=>1,'url'=>U('index/index')),
   			);
	}
	public function index(){
		$this->init = array(
			'title'	=>	'首页',
			'key'	=>	1
			);

		$this->display();
	}
}