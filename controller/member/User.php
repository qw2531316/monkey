<?php
/**
 * Created by PhpStorm.
 * User: Lzw
 * Date: 2020/1/27
 * Time: 21:59
 */

namespace controller\member;

use Monkey;

class User
{
    public function actionIndex()
    {
        echo 'say User Action';
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