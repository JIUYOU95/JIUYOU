<?php
Class BlogBlogViewModel extends ViewModel{
	Protected $viewFields=array(
		'BlogBlog'=>array(
			'id','typeid','title','time','optime','hits','content',
			'_type'=>'LEFT'
			),
		'BlogCategory'=>array(
			'typename','_on'=>'BlogBlog.typeid=BlogCategory.id'
			)
		);

	Public function getAll($where,$limit){
		return $this->where($where)->order('optime desc')->select();
	}
}
?>