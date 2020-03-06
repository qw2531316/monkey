<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/3/6
 * Time: 10:09
 */

namespace monkey\web;


class View extends \monkey\base\View
{
    /**
     * 返回ajax数据
     * @param string $view
     * @param array $params
     */
    public function viewAjax(string $view,array $params = [])
    {
        $viewFile = $this->findViewFile($view);

    }
}