<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/3/6
 * Time: 9:54
 */

namespace monkey\base;

use Monkey;

abstract class Controller extends Component
{
    /**
     * @var Module
     */
    public $module;

    public function __construct(Module $module,array $config = [])
    {
        $this->module = $module;
        parent::__construct($config);
    }

    /**
     * 输出html
     * @param string $view
     * @param array $params
     * @return string
     * @throws \Exception
     */
    public function view(string $view,array $params = [])
    {
        return Monkey::$app->getView()->view($view,$params);
    }
}