<?php
import("ORG.Util.Page");
class SearchAction extends CommonAction {

	Public function index(){
		$this->head();
		$tags = D("BlogBlog");
		if(I('tag')==''){
			$this->tags=I('tag');
			
		}else{
			$where['status'] = 1;
		    $where['del'] = 0;
		    $where['tag'] = array('like',"%".$_GET['tag']."%");
		    $count = $tags->where($where)->count();
		    $p = new Page($count, 10);
		    $map['status'] = 1;
		    $map['del'] = 0;
		    $map['tag'] = $_GET['tag'];
		    foreach($map as $key=>$val) {   
		        $p->parameter .= "$key=".urlencode($val)."&";   
		    }
		    $page = $p->show();
		    $list = $tags->where($where)->order('time ASC')->limit($p->firstRow.','.$p->listRows)->relation(true)->select();
		    $this->assign('page', $page);
		    $this->assign('blog', $list);
		    $this->assign('tags',$_GET['tag']);
		}
	    
	    $this->display();
		}
	public function search(){
		$this->head();
		$tags = D("BlogBlog");
		if(I('keywords')==''){
			$this->tags=I('keywords');
			
		}else{
			$where['status'] = 1;
		    $where['del'] = 0;
		    $where['keywords'] = array('like',"%".$_GET['keywords']."%");
		    $count = $tags->where($where)->count();
		    $p = new Page($count, 10);
		    $map['status'] = 1;
		    $map['del'] = 0;
		    $map['keywords'] = $_GET['keywords'];
		    foreach($map as $key=>$val) {   
		        $p->parameter .= "$key=".urlencode($val)."&";   
		    }
		    $page = $p->show();
		    $list = $tags->where($where)->order('time ASC')->limit($p->firstRow.','.$p->listRows)->relation(true)->select();
		    $this->assign('page', $page);
		    $this->assign('blog', $list);
		    $this->assign('tags',$_GET['keywords']);
		}
	    
	    $this->display('index');
	}
}
?>