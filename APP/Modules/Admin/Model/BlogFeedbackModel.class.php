<?php
Class BlogFeedbackModel extends RelationModel{
	protected $_auto = array (
    	array('retime','strtotime',2,'function'),    // 新增 	   	 对regtime字段在新增的时候写入当前时间戳
    	array('optime','time',2,'function'),    // 新增 	   	 对regtime字段在新增的时候写入当前时间戳
    	array('reuid','getUid',2,'callback'),      // 新增 	   	 对uid字段在新增的时候写入当前用户id
	);
	protected function getUid(){
		return $_SESSION[C('USER_AUTH_KEY')];
	}
	protected $_link=array(
		'User'=> array(  
	    	  'mapping_type'=>BELONGS_TO,
	          'class_name'=>'User',
	          'foreign_key'=>'reuid',
	          'mapping_name'=>'user',
	          'mapping_fields'=>'username',
	          'as_fields'=>'username:uname'
	    ),
	);
	
}
?>