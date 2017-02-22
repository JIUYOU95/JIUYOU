<?php
Class BlogLinkModel extends Model{	
	protected $_auto = array (
    	array('time','time',3,'function'),// 新增 	   	     对time字段在新增的时候写入当前时间戳
	);
}
?>