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
		M('test')->add(array('content'=>json_encode(file_get_contents("php://input"))));

		$message = $weObj->getRev();
		M('test')->add(array('content'=>'获得的消息:'.$message));
		$type = $weObj->getRev()->getRevType();
		M('test')->add(array('content'=>$type));
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
			$weObj->text("help info")->reply();
				
			}	
	}

}