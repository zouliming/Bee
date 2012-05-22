<?php 

#================================================================
#===EasyTemplate 对象维持
function tt(){
	static $tt;
	if(!$tt){
		$t = new EasyTemplate();
	}
	return $tt;
}

#================================================================
#===EasyDBAccess 对象维持
function dba(){
	static $dba;
	if(!$dba){
		global $db_conf;
		$dba = new EasyDBAccess($db_conf);
	}
	return $dba;
}

#================================================================
#===Validator 对象维持
function va(){
	static $va;
	if(!$va){
		$va = new Validator();
	}
	return $va;
}

#================================================================
#===输出页面
function view($vars=array(), $tmpl_name=NULL, $layout=NULL){
	$tt = new EasyTemplate($vars);
	if($tmpl_name){
		$tt->set_tmpl($tmpl_name);
	}
	if($layout){
		$tt->set_layout($layout);
	}
	return $tt->process();
}

#================================================================
#===是否需要使用session
if(!(defined("NO_SESSION") && NO_SESSION) && key_exists("SERVER_NAME",$_SERVER)){
	session_start();
}

?>