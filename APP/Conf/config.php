<?php
return array(
	//'配置项'=>'配置值'
	//开启应用分组
	'APP_GROUP_LIST'=>'Images,Admin,Blog',	//项目分组
	'DEFAULT_GROUP'	=>'Blog',		//默认分组
	//开启独立分组
	'APP_GROUP_MODE'=>1,			//独立分组
	//独立分组名称
	'APP_GROUP_PATH'=>'Modules',	//分组名称
	//加载配置文件
	//'LOAD_EXT_CONFIG'=>'verify',
	
	'TMPL_L_DELIM'=>'<{', //修改左定界符
	'TMPL_R_DELIM'=>'}>', //修改右定界符
	//点语法默认解析
	//'TMPL_VAR_IDENTIFY'=>'array',
	//数据库配置
	'DB_TYPE'=>'mysql',		//数据库类型
	'DB_HOST'=>'bdm238721156.my3w.com',	//主机
	'DB_NAME'=>'bdm238721156_db',		//数据库名字
	'DB_USER'=>'bdm238721156',		//用户名
	'DB_PWD' =>'Mysql520',		//密码
	//'DB_PORT'=>3306,		//端口
	'DB_PREFIX'=>'tpl_',		//表前缀
	
	//'SHOW_PAGE_TRACE'=>true,		//显示页面Trace信息 
	//URL访问模式支持 0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE 模式);3 (兼容模式)
	'URL_MODEL'=>2,
	'URL_ROUTER_ON'=>true,
	'URL_ROUTE_RULES'=>array(
		'/^nav(\d+)$/'=>'Blog/Index/blog?id=:1',	//博客栏目
		'Article/:id\d'=>'Blog/Article/index',		//文章
		'Data/:year/:month'=>'Blog/Article/archive',//日期
		'tags/:tag'=>'Blog/Search/index',			//关键字查询
		'keywords'=>'Blog/Search/search',			
		'Message'=>'Blog/Message/index',			//留言
		
		'login'=>'Blog/Login/index',				//登录注册
		'sign'=>'Blog/Login/login',					//登录跳转

		'sdk/:code'=>'http://www.itnetve.top?m=Login&a=callback&type=qq',
		
		'cate/:id\d'=>'Images/Index/index',
		'Img/:id\d'=>'Images/Img/index',
		)
	
);
?>