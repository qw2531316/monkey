<?php
/**
 * Created by PhpStorm.
 * User: Lzw
 * Date: 2020/3/14
 * Time: 2:18
 */

namespace controller\site;

use Monkey;
use monkey\web\Controller;

class HomeController extends Controller
{
    public function actionIndex()
    {
        return $this->view('site/index',['user' => 'monkey']);
    }
}