<?php

// change the following paths if necessary
$bee=dirname(__FILE__).'/framework/bee.php';
$config=dirname(__FILE__).'/config/main.php';

// remove the following lines when in production mode
defined('BEE_DEBUG') or define('BEE_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('BEE_TRACE_LEVEL') or define('BEE_TRACE_LEVEL',3);
//开发模式
define('BEE_MODE','');
require_once($bee);
require_once(dirname(__FILE__).'/config/define.php');
if(BEE_MODE=='live'){
    require_once(dirname(__FILE__).'/config/liveConfig.php');
}else{
    require_once(dirname(__FILE__).'/config/localConfig.php');
}
Bee::createWebApplication($config)->run();
