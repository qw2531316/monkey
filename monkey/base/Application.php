<?php
/**
 * Created by PhpStorm.
 * User: Lzw
 * Date: 2020/1/3
 * Time: 11:07
 * Use : 应用程序基类
 */

namespace monkey\base;

use Monkey;
use monkey\url\UrlManager;
use monkey\web\Request;
use monkey\web\Response;
use monkey\web\View;

/**
 * 应用程序基类
 *
 * @property monkey\db\DbQuery $db
 * @property \monkey\web\Request $request
 *
 * @package monkey\base
 */
abstract class Application extends Module
{
    /**
     * 已加载的模块
     * @var array
     */
    public $loadedModules = [];

    /**
     * 记录响应流程状态
     * @var string
     */
    private $state;

    /**
     * 开始发送响应
     */
    const STATE_BEGIN = 0;
    /**
     * 发送响应中
     */
    const STATE_SENDING_RESPONSE = 1;
    /**
     * 响应结束状态
     */
    const STATE_END = 2;
    /**
     * @var string 当前应用的字符集
     */
    public $charset = 'UTF-8';

    public function __construct(array $config)
    {
        Monkey::$app = $this;
        Monkey::$app->loadedModules[get_class($this)] = $this;
        //static::setInstance($this);

        // 合并默认组件
        $config['components'] = array_merge($config['components'],$this->components());
        Component::__construct($config);
    }

    public function run()
    {
        try{
            // 解析请求 并 响应内容
            $this->state = self::STATE_BEGIN;
            $response = $this->handleRequest($this->getRequest());
            $this->state = self::STATE_SENDING_RESPONSE;
            $response->send();
            $this->state = self::STATE_END;

            return $response->getStatusCode();
        }catch (\Exception $e){
            Monkey::error('服务器内部错误【' . $e->getMessage() . '】');
            return 0;
        }
    }

    /**
     * 处理请求
     * @param $request Request
     * @return Response
     * @throws \Exception
     */
    abstract public function handleRequest($request);

    /**
     * 获取URLManager对象
     * @return callable|UrlManager|object
     * @throws \Exception
     */
    public function getUrlManager()
    {
        return $this->get('urlManager');
    }

    /**
     * 获取Request对象
     * @return callable|object|Request
     * @throws \Exception
     */
    public function getRequest()
    {
        return $this->get('request');
    }

    /**
     * 获取Response对象
     * @return callable|object|Response
     * @throws \Exception
     */
    public function getResponse()
    {
        return $this->get('response');
    }

    /**
     * View对象
     * @return callable|object|View
     * @throws \Exception
     */
    public function getView()
    {
        return $this->get('view');
    }

    /**
     * 默认组件
     * @return array
     */
    protected function components()
    {
        return [];
    }
}