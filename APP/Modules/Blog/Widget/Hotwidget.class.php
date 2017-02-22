<?php
Class HotWidget extends Widget{
	Public function render($data){
		$field=array('id','title');
		$blog=M('BlogBlog')->field($field)->order('time asc')->limit(5)->select();
		return $this->renderFile('',$data);
	}
}

?>