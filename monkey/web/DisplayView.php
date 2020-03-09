<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/3/6
 * Time: 17:12
 */

namespace monkey\web;


use monkey\base\View;

class DisplayView extends \monkey\base\DisplayView
{
    /**
     * Display a view file.
     * @param View $view
     * @param string $file
     * @param array $params
     * @return string
     */
    public function display(string $file, array $params = [])
    {
        ob_start();
        ob_implicit_flush(false);
        extract($params,EXTR_OVERWRITE);
        require($file);
        return ob_get_clean();
    }
}