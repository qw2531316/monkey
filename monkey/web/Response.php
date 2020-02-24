<?php
/**
 * Created by PhpStorm.
 * User: Lzw
 * Date: 2020/2/7
 * Time: 22:39
 * Use : 返回请求结果
 */

namespace monkey\web;

use Monkey;
use monkey\exception\HttpAlreadySentException;

class Response extends \monkey\base\Response
{
    /**
     * @var string 响应内容
     */
    public $content;

    /**
     * @var string 请求页面时通信协议的名称和版本
     */
    public $version;

    /**
     * @var string 响应内容字符集
     */
    public $charset;

    /**
     * @var bool 是否发送内容
     */
    public $isSend = false;

    /**
     * @var int 当前响应状态码
     */
    private $statusCode = 200;

    /**
     * @var string 当前响应状态信息
     */
    private $statusText = 'OK';

    /**
     * @var HeaderCollection
     */
    private $headers;

    private $cookies;

    /**
     * @var array 响应状态码集
     */
    public static $htmlStatusCode = [
        200 => 'OK',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        403 => 'Forbidden',
        404 => 'Not Found',
        500 => 'Internal Server Error',
        502 => 'Bad Gateway or Proxy Error',
        503 => 'Service Unavailable',
    ];

    /**
     * 初始化Response
     */
    public function init()
    {
        if ($this->version === null) {
            if (isset($_SERVER['SERVER_PROTOCOL']) && $_SERVER['SERVER_PROTOCOL'] === 'HTTP/1.0') {
                $this->version = '1.0';
            } else {
                $this->version = '1.1';
            }
        }
        if($this->charset === null){
            $this->charset = Monkey::$app->charset;
        }
    }

    /**
     * 获取响应状态码
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * 设置响应状态码
     * @param int $code
     * @param string|null $text
     * @return $this
     */
    public function setStatusCode(int $code,string $text = null)
    {
        if(empty($code)){
            $code = 200;
        }
        $this->statusCode = (int)$code;
        if(empty($text)){
            $this->statusText = static::$htmlStatusCode[$this->statusCode] ?? '';
        }else{
            $this->statusText = $text;
        }
        return $this;
    }

    /**
     * @return HeaderCollection
     */
    public function getHeaders()
    {
        if($this->headers === null){
            $this->headers = new HeaderCollection();
        }

        return $this->headers;
    }

    /**
     * 发送响应头
     * @throws HttpAlreadySentException
     */
    public function sendHeaders()
    {
        // 头文件已发送抛出异常
        if(headers_sent($file,$line)){
            throw new HttpAlreadySentException($file,$line);
        }
        if($this->headers){
            foreach ($this->headers as $name => $values){
                $name = str_replace(' ','-',ucwords(str_replace('-',' ',$name)));
                $allowMultiple = true;
                foreach ($values as $value){
                    header("$name: $value",$allowMultiple);
                    $allowMultiple = false;
                }
            }
        }
        $statusCode = $this->getStatusCode();
        header("HTTP/{$this->version} $statusCode {$this->statusText}");
        $this->sendCookies();
    }

    /**
     * 发送cookies
     */
    protected function sendCookies()
    {

    }

    /**
     * 发送内容
     */
    public function sendContent()
    {
        echo $this->content;
        return ;
    }

    /**
     * 发送响应内容到客户端
     * @throws HttpAlreadySentException
     */
    public function send()
    {
        if($this->isSend){
            return ;
        }
        $this->sendHeaders();
        $this->sendContent();
        $this->isSend = true;
        exit;
    }
}