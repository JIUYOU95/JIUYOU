<?php
class LoginAction extends CommonAction {
	public function index(){
		$this->head();
		$this->display();
	}
	public function login($type = null){
		empty($type) && $this->error('参数错误');

		//加载ThinkOauth类并实例化一个对象
		import("ORG.ThinkSDK.ThinkOauth");
		$sns  = ThinkOauth::getInstance($type);

		//跳转到授权页面
		redirect($sns->getRequestCodeURL());
	}

	//授权回调地址
	public function callback($type = null, $code = null){
		(empty($type) || empty($code)) && $this->error('参数错误');
		
		//加载ThinkOauth类并实例化一个对象
		import("ORG.ThinkSDK.ThinkOauth");
		$sns = ThinkOauth::getInstance($type);

		//腾讯微博需传递的额外参数
		$extend = null;
		if($type == 'tencent'){
			$extend = array('openid' => $this->_get('openid'), 'openkey' => $this->_get('openkey'));
		}
		//请妥善保管这里获取到的Token信息，方便以后API调用
		//调用方法，实例化SDK对象的时候直接作为构造函数的第二个参数传入
		//如： $qq = ThinkOauth::getInstance('qq', $token);
		$token = $sns->getAccessToken($code,null);
		//
		//获取当前登录用户信息
		if(is_array($token)){
			$user_info = A('Type', 'Event')->$type($token);

			echo("<h1>恭喜！使用 {$type} 用户登录成功</h1><br>");
			echo("授权信息为：<br>");
			dump($token);
			echo("当前登录用户信息为：<br>");
			dump($user_info);
		}
	}


//注册
	public function user(){
		$User=D('User');
	    if(IS_POST){
	    	if($User->create()){	
				if($User->add()){
	        		$this->redirect('Blog/Login/index');
		        }else{
		         	$this->error();
		        }
		    } 
	    }
   }
	public function checkAccount(){
    	$username=I('post.username');
    	$list=M('User')->where(array('username'=>$username))->select();
    	$list=$list[0];
	    if(!empty($list)){
	       $this->ajaxReturn(1);
	    }
   }
   	public function checkEmail(){
	    $email=I('post.email');
	    $list=M('User')->where(array('email'=>$email))->select();
	    $list=$list[0];
	    if(!empty($list)){
	     	$this->ajaxReturn(1);
	    }
   }
//登录
   public function docheckEmail(){
	    $email=I('post.email');
	    $list=M('User')->where(array('email'=>$email))->select();
	    $list=$list[0];
	    if(!empty($list)){
	     	$this->ajaxReturn(1);
	    }
   }
   public function docheckpwd(){
   		$user=M('User');
	    $email=I('email');
	    $pwd=I('password');
	    $list=$user->where(array('email'=>$email,'password'=>$pwd))->select();
	    if($list){
	    	$this->ajaxReturn($list);  	
	    }
   }
   public function chack(){
   		$user =M('User');
        if(!IS_AJAX){
           $this->ajaxReturn(array(
                'info' => '非法的请求方式'
            ));
        }
        $email     = I('email', '');
        $password = I('password', '');
       // $password = md5($password);
        $filter = array(
            'email'     => $email,
            'password' => $password
        );
        $user_info = $user->where($filter)->find();
        if (1 == $user_info['purviews']) {
            $this->ajaxReturn(array(
                'info' => '你无权登录后台'
            ));
        }
        if($user_info){
            // 更新登录ip
            $info['loginip'] = get_client_ip();
            //更新登录时间
            $info['logintime'] =time();
            $user->where(array('id' => $user_info['id']))->save($info);
            session('user_id',$user_info['id']);
           
            $data = array(
                'info' => 'ok',
                'callback' => U('Blog/Index')
            );
        }else{
            $data = array(
                    'info' => '登录失败，请检查登录名和密码是否正确'
            );
        }
        $this->ajaxReturn($data);
   }
   public function loginout(){
 		session_unset();
		session_destroy();
 		$this->redirect('Blog/Index');
 	}
}
?>