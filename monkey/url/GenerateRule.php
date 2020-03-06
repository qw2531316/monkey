<?php
/**
 * Created by PhpStorm.
 * User: Lzw
 * Date: 2020/1/8
 * Time: 20:06
 * Use : 生成 controller->action 路由
 */

namespace monkey\url;

use Monkey;
use monkey\base\ObjectMonkey;

class GenerateRule extends ObjectMonkey implements RuleInterface
{

    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    /**
     * 初始化路由
     * @throws \Exception
     */
    public function init()
    {
        parent::init();
    }

    /**
     * 创建路由
     * @param UrlManager $manager
     * @param string $route
     * @return array
     */
    public function createUrl(UrlManager $manager,string $route)
    {
        if(!empty($route)) {
            $route = explode('/', $route);
            $classPath = 'controller\\' . $route[0] . '\\' . ucfirst($route[1]);
            // 处理action
            if (isset($route[2])) {
                $actionMap = explode('-', $route[2]);
                $action = implode(array_map(function ($action) {
                    return ucfirst($action);
                }, $actionMap));
            } else {
                $action = $manager->defaultAction;
            }
        }else{
            // 默认首页
            $classPath = $manager->defaultController;
            $action = $manager->defaultAction;
        }

        return [$classPath . 'Controller' , 'action' . ucfirst($action)];
    }
}