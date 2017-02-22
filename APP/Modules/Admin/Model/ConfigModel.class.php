<?php
Class ConfigModel extends Model{
	protected $_auto = array (
    	array('optime','time',1,'function'),    // 新增 	   	 对regtime字段在新增的时候写入当前时间戳
	);
	protected  $_validate =array(
		array('configname','require','变量名必须填写',0,'',1),			//新增  	变量名必须填写
		array('info','require','参数说明必须填写',0,'',1),				//新增  	参数说明必须填写
		array('configname','checkName','变量名已经存在',0,'callback',1),//新增 		变量名是否存在
	);
	protected function checkName($configname){
		$Config=M('Config');
		$where['configname']=$configname;
		$count=$Config->where($where)->count();
		if($count>0)
			return false;
		else
			return ture;
	}
	
}
?>