<?php
/**
 * Created by PhpStorm.
 * User: Lzw
 * Date: 2020/1/27
 * Time: 21:59
 */

namespace controller\member;

use Monkey;
use monkey\web\Controller;

class UserController extends Controller
{
    public function actionIndex()
    {
        return $this->view('user/index',['user' => 'monkey']);
    }

    public function actionUserPass()
    {
        echo 'say User Pass';
    }

    public function actionTestParams()
    {
        $id = Monkey::$app->request->get('id');
        return 'Test Response';
    }

    public function actionTestUsername()
    {
        echo 'say Test Username<BR>';
        $username = $_GET['username'];
        echo $username;
    }
}