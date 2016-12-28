<?php
namespace Wx\Controller;
use Think\Controller;
use Org\Wechat\wechat;
use Org\Net\IpLocation;
/**
*微信服务接受
*
*/
class IndexController extends Controller
{
    public function _initialize(){
			$options = C('WX_OPTIONS');
			$this->weObj = new Wechat($options);
			$this->weObj->valid();
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
		//获取回复类型
		$type = $this->weObj->getRev()->getRevType();
		M('test')->add(array('content'=>'消息类型：'.$type));
		$content = $this->weObj->getRevData();		
		switch($type) {
			//消息回复
			case Wechat::MSGTYPE_TEXT:
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
					$this->_event($content);
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
	private function _event($data){
		//事件监听
		$event = $this->weObj->getRevEvent();
		$this->_log('监听事件: 事件名称 ['.$event['event'].']');
		//获取发送信息
		$openid = $data['FromUserName'];
		$userinfo = $this->weObj->getUserInfo($openid);//用户信息		
		switch ($event['event']) {
			case Wechat::EVENT_LOCATION:
				$place = $this->weObj->getRevEventGeo();//获取事件上报地址
				$this->_log('上报地址 x:'.$place['x'].' y:'.$place['y'].' 更多:'.$place['precision']);
				$this->_log('openid'.$openid.'获取用户信息:'.serialize($userinfo));
				//$this->weObj->text($userinfo['nickname'].'上报地理位置成功 x:'.$place['x'].' y:'.$place['y'])->reply();
				break;
			case Wechat::EVENT_MENU_CLICK:
				switch ($event['key']) {
					case 'quickAdd':
						$r = $this->_regUser($userinfo);
						if($r['code'] == 1){
								$text = '您已经是我们的会员：'.$userinfo['nickname'];
								$this->weObj->text($text)->reply();
							}elseif($r['code'] == 2){
								$text = '欢迎加入我们：'.$nickname."\n";
								$text .= '您的账号为：'.$r['username']."\n";
								$text .= '登陆密码和支付密码为：'.$r['username']."\n";
								$this->weObj->text($text)->reply();
							}	
						break;		
					default:
						# code...
						$this->weObj->text('您触发了点击事件')->reply();
						break;
				}
				break;
			case Wechat::EVENT_SUBSCRIBE://订阅
				$this->_log($userinfo['nickname'].'关注了账号');

				$r = $this->_regUser($userinfo);
				if($r['code'] == 1){
					$text = '欢迎回来：'.$userinfo['nickname'];
					$this->weObj->text($text)->reply();
				}elseif($r['code'] == 2){
					$text = '欢迎加入我们：'.$nickname."\n";
					$text .= '您的账号为：'.$r['username']."\n";
					$text .= '登陆密码和支付密码为：'.$r['username']."\n";
					$this->weObj->text($text)->reply();
				}		
				break;
			default:
				//$this->weObj->text('更多事件')->reply();
				break;
		}		
	}
	private function _log($content,$tableName = 'test'){
		$data = $this->weObj->getRevData();
		M($tableName)->add(array('content'=>$content,'user'=>$data['FromUserName']));
	}
	//注册用户
	private function _regUser($userinfo){
		$openid = $userinfo['openid'];
		$user = M('users')->where(array('openid'=>$openid))->find();
		if($user){
			$return['code'] = 2;//已拥有账号
			return $return;
		}
		//判断用户是否登陆
		$pwd = '000000';
		$return['pwd'] = $pwd;
		$data = array();
		$data['username'] = 'wx_'+time();
		$data['nicename'] = $userinfo['nickname'];
		$data['regtime'] = time();
		$data['regip'] = get_client_ip();
		$data['password'] = md5($pwd);
		$data['pay_passwrod'] = md5($pwd);
		$data['headImgUrl'] = $data['headImgUrl'];
		M('users')->add($data);
		$return['code'] = 1;//注册
		$return['username'] = $data['username'];
		return $return;
	}
}