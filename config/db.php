<?php
/**
 * Created by PhpStorm.
 * User: Lzw
 * Date: 2019/12/24
 * Time: 20:29
 * Use : 数据库配置文件
 */

return [
    'class' => 'monkey\db\DBQuery',
    'driver' => 'mysql',
    'fetchType' => PDO::FETCH_ASSOC,
    'db_test' => [
        'dns' => 'mysql:host=127.0.0.1;port=3306;dbname=monkey',
        'host' => '127.0.0.1',
        'port' => 3306,
        'dbName' => 'monkey',
        'username' => 'root',
        'password' => 'houhou',
        'prefix' => 'monkey_',
    ],
    'debug' => true,
];