<?php
/**
 * Created by PhpStorm.
 * User: Lzw
 * Date: 2020/1/3
 * Time: 20:27
 */

namespace monkey\web;

class Application extends \monkey\base\Application
{
    /**
     * @var Controller 控制器基类
     */
    public $controller;

    /**
     * @var string 主页Url
     */
    private $_homeUrl;

    /**
     * @return string 返回主页URL
     * @throws \Exception
     */
    public function getHomeUrl()
    {
        if($this->_homeUrl === null){
            $this->_homeUrl = $this->getRequest()->getBaseUrl() . DIRECTORY_SEPARATOR;
        }
        return $this->_homeUrl;
    }

    /**
     * @param string $value 设置主页URL
     */
    public function setHomeUrl(string $value)
    {
        $this->_homeUrl = $value;
    }

    /**
     * 处理请求
     * @param $request Request
     * @return Response
     * @throws \Exception
     */
    public function handleRequest($request)
    {
        $result = $request->resolve();
        $response = $this->getResponse();
        if($result !== null){
            $response->content = $result;
        }
        return $response;
    }

    public function components()
    {
        return array_merge(parent::components(),[
            'request' => ['class' => '\monkey\web\Request'],
            'response' => ['class' => '\monkey\web\Response'],
            'rule' => ['class' => '\monkey\url\GenerateRule'],
            'view' => ['class' => '\monkey\web\View'],
        ]);
    }
}