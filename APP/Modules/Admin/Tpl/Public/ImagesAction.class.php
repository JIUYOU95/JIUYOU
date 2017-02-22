<?php
import('ORG.Util.Page');
import('Common.Category',APP_PATH);
class ImagesAction extends CommonAction{
	public function index(){
		$this->display();
	}
	public function menu(){
		$cate=D('ImgCategory')->order('sort asc')->select();
		$this->cate=Category::unlimitedForLevel($cate,'&nbsp;&nbsp;├&nbsp');
		$this->display();
	}
	public function addmenu(){
		$Category=D('ImgCategory');
		if(IS_POST){
	 		if($Category->create()){
	 			$lastid=$Category->add();
	 			if($lastid){
	 				//记录日志
					$ConfigAction=new ConfigAction();
					$logid=$ConfigAction->addlog($_SESSION['username'].'在'.date("Y-m-d H:i:s").'添加图片分类'.I('typename'));
					if(!$logid)
		 				$this->error('日志记录失败');
		 			else
		 				$this->success('图片分类添加成功',U(GROUP_NAME.'/Images/menu'));
	 			}else{
	 				$this->error('图片分类添加失败');
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
	public function editmenu(){
		$Category=D('ImgCategory');
		if(IS_POST){
	 		if(!$Category->create()){
	 			$this->error($Category->getError());
	 		}else{
	 			$count=$Category->save();
	 			if($count>0){
					$ConfigAction=new ConfigAction();
					$logid=$ConfigAction->addlog($_SESSION['username'].'在'.date("Y-m-d H:i:s").'修改图片分类'.I('typename'));
					if(!$logid)
						$this->error('日志记录失败');
					else
						$this->success('修改分类成功',U(GROUP_NAME.'/Images/menu'));
	 			}else{
	 				$this->error('修改分类失败');
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
	public function delmenu(){
		$where['id']=I('id');
	 	$Category=D('ImgCategory');
		$data=$Category->where($where)->find();
	 	if($Category->where($where)->delete()){
			$ConfigAction=new ConfigAction();
			$logid=$ConfigAction->addlog($_SESSION['username'].'在'.date("Y-m-d H:i:s").'删除博客栏目'.$data['typename']);
			if(!$logid)
				$this->error('日志记录失败');
			else
				$this->success('栏目删除成功',U(GROUP_NAME.'/Images/menu'));
	 	}else{
	 		$this->error('栏目删除失败');
	 	}
	}
	public function sortHandle(){
		$db=D('ImgCategory');
		foreach($_POST as $id => $sort){
			$count=$db->where(array('id'=>$id))->setField('sort',$sort);
		}
		
		$ConfigAction=new ConfigAction();
		$logid=$ConfigAction->addlog($_SESSION['username'].'在'.date("Y-m-d H:i:s").'图片分类排序');
		if(!$logid)
			$this->error('日志记录失败');
		else
			$this->success('栏目排序成功',U(GROUP_NAME.'/Images/menu'));
	}

	public function img(){
		$Img=D('ImgImg');

  	  $count      = $Img->where($where)->count();// 查询满足要求的总记录数
  	  $Page       = new Page($count,20);// 实例化分页类 传入总记录数
	  // 进行分页数据查询 注意page方法的参数的前面部分是当前的页数使用 $_GET[p]获取
	  $nowPage  = isset($_GET['p'])?$_GET['p']:1;
	  $orderby['optime']='asc';
	  $lists 	  = $Img->relation(true)->order($orderby)->where($where)->page($nowPage.','.$Page->listRows)->getBlogs();
	  $show       = $Page->show();// 分页显示输出
	  $this->assign('page',$show);// 赋值分页输出
	  $this->assign('lists',$lists);// 赋值数据集
      //$this->assign('userid',$Opadmin->getUserid());

		$this->display();
	}

	public function addimg(){
		$Img=D('ImgImg');
		if(IS_POST){
	 		if($Img->create()){
	 			if (!empty($_FILES['pic']['name'][0])) {
		           $info=$this->up('img_img');
		           if(!empty($info)){
		              foreach($info as $item){
		                $pic.="|".$item['savename'];
		                $img.="|".$item['name'];
		              }
		            }
		           $pic=substr($pic,1);
		           $Img->pic=$pic;
		            $img=substr($img,1);
		           $Img->img=$img;
		        } 
	 			$bid=$Img->add();
	 			if($bid){
	 				//记录日志
					$ConfigAction=new ConfigAction();
					$logid=$ConfigAction->addlog($_SESSION['username'].'在'.date("Y-m-d H:i:s").'添加图片'.I('title'));
					if(!$logid)
		 				$this->error('日志记录失败');
		 			else
		 				$this->success('图片添加成功',U(GROUP_NAME.'/Images/img'));
	 			}else{
	 				$this->error('图片添加失败');
	 			}
	 		}else{
				$this->error($Img->getError());
			}
		}else{
			//博文所属分类
			$cate=M('ImgCategory')->order('sort asc')->select();
			$this->cate=Category::unlimitedForLevel($cate,'&nbsp;&nbsp;├&nbsp');
			$this->display();
		}
	}
	public function editimg(){
		$Img=D('ImgImg');
		if(IS_POST){
			if($Img->create()){
				$where['id']=I('id');

				if (!empty($_FILES['pic']['name'][0])) {
		           $info=$this->up('img_img');
		           if(!empty($info)){
		              foreach($info as $item){
		                $pic.="|".$item['savename'];
		              }
		            }
		           $pic=substr($pic,1);

		           $Img->pic=$pic;
		        }
		        $imgname=I('imgname');
		        if(!empty($imgname)){
		          $imgname=implode('|',$imgname);
		          if($pic){
		            $Img->pic=$imgname."|".$pic;
		          }else{
		          	$Img->pic=$imgname;
		          }

		        }
		        if(empty($_FILES['pic']['name'][0])&&empty($imgname)){
		          $Img->pic='';
		        }
				$bid=$Img->where($where)->save();
				if($bid>0){
					//记录日志
					$LogAction=new ConfigAction();
					$logid=$LogAction->addlog($_SESSION['username'].'在'.date("Y-m-d H:i:s").'修改图片'.I('title'));
					if(!$logid)
			 			$this->error('日志记录失败');
			 		else
			 			$this->success('修改成功！',U(GROUP_NAME.'/Images/img'));
				}else{
					$this->error('修改失败！');
				}
			}else{
				$this->error($Img->getError());
			}
		}else{
			//博文所属分类
			$cate=D('ImgCategory')->order('sort asc')->select();
			$this->cate=Category::unlimitedForLevel($cate,'&nbsp;&nbsp;├&nbsp');
	
			$where['id']=I('id');
			$data=$Img->where($where)->find();
			$this->assign('data',$data);
			if($data['pic']!=''){
		        $pic=explode('|', $data['pic']);
		        $this->assign("pic", $pic);

		     }
		
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
			if(M('ImgImg')->save($update)){
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
		$this->lists=D('ImgImg')->getBlogs(1);
		$this->display('Images/img');
	}
		//回收站删除
	public function delimg(){
		$ids=explode(',', I('id'));
	    foreach($ids as $key=>$vaule){    
			if(M('ImgImg')->delete($vaule)){
				//记录日志
				$LogAction=new ConfigAction();
				$logid=$LogAction->addlog($_SESSION['username'].'在'.date("Y-m-d H:i:s").'彻底删除图片'.I('title'));
				if(!$logid)
				 	$this->error('日志记录失败');
				 else
				 	$this->success('彻底删除成功！',U(GROUP_NAME.'/Images/trach'));
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
    $this->success('文档删除成功',U(GROUP_NAME.'/Images/trach',array('typeid'=>$typeid)));
 }

	//审核
	public function ischeck(){
	  $ids=explode(',', I('id'));
	    foreach($ids as $key=>$vaule){    
	      if(!$typeid=$this->upkarc('status',$vaule,1)){
	           $this->error('部分文档审核失败');
	       }
	    }
	    $this->success('文档审核成功',U(GROUP_NAME.'/Images/img',array('typeid'=>$typeid)));
	}
	public function ischeckd(){
	  $ids=explode(',', I('id'));
	    foreach($ids as $key=>$vaule){    
	      if(!$typeid=$this->upkarc('status',$vaule,0)){
	           $this->error('部分文档删除审核失败');
	       }
	    }
	    $this->success('文档删除审核成功',U(GROUP_NAME.'/Images/img',array('typeid'=>$typeid)));
	}
	//更改文档属性
	private function upkarc($field,$id,$status){
	  $where['id']=$id;
	  $Article=D('ImgImg');
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
	//博客统计
    public function count(){
    	$img=M('ImgImg');
    	$this->menu=M()->query("select count(a.typeid) as num,c.typename,c.id,a.optime from tpl_img_img a,tpl_img_category c where a.typeid=c.id AND a.del=0 AND a.status=1 group by c.typename order by c.id asc");
    	$this->img=$img->count("id");		//博客总计
    	$this->sumhits=$img->sum('hits');	//访问总量
    	$this->display();
    }

	public function config(){
		$Config=D('ImgConfig');
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
			$this->success('系统基本设置更改成功',U(GROUP_NAME.'/Images/config'));
    }else{
     	$datalists=$Config->order('id')->select();
     	$this->assign('datalists',$datalists);
     	$this->display();
    	}
	}
	public function do_config(){  
 	 if(!IS_POST)_404('页面不存在','index');
 	 $Config=D('ImgConfig');
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
				$this->success('变量添加成功',U(GROUP_NAME.'/Images/config'));
 	 	}
 	 	else
 	 		$this->error('变量添加失败');
 	 }else{
 	 	$this->error($Config->getError());
 	 }

 	}
 	//更新配置文件webconfig文件的内容
private	function ReWriteConfig(){
	    $Config=D('ImgConfig');
	    $configpath = C('cfg_conf');
	    if(!is_writeable($configpath.'imgconfig.php')){
	        $this->error("配置文件'{$configpath}blogconfig.php'不支持写入，无法修改系统配置参数！");
	    }
	    $datalists=$Config->order('id asc')->select();
	    foreach($datalists as $datalist){
	    	$data[$datalist['configname']]=$datalist['content'];
	    }
        F('imgconfig',$data,$configpath);
	}
	private  function up($cfg){
	    import('ORG.Net.UploadFile');
	    $upload = new UploadFile();// 实例化上传类
	    $upload->maxSize    = 31457280 ;// 设置附件上传大小
	    $types=explode('|',C($cfg));
	    $upload->allowExts  = $types;// 设置附件上传类型
	    $upload->savePath   = "./".C('img_uploadpath');// 设置附件上传目录
	    $upload->thumb = true;
		$upload->thumbMaxWidth = '140,300';
		$upload->thumbMaxHeight = '120,250';
	    if(!$upload->upload()) {// 上传错误提示错误信息
	    	$this->error($upload->getErrorMsg());die;
	    }else{// 上传成功 获取上传文件信息
	    	$info =  $upload->getUploadFileInfo();
	    }
   return $info;
 	}
 	 public function remoefile(){
	   if(!unlink(C('img_uploadpath').I('file'))){
	    //$this->show('删除失败');
	   }
  	}


}
?>