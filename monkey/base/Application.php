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

/**
 * 应用程序基类
 *
 * @property monkey\log\Log $log
 * @property monkey\db\DbQuery $db
 *
 * @package monkey\base
 */
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
        Monkey::$app->loadedModules[get_class($this)] = $this;
        //static::setInstance($this);

        Component::__construct($config);
    }

    public function run()
    {
        echo '<pre>';
        Monkey::$app->log->info('test DI/Service Locator');
        print_r(Monkey::$app->db->table('user')->where(['username' => 'monkey'])->getOne());
        die;
    }
}