<?php
/**
 * Created by PhpStorm.
 * User: Lzw
 * Date: 2019/12/25
 * Time: 19:47
 * Use : 日志处理接口
 */

namespace monkey\log;


interface LogInterface
{
    // 日志等级
    const INFO_TYPE = 'info';
    const WARNING_TYPE = 'warning';
    const ERROR_TYPE = 'error';
    const SQL_TYPE = 'sql';

    /**
     * 记录日志
     * @param string $content
     * @return void
     */
    public static function info(string $content);

    /**
     * 记录警告错误等级日志
     * @param string $content
     * @return void
     */
    public static function warning(string $content);

    /**
     * 记录致命错误日志
     * @param string $content
     * @return void
     */
    public static function error(string $content);

    /**
     * 记录SQL执行语句
     * @param string $content
     * @return void
     */
    public static function sqlLog(string $content);

    /**
     * 记录日志
     * @param string $content
     * @param string $levelType
     * @return mixed
     */
    public static function write(string $content,string $levelType);
}