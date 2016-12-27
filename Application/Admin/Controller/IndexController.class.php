<?php
namespace Admin\Controller;
use Think\Controller;
use Common\Controller\AdminController;
class IndexController extends AdminController
{
	public function index(){	//name:品牌商管理
		$type = $weObj->getRev()->getRevType();
		switch($type) {
			case Wechat::MSGTYPE_TEXT:
					$weObj->text("hello, I'm wechat")->reply();
					exit;
					break;
			case Wechat::MSGTYPE_EVENT:
					break;
			case Wechat::MSGTYPE_IMAGE:
					break;
			default:
				
			}
}