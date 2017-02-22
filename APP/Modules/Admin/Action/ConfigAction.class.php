<?php
/*
 *后台博客设置控制器 
 */
class ConfigAction extends CommonAction{
	//博客统计
	public function index(){
		$this->display();
	}
	public function config(){
		$Config=D('Config');
		if(IS_POST){
     	foreach(I('post.') as $k=>$v) {
	        //$k = preg_replace("#^edit___#", "", $k);
	        $data["content"]=$v;
	        $data["optime"]=time();
	        $where['configname']=$k;
	        $Config->where($where)->save($data);		       	
	    }
	    $this->ReWriteConfig();
	   //记录日志
		$ConfigAction=new ConfigAction();
		$logid=$ConfigAction->addlog($_SESSION['username'].'在'.date("Y-m-d H:i:s").'更改系统基本设置');
		if(!$logid)
			$this->error('日志记录失败');
		else
			$this->success('系统基本设置更改成功',U(GROUP_NAME.'/Config/config'));
    }else{
     	$datalists=$Config->select();
     	$this->assign('datalists',$datalists);
     	$this->display();
    	}
	}
	 public function do_config(){  
 	 if(!IS_POST)_404('页面不存在','config');
 	 $Config=D('Config');
 	 if($Config->create()){
 	 	$lastid=$Config->add();
 	 	if($lastid){
 	 		 $this->ReWriteConfig();
	   		//记录日志
			$ConfigAction=new ConfigAction();
			$logid=$ConfigAction->addlog($_SESSION['username'].'在'.date("Y-m-d H:i:s").'添加'.I('info').'系统变量'.I('configname'));
			if(!$logid)
				$this->error('日志记录失败');
			else
				$this->success('变量添加添加成功',U(GROUP_NAME.'/Config/config'));
 	 	}
 	 	else
 	 		$this->error('变量添加失败');
 	 }
 	 else
 	 {
 	 	$this->error($Config->getError());
 	 }

 }
//更新配置文件webconfig文件的内容
private	function ReWriteConfig(){
	    $Config=D('Config');
	    $configpath = C('cfg_conf');
	    if(!is_writeable($configpath.'webconfig.php')){
	        $this->error("配置文件'{$configpath}webconfig.php'不支持写入，无法修改系统配置参数！");
	    }
	    $datalists=$Config->order('id asc')->select();
	    foreach($datalists as $datalist){
	    	$data[$datalist['configname']]=$datalist['content'];
	    }
        F('webconfig',$data,$configpath);
	}

		//博客日志
	public function log(){
		$this->userid=$_SESSION[C('USER_AUTH_KEY')];
		$Log=D('Log');
    	$search=false;//查询特定用户日志的标志
	    if(I('uid'))
			$where['uid']=I('uid');
		$search=true;
		$this->assign('search',$search);

		if($search){
			if(I('username')){
				$whereu['username']=I('username');
				$Admin=D('User');
				$datau=$Admin->where($whereu)->find();
				$where['uid']=$datau['id'];
			}
		}

	  	import('ORG.Util.Page');// 导入分页类
		$count      = $Log->relation(true)->where($where)->count();// 查询满足要求的总记录数
		$Page       = new Page($count,16);// 实例化分页类 传入总记录数
		// 进行分页数据查询 注意page方法的参数的前面部分是当前的页数使用 $_GET[p]获取
		$nowPage 	= isset($_GET['p'])?$_GET['p']:1;
		$lists 	    = $Log->order('time desc,uid desc,id desc')->page($nowPage.','.$Page->listRows)->relation(true)->where($where)->select();
		$show       = $Page->show();// 分页显示输出
		$this->assign('page',$show);// 赋值分页输出
		$this->assign('lists',$lists);// 赋值数据集
		$this->display();
	}
	//博客日志删除
	public function dellog(){
		$where['id']=I('id');
		if(M('Log')->where($where)->delete()){
			
			$this->success('日志删除成功！',U(GROUP_NAME.'/Config/log'));
		}else{
			$this->error('日志删除失败！');
		}
		
	}
	private function delllog($id){
	if(!$id)
		return false;
	$where['id']=$id;
 	$Log=D('Log');
 	$count=$Log->where($where)->delete();
 	if($count){
	 		
		return true;
 	}else{	
 		return false;
 	}

	}
	public function alldel(){
	$ids=explode(',',substr(I('id'),1));
	foreach($ids as $key=>$id){
		if(!$this->delllog($id))
		{
			$this->show(0);
		}
	}
	$this->show(1);
	}
	public function addlog($content,$type=0){
		if(C('cfg_log')=='N')
 		return true;
	 	$data['uid']=$_SESSION[C('USER_AUTH_KEY')];
		$data['time']=time();
		$data['logtext']=$content;
		$data['type']=$type;
		$Log=D('Log');
		$lastid=$Log->add($data);
		if($lastid)
			return true;
		else
			return false;
 	}
	
}