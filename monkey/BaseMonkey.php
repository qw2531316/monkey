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
use monkey\log\LogInterface;

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
     * @var array 应用组件配置
     */
    private static $config;
    /**
     * @var array 应用参数配置
     */
    private static $paramsConfig;


    /**
     * 配置属性对象
     * @param ObjectMonkey $object
     * @param array $config
     * @return ObjectMonkey
     */
    public static function config(ObjectMonkey $object,array $config)
    {
        if(self::$config === null){
            self::$config = $config['components'];
        }
        if(self::$paramsConfig === null){
            self::$paramsConfig = $config['params'];
        }
        foreach ($config as $name => $value){
            $object->$name = $value;
        }
        return $object;
    }

    public static function getConfig(string $key = null)
    {
        if($key === null){
            return self::$config;
        }
        return self::$config[$key];
    }

    public static function getParamsConfig(string $key = null)
    {
        if($key === null){
            return self::$paramsConfig;
        }
        return self::$paramsConfig[$key];
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

    // ----- 日志 begin
    /**
     * @var LogInterface
     */
    private static $log;
    /**
     * @return LogInterface
     */
    public static function getLog()
    {
        if(self::$log === null){
            self::$log = self::createObject('monkey\log\Log',self::$config['log']);
        }
        return self::$log;
    }

    public static function write(string $content, string $level)
    {
        static::getLog()->write($content,$level);
    }

    public static function error(string $content)
    {
        static::getLog()->error($content);
    }

    public static function info(string $content)
    {
        static::getLog()->info($content);
    }

    public static function sqlLog(string $content)
    {
        static::getLog()->sqlLog($content);
    }

    public static function warning(string $content){
        static::getLog()->warning($content);
    }
    // ---- 日志 end

    /**
     * @return string 返回应用根目录
     */
    public static function getBasePath()
    {
        return ROOT_PATH;
    }
}