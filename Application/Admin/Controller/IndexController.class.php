<?php
namespace Admin\Controller;
use Think\Controller;
use Org\Wechat\wechat;
class IndexController extends Controller
{
    public function _initialize(){
	
	}
	public function index(){
		$options = C('WX_OPTIONS');
		$this->weObj = new Wechat($options);
		$this->weObj->valid();
		$this->bulid_menu();
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
		M('test')->add(array('content'=>'事件类型：'.$event));
		switch ($event['Event']) {
			case EVENT_LOCATION:
				$this->weObj->text('上报地理位置成功')->reply();
				break;
			case EVENT_MENU_CLICK:
				$this->weObj->text('您触发了点击事件')->reply();
				break;	
			default:
				//$this->weObj->text('更多事件')->reply();
				break;
		}		
	}
}