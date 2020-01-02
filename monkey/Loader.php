<?php
/**
 * Created by PhpStorm.
 * User: Lzw
 * Date: 2019/12/25
 * Time: 1:46
 * Use : 自动加载
 */

namespace monkey;

class Loader
{
    private static $_instance = null;

    private $loadMap = [];

    private function __construct(array $map)
    {
        $this->loadMap = $map;
        spl_autoload_register([$this,'autoLoad']);
    }

    public static function getInstance($map)
    {
        if(is_null(self::$_instance) || !self::$_instance instanceof Loader){
            self::$_instance = new Loader($map);
        }
        return self::$_instance;
    }

    /**
     * 自动加载
     * @param $class string 类名
     */
    private function autoLoad($class)
    {
        $file = $this->findFile($class);
        if(file_exists($file)){
            $this->loadFile($file);
        }
    }

    /**
     * 解析类名查找文件路径
     * @param $class string 类名
     * @return string 文件路径
     */
    private function findFile($class)
    {
        // 顶级命名空间
        $firstName = substr($class,0,strpos($class,'\\'));
        // 文件根目录
        $fileBasePath = $this->loadMap[$firstName];
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
    private function loadFile($file)
    {
        include_once $file;
    }
}