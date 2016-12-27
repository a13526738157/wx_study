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
		$type = $this->weObj->getRev()->getRevType();
		//M('test')->add(array('content'=>json_encode($this->weObj->getRevData())));
		switch($type) {
			case Wechat::MSGTYPE_TEXT:
					$content = $this->weObj->getRevData();
					$content = $content['Content'];
					switch ($content) {
						case '你好':
							$text = '您好';
							break;
						case '位置':
							$location = $this->weObj->getRev()->getRevGeo();
							//$text = '您的经度'.$location['Longitude'];
							M('test')->add(array('content'=>$location));
							$text = '位置'.$location;
							break;
						default:
							$text = '你好世界';
							break;
					}
					$this->weObj->text($text)->reply();
					break;
			case Wechat::MSGTYPE_EVENT:
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
}