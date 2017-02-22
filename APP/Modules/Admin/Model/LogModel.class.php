<?php
Class LogModel extends RelationModel{
	//定义主表名称
	Protected $tableName='log';

	protected $_link=array(
		'User'=> array(  
	    	  'mapping_type'=>BELONGS_TO,
	          'class_name'=>'User',
	          'foreign_key'=>'uid',
	          'mapping_name'=>'user',
	          'mapping_fields'=>'username',
	          'as_fields'=>'username'
	    ),
	);
}
?>