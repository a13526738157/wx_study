<?php
namespace Admin\Controller;
use Think\Controller;
use Common\Controller\AdminController;
class IndexController extends AdminController
{
	public function index(){	//name:品牌商管理
		$this->title = '微信测试';
		header('HTTP/1.1 200 ok');
	}
}