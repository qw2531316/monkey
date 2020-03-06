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
    /**
     * @var string 模块根目录路径
     */
    private $moduleBasePath;

    /**
     * @var string 默认视图根目录
     */
    private $defaultViewPath = 'view';

    /**
     * @var string 默认视图布局文件名
     */
    private $layout = 'layout';

    /**
     * @var string 视图文件路径
     */
    private $viewPath;

    public static function getInstance()
    {
        $class = get_called_class();
        return isset(Monkey::$app->loadedModules[$class]) ? Monkey::$app->loadedModules[$class] : null;
    }

    public static function setInstance(Module $instance)
    {
        Monkey::$app->loadedModules[get_class($instance)] = $instance;
    }

    public function getModuleBasePath()
    {
        if($this->moduleBasePath === null){
            $class = new \ReflectionClass($this);
            $this->moduleBasePath = dirname($class->getFileName());
        }
        return $this->moduleBasePath;
    }

    public function getViewPath()
    {
        if($this->viewPath === null){
            $this->viewPath = $this->getModuleBasePath() . DIRECTORY_SEPARATOR . $this->defaultViewPath;
        }
        return $this->viewPath;
    }

    /**
     * @return string
     */
    public function getDefaultViewPath()
    {
        return $this->defaultViewPath;
    }

    /**
     * @return string
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * @param string $defaultViewPath
     */
    public function setDefaultViewPath(string $defaultViewPath)
    {
        $this->defaultViewPath = $defaultViewPath;
    }

    /**
     * @param string $layout
     */
    public function setLayout(string $layout)
    {
        $this->layout = $layout;
    }
}