<?php
/**
 * Created by PhpStorm.
 * User: Lzw
 * Date: 2019/12/25
 * Time: 19:35
 * Use : 日志处理
 */

namespace monkey\log;

use monkey\base\Component;

class Log extends Component implements LogInterface
{
    use LogBuilder;

    public function __construct(array $config)
    {
        // 加载配置
        $this->config($config);
        parent::__construct($config);
    }

    /**
     * 记录日志
     * @param string $content
     * @return void
     */
    public function info(string $content)
    {
        $this->write($content,LogInterface::INFO_TYPE);
    }

    /**
     * 记录警告错误等级日志
     * @param string $content
     * @return void
     */
    public function warning(string $content)
    {
        $this->write($content,LogInterface::WARNING_TYPE);
    }

    /**
     * 记录致命错误日志
     * @param string $content
     * @return void
     */
    public function error(string $content)
    {
        $this->write($content,LogInterface::ERROR_TYPE);
    }

    /**
     * 记录SQL执行语句
     * @param string $content
     * @return void
     */
    public function sqlLog(string $content)
    {
        $this->write($content,LogInterface::SQL_TYPE);
    }

    /**
     * 记录日志
     * @param string $content
     * @param string $levelType
     * @return mixed|void
     */
    public function write(string $content, string $levelType)
    {
        $this->writeLog($content,$levelType);
    }
}