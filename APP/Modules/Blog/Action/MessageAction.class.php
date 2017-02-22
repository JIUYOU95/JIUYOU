<?php
class MessageAction extends CommonAction {
	 public function index(){
	 	$this->head();
	 	$message=D('BlogFeedback');
	 	if(IS_POST){
	 		if($message->create()){
	 			$message->add();
	 			$this->success('留言成功',U(GROUP_NAME.'/Message/index'));
	 		}
	 	}else{
	 		$this->display();
	 	}
	 	
	 }
}
?>