<?php
$db=array(
	'TMPL_PARSE_STRING'=>array(
		'__PUBLIC__'=>__ROOT__. '/' . APP_NAME .'/Modules/'. GROUP_NAME . '/Tpl/Public',
	),
	//开启静态缓存
	'HTML_CACHE_ON'=>true,
	'HTML_CACHE_RULES'=>array(
		'Img:index'=>array('{:module}_{:action}_{id}',2),
		),
	//动态缓存方式
	//'DATA_CACHE_TYPE'=>'File',			//File,Memcache,Redis
	//'MEMCACHE_HOST'=>'127.0.0.1',			//Memcache服务器地址
	//'MEMCACHE_PORT'=>11211,					//Memcache端口号


	'URL_HTML_SUFFIX'=>'html',		//URL伪静态后缀设置 
	'TMPL_EXCEPTION_FILE'=>'./APP/Modules/Images/Tpl/Public/404/error.html', // 定义公共错误模板
	
	'LOAD_EXT_CONFIG' => 'imgconfig', // 加载扩展配置文件
);

$imgconfig=include './'.APP_NAME.'/Conf/imgconfig.php';//网站项目公用配置文件，包含seo 上传 日志记录 等配置信息
return array_merge($db,$imgconfig);
?>