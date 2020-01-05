<?php
/**
 * Created by PhpStorm.
 * User: Lzw
 * Date: 2020/1/3
 * Time: 11:08
 * Use : 模块跟应用的基类
 */

namespace monkey\base;

require ROOT_PATH . 'monkey/di/ServiceLocator.php';

use Monkey;
use monkey\di\ServiceLocator;

class Module extends ServiceLocator
{
    public static function getInstance()
    {
        $class = get_called_class();
        return isset(Monkey::$app->loadedModules[$class]) ? Monkey::$app->loadedModules[$class] : null;
    }

    public static function setInstance(Module $instance)
    {
        if($instance === null){
            unset(Monkey::$app->loadedModules[get_called_class()]);
        }else{
            Monkey::$app->loadedModules[get_class($instance)] = $instance;
        }
    }
}