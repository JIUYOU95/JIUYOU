<?php
/*
 *Admin后台首页控制器 
 */
class IndexAction extends CommonAction {
	//后台首页
    public function index(){
        $this->assign('title',C('cfg_name'));
		$this->display();
    }
	public function top(){
		$this->nickname=$_SESSION["nickname"];
		$this->display();
	}
	public function main(){
		//获取服务器信息
        $sysdata['sysos'] = $_SERVER["SERVER_SOFTWARE"]; //获取服务器标识的字串
        $sysdata['sysversion'] = PHP_VERSION; //获取PHP服务器版本
        //以下两条代码连接MySQL数据库并获取MySQL数据库版本信息
        /*  mysql_connect("localhost", "mysql_user", "mysql_pass");
        $mysqlinfo = mysql_get_server_info();*/
        //从服务器中获取GD库的信息
        if(function_exists("gd_info")){ 
        $gd = gd_info();
        $sysdata['gdinfo'] = $gd['GD Version'];
        }else {
        $sysdata['gdinfo'] = "未知";
        }
        //从GD库中查看是否支持FreeType字体
        $sysdata['freetype'] = $gd["FreeType Support"] ? "支持" : "不支持";
        //从PHP配置文件中获得是否可以远程文件获取
        $sysdata['allowurl']= ini_get("allow_url_fopen") ? "支持" : "不支持";
        //从PHP配置文件中获得最大上传限制
        $sysdata['max_upload'] = ini_get("file_uploads") ? ini_get("upload_max_filesize") : "Disabled";
        //从PHP配置文件中获得脚本的最大执行时间
        $sysdata['max_ex_time']= ini_get("max_execution_time")."秒";
        //以下两条获取服务器时间，中国大陆采用的是东八区的时间,设置时区写成Etc/GMT-8
        date_default_timezone_set("Etc/GMT-8");
        $sysdata['systemtime'] = date("Y-m-d H:i:s",time()); 
		$sysdata['sysip']=get_client_ip();
        $this->assign('sysdata',$sysdata);
		$this->display();      
	}
	//后台登出
	public function loginout(){
 		session_unset();
		session_destroy();
 		$this->redirect('Login/index');
 	}
}