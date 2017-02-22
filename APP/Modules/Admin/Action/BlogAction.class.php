<?php
import('Common.Category',APP_PATH);
/*
 *后台博客页面控制器 
 */
class BlogAction extends CommonAction{
	public function index(){
		$this->display();
	}
	//博客栏目
	public function menu(){
		$cate=D('BlogCategory')->order('sort asc')->select();
		$this->cate=Category::unlimitedForLevel($cate,'&nbsp;&nbsp;├&nbsp');
		$this->display();
	}
	//博客栏目排序表单处理
	public function sortHandle(){
		$db=D('BlogCategory');
		foreach($_POST as $id => $sort){
			$count=$db->where(array('id'=>$id))->setField('sort',$sort);
		}
		
		$ConfigAction=new ConfigAction();
		$logid=$ConfigAction->addlog($_SESSION['username'].'在'.date("Y-m-d H:i:s").'博客栏目排序');
		if(!$logid)
			$this->error('日志记录失败');
		else
			$this->success('栏目排序成功',U(GROUP_NAME.'/Blog/menu'));
	}
	//添加博客栏目
	public function addmenu(){
		$Category=D('BlogCategory');
		if(IS_POST){
	 		if($Category->create()){
	 			$lastid=$Category->add();
	 			if($lastid){
	 				//记录日志
					$ConfigAction=new ConfigAction();
					$logid=$ConfigAction->addlog($_SESSION['username'].'在'.date("Y-m-d H:i:s").'添加博客栏目'.I('typename'));
					if(!$logid)
		 				$this->error('日志记录失败');
		 			else
		 				$this->success('栏目添加成功',U(GROUP_NAME.'/Blog/menu'));
	 			}else{
	 				$this->error('博客栏目添加失败');
	 			}
	 		}else{
				$this->error($Category->getError());
			}
		}else{
			$this->reid=I('reid',0,'intval');
			$this->typename=I('typename',顶级栏目);
			$this->display();
		}
	}
	//博客栏目修改
	public function editmenu(){
		$Category=D('BlogCategory');
		if(IS_POST){
	 		if(!$Category->create()){
	 			$this->error($Category->getError());
	 		}else{
	 			$count=$Category->save();
	 			if($count>0){
					$ConfigAction=new ConfigAction();
					$logid=$ConfigAction->addlog($_SESSION['username'].'在'.date("Y-m-d H:i:s").'修改博客栏目'.I('typename'));
					if(!$logid)
						$this->error('日志记录失败');
					else
						$this->success('修改栏目成功',U(GROUP_NAME.'/Blog/menu'));
	 			}else{
	 				$this->error('修改栏目失败');
	 			}
	 		}
 		}else{
		 	$where['id']=I('id');
			//博文所属分类
			$category=$Category->order('sort asc')->select();
			$this->category=Category::unlimitedForLevel($category,'&nbsp;&nbsp;├&nbsp');
			//显示要修改的博客栏目
			$this->cate=$Category->where($where)->order('sort asc')->select();
			$this->display();
 		}
	}
	//博客栏目删除
	public function delmenu(){
	 	$where['id']=I('id');
	 	$Category=D('BlogCategory');
		$data=$Category->where($where)->find();
	 	if($Category->where($where)->delete()){
			$ConfigAction=new ConfigAction();
			$logid=$ConfigAction->addlog($_SESSION['username'].'在'.date("Y-m-d H:i:s").'删除博客栏目'.$data['typename']);
			if(!$logid)
				$this->error('日志记录失败');
			else
				$this->success('栏目删除成功',U(GROUP_NAME.'/Blog/menu'));
	 	}else{
	 		$this->error('栏目删除失败');
	 	}
	}

	//博客列表显示
	public function blog(){
		$Blog=D('BlogBlog');
		$Category=D('BlogCategory');
		$cate=$Category->order('sort asc')->select();
		$this->cate=Category::unlimitedForLevel($cate,'&nbsp;&nbsp;├&nbsp');
		//$this->blog=$Blog->order('optime desc')->getBlogs();
  	  if($this->isGet()){
        if(I('uid')){
            $where['uid']=I('uid');
        }
	  	  //typeid 是否有分类id
	  	  if(I('typeid')){
	  	  	$id=(int) $_GET['typeid'];
	  	  	$cids=Category::getChildsId($cate,$id);
    		$cids[]=$id;
    		$where=array('typeid'=>array('IN',$cids));
	  	  	}
	 	  //title 是否有查询
	 	  if(I('keyword')){
	 	  		$where['title']=array('like','%'.I('keyword').'%');
	 	  		//$where['keywords']=array('like','%'.I('keyword').'%');
	 	  	}
	 	  //orderby 是否有排序
	 	  if(I('orderby'))
	 	  	$orderby[I('orderby')]='desc';
  	  }
  	  import('ORG.Util.Page');// 导入分页类
  	  $count      = $Blog->where($where)->count();// 查询满足要求的总记录数
  	  $Page       = new Page($count,20);// 实例化分页类 传入总记录数
	  // 进行分页数据查询 注意page方法的参数的前面部分是当前的页数使用 $_GET[p]获取
	  $nowPage  = isset($_GET['p'])?$_GET['p']:1;
	  $orderby['optime']='desc';
	  $lists 	  = $Blog->relation(true)->order($orderby)->where($where)->page($nowPage.','.$Page->listRows)->getBlogs();
	  $show       = $Page->show();// 分页显示输出
	  $this->assign('page',$show);// 赋值分页输出
	  $this->assign('lists',$lists);// 赋值数据集
      //$this->assign('userid',$Opadmin->getUserid());

		$this->display();
	}
	//添加博客
	public function addblog(){
		$Blog=D('BlogBlog');
		if(IS_POST){
	 		if($Blog->create()){
	 			$bid=$Blog->add();
		 		if(isset($_POST['aid'])){
					$sql='INSERT INTO '.C('DB_PREFIX').'blog_attr_menu (bid,aid) VALUES';
					foreach($_POST['aid'] as $v){
						$sql.='('.$bid.','.$v.'),';
					}
					$sql=rtrim($sql,',');
					M('blog_attr_menu')->query($sql);
				}
	 			if($bid){
	 				//记录日志
					$ConfigAction=new ConfigAction();
					$logid=$ConfigAction->addlog($_SESSION['username'].'在'.date("Y-m-d H:i:s").'添加博客'.I('title'));
					if(!$logid)
		 				$this->error('日志记录失败');
		 			else
		 				$this->success('博客添加成功',U(GROUP_NAME.'/Blog/blog'));
	 			}else{
	 				$this->error('博客添加失败');
	 			}
	 		}else{
				$this->error($Blog->getError());
			}
		}else{
			//博文所属分类
			$cate=M('BlogCategory')->order('sort asc')->select();
			$this->cate=Category::unlimitedForLevel($cate,'&nbsp;&nbsp;├&nbsp');
			//博文属性
			$this->attr=M('BlogAttr')->select();
			$this->display();
		}
	}
	//博客修改
	public function editblog(){
		$Blog=D('BlogBlog');
		if(IS_POST){
			if($Blog->create()){
				$where['id']=I('id');
				$bid=$Blog->where($where)->save();
				if($bid>0){
					if(isset($_POST['aid'])){
						$sq='delete from '.C('DB_PREFIX').'blog_attr_menu where bid='.I('id').'';
						M('attr_menu')->query($sq);
						$sql='INSERT INTO '.C('DB_PREFIX').'blog_attr_menu (bid,aid) VALUES';
						foreach($_POST['aid'] as $v){
							$sql.='('.I('id').','.$v.'),';
						}
						$sql=rtrim($sql,',');
						M('attr_menu')->query($sql);
					}
					//记录日志
					$LogAction=new ConfigAction();
					$logid=$LogAction->addlog($_SESSION['username'].'在'.date("Y-m-d H:i:s").'修改博客'.I('title'));
					if(!$logid)
			 			$this->error('日志记录失败');
			 		else
			 			$this->success('修改成功！',U(GROUP_NAME.'/Blog/blog'));
				}else{
					$this->error('修改失败！');
				}
			}else{
				$this->error($Blog->getError());
			}

			
		}else{
			//博文所属分类
			$cate=D('BlogCategory')->order('sort asc')->select();
			$this->cate=Category::unlimitedForLevel($cate,'&nbsp;&nbsp;├&nbsp');
			//博文属性
			$this->attr=D('BlogAttr')->select();
			$blog=D('BlogBlogRelation');
			
			$where['id']=I('id');
			$this->blog=$Blog->where($where)->getBlogs();
		
			$this->display();	
		}	
	}

	//删除、还原到回收站
	public function toTrach(){
		$ids=explode(',', I('id'));
	    foreach($ids as $key=>$vaule){
			$type=(int) $_GET['type'];
			$msg=$type ? '删除' : '还原';
			$update=array(
				'id'=>$vaule,
				'del'=>$type,
			);
			if(M('BlogBlog')->save($update)){
				//记录日志
				$LogAction=new ConfigAction();
				$logid=$LogAction->addlog($_SESSION['username'].'在'.date("Y-m-d H:i:s").$msg.'博客'.I('title'));
				$this->success($msg.'成功！');
			}else{
				$this->error($msg.'失败！');
			}
	    }



	}
	//回收站
	public function trach(){
		$this->lists=D('BlogBlog')->getBlogs(1);
		$this->display('Blog/blog');
	}
	//回收站删除
	public function delblog(){
		$ids=explode(',', I('id'));
	    foreach($ids as $key=>$vaule){    
			if(M('BlogBlog')->delete($vaule)){
				M('BlogAttr_menu')->where(array('bid'=>$id))->delete();
				//记录日志
				$LogAction=new ConfigAction();
				$logid=$LogAction->addlog($_SESSION['username'].'在'.date("Y-m-d H:i:s").'彻底删除博客'.I('title'));
				if(!$logid)
				 	$this->error('日志记录失败');
				 else
				 	$this->success('彻底删除成功！',U(GROUP_NAME.'/Blog/trach'));
			}else{
				$this->success('删除失败！');
			}
	    }

		//$id=(int) $_GET['id'];

	}
 public function delall(){
    $ids=explode(',', I('id'));
    foreach($ids as $key=>$vaule){    
      if(!$typeid=$this->delarc($vaule)){
            $this->error('部分文档删除失败');
       }
    }
    $this->success('文档删除成功',U(GROUP_NAME.'/Blog/trach',array('typeid'=>$typeid)));
 }

	//审核
	public function ischeck(){
	  $ids=explode(',', I('id'));
	    foreach($ids as $key=>$vaule){    
	      if(!$typeid=$this->upkarc('status',$vaule,1)){
	           $this->error('部分文档审核失败');
	       }
	    }
	    $this->success('文档审核成功',U(GROUP_NAME.'/Blog/blog',array('typeid'=>$typeid)));
	}
	public function ischeckd(){
	  $ids=explode(',', I('id'));
	    foreach($ids as $key=>$vaule){    
	      if(!$typeid=$this->upkarc('status',$vaule,0)){
	           $this->error('部分文档删除审核失败');
	       }
	    }
	    $this->success('文档删除审核成功',U(GROUP_NAME.'/Blog/blog',array('typeid'=>$typeid)));
	}
	//更改文档属性
	private function upkarc($field,$id,$status){
	  $where['id']=$id;
	  $Article=D('BlogBlog');
	  $data=$Article->where($where)->find();
	  $udata[$field]=$status;
	  $udata['optime']=time();
	  $count=$Article->where($where)->save($udata);
	  if($count){
	      //记录日志
	      $LogAction=new ConfigAction();
	      $logid=$LogAction->addlog($_SESSION['username'].'在'.date("Y-m-d H:i:s").'更改文档'.$data['title']."属性");
	      if(!$logid)
	        return false;
	      else
	        return $data['typeid'];
	    }else{
	       return false;
	    }
	}




	//属性列表
	public function attr(){
		$this->attr=M('BlogAttr')->select();
		$this->display();
	}
	//添加属性
	public function addattr(){
		$Attr=D('BlogAttr');
		if(IS_POST){
			if(!$Attr->create()){
	 			$this->error($Attr->getError());
	 		}else{
	 			$count=$Attr->add();
	 			if($count>0){
	 				$ConfigAction=new ConfigAction();
					$logid=$ConfigAction->addlog($_SESSION['username'].'在'.date("Y-m-d H:i:s").'添加博客属性'.I('name'));
					if(!$logid)
						$this->error('日志记录失败');
					else
						$this->success('博文属性添加成功！',U(GROUP_NAME.'/Blog/attr'));
	 			}else{
	 				$this->error('博文属性添加失败！');
	 			}
	 		}
		}else{
			$this->display();
		}
		
	}
	//修改属性
	public function editattr(){
		$Attr=D('BlogAttr');
		if(IS_POST){
			if(!$Attr->create()){
	 			$this->error($Attr->getError());
	 		}else{
	 			$count=$Attr->save();
	 			if($count>0){
	 				$LogAction=new ConfigAction();
					$logid=$LogAction->addlog($_SESSION['username'].'在'.date("Y-m-d H:i:s").'修改博客属性'.I('name'));
					if(!$logid)
						$this->error('日志记录失败');
					else
						$this->success('博文属性修改成功！',U(GROUP_NAME.'/Blog/attr'));
	 			}else{
	 				$this->error('博文属性修改失败！');
	 			}
	 		}


		}else{
			$where['id']=I('id');
			$this->attr=M('BlogAttr')->where($where)->select();
			$this->display();
		}
	}
	//删除属性
	public function delattr(){
		$attr=M('BlogAttr');
		$where['id']=I('id');
		if($attr->where($where)->delete()){
			$LogAction=new ConfigAction();
			$logid=$LogAction->addlog($_SESSION['username'].'在'.date("Y-m-d H:i:s").'删除博客属性'.I('name'));
			if(!$logid)
				$this->error('日志记录失败');
			else
				$this->success('博文属性删除成功！',U(GROUP_NAME.'/Blog/attr'));
		}else{
			$this->error('博文属性删除失败！');
		}
	}

	//友情链接
	public function link(){
    	$Link=D('BlogLink');
    	if(IS_POST){
    		if($Link->create()){
    			$count=$Link->save();
    			if($count){
	 				//记录日志
					$LogAction=new ConfigAction();
					$logid=$LogAction->addlog($_SESSION['username'].'在'.date("Y-m-d H:i:s").'修改友情链接'.I('name'));
					if(!$logid)
		 				$this->error('日志记录失败');
		 			else
		 				$this->success('友情链接修改成功',U(GROUP_NAME.'/Blog/link'));
	 			}else{
	 				$this->error($Link->getError());
	 			}
    		}else{
    			$this->error($Link->getError());
    		}
    	}else{
			$this->link=$Link->order('time desc')->select();
			$this->display();
    	}
    }
    public function addlink(){
    	$link=D('BlogLink');
    	if(IS_POST){
    		if($link->create()){
	 			$lastid=$link->add();
	 			if($lastid){
	 				//记录日志
					$LogAction=new ConfigAction();
					$logid=$LogAction->addlog($_SESSION['username'].'在'.date("Y-m-d H:i:s").'添加友情链接'.I('name'));
					if(!$logid)
		 				$this->error('日志记录失败');
		 			else
		 				$this->success('友情链接添加成功',U(GROUP_NAME.'/Blog/link'));
	 			}else{
	 				$this->error('友情链接添加失败');
	 			}
	 		}else{
				$this->error($link->getError());
			}
    	}
    }
    public function editlink(){
    	$where['id']=I('id');
    	$this->link=D('BlogLink')->where($where)->select();
    	$this->display();
    }
    public function dellink(){
    	$Link=M('BlogLink');
		$where['id']=I('id');
		if($Link->where($where)->delete()){
			$LogAction=new ConfigAction();
			$logid=$LogAction->addlog($_SESSION['username'].'在'.date("Y-m-d H:i:s").'删除友情链接'.I('name'));
			if(!$logid)
				$this->error('日志记录失败');
			else
				$this->success('友情链接删除成功！',U(GROUP_NAME.'/Blog/link'));
		}else{
			$this->error('友情链接删除失败！');
		}
    }
    //博客统计
    public function count(){
    	$blog=M('BlogBlog');
    	$this->menu=M()->query("select count(a.typeid) as num,c.typename,c.id,a.optime from tpl_blog_blog a,tpl_blog_category c where a.typeid=c.id AND c.isshow=1 AND a.del=0 AND a.status=1 group by c.typename order by c.id asc");
    	$this->blog=$blog->count("id");		//博客总计
    	$this->sumhits=$blog->sum('hits');	//访问总量
    	$this->attrcoun =M()->query("select count(a.aid) as num, a.bid,a.aid,b.name,b.id from tpl_blog_attr_menu a inner join tpl_blog_attr b on a.aid=b.id group by b.name order by b.id asc");
    	$this->display();
    }
    //博客设置
    public function config(){
		$Config=D('BlogConfig');
		if(IS_POST){
			$info=$this->up('cfg_img');
         	$blog_ava=$info[0]['savename'];
         	$where['configname']='blog_ava';
        	$count=$Config->where($where)->setField('content',$blog_ava);
     	foreach(I('post.') as $k=>$v) {
           

	        //$k = preg_replace("#^edit___#", "", $k);
	        $data["content"]=$v;
	        $data["optime"]=time();
	        $where['configname']=$k;
	        $Config->where($where)->save($data);		       	
	    }
	    $this->ReWriteConfig();
	   //记录日志
		$LogAction=new ConfigAction();
		$logid=$LogAction->addlog($_SESSION['username'].'在'.date("Y-m-d H:i:s").'更改系统基本设置');
		if(!$logid)
			$this->error('日志记录失败');
		else
			$this->success('系统基本设置更改成功',U(GROUP_NAME.'/Blog/config'));
    }else{
     	$datalists=$Config->order('id')->select();
     	$this->assign('datalists',$datalists);
     	$this->display();
    	}
	}
	 public function do_config(){  
 	 if(!IS_POST)_404('页面不存在','index');
 	 $Config=D('BlogConfig');
 	 if($Config->create()){
 	 	$lastid=$Config->add();
 	 	if($lastid){
 	 		 $this->ReWriteConfig();
	   		//记录日志
			$LogAction=new ConfigAction();
			$logid=$LogAction->addlog($_SESSION['username'].'在'.date("Y-m-d H:i:s").'添加'.I('info').'系统变量'.I('configname'));
			if(!$logid)
				$this->error('日志记录失败');
			else
				$this->success('变量添加成功',U(GROUP_NAME.'/Blog/config'));
 	 	}
 	 	else
 	 		$this->error('变量添加失败');
 	 }else{
 	 	$this->error($Config->getError());
 	 }

 	}
 	public function editconfig(){
 		$Config=D('BlogConfig');
 		if(IS_POST){
 			if($Config->create()){
 	 			$lastid=$Config->save();
		 	 	if($lastid){
		 	 		 $this->ReWriteConfig();
			   		//记录日志
					$LogAction=new ConfigAction();
					$logid=$LogAction->addlog($_SESSION['username'].'在'.date("Y-m-d H:i:s").'修改'.I('info').'系统变量'.I('configname'));
					if(!$logid)
						$this->error('日志记录失败');
					else
						$this->success('变量修改成功',U(GROUP_NAME.'/Blog/config'));
		 	 	}
 	 			else
 	 				$this->error('变量修改失败');
		 	}else{
		 	 	$this->error($Config->getError());
		 	}

 		}else{
	 		$where['id']=I('id');
	 		$this->con=$Config->where($where)->find();
	 		$this->display();
 		}
 		
 	}
 	public function delconfig(){
 		$where['id']=I('id');
 		$count=M('BlogConfig')->where($where)->delete();
 		if($count){
 			$LogAction=new ConfigAction();
			$logid=$LogAction->addlog($_SESSION['username'].'在'.date("Y-m-d H:i:s").'删除'.I('info').'系统变量'.I('configname'));
			if(!$logid)
				$this->error('日志记录失败');
			else
				$this->success('变量删除成功',U(GROUP_NAME.'/Blog/config'));
 		}else{
 			$this->error('变量删除失败');
 		}
 	}
//更新配置文件webconfig文件的内容
private	function ReWriteConfig(){
	    $Config=D('BlogConfig');
	    $configpath = C('cfg_conf');
	    if(!is_writeable($configpath.'blogconfig.php')){
	        $this->error("配置文件'{$configpath}blogconfig.php'不支持写入，无法修改系统配置参数！");
	    }
	    $datalists=$Config->order('id asc')->select();
	    foreach($datalists as $datalist){
	    	$data[$datalist['configname']]=$datalist['content'];
	    }
        F('blogconfig',$data,$configpath);
	}
	//博客留言
	public function feedback(){
		$Feedback=D('BlogFeedback');
	  	import('ORG.Util.Page');// 导入分页类
		$count      = $Feedback->relation(true)->count();// 查询满足要求的总记录数
		$Page       = new Page($count,20);// 实例化分页类 传入总记录数
		// 进行分页数据查询 注意page方法的参数的前面部分是当前的页数使用 $_GET[p]获取
		$nowPage   = isset($_GET['p'])?$_GET['p']:1;
		$lists 	    = $Feedback->order(array('id'=>'desc'))->page($nowPage.','.$Page->listRows)->relation(true)->select();
		$show       = $Page->show();// 分页显示输出
		$this->assign('page',$show);// 赋值分页输出
		$this->assign('back',$lists);// 赋值数据集
		$this->display();
	}
	//回复留言
	public function editFeedback(){
   $Feedback=D('BlogFeedback');
   if(IS_POST) {
   		$where['id']=I('id');
   		$data=$Feedback->where($where)->find();
   		if($Feedback->create()){
   			$count=$Feedback->where($where)->save();
   			if($count){
   				//记录日志
				$LogAction=new ConfigAction();
				$logid=$LogAction->addlog($_SESSION['username'].'在'.date("Y-m-d H:i:s").'修改留言'.$data['title']."信息");
				if(!$logid)
					$this->error("日志记录失败");
				else
					$this->success("更改留言信息成功",U(GROUP_NAME.'/Blog/feedback'));
			}else{
   				$this->error("更改留言信息失败");
   			}
   		}else{
   			$this->error($Feedback->getError());
   		}
   }else{
		$where['id']=I('id');
		$data=$Feedback->where($where)->find();
		$this->assign('data',$data);
		$this->display();
   }
 }
 //删除留言
  public function delFeedback(){
   if($this->dellfeedback(I('id'))){
      $this->success('留言信息删除成功',U(GROUP_NAME.'/Blog/feedback'));
   }else{
      $this->error('留言信息删除失败');
   }
 }
  public function alldelFeedback(){
      $ids=explode(',',substr(I('id'),1));
      foreach($ids as $key=>$id){
         if(!$this->dellfeedback($id)){
            $this->show(0);
         }
      }
      $this->show(1);
}
  private function dellfeedback($id){
   $where['id']=$id;
   $Feedback=D('BlogFeedback');
   $data=$Feedback->where($where)->find();
   $count=$Feedback->where($where)->delete();
   if($count) {
      //记录日志
      $LogAction=new ConfigAction();
      $logid=$LogAction->addlog($_SESSION['username'].'在'.date("Y-m-d H:i:s").'删除留言信息'.$data['useraname']);
      if(!$logid)
         return false;
      else
        return true;
   }else{
     return false;
   }
 }
 //审核留言
 	public function allchecked($status){
     $ids=explode(',',substr(I('id'),1));
      foreach($ids as $key=>$id){
         if(!$this->checkfeedback($id,$status)){
            $this->show(0);
         }
      }
      $this->show(1);
}
private function checkfeedback($id,$status){
   $Feedback=D('BlogFeedback');
   $where['id']=$id;
   $data['status']=$status;
   $data['optime']=time();
   $data['reuid']=$_SESSION[C('USER_AUTH_KEY')];
   $count=$Feedback->where($where)->save($data);
   if($count){
      //记录日志
      $LogAction=new ConfigAction();
      $logid=$LogAction->addlog($_SESSION['username'].'在'.date("Y-m-d H:i:s").'审核'.$data['title']."信息");
      if(!$logid)
        return false;
      else
         return true;
   }
   return false;
}
	public function creatFeedback(){
		$where['id']=I('id');
		$this->feedback=D('BlogFeedback')->where($where)->relation(true)->find();
		//p($feedback);die;
		$this->display();
	}

	private  function up($cfg){
	    import('ORG.Net.UploadFile');
	    $upload = new UploadFile();// 实例化上传类
	    $upload->maxSize    = 31457280 ;// 设置附件上传大小
	    $types=explode('|',C($cfg));
	    $upload->allowExts  = $types;// 设置附件上传类型
	    $upload->savePath   = "./".C('cfg_uploadpath');// 设置附件上传目录
	    $upload->thumb = true;
		$upload->thumbMaxWidth = '180,200';
		$upload->thumbMaxHeight = '180,200';

	    if(!$upload->upload()) {// 上传错误提示错误信息
	    	$this->error($upload->getErrorMsg());die;
	    }else{// 上传成功 获取上传文件信息
	    	$info =  $upload->getUploadFileInfo();
	    }
   return $info;
 	}


}
?>