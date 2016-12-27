<?php
namespace Admin\Controller;
use Think\Controller;
use Common\Controller\AdminController;
use Org\Wechat\wechat;
class IndexController extends Controller
{
    public function _initialize(){
		$options = array(
				'token'=>'weixin', //填写你设定的key
		        'encodingaeskey'=>'dCAZhdWvBHrz2vJjBzTOgMzqeQfUdIu5exSAQdgojQa', //填写加密用的EncodingAESKey，如接口为明文模式可忽略
		        'appid'	=>	'wx0aac34300c5a1982',
		        'appsecret'	=>	'132d1797e84428db705eabc15b078765'
			);
		$this->weObj = new Wechat($options);
		$this->weObj->valid();
		$this->bulid_menu();
		$type = $this->weObj->getRev()->getRevType();
		M('test')->add(array('content'=>$type));
		switch($type) {
			case Wechat::MSGTYPE_TEXT:
					$this->weObj->text("hello, I'm wechat".json_encode($this->getRevData()))->reply();
					exit;
					break;
			case Wechat::MSGTYPE_EVENT:
					break;
			case Wechat::MSGTYPE_IMAGE:
					break;
			default:
			$this->weObj->text("help info")->reply();
				
			}	
	}
	private function bulid_menu(){
		//获取菜单操作:
	$weObj = $this->weObj;
    $menu = $weObj->getMenu();
    //设置菜单
    $newmenu =  array(
    		"button"=>
    			array(
    				array('type'=>'click','name'=>'最新消息','key'=>'MENU_KEY_NEWS'),
    				array('type'=>'view','name'=>'我要搜索','url'=>'http://www.baidu.com'),
    				)
   		);
   $result = $weObj->createMenu($newmenu);
	}
}