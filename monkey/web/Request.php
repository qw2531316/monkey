<?php
/**
 * Created by PhpStorm.
 * User: Lzw
 * Date: 2020/1/27
 * Time: 14:29
 * Use : 应用请求
 */

namespace monkey\web;

use Monkey;

class Request extends \monkey\base\Request
{
    public $_pathInfo;

    public $_url;

    public $_baseUrl;

    /**
     * 解析请求
     * @throws \Exception
     */
    public function resolve()
    {
        // 解析请求
        return Monkey::$app->getUrlManager()->parseRequest($this);
    }

    /**
     * 获取路径信息
     * @return string
     * @throws \Exception
     */
    public function getPathInfo()
    {
        if($this->_pathInfo === null){
            $this->_pathInfo = $this->resolvePathInfo();
        }
        return $this->_pathInfo;
    }

    /**
     * 解析路径信息
     * @return string
     * @throws \Exception
     */
    public function resolvePathInfo()
    {
        $pathInfo = $this->getUrl();
        $pos = strpos($pathInfo,'?');
        if($pos !== false){
            $pathInfo = substr($pathInfo,0,$pos);
        }
        $pathInfo = urldecode($pathInfo);

        // 获取当前脚本的URL
        $scriptUrl = $this->getScriptUrl();
        // 获取 base Url
        $baseUrl = $this->getBaseUrl();

        if(strpos($pathInfo,$scriptUrl) === 0){
            $pathInfo = substr($pathInfo,strlen($scriptUrl));
        }else if($baseUrl === '' || strpos($pathInfo,$baseUrl) === 0){
            $pathInfo = substr($pathInfo,strlen($baseUrl));
        }else if(isset($_SERVER['PHP_SELF']) && strpos($_SERVER['PHP_SELF'],$scriptUrl) === 0){
            $pathInfo = substr($_SERVER['PHP_SELF'],strlen($scriptUrl));
        }
        if($pathInfo[0] === '/'){
            $pathInfo = substr($pathInfo,1);
        }
        return (string)$pathInfo;
    }

    /**
     * 获取uri
     * @return null|string|string[]
     * @throws \Exception
     */
    public function getUrl()
    {
        if($this->_url === null){
            $this->_url = $this->resolveRequestUri();
        }
        return $this->_url;
    }

    /**
     * 获取请求 Uri
     * @return null|string|string[]
     * @throws \Exception
     */
    protected function resolveRequestUri()
    {
        if(isset($_SERVER['REQUEST_URI'])){
            $uri = $_SERVER['REQUEST_URI'];
            // URI 开头不为 '/'，则去除前面 http:// | https:// 到第一个 '/' 为止
            if(!empty($uri) && $uri[0] !== '/'){
                $uri = preg_replace("/^(http|https):\/\/[^\/]+/i",'',$uri);
            }
            return $uri;
        }
        Monkey::$app->log->error('无法确认请求 Uri');
        throw new \Exception('无法确认请求 Uri');
    }

    /**
     * 获取脚本文件路径
     */
    public function getScriptUrl()
    {
        $scriptFile = $_SERVER['SCRIPT_FILENAME'];
        $scriptName = basename($scriptFile);

        if(basename($_SERVER['SCRIPT_NAME']) === $scriptName){
            $scriptUrl = $_SERVER['SCRIPT_NAME'];
        }else{
            $scriptUrl = $_SERVER['PHP_SELF'];
        }
        return $scriptUrl;
    }

    /**
     * 获取 base Url
     * @return string
     */
    public function getBaseUrl()
    {
        if($this->_baseUrl === null){
            $this->_baseUrl = rtrim(dirname($this->getScriptUrl()),"\\/");
        }
        return $this->_baseUrl;
    }
}