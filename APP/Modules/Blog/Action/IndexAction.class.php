<?php

import('ORG.Util.Page');
import('Common.Category',APP_PATH);
class IndexAction extends CommonAction {
    public function index(){
    	$this->head();
    	$Blog=D('BlogBlog');
		$where['del']=0;
		$where['status']=1;
		$count=$Blog->where($where)->count();

	  	$Page=new Page($count,10);
	  	$Page->setConfig('prev','<');
	  	$Page->setConfig('next','>');
	  	$Page->setConfig('theme',"%nowPage%/%totalPage% %first% %upPage% %prePage% %linkPage% %nextPage% %downPage% %end%");


		$nowPage=isset($_GET['p'])?$_GET['p']:1;
		$lists=$Blog->order('optime desc')->where($where)->page($nowPage.','.$Page->listRows)->relation(true)->select();
		$show=$Page->show();// 分页显示输出	
		$this->assign('page',$show);// 赋值分页输出
		$this->assign('blog',$lists);// 赋值数据集
		$this->display();
    }
    public function blog(){
    	$this->head();
    	$id=(int) $_GET['id'];
    	$cate=M('BlogCategory')->order('sort')->select();
    	$this->parent=Category::getParents($cate,$id);

    	$cids=Category::getChildsId($cate,$id);
    	$cids[]=$id;

    	$where=array('typeid'=>array('IN',$cids),'del'=>'0','status'=>'1');
    	$count=M('BlogBlog')->where($where)->count();
    	$page=new Page($count,15);
    	$limit=$page->firstRow.','.$page->listRows;
    	$this->page=$page->show();

    	$this->blog=D('BlogBlogView')->getAll($where,$limit);
		$this->display('index');
    }
}