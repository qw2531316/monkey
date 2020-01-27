<?php
/**
 * Created by PhpStorm.
 * User: Lzw
 * Date: 2020/1/27
 * Time: 21:59
 */

namespace controller\member;

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
        echo 'say Test Params<BR>';
        $id = $_GET['id'];
        echo 'id:' . $id . '<BR>';
        $page = $_GET['page'];
        echo 'page:' . $page . '<BR>';
    }

    public function actionTestUsername()
    {
        echo 'say Test Username<BR>';
        $username = $_GET['username'];
        echo $username;
    }
}