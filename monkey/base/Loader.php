<?php
/**
 * Created by PhpStorm.
 * User: Lzw
 * Date: 2019/12/25
 * Time: 1:46
 * Use : 自动加载
 */

namespace monkey\base;

use Monkey;
use monkey\log\Log;

class Loader
{
    private function __construct(){}

    /**
     * 自动加载
     * @param $class string 类名
     */
    public static function autoLoad($class)
    {
        if(isset(Monkey::$classes[$class])){
            $file = Monkey::$classes[$class];
        }else {
            $file = self::findFile($class);
        }
        if(file_exists($file)){
            self::loadFile($file);
        }
    }

    /**
     * 解析类名查找文件路径
     * @param $class string 类名
     * @return string 文件路径
     */
    private static function findFile($class)
    {
        // 顶级命名空间
        $firstName = substr($class,0,strpos($class,'\\'));
        // 文件根目录
        $fileBasePath = ROOT_PATH . $firstName;
        if(empty($fileBasePath)){
            Monkey::$app->log->error($class . " namespace is wrong");
        }
        // 文件相对路径
        $filePath = substr($class,strlen($firstName)) . '.php';
        // 文件绝对路径
        $fileAbsolutePath = str_replace("\\",DIRECTORY_SEPARATOR,$fileBasePath . $filePath);

        return $fileAbsolutePath;
    }

    /**
     * 包含文件
     * @param $file string 文件路径
     */
    private static function loadFile($file)
    {
        include_once $file;
    }
}