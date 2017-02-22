<?php
class CommonAction extends Action {
	public function head(){
/*网站标题*/	 		$this->assign('img_name',C('img_name'));
/*网站关键字*/			$this->assign('keywords',C('img_keywords'));
/*网站关键字*/			$this->assign('description',C('img_description'));
		$menu=M('ImgCategory');
		$cate=$menu->order('sort asc')->select();
		$category=Category::unlimitedForLayer($cate);

		$this->assign('cate',$category);
	}
}
?>