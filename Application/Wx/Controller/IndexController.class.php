<?php
namespace Wx\Controller;
use Think\Controller;
use Org\Wechat\wechat;
/**
*微信服务接受
*
*/
class IndexController extends Controller
{
    public function _initialize(){
			$options = C('WX_OPTIONS');
			$this->weObj = new Wechat($options);
			$this->bulid_menu();
			//获取accessToken
			// $accessArr = F('accessToken');

			// if(!$accessArr['accessToken']||$accessArr['time']<time()){
			// 	$accessArr['accessToken'] = $this->weObj->getOauthAccessToken();
			// 	$this->_log(json_encode($accessArr));
			// 	$accessArr['time'] = time()+7100;
			// 	F('accessToken',$accessArr);
			// }

	}
	public function index(){
		$this->weObj->valid();
		//获取回复类型
		$type = $this->weObj->getRev()->getRevType();
		M('test')->add(array('content'=>'消息类型：'.$type));
		switch($type) {
			//消息回复
			case Wechat::MSGTYPE_TEXT:
					$content = $this->weObj->getRevData();
					$content = $content['Content'];
					switch ($content) {
						case '你好':
							$text = '您好';
							break;
						default:
							$text = '你好世界';
							break;
					}
					$this->weObj->text($text)->reply();
					break;
			//事件监听
			case Wechat::MSGTYPE_EVENT:
					$this->_event();
					break;
			case Wechat::MSGTYPE_IMAGE:
					break;		
			default:
					$this->weObj->text("help info")->reply();
					break;
			}
			
	}
	private function bulid_menu(){
		//获取菜单操作:
		$weObj = $this->weObj;
	    $menu = $weObj->getMenu();
	    //设置菜单
	    $newmenu =  C('WX_MENU');
	   	$result = $weObj->createMenu($newmenu);
	}
	//消息处理
	private function _msg(){

	}
	//事件处理
	private function _event(){
		//事件监听
		$event = $this->weObj->getRevEvent();
		$this->_log('监听事件: 事件名称 ['.$event['event'].']');
		switch ($event['event']) {
			case Wechat::EVENT_LOCATION:
				$place = $this->weObj->getRevEventGeo();//获取事件上报地址

				$this->_log('上报地址 x:'.$place['x'].' y:'.$place['y'].' 更多:'.$place['precision']);
				$this->weObj->text('上报地理位置成功 x:'.$place['x'].' y:'.$place['y'])->reply();
				break;
			case Wechat::EVENT_MENU_CLICK:
				$this->weObj->text('您触发了点击事件')->reply();
				break;	
			default:
				//$this->weObj->text('更多事件')->reply();
				break;
		}		
	}
	private function _log($content){
		$data = $this->weObj->getRevData();
		M('test')->add(array('content'=>$content,'user'=>$data['FromUserName']));
	}
}