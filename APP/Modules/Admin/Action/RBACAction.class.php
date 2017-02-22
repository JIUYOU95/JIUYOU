<?php
import("ORG.Util.Page");
/*
 *后台RBAC控制器 
 */
class RBACAction extends CommonAction{
	//首页
	public function index(){
		$this->display();
	}
	//用户列表
	public function user(){
		$User=D('User');
		$count=$User->relation(true)->count();
		$Page=new Page($count,25);
		$nowPage=isset($_GET['p'])?$_GET['p']:1;
		$list=$User->field('password', true)->order('id asc')->page($nowPage.','.$Page->listRows)->relation(true)->select();
		$this->assign('user',$list);
		$show=$Page->show();
		$this->assign('page',$show);
		$this->display();


		//$this->user=D('User')->field('password', true)->relation(true)->select();
		//$this->display();
	}
	//角色列表
	public function role(){
		$Role=D('Role');
		$count=$Role->count();
		$Page=new Page($count,25);
		$nowPage=isset($_GET['p'])?$_GET['p']:1;
		$list=$Role->order('id asc')->page($nowPage.','.$Page->listRows)->select();
		$this->assign('role',$list);
		$show=$Page->show();
		$this->assign('page',$show);
		$this->display();
		//$this->role=M('Role')->select();
		//$this->display();
	}
	//角色锁定处理
	public function lockHandle(){
		$id=I('id');
		$lock=I('lock');
		$msg=$lock ? '锁定' : '解锁';
		$count=M('user')->where(array('id'=>$id))->setField('lock',$lock);
		if($count>0){
			$ConfigAction=new ConfigAction();
			$logid=$ConfigAction->addlog($_SESSION['username'].'在'.date("Y-m-d H:i:s").$msg.'用户'.$count['username']);
			if(!$logid)
				$this->error('日志记录失败');
			else
				$this->success('用户'.$msg.'成功！',U(GROUP_NAME.'/RBAC/user'));
		}else{
			$this->error('用户'.$msg.'失败！');
		}
	}
	//节点列表
	public function node(){
		$field=array('id','name','title','pid');
		$node=M('Node')->field($field)->order('sort')->select();
		$this->node=node_merge($node);
		$this->display();
	}
	//添加用户
	public function addUser(){
		$User=D('User');
		if(IS_POST){
			if($User->create()){
				//所属角色
				$role=array();
				$user=array(
					'username'=>I('username'),
					'nickname'=>I('nickname'),
					'password'=>I('password','','md5'),
					'logintime'=>time(),
					'regtime'=>time(),
					'loginip'=>get_client_ip()
				);
				if($uid=$User->add($user)){

					foreach($_POST['role_id'] as $v){
						$role[]=array(
							'role_id'=>$v,
							'user_id'=>$uid
						);
					}
					//添加用户角色
					M('Role_user')->addAll($role);
					$ConfigAction=new ConfigAction();
					$logid=$ConfigAction->addlog($_SESSION['username'].'在'.date("Y-m-d H:i:s").'添加用户'.I('username'));
					if(!$logid)
						$this->error('日志记录失败');
					else
						$this->success('添加用户成功！',U(GROUP_NAME.'/RBAC/user'));
				}else{
					$this->error('添加用户失败！');
				}

			}else{
				$this->error($User->getError());
			}
		}else{
			$this->role=M('Role')->select();
			$this->display();
		}
	}
	//修改用户
	public function edituser(){
		$User=D('User');
		if(IS_POST){
		$where['id']=I('id');
		$where['password']=md5(I('pwd'));
		$count=$User->where($where)->count();
		if(!$count&&I('password')!=''){
			$this->error('原始密码不正确');
		}
		if(!I('password')){
			unset($_POST['password']);
			unset($_POST['repassword']);
		}else{
			$_POST['password']=md5(I('password'));
			$_POST['repassword']=md5(I('repassword'));
		}

			if($User->create()){
				$where['id']=I('id');
				$count=$User->save();
				if($count){
					M('Role_user')->where(array('user_id'=>I('id')))->delete();
					foreach($_POST['role_id'] as $v){
						$role[]=array(
							'role_id'=>$v,
							'user_id'=>I('id'),
						);
					}
					//添加用户角色
					M('Role_user')->addAll($role);
					$ConfigAction=new ConfigAction();
					$logid=$ConfigAction->addlog($_SESSION['username'].'在'.date("Y-m-d H:i:s").'修改用户'.I('username'));
					if(!$logid)
			 			$this->error('日志记录失败');
			 		else
			 			$this->success('修改成功！',U(GROUP_NAME.'/RBAC/user'));
				}else{
					$this->error('修改失败！');
				}
			}else{
				$this->error($User->getError());
			}
		}else{
			$this->role=M('Role')->select();
			$where['id']=I('id');
			$this->user=$User->field('password', true)->where($where)->relation(true)->select();
			$this->display();
			}
		
	}
	//删除用户
	public function deluser(){
		$User=D('User');
		$where['id']=I('id');
		if($User->where($where)->delete()){
			M('Role_user')->where(array('user_id'=>I('id')))->delete();
			$ConfigAction=new ConfigAction();
			$logid=$ConfigAction->addlog($_SESSION['username'].'在'.date("Y-m-d H:i:s").'删除用户'.I('name'));
			if(!$logid)
				$this->error('日志记录失败');
			else
				$this->success('用户删除成功！',U(GROUP_NAME.'/RBAC/user'));
		}else{
			$this->error('用户删除失败！');
		}
	}

	//添加角色
	public function addRole(){
		$Role=D('Role');
		if(IS_POST){
			if($Role->create()){
				$count=$Role->add();
				if($count>0){
					//记录日志
					$ConfigAction=new ConfigAction();
					$logid=$ConfigAction->addlog($_SESSION['username'].'在'.date("Y-m-d H:i:s").'添加角色'.I('name'));
					if(!$logid)
			 			$this->error('日志记录失败');
			 		else
			 			$this->success('添加角色成功！',U(GROUP_NAME.'/RBAC/role'));
				}else{
					$this->error('添加角色失败！');
				}
			}else{
				$this->error($Role->getError());
			}
		}else{
			$this->display();
		}
		
	}
	//修改角色
	public function editrole(){
		$Role=D('Role');
		if(IS_POST){
			if(!$Role->create()){
	 			$this->error($Role->getError());
	 		}else{
	 			$count=$Role->save();
	 			if($count>0){
	 				$ConfigAction=new ConfigAction();
					$logid=$ConfigAction->addlog($_SESSION['username'].'在'.date("Y-m-d H:i:s").'修改角色'.I('name'));
					if(!$logid)
						$this->error('日志记录失败');
					else
						$this->success('角色修改成功！',U(GROUP_NAME.'/RBAC/role'));
	 			}else{
	 				$this->error('角色修改失败！');
	 			}
	 		}


		}else{
			$where['id']=I('id');
			$this->role=$Role->where($where)->select();
			$this->display();
		}

	}
	//删除角色
	public function delrole(){
		$Role=D('Role');
		$where['id']=I('id');
		if($Role->where($where)->delete()){
			$ConfigAction=new ConfigAction();
			$logid=$ConfigAction->addlog($_SESSION['username'].'在'.date("Y-m-d H:i:s").'删除角色'.I('name'));
			if(!$logid)
				$this->error('日志记录失败');
			else
				$this->success('角色删除成功！',U(GROUP_NAME.'/RBAC/role'));
		}else{
			$this->error('角色删除失败！');
		}

	}
	//添加节点
	public function addNode(){
		$Node=D('Node');
		if(IS_POST){
			if($Node->create()){
				$count=$Node->add();
				if($count>0){
					//记录日志
					$ConfigAction=new ConfigAction();
					$logid=$ConfigAction->addlog($_SESSION['username'].'在'.date("Y-m-d H:i:s").'添加节点'.I('name'));
					if(!$logid)
			 			$this->error('日志记录失败');
			 		else
			 			$this->success('添加节点成功！',U(GROUP_NAME.'/RBAC/node'));
				}else{
					$this->error('添加节点失败！');
				}
			}else{
				$this->error($Node->getError());
			}

		}else{
			$this->pid=I('pid',0,'intval');
			$this->level=I('level',1,'intval');
			
			switch($this->level){
				case 1:
					$this->type='应用';
					break;
				case 2:
					$this->type='控制器';
					break;
				case 3:
					$this->type='动作方法';
					break;
			}
			$this->display();
		}

	}
	//修改节点
	public function editnode(){
		$Node=D('Node');
		if(IS_POST){
			if($Node->create()){
				$count=$Node->save();
				if($count>0){
					//记录日志
					$ConfigAction=new ConfigAction();
					$logid=$ConfigAction->addlog($_SESSION['username'].'在'.date("Y-m-d H:i:s").'修改节点'.I('name'));
					if(!$logid)
			 			$this->error('日志记录失败');
			 		else
			 			$this->success('修改节点成功！',U(GROUP_NAME.'/RBAC/node'));
				}else{
					$this->error('修改节点失败！');
				}
			}else{
				$this->error($Node->getError());
			}

		}else{
			$where['id']=I('id');
			$this->node=$Node->where($where)->select();
			$this->level=I('level');
			
			switch($this->level){
				case 1:
					$this->type='应用';
					break;
				case 2:
					$this->type='控制器';
					break;
				case 3:
					$this->type='动作方法';
					break;
			}
			$this->display();
		}

	}
	//删除节点
	public function delnode(){
		$Node=D('Node');
		$where['id']=I('id');
		if($Node->where($where)->delete()){
			$ConfigAction=new ConfigAction();
			$logid=$ConfigAction->addlog($_SESSION['username'].'在'.date("Y-m-d H:i:s").'删除节点'.I('name'));
			if(!$logid)
				$this->error('日志记录失败');
			else
				$this->success('节点删除成功！',U(GROUP_NAME.'/RBAC/node'));
		}else{
			$this->error('节点删除失败！');
		}
	}
	//配置权限
	public function access(){
		$rid=I('rid',0,'intval');
		
		$field=array('id','name','title','pid');
		$node=M('Node')->order('sort')->field($field)->select();
		//原有权限
		$access=M('Access')->where(array('role_id'=>$rid))->getField('node_id',true);
		$this->node=node_merge($node,$access);
		$this->rid=$rid;
		$this->display();
	}
	//修改权限
	public function setAccess(){
		$rid=I('rid',0,'intval');
		$db=M('Access');		
		//清空原权限
		$db->where(array('role_id'=>$rid))->delete();		
		//组合新权限
		$data=array();
		foreach ($_POST['access'] as $v){
			$tmp=explode('_',$v);
			$data[]=array(
				'role_id'=>$rid,
				'node_id'=>$tmp[0],
				'level'=>$tmp[1],
			);
		}		
		//插入新权限
		if($db->addAll($data)){
			//记录日志
			$ConfigAction=new ConfigAction();
			$logid=$ConfigAction->addlog($_SESSION['username'].'在'.date("Y-m-d H:i:s").'修改权限');
			if(!$logid)
				 $this->error('日志记录失败');
			else
				$this->success('权限修改成功！',U(GROUP_NAME.'/RBAC/role'));
		}else{
			$this->error('权限修改失败！');
		}
	}
}
?>