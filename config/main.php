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
    'components' => [
        'Log' => [
            'class' => 'monkey\log\Log',
            'BasePath' => [
                'info' => 'runtime/info',
                'warning' => 'runtime/warning',
                'error' => 'runtime/error',
            ]
        ],

        'UrlManager' => [
            'class' => 'monkey\url\UrlManager',
            'enableManagerUrl' => true,
            'suffix' => '.html',
            'showScriptName' => false,
            'rules' => [
                '/' => 'site/index',
            ],
        ],
        'db' => $db,
    ],
    'params' => $param,
];
