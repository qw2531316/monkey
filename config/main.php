<?php
/**
 * Created by PhpStorm.
 * User: Lzw
 * Date: 2019/12/24
 * Time: 20:28
 * Use : 程序配置文件
 */

$param = require (ROOT_PATH . 'config/param.php');
$db = require (ROOT_PATH . 'config/db.php');

return [
    // 调试
    'debug' => 'true',

    'log' => [
        'BasePath' => [
            'info' => 'runtime/info',
            'warning' => 'runtime/warning',
            'error' => 'runtime/error',
        ]
    ],

    'namespaceMap' => [
        'monkey' => ROOT_PATH . 'monkey',
        'controller' => ROOT_PATH . 'controller',
        'model' => ROOT_PATH . 'model',
    ],

    'urlMap' => [
        'enableManagerUrl' => true,
        'suffix' => '.html',
        'showScriptName' => false,
        'rules' => [
            '/' => 'site/index',
        ],
    ],

    'params' => $param,
    'db' => $db,
];
