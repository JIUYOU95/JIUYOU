<?php
Class BlogCategoryModel extends Model{
	protected $_auto = array (
    	array('optime','time',3,'function'),     // 新增和修改 	 对optime字段 写入当前时间戳
	);
	protected  $_validate =array(
			array('typename','require','栏目名称必须填写',0,'',3),				//新增和修改栏目  	栏目名称必须填写
			array('cmark','require','栏目标识必须填写',0,'',3),				    //新增和修改栏目  	栏目标识必须填写
			array('typename','checkName','栏目名称已经存在',0,'callback',1),	//新增栏目 			栏目名称是否存在
			
	);
	protected function checkName($typename){
		$Category=M('Blog_Category');
		$where['typename']=$typename;
		$count=$Category->where($where)->count();
		if($count>0)
			return false;
		else
			return ture;
	}
	
}
?>