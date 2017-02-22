<?php
//定义回调URL通用的URL
define('URL_CALLBACK', 'http://www.itnetve.top?m=Login&a=callback&type=');
$db=array(
	'TMPL_PARSE_STRING'=>array(
		'__PUBLIC__'=>__ROOT__. '/' . APP_NAME .'/Modules/'. GROUP_NAME . '/Tpl/Public',
	),
	//开启静态缓存
	'HTML_CACHE_ON'=>true,
	'HTML_CACHE_RULES'=>array(
		'Article:index'=>array('{:module}_{:action}_{id}',2),
		),
	//动态缓存方式
	//'DATA_CACHE_TYPE'=>'File',			//File,Memcache,Redis
	//'MEMCACHE_HOST'=>'127.0.0.1',			//Memcache服务器地址
	//'MEMCACHE_PORT'=>11211,					//Memcache端口号

	'URL_HTML_SUFFIX'=>'html',		//URL伪静态后缀设置 
	'TMPL_EXCEPTION_FILE'=>'./APP/Modules/Blog/Tpl/Public/404/error.html', // 定义公共错误模板
	
	//自定义标签库
	'APP_AUTOLOAD_PATH'=>'@.TagLib',
	'TAGLIB_BUILD_IN'=>'Cx,Hd',

	'LOAD_EXT_CONFIG' => 'blogconfig', // 加载扩展配置文件

	//腾讯QQ登录配置
	'THINK_SDK_QQ' => array(
		'APP_KEY'    => '101382944', //应用注册成功后分配的 APP ID
		'APP_SECRET' => '15952c330b3ca585d06b576f97a72538', //应用注册成功后分配的KEY
		'CALLBACK'   => URL_CALLBACK,
	),
	//新浪微博配置
	'THINK_SDK_SINA' => array(
		'APP_KEY'    => '2434751402', //应用注册成功后分配的 APP ID
		'APP_SECRET' => 'ba34635b8520c0fc813b912d15be4575', //应用注册成功后分配的KEY
		'CALLBACK'   => URL_CALLBACK . 'sina',
	),
);

$blogconfig=include './'.APP_NAME.'/Conf/blogconfig.php';//网站项目公用配置文件，包含seo 上传 日志记录 等配置信息
return array_merge($db,$blogconfig);
?>