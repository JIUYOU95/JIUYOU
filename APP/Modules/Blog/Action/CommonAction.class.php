<?php
Load('extend');
class CommonAction extends Action {
	public function head(){
/*网站标题*/	 		$this->assign('blog_name',C('blog_name'));
/*网站主旨*/	 		$this->assign('purpose',C('blog_purpose'));
/*网站URL*/	 			$this->assign('URL',C('blog_url'));
/*网站关键字*/			$this->assign('keywords',C('blog_keywords'));
/*网站关键字*/			$this->assign('description',C('blog_description'));
/*我的座右铭*/	 		$this->assign('motto',C('blog_motto'));
/*网站公告*/	 		$this->assign('notice',C('blog_notice'));
/*网站LOGO*/	 		$this->assign('ava',C('blog_ava'));
/*网站底部版权*/	 	$this->assign('powerby',C('blog_powerby'));
/*友情链接*/			$this->link=M('BlogLink')->order('id asc')->select();
		$where['id']=$_SESSION['user_id'];
		$user=M('User')->where($where)->find();
		session('name',$user['username']);
		$this->name=$_SESSION['name'];


		/* if(empty($_GET)){
			
		}else{
			$code=$_GET['code'];
			$qq=A('Login');
			$qq->callback('qq',$code);
			
			
		} */
	/* if(is_array($token)){
		import("ORG.ThinkSDK.ThinkOauth"); //导入SDK基类
		$qq   = ThinkOauth::getInstance('qq', $token); //实例化腾讯QQ开放平台对象 $token 参数为授权成功后获取到的 $token
		$data = $qq->call('user/get_user_info'); //调用接口 
		

	} */

		
	}
	

}
?>