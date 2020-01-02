<?php
/**
 * Created by PhpStorm.
 * User: Lzw
 * Date: 2019/12/25
 * Time: 1:12
 * Use : 执行程序入口
 */

namespace monkey;

use monkey\db\builder\QueryBuilder;
use monkey\log\Log;
use monkey\db\DbQuery;

class Monkey
{
    public static $app;

    public $main;
    public $params;
    public $dbConfig;
    /**
     * @var Log
     */
    public $log;
    /**
     * @var QueryBuilder
     */
    public $db;

    public function __construct(array $config)
    {
        // 配置文件
        $this->params = $config['params'];
        $this->dbConfig = $config['db'];
        $this->main = $config;
        // 自动加载
        Loader::getInstance($this->main['namespaceMap']);
        // 加载日志对象
        $this->log = Log::getInstance($this->main['log']);
        // 加载数据库对象
        $this->db = DbQuery::getInstance($this->dbConfig);

        self::$app = $this;
    }

    /**
     * 执行程序
     */
    public function run()
    {
        // 解析url
        print_r($_SERVER['REQUEST_URI']);die;
    }
}