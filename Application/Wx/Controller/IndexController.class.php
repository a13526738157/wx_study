<?php
namespace Wx\Controller;
use Think\Controller;
//use Org\Wechat\wechat;
use Org\Wechat\Thinkphp\TPWechat;
/**
*微信服务接受
*
*/
class IndexController extends Controller
{
    public function _initialize(){
			$options = C('WX_OPTIONS');
			$this->weObj = new TPWechat($options);
			$this->weObj->valid();
			$is_menu = M('sysConfig')->where(array('name'=>'menu'))->find();
			if($is_menu['val']){
				$this->bulid_menu();
			}
	}
	public function index(){
		//获取回复类型
		$type = $this->weObj->getRev()->getRevType();
		$content = $this->weObj->getRevData();
		$openid = $content['FromUserName'];
		$user = M('users')->where(array('openid'=>$openid))->find();
		if($user){
			$this->userinfo = $user;
		}else{
			$this->userinfo = $this->weObj->getUserInfo($openid);//用户信息		
		}	
		switch($type) {
			//消息回复
			case TPWechat::MSGTYPE_TEXT:
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
			case TPWechat::MSGTYPE_EVENT:
					$this->_event($content);
					break;
			case TPWechat::MSGTYPE_IMAGE:
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
	    //$result = $weObj->addconditionalMenu($newmenu);
	   	$result = $weObj->createMenu($newmenu);
	   	M('sysConfig')->where(array('name'=>'menu'))->setField(array('val'=>0));
	}
	//事件处理
	protected function _event($data){
		//事件监听
		$event = $this->weObj->getRevEvent();
		$userinfo = $this->userinfo;
		$this->_log('监听事件: 事件名称 ['.$event['event'].']');
		//获取发送信息	
		switch ($event['event']) {
			case TPWechat::EVENT_LOCATION:
				$place = $this->weObj->getRevEventGeo();//获取事件上报地址
				$this->_log('上报地址 纬度:'.$place['x'].' 经度:'.$place['y'].' 精度:'.$place['precision']);
				break;
			case TPWechat::EVENT_MENU_CLICK:
				switch ($event['key']) {
					case 'quickAdd':
						$r = $this->_regUser($userinfo);
						if($r['code'] == 2){
								$text = '您已经是我们的会员：'.$userinfo['nickname'];
								$this->weObj->text($text)->reply();
							}elseif($r['code'] == 1){
								$text = '欢迎加入我们：'.$userinfo['nickname']."\n";
								$text .= '您的账号为：'.$r['username']."\n";
								$text .= '登陆密码和支付密码为：'.$r['pwd']."\n";
								$this->weObj->text($text)->reply();
							}	
						break;		
					default:
						# code...
						$this->weObj->text('您触发了点击事件')->reply();
						break;
				}
				break;
			case TPWechat::EVENT_SUBSCRIBE://订阅
				$this->_log($userinfo['nickname'].'关注了账号');

				$r = $this->_regUser($userinfo);
				if($r['code'] == 2){
					$text = '欢迎回来：'.$userinfo['nickname'];
					$this->weObj->text($text)->reply();
				}elseif($r['code'] == 1){
					$text = '欢迎加入我们：'.$userinfo['nickname']."\n";
					$text .= '您的账号为：'.$r['username']."\n";
					$text .= '登陆密码和支付密码为：'.$r['pwd']."\n";
					$this->weObj->text($text)->reply();
				}		
				break;
			case TPWechat::EVENT_UNSUBSCRIBE://取消订阅
				$this->_log($userinfo['nickname'].'取消订阅');
				$this->_unsubscribe();
				break;	
			default:
				//$this->weObj->text('更多事件')->reply();
				break;
		}		
	}
	protected function _unsubscribe(){
		$userinfo = $this->userinfo;//收取的用户信息
		$openid = $userinfo['openid'];
		M('users')->where(array('openid'=>$openid,'subscribe'=>1))->setField(array('subscribe'=>0));

	}
	protected function _subscribe(){
		$userinfo = $this->userinfo;//收取的用户信息
		$openid = $userinfo['openid'];
		M('users')->where(array('openid'=>$openid,'subscribe'=>0))->setField(array('subscribe'=>1));
	}
	//注册用户
	protected function _regUser($userinfo){
		$openid = $userinfo['openid'];
		$user = M('users')->where(array('openid'=>$openid))->find();
		if($user){		
			if($user['subscribe']==0){
				$this->_subscribe();
			}
			$return['code'] = 2;//已拥有账号
			return $return;
		}
		//判断用户是否登陆
		$pwd = '000000';
		$return['pwd'] = $pwd;
		$data = array();
		$data['username'] = 'wx_'.time();
		$data['nickname'] = $userinfo['nickname'];
		$data['regtime'] = time();
		$data['openid'] = $openid;
		$data['regip'] = get_client_ip();
		$data['password'] = md5($pwd);
		$data['pay_password'] = md5($pwd);
		$data['headImgUrl'] = $userinfo['headimgurl'];
		M('users')->add($data);

		$return['code'] = 1;//注册
		$return['username'] = $data['username'];
		$this->_log('新用户注册-用户名:'.$data['username']);
		return $return;
	}
	//记录日志
	protected function _log($content,$tableName = 'actionlog'){
		$userinfo = $this->userinfo;
		M($tableName)->add(array('content'=>$content,'user'=>$userinfo['nickname']));
	}
}