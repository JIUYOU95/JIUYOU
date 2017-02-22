<?php
/*
 *自定义标签库
 */
Class TagLibHd extends TagLib{
	Protected $tags=array(
		'nav'=>array('attr'=>'limit,order','close'=>1),
		'hot'=>array('attr'=>'limit','close'=>1),
		'archiving'=>array('attr'=>'limit','close'=>1),
		'menu'=>array('attr'=>'sort','close'=>1),
		'tag'=>array('attr'=>'limit','close'=>1),
		);

	//头部栏目
	Public function _nav($attr,$content){
		$attr=$this->parseXmlAttr($attr);
		$str=<<<str
<?php
	\$_nav_cate=D('BlogCategory')->order("{$attr['order']}")->where(array('isshow'=>1))->select();
	import('Common.Category',APP_PATH);
	\$_nav_cate=Category::unlimitedForLayer(\$_nav_cate);
	foreach(\$_nav_cate as \$_nav_cate_v) :
		extract(\$_nav_cate_v);
		\$url=U('/nav'.\$id);
?>
str;
		$str.=$content;
		$str.='<?php endforeach ?>';
		return $str;
	}
	//最新文章
	Public function _hot($attr,$content){
		$attr=$this->parseXmlAttr($attr);
		$limit=$attr['limit'];
		$str='<?php ';
		$str.='$field=array("id","title","time");';
		$str.='$_hot_blog=M("BlogBlog")->field($field)->limit('.$limit.')->where(array("del"=>0,"status"=>1))->order("time desc")->select();';
		$str.='foreach($_hot_blog as $_hot_value):';
		$str.='extract($_hot_value);';
		$str.='$url=U("/Article/".$id);?>';
		$str.=$content;
		$str.='<?php endforeach; ?>';
		return $str;
	}
	//时间归档
	Public function _archiving($attr,$content){
		$attr=$this->parseXmlAttr($attr);
		$str=<<<str
<?php
	\$_time_archive=M()->query("select count(id) as num,FROM_UNIXTIME(time,'%Y年%m月') as t,FROM_UNIXTIME(time,'%Y/%m') as t1  from tpl_blog_blog where(status=1 and del=0) group by t order by t desc");
	foreach(\$_time_archive as \$_time_archive_v) :
		extract(\$_time_archive_v);
		\$url=U('/Data/'.\$t1);
?>
str;
		$str.=$content;
		$str.='<?php endforeach ?>';
		return $str;

	}
	//栏目归档
	Public function _menu($attr,$content){
		$attr=$this->parseXmlAttr($attr);
		$str=<<<str
<?php
	\$_menu_blog=M()->query("select count(a.typeid) as num,c.typename,c.id from tpl_blog_blog a,tpl_blog_category c where a.typeid=c.id AND c.isshow=1 AND a.del=0 AND a.status=1 group by c.typename order by c.id asc");
	foreach(\$_menu_blog as \$_menu_value) :
		extract(\$_menu_value);
		\$url=U("/nav".\$id);
?>
str;
		$str.=$content;
		$str.='<?php endforeach ?>';
		return $str;
	}
	//tag
	Public function _tag($attr,$content){
		$attr=$this->parseXmlAttr($attr);
		$limit=$attr['limit'];
		$str='<?php ';
		$str.='$field=array("tag","id");';
		$str.='$_tag_blog=M("BlogBlog")->group("tag")->field($field)->limit('.$limit.')->where(array("del"=>0,"status"=>1))->order("time desc")->Distinct(true)->select();';
		$str.='foreach($_tag_blog as $_tag_value):';
		$str.='extract($_tag_value);';
		$str.='$url=U("/tags/".$tag);?>';
		$str.=$content;
		$str.='<?php endforeach; ?>';
		return $str;
	}
}
?>