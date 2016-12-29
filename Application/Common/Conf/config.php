<?php

return array(
	//'配置项'=>'配置值'
	//数据库配置信息
    'DB_TYPE' => 'mysql', // 数据库类型
    'DB_HOST' => '122.114.90.180', // 服务器地址
    'DB_NAME' => 'ht', // 数据库名
    'DB_USER' => 'user_1', // 用户名
    'DB_PWD' => 'user_1', // 密码
    // 'DB_HOST' => 'localhost', // 服务器地址
    // 'DB_NAME' => 'ht', // 数据库名
    // 'DB_USER' => 'root', // 用户名
    // 'DB_PWD' => 'root', // 密码
    'DB_PORT' => 3306, // 端口
    'DB_PREFIX' => 'ht_', // 数据库表前缀
    'DB_CHARSET'=> 'utf8', // 字符集
	'MODULE_ALLOW_LIST' => array('Common','Admin','Wx'),
	'DEFAULT_MODULE' => 'Admin', //默认模块
    'DEFAULT_CONTROLLER' => 'Index', // 默认控制器名称
    'DEFAULT_ACTION' => 'index', // 默认操作名称
    'DEFAULT_CHARSET' => 'utf-8', // 默认输出编码
    'DEFAULT_TIMEZONE' => 'PRC', // 默认时区
    'DEFAULT_FILTER' => 'htmlspecialchars', // 默认参数过滤方法 用于I函数...
    'URL_MODEL' => '2', //PATHINFO模式
    //多级域名
    'APP_SUB_DOMAIN_DEPLOY'   =>    1, // 开启子域名配置
    'APP_SUB_DOMAIN_RULES'    =>    array(   
        'ht'  => 'Admin',  // admin.domain1.com域名指向Admin模块
        'blogch.top'    =>  'Admin',
        'www.blogch.top' => 'Admin',   
        'wx.woxuewangs.com'	=>	'Wx',
        'woxuewangs.com'	=>	'Home',
        'www.woxuewangs.com'	=>	'Home',
        'test'   => 'Wx',  // test.domain2.com域名指向Test模块
    ),
	'AUTH_KEY' => '&*!^#&!@*#(@!SYNCL.A76T^&!*~@*_(!)J@KL!:JH!)SA*(HDSAHD&*(*(E^!EKJH',
	'TOKEN_ON' => true,
	'TOKEN_NAME' => '__hash__',
	'TOKEN_TYPE' => 'md5',
    'TOKEN_RESET'   =>    true,

    //加载自定义标签
    'TAGLIB_BUILD_IN'=>'Cx,Common\Tag\Mytag',

	/*默认错误跳转对应的模板文件*/
	'TMPL_ACTION_ERROR' => '../../Common/View/error',
	/*默认成功跳转对应的模板文件*/
	'TMPL_ACTION_SUCCESS' => '../../Common/View/success',
    'THEME_PATH'        =>  '/Application/Member/View/',
	'URL_HTML_SUFFIX' => '',
	'SHOW_PAGE_TRACE' =>true,
	/*权限验证设置*/
	'AUTH_CONFIG' => array(
		'AUTH_ON' => true, //认证开关
		'AUTH_TYPE' => 1, // 认证方式，1为时时认证；2为登录认证。
		'AUTH_GROUP' => 'ht_auth_group', //用户组数据表名
		'AUTH_GROUP_ACCESS' => 'ht_auth_group_access', //用户组明细表
		'AUTH_RULE' => 'ht_auth_rule', //权限规则表
		'AUTH_USER' => 'ht_admin'//用户信息表
	),
	/*超级管理员id,拥有全部权限*/
	'ADMINISTRATOR' => array('1'),
	/* Cookie设置 */
    'COOKIE_EXPIRE' => 86400*365*2, // Cookie有效期
    'COOKIE_DOMAIN' => '', // Cookie有效域名
    'COOKIE_PATH' => '/', // Cookie路径
    'COOKIE_PREFIX' => '', // Cookie前缀 避免冲突
    'COOKIE_HTTPONLY' => 1, // Cookie httponly设置

    'SESSION_OPTIONS'       =>  array('expire'=>86400),

    'LOG_RECORD'            =>  true,  // 进行日志记录
    'LOG_EXCEPTION_RECORD'  =>  true,    // 是否记录异常信息日志
    'LOG_LEVEL'             =>  'EMERG,ALERT,DEBUG',  // 允许记录的日志级别

    'DB_FIELDS_CACHE'       =>  false, // 字段缓存信息
    'DB_SQL_LOG'            =>  true, // 记录SQL信息
    'TMPL_CACHE_ON'         =>  false,        // 是否开启模板编译缓存,设为false则每次都会重新编译
    'TMPL_STRIP_SPACE'      =>  false,       // 是expire否去除模板文件里面的html空格与换行
    'SHOW_ERROR_MSG'        =>  true,    // 显示错误信息
    'URL_CASE_INSENSITIVE'  =>  false,  // URL区分大小写
    /*语言包设置*/
    'LANG_SWITCH_ON'   => true,      // 开启语言包功能
    'LANG_AUTO_DETECT' => true,    // 自动侦测语言 开启多语言功能后有效
    'LANG_LIST'        => 'zh-cn,en-us',         // 允许切换的语言列表 用逗号分隔
    'VAR_LANGUAGE'     => 'l',          // 默认语言切换变量，注意到上面发的链接了么，l=zh-cn，就是在这里定义l这个变量
    #y邮箱
    'MAIL_FROM_EMAIL' => 'adoceans1688@163.com',
    'MAIL_FROM_NAME' => '管理员',
    'MAIL_HOST_ADDRESS' => 'smtp.163.com',
    'MAIL_PASSWORD' => 'adoceans16',
    'MAIL_USERNAME' => 'adoceans1688@163.com',

);
