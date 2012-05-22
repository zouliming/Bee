<?php
define("UTF8_CHARSET","utf-8");
define("SEP","/");
define('ROOT',dirname(__FILE__).SEP.'..'.SEP);
define("SERVER_URL","http://".$_SERVER['HTTP_HOST'].SEP);
define('JS_URL',SERVER_URL."js".SEP);
define('CSS_URL',SERVER_URL."css".SEP);
define('CSS_IMAGE_URL',SERVER_URL."img".SEP);

date_default_timezone_set("Asia/Shanghai");

#页面默认字符集可选择 ASCII_CHARSET (gbk) 或者 UTF8_CHARSET (utf8)
define("OUTPUT_CHARSET",UTF8_CHARSET);

$conf=array();

function conf($key=false){
	global $conf;
	if($key===false){
		return $conf;
	}else{
		return @$conf[$key];
	}
}
