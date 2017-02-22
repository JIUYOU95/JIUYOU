<?php
Class BlogAttrModel extends Model{
	protected  $_validate =array(
		array('name','require','属性名必须填写',0,'',3),			
		array('color','require','属性颜色必须填写',0,'',3),				
		array('name','checkName','属性名已经存在',0,'callback',1),
	);
	protected function checkName($name){
		$Attr=M('BlogAttr');
		$where['name']=$name;
		$count=$Attr->where($where)->count();
		if($count>0)
			return false;
		else
			return ture;
	}
	
}
?>