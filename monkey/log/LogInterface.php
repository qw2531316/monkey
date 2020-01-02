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
    // 错误等级
    const INFO_TYPE = 'info';
    const WARNING_TYPE = 'warning';
    const ERROR_TYPE = 'error';
    const SQL_TYPE = 'sql';

    /**
     * 记录日志
     * @param string $content
     * @param string $levelType
     * @return void
     */
    public function info(string $content,string $levelType = LogInterface::INFO_TYPE);

    /**
     * 记录警告错误等级日志
     * @param string $content
     * @param string $levelType
     * @return void
     */
    public function warning(string $content,string $levelType = LogInterface::WARNING_TYPE);

    /**
     * 记录致命错误日志
     * @param string $content
     * @param string $levelType
     * @return void
     */
    public function error(string $content,string $levelType = LogInterface::ERROR_TYPE);

    /**
     * 记录SQL执行语句
     * @param string $content
     * @param string $levelType
     * @return void
     */
    public function sqlLog(string $content,string $levelType = LogInterface::SQL_TYPE);
}