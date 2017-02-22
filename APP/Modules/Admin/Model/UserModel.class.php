<?php
Class UserModel extends RelationModel{
	//定义关联关系
	Protected $_link=array(
		'role'=>array(
				'mapping_type'=>MANY_TO_MANY,		//多对多
				'foreign_key'=>'user_id',			//主表在中间表的名称
				'relation_key'=>'role_id',			//副表在中间表的名称
				'relation_table'=>'tpl_role_user',	//中间表名称
				'mapping_fields'=>'id, name, remark',		//自定义副表读取的字段
		),
	);

	protected $_auto = array (
    	array('password','md5',1,'function') , 	 //新增    对password字段在新增的时候使md5函数处理
    	array('repassword','md5',1,'function') , //新增    对repassword在新增和修改的时候使md5函数处理 用于验证两次密码是否输入一致
    	array('regtime','time',1,'function'),    // 新增 	   	 对regtime字段在新增的时候写入当前时间戳
    	array('logintime','time',1,'function'),  // 新增 	   	 对logtime字段在新增的时候写入当前时间戳
    	array('optime','time',3,'function'),     // 新增和修改 	 对optime字段 写入当前时间戳
	);
	protected  $_validate =array(
		array('username','require','用户名必须填写',0,'',1),				//新增用户  		用户名必须填写
		array('username','checkName','用户名已经存在',0,'callback',1),		//新增用户 			用户名是否存在
		array('nickname','require','昵称必须填写',0,'',3),					//新增和修改用户	昵称必须存在
	   
	    array('password','require','密码必须填写',0,'',3),					//新增和修改用户                    密码必须填写
		array('password','/^.{6,}$/','密码必须5位数以上',0,'regex',1),  	//新增和修改用户	密码必须5位数以上
		array('repassword','require','确认密码必须填写',0,'',3),			//新增和修改用户	确认密码必须填写
		array('repassword','password','两次密码不一致',0,'confirm',3),		//新增和修改用户	确认密码不正确
	);
	protected function checkName($username)
	{
		$Admin=M('User');
		$where['username']=$username;
		$count=$Admin->where($where)->count();
		if($count>0)
			return false;
		else
			return ture;
	}
	
	
}
?>