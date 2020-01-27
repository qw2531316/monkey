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
use monkey\url\UrlManager;

/**
 * 应用程序基类
 *
 * @property monkey\log\Log $log
 * @property monkey\db\DbQuery $db
 * @property \monkey\web\Request $request
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

        // 合并默认组件
        $config['components'] = array_merge($config['components'],$this->components());
        Component::__construct($config);
    }

    /**
     * @throws \Exception
     */
    public function run()
    {
        // 解析请求
        Monkey::$app->request->resolve();
        echo '<pre>';
        print_r(Monkey::$app->db->table('user')->where(['username' => 'monkey'])->getOne());
        die;
    }

    /**
     * 获取URLManager对象
     * @return callable|UrlManager|object
     * @throws \Exception
     */
    public function getUrlManager()
    {
        return $this->get('urlManager');
    }

    /**
     * 默认组件
     * @return array
     */
    protected function components()
    {
        return [
            'request' => ['class' => '\monkey\web\Request'],
            'rule' => ['class' => '\monkey\url\GenerateRule'],
        ];
    }
}