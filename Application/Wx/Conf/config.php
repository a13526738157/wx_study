<?php
return array(
	//菜单
	'WX_MENU'	=>	array(
    		"button"=>
    			array(
    				array('type'=>'click','name'=>'加入会员','key'=>'quickAdd'),
    				array('type'=>'view','name'=>'微官网1','url'=>'http://woxuewangs.com'),
    				array(
                        'name'=>'更多功能',
                        'sub_button'    =>  array(
                            array('type' => 'pic_sysphoto','name' => '系统拍照','key' => 'selectImg'),
                            array('type' => 'pic_photo_or_album','name'  =>  '选取相册','key'=>'photos')
                            )
                        )
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