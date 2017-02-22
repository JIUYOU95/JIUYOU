<?php
import('ORG.Util.Page');
import('Common.Category',APP_PATH);
class ImgAction extends CommonAction {
	public function index(){
		$this->head();
		$where['id']=I('id');
		$img=M('ImgImg')->where($where)->select();

		foreach ($img as $url) {
			$pic=explode("|",$url['pic']); 
			$tmg=explode("|",$url['img']);
			$p=$url['typeid'];
			$this->title=$url['title'];
			
		}
		$cate=M('ImgCategory')->order('sort')->select();
		$this->parent=Category::getParents($cate,$p);

		$this->p=array_combine($tmg,$pic);
		$this->assign('img',$img);
		$this->display();
	}
}
?>