<?php
import('ORG.Util.Page');
import('Common.Category',APP_PATH);
class ArticleAction extends CommonAction {
	public function index(){
		$this->head();
		$blog=D('BlogBlog');
		$id=I('id');
		$this->blog=$blog->find($id);

		$typeid=$this->blog['typeid'];
		$cate=M('BlogCategory')->order('sort')->select();
		$this->parent=Category::getParents($cate,$typeid);


$front=$blog->where("id<".$id)->order('id desc')->limit('1')->find();   
$this->assign('front',$front);  
 
$after=$blog->where("id>".$id)->order('id desc')->limit('1')->find();
$this->assign('after',$after);

		$this->display();
	}
	public function ClinkNum(){
		$where['id']=I('id');
		$hits=M('BlogBlog')->where($where)->getField('hits');
		M('BlogBlog')->where($where)->setInc('hits');
		
		echo 'document.write('.$hits.')';
	}
	public function archive(){
		$this->head();
		$blog=D('BlogBlog');
		$where['del']=0;
		$where['status']=1;
		
		$years=I('year');
		$months=I('month');
		$year=$years.$months;

		$stat=strtotime("$years-$months-01");
		$end=strtotime("$years-$months-31");
		$where['time']=array('EGT',$stat);
		$where['time']=array('ELT',$end);
		
		
		$count=$blog->where($where)->count();
		$Page=new Page($count,10);
		$nowPage=isset($_GET['p'])?$_GET['p']:1;
		$this->list=$blog->where($where)->page($nowPage.','.$Page->listRows)->relation(true)->select();
		$show=$Page->show();// 分页显示输出	
		$this->assign('page',$show);// 赋值分页输出
		
		$this->assign('year',$year);
		$this->year=I('year').I('month');
		$this->display();
	}
}
?>