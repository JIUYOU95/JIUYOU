<?php
$db=array(

	'RBAC_SUPERADMIN'=>'JIUYOU',		//超级管理员名称
	'ADMIN_AUTH_KEY'=>'superadmin',		//超级管理员识别
	'USER_AUTH_ON'=>true,				//是否开启验证
	'USER_AUTH_TYPE'=>1,				//验证类型（1：登录验证 2：时时验证）
	'USER_AUTH_KEY'=>'uid',				//用户认证识别号
	'NOT_AUTH_MODULE'=>'Index',			//无需认证的控制器
	'NOT_AUTH_ACTION'=>'ReWriteConfig,addlog,delllog,alldel',				//无需认证的方法
	'RBAC_ROLE_TABLE'=>'tpl_role',		//角色表名称
	'RBAC_USER_TABLE'=>'tpl_role_user',	//角色与用户的中间表名称
	'RBAC_ACCESS_TABLE'=>'tpl_access',	//权限表名称
	'RBAC_NODE_TABLE'=>'tpl_node',		//节点表名称
	
	
// USER_AUTH_ON 是否需要认证
// USER_AUTH_TYPE 认证类型
// USER_AUTH_KEY 认证识别号
// REQUIRE_AUTH_MODULE  需要认证模块
// NOT_AUTH_MODULE 无需认证模块
// USER_AUTH_GATEWAY 认证网关
// RBAC_DB_DSN  数据库连接DSN
// RBAC_ROLE_TABLE 角色表名称
// RBAC_USER_TABLE 用户表名称
// RBAC_ACCESS_TABLE 权限表名称
// RBAC_NODE_TABLE 节点表名称
	
	'TMPL_PARSE_STRING'=>array(
		'__PUBLIC__'=>__ROOT__. '/' . APP_NAME .'/Modules/'. GROUP_NAME . '/Tpl/Public',
	),
	'URL_HTML_SUFFIX'=>'',		//URL伪静态后缀设置 
	//'SHOW_PAGE_TRACE'=>true,		//显示页面Trace信息 
	
	//URL访问模式支持 0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE 模式);3 (兼容模式)
	'URL_MODEL'=>1,

	'CFG_CONf'=>'./'. APP_NAME .'/Conf/',//网站基本配置文件路径
	'LOAD_EXT_CONFIG' => 'webconfig,blogconfig,imgconfig', // 加载扩展配置文件
	
	
	//'TMPL_EXCEPTION_FILE'=>'./Public/Tpl/error.html',	//异常页面的模板文件 
	//默认过滤函数
	//'DEFAULT_FILTER'=>'htmlspeciachars',
	//自定义SESSION数据库存储
	'SESSION_TYPE'=>'Db',
	
	//'SESSION_TYPE'=>'Redis',
	//'REDIS_HOST'=>'127.0.0.1',	//Redis服务器地址
	//'REDIS_PORT'=>6379,			//Redis端口号
	
);

$webconfig=include './'.APP_NAME.'/Conf/webconfig.php';
$blogconfig=include './'.APP_NAME.'/Conf/blogconfig.php';
$imgconfig=include './'.APP_NAME.'/Conf/imgconfig.php';
$dbarr=array_merge($webconfig,$blogconfig,$imgconfig);
return array_merge($db,$dbarr);
?>