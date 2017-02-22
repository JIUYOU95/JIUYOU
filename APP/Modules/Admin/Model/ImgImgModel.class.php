<?php
Class ImgImgModel extends RelationModel{		
	Protected $_link=array(
	//一对多blog-cagegory 博客-博客分类
		'ImgCategory'=>array(
			'mapping_type'=>BELONGS_TO,
			'foreign_key'=>'typeid',
			'foreign_fields'=>'typename',
			'as_fields'=>'typename:cate',
		),
	//一对多blog-cagegory 博客-用户
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
	//自动完成
	protected $_auto = array (
		array('time','strtotime',3,'function'),// 新增 	   	     对time字段在新增的时候写入当前时间戳
		array('optime','time',3,'function'),// 新增 	
    	array('uid','getUid',3,'callback'),   // 新增 	   	     对uid字段在新增的时候写入当前用户id
    	
	);
	protected function getUid(){
			$userid=$_SESSION[C('USER_AUTH_KEY')];
		return $userid;
	}
	//自动验证
	protected  $_validate =array(
		array('typeid','require','请选择栏目',0,'',3),				//新增和修改栏目    栏目必须选择
		array('title','require','标题必须填写',0,'',3),				//新增和修改标题    标题必须填写、
		array('pic','require','文件不存在',0,'',1),				//新增和修改标题    标题必须填写
		array('title','checkTitle','标题已经存在',0,'callback',1),	//新增标题 			标题是否存在
	);
    protected function checkTitle($title){
    	$Article=D('ImgImg');
    	$where['title']=$title;
    	$count=$Article->where($where)->count();
    	if($count>0)
    		return false;
    	else
    	 	return true;
    }
}
