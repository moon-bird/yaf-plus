<?php
define('APPLICATION_PATH', dirname(__FILE__));

$application = new Yaf_Application( APPLICATION_PATH . "/conf/application.ini");

$application->getDispatcher()->setRequest(new Yaf_Request_Simple());

$application->bootstrap()->run();

//调用说明
// php cli.php "request_uri=/index/test&env=common"
// request_uri ：调用信息
// env ：调用环境
// 其他参数...

// 可直接使用request的getParam进行参数的获取
