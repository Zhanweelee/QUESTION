<?php
return array(
	//'配置项'=>'配置值'
	'MODULE_ALLOW_LIST'    =>    array('Home'),
	'DEFAULT_MODULE'       =>    'Home',

	'URL_MODEL'             => 2,  // URL访问模式,可选参数0、1、2、3
	'SESSION_AUTO_START' => true, //是否开启session
	'URL_CASE_INSENSITIVE'  => false,   // 默认false 表示URL区分大小写 true则表示不区分大小写
	/*
	 *  数据库设置
	 *  
	 */
    'DB_TYPE'               => 'mysql',     // 数据库类型
	'DB_HOST'               => '127.0.0.1', // 服务器地址
	'DB_NAME'               => 'JNSurvey',          // 数据库名
	'DB_USER'               => 'root',
	'DB_PWD'                => '',
    'DB_PORT'               => '3306',        // 端口
    'DB_PREFIX'             => 'wj_',    // 数据库表前缀
);