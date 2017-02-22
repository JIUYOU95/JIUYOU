<?php
Class RoleModel extends Model{
	protected  $_validate =array(
		array('name','require','角色名必须填写',0,'',3),			
		array('remark','require','角色描述必须填写',0,'',3),				
		array('remark','checkName','角色描述不能重复',0,'callback',1),
	);
	protected function checkName($remark){
		$Attr=M('Role');
		$where['remark']=$remark;
		$count=$Attr->where($where)->count();
		if($count>0)
			return false;
		else
			return ture;
	}
	
}
?>