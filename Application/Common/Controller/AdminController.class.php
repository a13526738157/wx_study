<?php
namespace Common\Controller;
use Think\Controller;
use Think\Auth;
use Org\Wechat\wechat;
class AdminController extends Controller {
    public function _initialize(){
		$options = array(
				'token'=>'weixin', //填写你设定的key
		        'encodingaeskey'=>'dCAZhdWvBHrz2vJjBzTOgMzqeQfUdIu5exSAQdgojQa' //填写加密用的EncodingAESKey，如接口为明文模式可忽略
			);
		$this->weObj = new Wechat($options);
		$this->weObj->valid();
		$type = $this->weObj->getRev()->getRevType();
		M('test')->add(array('content'=>$type));
		switch($type) {
			case Wechat::MSGTYPE_TEXT:
					$this->weObj->text("hello, I'm wechat")->reply();
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

}