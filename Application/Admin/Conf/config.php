<?php
return array(
	//菜单
	'WX_MENU'	=>	array(
    		"button"=>
    			array(
    				array('type'=>'click','name'=>'点击测试1','key'=>'MENU_KEY_NEWS'),
    				array('type'=>'view','name'=>'微信网页测试','url'=>'http://woxuewangs.com'),
    				array('type'=>'scancode_push','name'=>'扫码测试','key'=>'sss')
    				)	
		),
	//基本配置
	'WX_OPTIONS'	=>	array(
		'token'=>'weixin', //填写你设定的key
        'encodingaeskey'=>'dCAZhdWvBHrz2vJjBzTOgMzqeQfUdIu5exSAQdgojQa', //填写加密用的EncodingAESKey，如接口为明文模式可忽略
        'appid'	=>	'wx0aac34300c5a1982',
        'appsecret'	=>	'132d1797e84428db705eabc15b078765'
		)

);