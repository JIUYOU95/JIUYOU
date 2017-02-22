<?php
//import('@.Common.Opadmin');
class LoginAction extends Action{
    public function index(){
    	$this->assign('powerby',C('cfg_powerby'));
    	$this->assign('verify',C('cfg_verify'));
		$this->display();
    }
	 Public function verify(){
  	import('ORG.Util.Image');
  	Image::buildImageVerify(4,1,'png');
	}
	public function login(){
		if(!IS_POST) halt('页面不存在');
		if(I('code','','md5') !=session('verify')){
			$this->error('验证码错误');
		}
/*		$Opadmin=new Opadmin(I('username'),I('password'));

		if($Opadmin->login()){
	 		//记录登陆日志
			$LogAction=new ConfigAction();
			$Opadmin=new Opadmin();
			$logid=$LogAction->addlog($Opadmin->getUsername().'在'.date("Y-m-d H:i:s").'登陆','1');

			if(!$logid)
				$this->error('日志记录失败');
			else
	 			$this->success('登陆成功',U(GROUP_NAME.'/Index/index'));
		 }else{
		 	$this->error('用户名或密码不对');
		 }*/



		$username=I('username');
		$pwd=I('password','','md5');

		$user=M('User')->where(array('username'=>$username))->find();
		if(!$user||$user['password'] !=$pwd){
			$this->error('账号或密码错误！');
		}
		if($user['lock']) $this->error('用户被锁定！');
		$data=array(
			'id'=>$user['id'],
			'logintime'=>time(),
			'loginip'=>get_client_ip(),
			);
		$count=M('User')->save($data);


		
		session(C('USER_AUTH_KEY'),$user['id']);
		session('username',$user['username']);
		session('nickname',$user['nickname']);
		session('logintime',date('Y-m-d H:i:s',$user['logintime']));
		session('loginip',$user['loginip']);
		
		//超级管理员识别
		
		 if($user['username'] == C('RBAC_SUPERADMIN')){
			session(C('ADMIN_AUTH_KEY'),true);
		}
		//读取用户权限
		import('ORG.Util.RBAC');
		RBAC::saveAccessList();
		if($count>0){
			$this->success('登录成功！',U(GROUP_NAME.'/Index/index'));
		}else{
			$this->error('登录失败！');
		}

		
	}
}
?>