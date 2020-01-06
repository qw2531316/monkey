<?php
/**
 * Created by PhpStorm.
 * User: Lzw
 * Date: 2020/1/3
 * Time: 14:43
 */

namespace monkey;

use monkey\base\Application;
use monkey\base\ObjectMonkey;
use monkey\di\Container;

defined('MONKEY_PATH') or define('MONKEY_PATH',__DIR__ . DIRECTORY_SEPARATOR);

class BaseMonkey
{
    /**
     * 应用
     * @var Application|\monkey\web\Application
     */
    public static $app;

    /**
     * DI容器
     * @var Container
     */
    public static $container;

    /**
     * 类映射表
     * @var array
     */
    public static $classes = [];


    /**
     * 配置属性对象
     * @param ObjectMonkey $object
     * @param array $config
     * @return ObjectMonkey
     */
    public static function config(ObjectMonkey $object,array $config)
    {
        foreach ($config as $name => $value){
            $object->$name = $value;
        }
        return $object;
    }

    /**
     * 创建对象
     * @param mixed $mixed 组件/服务 定义
     * @param array $params
     * @return object
     * @throws \Exception
     */
    public static function createObject($mixed,array $params = [])
    {
        if(is_string($mixed)){
            return self::$container->get($mixed,$params);
        }
        if(is_callable($mixed,true)){
            return call_user_func($mixed,$params);
        }
        if(is_array($mixed) && isset($mixed['class'])){
            $className = $mixed['class'];
            unset($mixed['class']);
            return self::$container->get($className,$params,$mixed);
        }else if(!isset($mixed['class'])){
            throw new \Exception("组件配置必须包含 【class】 元素");
        }
        throw new \Exception("未知的组件配置【" . gettype($mixed) . "】");
    }
}