<?php
/**
 * Created by PhpStorm.
 * User: Lzw
 * Date: 2019/12/24
 * Time: 20:23
 * Use : 入口文件
 */

defined('MONKEY_ENVIRONMENT') or define('MONKEY_ENVIRONMENT','test');
defined('MONKEY_DEBUG') or define('MONKEY_DEBUG',true);
define('ROOT_PATH',__DIR__ . DIRECTORY_SEPARATOR );
error_reporting(-1);

// 自动加载
require(ROOT_PATH . 'monkey/base/Loader.php');
require(ROOT_PATH . 'monkey/Monkey.php');
require(ROOT_PATH . 'monkey/web/Application.php');

$config = require (ROOT_PATH . 'config/main.php');

$monkey = new \monkey\web\Application($config);
$statusCode = $monkey->run();
exit($statusCode);