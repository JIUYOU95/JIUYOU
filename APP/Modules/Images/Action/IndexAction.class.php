<?php
import('ORG.Util.Page');
import('Common.Category',APP_PATH);
class IndexAction extends CommonAction {
	public function index(){
		$this->head();
		$img=M('ImgImg');
		$id=(int) $_GET['id'];
		$where['status']=1;
		$where['del']=0;
		$cate=M('ImgCategory')->order('sort')->select();
		$cids=Category::getChildsId($cate,$id);
    	$cids[]=$id;
    	$where=array('typeid'=>array('IN',$cids));
		$this->img=$img->where($where)->order('id desc')->limit(40)->select();

    	//$this->img=$img->where($where)->order('id')->select();
		/* $uid=I('input');
		if(!empty($uid)){
			$mg=$img->where($where)->order('id')->limit($uid,4)->select();
			$this->ajaxReturn($mg);
		} */
		$this->display();
	}
	//获取下一栏数据
	public function getDbMore(){
		$last_id = $this->_get('last_id');
        $map['id'] = array('lt', $last_id);
        $list = M('ImgImg')->where($map)->order('id DESC')->limit(3)->select();
        $this->ajaxReturn($list);
	}


}
?> 