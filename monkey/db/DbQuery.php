<?php
/**
 * Created by PhpStorm.
 * User: Lzw
 * Date: 2019/12/25
 * Time: 12:26
 * User : DbQuery 入口
 */

namespace monkey\db;

use Monkey;
use monkey\base\Component;
use monkey\log\Log;
use monkey\db\common\LoadDbConfig;
use monkey\db\connect\ConnectionInterface;
use monkey\db\connect\Connection;
use monkey\db\builder\QueryBuilder;

class DbQuery extends Component
{
    use LoadDbConfig;

    /**
     * @var ConnectionInterface
     */
    private $connect;

    /**
     * @var QueryBuilder
     */
    private $builder;

    public function __construct(array $config){
        self::config($config);
        $this->builder = new QueryBuilder($this->getQuery());
        parent::__construct($config);
    }

    private function __clone(){}

    /**
     * 获取数据库实例
     * @return ConnectionInterface
     * @throws \Exception
     */
    private function getQuery()
    {
        if(is_null($this->connect) || !$this->connect instanceof ConnectionInterface){
            $config = self::getConfig();
            if(empty($config)){
                $message = "数据库配置文件获取失败";
                Log::error($message);
                throw new \Exception($message);
            }
            $this->connect = self::createQuery($config);
        }
        return $this->connect;
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
     * 设置表名
     * @param string $table
     * @param string $alias
     * @return QueryBuilder
     */
    public function table(string $table,string $alias = '')
    {
        $this->builder->table = trim($table);
        $this->builder->alias = trim($alias);
        return $this->builder;
    }
}