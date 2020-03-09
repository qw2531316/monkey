<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/3/6
 * Time: 17:02
 */

namespace monkey\base;


abstract class DisplayView extends Component
{
    /**
     * Display a view file.
     * @param View $view
     * @param string $file
     * @param array $params
     * @return string
     */
    abstract public function display(string $file,array $params = []);
}