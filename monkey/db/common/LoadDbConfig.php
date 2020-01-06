<?php
/**
 * Created by PhpStorm.
 * User: Lzw
 * Date: 2019/12/25
 * Time: 20:59
 * Use : 解析配置
 */

namespace monkey\db\common;


trait LoadDbConfig
{
    /**
     * 配置容器
     * @var array
     */
    protected static $config = [];

    /**
     * 驱动
     * @var string
     */
    protected static $_driverName = 'mysql';

    /**
     * 解析数据库配置
     * @param array $config
     * @throws \Exception
     */
    public static function config(array $config)
    {
        self::$config = self::parseConfig($config);
    }

    /**
     * 配置文件默认值
     * @return array
     */
    private static function getNeedKeys()
    {
        return [
            'dns' => '',
            'host' => '127.0.0.1',
            'port' => 3306,
            'dbName' => 'monkey',
            'username' => '',
            'password' => '',
        ];
    }

    /**
     * 解析配置文件
     * @param array $config
     * @return array
     * @throws \Exception
     */
    private static function parseConfig(array $config)
    {
        $driver = $config['driver'];
        if(empty($driver)){
            throw new \Exception("配置 driver 为空");
        }
        self::setDriverName($driver);
        $parseConfig = [
            'driverName' => self::$_driverName,
            'fetchType' => $config['fetchType'] ?: '',
            'debug' => $config['debug'] ?: '',
        ];
        $needKeys = self::getNeedKeys();
        $dbConfig = $config['db_' . MONKEY_ENVIRONMENT];
        foreach ($needKeys as $key => $value){
            if($key != 'dns' && empty($dbConfig[$key])){
                throw new \Exception("配置字段 `$key` 为空");
            }
            $parseConfig[$key] = $dbConfig[$key];
        }
        return $parseConfig;
    }

    private static function getConfig()
    {
        return self::$config;
    }

    /**
     * 设置数据库类型
     * @param string $driverName
     */
    private static function setDriverName(string $driverName)
    {
        self::$_driverName = $driverName;
    }
}