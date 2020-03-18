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
        'log' => [
            'class' => 'monkey\log\Log',
            'BasePath' => [
                'info' => 'runtime/info',
                'warning' => 'runtime/warning',
                'error' => 'runtime/error',
            ]
        ],

        'urlManager' => [
            'class' => 'monkey\url\UrlManager',
            'enableManagerUrl' => true,
            'suffix' => '.html',
            'defaultController' => 'controller\site\Home',
            'defaultAction' => 'index',
            'rules' => [
                'user' => 'member/user',
                'test' => 'site/home/index-test',
                'user/pass' => 'member/user/user-pass',
                'user-pass' => 'member/user/user-pass',
                'user/<id:\d+>/<page:\d+>' => 'member/user/test-params',
                //'user/<username:[a-z]+>' => 'monkey/user/test-username', // 暂不支持 [a-z]+ 参数
            ],
        ],
        'db' => $db,
    ],
    'params' => $param,
];
