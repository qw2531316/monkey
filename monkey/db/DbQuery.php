<?php
/**
 * Created by PhpStorm.
 * User: Lzw
 * Date: 2019/12/25
 * Time: 12:26
 * User : DbQuery 入口
 */

namespace monkey\db;

use monkey\Monkey;
use monkey\db\common\LoadDbConfig;
use monkey\db\connect\ConnectionInterface;
use monkey\db\connect\Connection;
use monkey\db\builder\QueryBuilder;

class DbQuery
{
    use LoadDbConfig;

    /**
     * @var ConnectionInterface
     */
    private static $connect;

    /**
     * @var QueryBuilder
     */
    private static $builder;

    private function __construct(){}

    /**
     * 单例
     * @param array $config
     * @return QueryBuilder
     */
    public static function getInstance(array $config)
    {
        if(is_null(self::$builder) || !self::$builder instanceof QueryBuilder){
            try {
                self::config($config);
                self::$builder = new QueryBuilder(self::getQuery());
            } catch (\Exception $e) {
                Monkey::$app->log->error($e->getMessage());
            }
        }
        return self::$builder;
    }

    /**
     * 获取数据库实例
     * @return ConnectionInterface
     * @throws \Exception
     */
    private static function getQuery()
    {
        if(is_null(self::$connect) || !self::$connect instanceof ConnectionInterface){
            $config = self::getConfig();
            if(empty($config)){
                $message = "数据库配置文件获取失败";
                Monkey::$app->log->error($message);
                throw new \Exception($message);
            }
            self::$connect = self::createQuery($config);
        }
        return self::$connect;
    }

    /**
     * 创建实例
     * @param array $config
     * @return ConnectionInterface
     * @throws \Exception
     */
    private static function createQuery(array $config)
    {
        $connection = new Connection();
        return $connection->setPdo($config);
    }

    /**
     * 获取数据库加载容器
     * @return QueryBuilder
     */
    private static function getBuilder()
    {
        return new QueryBuilder(self::$connect);
    }
}