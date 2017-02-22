<?php
Class UserModel extends Model{
	protected $_auto = array (
		array('regtime','time',1,'function'),    // 新增 	   	 
		array('password','md5',1,'function'), // 对password字段在新增的时候使md5函数处理
		array('lock','1'),  // 新增的时候把lock字段设置为1
		array('purviews','0'),  // 新增的时候把lock字段设置为1

   	 
	);
	
}
?>