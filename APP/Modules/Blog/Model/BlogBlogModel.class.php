<?php
	
Class BlogBlogModel extends RelationModel{
	Protected $_link=array(
	//多对多blog-blogattr 博客-博客属性
		'blogAttr'=>array(
			'mapping_type'=>MANY_TO_MANY,
			'mapping_name'=>'blogattr',
			'foreign_key'=>'bid',
			'relation_foreign_key'=>'aid',
			'relation_table'=>'tpl_blog_attr_menu',
		),
	//一对多blog-blogcagegory 博客-博客分类
		'blogCategory'=>array(
			'mapping_type'=>BELONGS_TO,
			'foreign_key'=>'typeid',
			'foreign_fields'=>'typename',
			'as_fields'=>'typename',
		),
	//一对多blog-blogcagegory 博客-用户
		'user'=>array(
			'mapping_type'=>BELONGS_TO,
			'foreign_key'=>'uid',
			'foreign_fields'=>'username',
			'as_fields'=>'username',
		)
	);
	
	public function getBlogs($type=0){
		$field=array('del');
		$where=array('del'=>$type);
		return $this->field($field,true)->where($where)->relation(true)->select();
	}
}
