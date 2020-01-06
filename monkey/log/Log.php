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
        self::config($config);
        parent::__construct($config);
    }

    /**
     * 记录日志
     * @param string $content
     * @return void
     */
    public static function info(string $content)
    {
        self::write($content,LogInterface::INFO_TYPE);
    }

    /**
     * 记录警告错误等级日志
     * @param string $content
     * @return void
     */
    public static function warning(string $content)
    {
        self::write($content,LogInterface::WARNING_TYPE);
    }

    /**
     * 记录致命错误日志
     * @param string $content
     * @return void
     */
    public static function error(string $content)
    {
        self::write($content,LogInterface::ERROR_TYPE);
    }

    /**
     * 记录SQL执行语句
     * @param string $content
     * @return void
     */
    public static function sqlLog(string $content)
    {
        self::write($content,LogInterface::SQL_TYPE);
    }

    /**
     * 记录日志
     * @param string $content
     * @param string $levelType
     * @return mixed|void
     */
    public static function write(string $content, string $levelType)
    {
        self::writeLog($content,$levelType);
    }
}