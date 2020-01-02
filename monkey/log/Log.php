<?php
/**
 * Created by PhpStorm.
 * User: Lzw
 * Date: 2019/12/25
 * Time: 19:35
 * Use : 日志处理
 */

namespace monkey\log;


class Log implements LogInterface
{
    use LogBuilder;

    /**
     * @var Log
     */
    private static $instance;

    private function __construct(){}

    /**
     * 单例
     * @param array $config
     * @return Log
     */
    public static function getInstance(array $config)
    {
        if(is_null(self::$instance) || !self::$instance instanceof Log){
            self::$instance = new Log();
            // 加载配置
            self::config($config);
        }
        return self::$instance;
    }

    /**
     * 记录日志
     * @param string $content
     * @param string $levelType
     * @return void
     */
    public function info(string $content,string $levelType = LogInterface::INFO_TYPE)
    {
        self::writeLog($content,$levelType);
    }

    /**
     * 记录警告错误等级日志
     * @param string $content
     * @param string $levelType
     * @return void
     */
    public function warning(string $content,string $levelType = LogInterface::WARNING_TYPE)
    {
        self::writeLog($content,$levelType);
    }

    /**
     * 记录致命错误日志
     * @param string $content
     * @param string $levelType
     * @return void
     */
    public function error(string $content,string $levelType = LogInterface::ERROR_TYPE)
    {
        self::writeLog($content,$levelType);
    }

    /**
     * 记录SQL执行语句
     * @param string $content
     * @param string $levelType
     * @return void
     */
    public function sqlLog(string $content, string $levelType = LogInterface::SQL_TYPE)
    {
        self::writeLog($content,$levelType);
    }
}