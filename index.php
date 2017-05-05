<?php
// 应用入口文件
header("Content-type: text/html; charset=utf-8");

// 检测PHP环境
//if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

// PHP报错设置
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

// 环境变量
if ($_SERVER['RUN_ENV'] && $_SERVER['RUN_ENV']=='DEV') {
    $_env = 'dev';
} else {
    $_env = 'pro';
}
define("ENV",$_env);

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',True);

//define('BIND_MODULE','Admin');
//define('BUILD_CONTROLLER_LIST', 'Login');


// 目录安全文件
define('DIR_SECURE_FILENAME', 'default.html');

// 定义应用目录
define('APP_PATH','./Application/');

// 引入ThinkPHP入口文件
require './ThinkPHP/ThinkPHP.php';

// 亲^_^ 后面不需要任何代码了 就是如此简单