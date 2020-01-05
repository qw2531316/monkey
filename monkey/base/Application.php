<?php
/**
 * Created by PhpStorm.
 * User: Lzw
 * Date: 2020/1/3
 * Time: 11:07
 * Use : 应用程序基类
 */

namespace monkey\base;

use Monkey;
use monkey\di\ServiceLocator;

abstract class Application extends Module
{
    /**
     * 已加载的模块
     * @var array
     */
    public $loadedModules = [];

    public function __construct(array $config)
    {
        Monkey::$app = $this;
        static::setInstance($this);

        Component::__construct($config);
    }

    public function run()
    {
        $service = new ServiceLocator();
        $service->set('db',[
            'class' => 'monkey\db\builder\QueryBuilder',
            'db_test' => [
                'dns' => 'mysql:host=127.0.0.1;port=3306;dbname=monkey',
                'host' => '127.0.0.1',
                'port' => 3306,
                'dbName' => 'monkey',
                'username' => 'root',
                'password' => 'root',
                'prefix' => 'monkey_',
            ],
        ]);

        echo '<pre>';
        print_r($service->db);
        print_r(Monkey::$app);
        die;
    }
}