<?php
/**
 * Created by PhpStorm.
 * User: Lzw
 * Date: 2019/12/25
 * Time: 1:12
 * Use : 框架功能类
 */

require __DIR__ . '/BaseMonkey.php';

class Monkey extends \monkey\BaseMonkey
{
}

spl_autoload_register(['monkey\base\Loader','autoLoad']);
Monkey::$classes = include_once 'classes.php';
Monkey::$container = new \monkey\di\Container();