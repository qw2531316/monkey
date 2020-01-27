<?php
/**
 * Created by PhpStorm.
 * User: Lzw
 * Date: 2020/1/3
 * Time: 16:53
 * Use : 核心类库map
 */

return [
    'monkey\base\Application' => MONKEY_PATH . 'base/Application.php',
    'monkey\base\Component' => MONKEY_PATH . 'base/Component.php',
    'monkey\base\Event' => MONKEY_PATH . 'base/Event.php',
    'monkey\base\Loader' => MONKEY_PATH . 'base/Loader.php',
    'monkey\base\Module' => MONKEY_PATH . 'base/Module.php',
    'monkey\base\ObjectMonkey' => MONKEY_PATH . 'base/ObjectMonkey.php',

    'monkey\db\DBQuery' => MONKEY_PATH . 'db/DBQuery.php',
    'monkey\db\builder\QueryBuilder' => MONKEY_PATH . 'db/builder/QueryBuilder.php',
    'monkey\db\builder\SQLBuilder' => MONKEY_PATH . 'db/builder/SQLBuilder.php',
    'monkey\db\common\LoadDbConfig' => MONKEY_PATH . 'db/common/LoadDbConfig.php',
    'monkey\db\connect\Connection' => MONKEY_PATH . 'db/connect/Connection.php',
    'monkey\db\connect\ConnectionInterface' => MONKEY_PATH . 'db/connect/ConnectionInterface.php',

    'monkey\di\Container' => MONKEY_PATH . 'di/Container.php',
    'monkey\di\ServiceLocator' => MONKEY_PATH . 'di/ServiceLocator.php',

    'monkey\log\Log' => MONKEY_PATH . 'log/Log.php',
    'monkey\log\LogInterface' => MONKEY_PATH . 'log/LogInterface.php',
    'monkey\log\LogBuilder' => MONKEY_PATH . 'log/LogBuilder.php',

    'monkey\url\UrlManager' => MONKEY_PATH . 'url/UrlManager.php',
    'monkey\url\GenerateRule' => MONKEY_PATH . 'url/GenerateRule.php',
    'monkey\url\RuleInterface' => MONKEY_PATH . 'url/RuleInterface.php',
];
