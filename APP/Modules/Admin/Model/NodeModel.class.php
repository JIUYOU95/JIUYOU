<?php
Class NodeModel extends Model{
	protected  $_validate =array(
		array('name','require','名称必须填写',0,'',3),			
		array('title','require','描述必须填写',0,'',3),				
	);
	
}
?>