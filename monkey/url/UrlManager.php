<?php
/**
 * Created by PhpStorm.
 * User: Lzw
 * Date: 2019/12/25
 * Time: 11:26
 * Use : 路由管理器
 */

namespace monkey\url;

use Monkey;
use monkey\base\Component;
use monkey\web\Request;

class UrlManager extends Component
{
    /**
     * @var GenerateRule
     */
    private $rule;

    // 是否管理url，默认false
    public $enableManagerUrl = false;

    // 路由规则
    public $rules = [];

    /**
     * controller->action 路由
     * @var string
     */
    public $route;

    // 路由后缀，仅 $enableManagerUrl = true 时生效
    public $suffix;

    // 默认
    public $defaultController;
    public $defaultAction;

    public $notFoundMessage = '404 Nou Found';

    /**
     * UrlManager constructor.
     * @param GenerateRule $rule
     * @param array $config
     */
    public function __construct(GenerateRule $rule, array $config)
    {
        $this->init();
        $this->rule = $rule;
        parent::__construct($config);
    }

    /**
     * 解析请求
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function parseRequest(Request $request)
    {
        if($this->enableManagerUrl){
            // 参数容器
            $params = [];
            // Uri路径
            $pathInfo = $request->getPathInfo();
            $matchRoute = '';
            if(!empty($pathInfo)){
                foreach ($this->rules as $rule => $route){
                    // 处理前 rule === pathInfo，应用该路由
                    if($rule === $pathInfo){
                        $matchRoute = $route;
                        break;
                    }else{
                        // 处理带参数的 rule
                        if (preg_match_all('/<(\w+):?([^>]+)?>/', $rule, $param)) {
                            $key = 0;
                            while (isset($param[1][$key])) {
                                $rule = preg_replace('/<' . $param[1][$key] . ':?([^>]+)?>/', $param[2][$key], $rule);
                                // 获取实际参数
                                if (preg_match_all('/(' . $param[2][$key] . ')/', $pathInfo, $actualParam)) {
                                    $params[$param[1][$key]] = $actualParam[1][0];
                                    // 替换 pathInfo 参数值为 正则表达式
                                    $pathInfo = str_replace($actualParam[1][0], $param[2][$key], $pathInfo);
                                }
                                $key++;
                            }
                        }
                        // 处理后的 rule === pathInfo，应用该路由
                        if($rule === $pathInfo){
                            $matchRoute = $route;
                            break;
                        }
                    }
                }
            }
            // 合并参数
            $_GET = array_merge($_GET,$params);
            // 创建路径
            list($className,$action) = $this->rule->createUrl($this,$matchRoute);
            $object = Monkey::createObject($className);
            return $object->$action();
        }
        return null;
    }
}