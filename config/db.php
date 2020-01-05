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
    'db_prod' => [
        'dns' => 'mysql:host=sdm7229065.my3w.com;port=3306;dbname=sdm7229065_db',//'mysql:host=127.0.0.1;port=3306;dbname=monkey',
        'host' => 'sdm7229065.my3w.com',
        'port' => 3306,
        'dbName' => 'sdm7229065_db',
        'username' => 'sdm7229065',
        'password' => 'HOUhou..',
        'prefix' => 'monkey_',
    ],
    'db_test' => [
        'dns' => 'mysql:host=127.0.0.1;port=3306;dbname=monkey',
        'host' => '127.0.0.1',
        'port' => 3306,
        'dbName' => 'monkey',
        'username' => 'root',
        'password' => 'root',
        'prefix' => 'monkey_',
    ],
    'debug' => true,
];