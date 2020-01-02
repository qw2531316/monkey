<?php
/**
 * Created by PhpStorm.
 * User: Lzw
 * Date: 2019/12/24
 * Time: 20:23
 * Use : 入口文件
 */

defined('MONKEY_DEBUG') or define('MONKEY_DEBUG',true) ;
define('ROOT_PATH',__DIR__ . DIRECTORY_SEPARATOR );

require(ROOT_PATH . 'monkey/Loader.php');
require(ROOT_PATH . 'monkey/Monkey.php');

$config = require (ROOT_PATH . 'config/main.php');

$monkey = new \monkey\Monkey($config);
$monkey->run();