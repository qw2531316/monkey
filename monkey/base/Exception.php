<?php
/**
 * Created by PhpStorm.
 * User: Lzw
 * Date: 2020/2/8
 * Time: 0:13
 * Use : Exception基类
 */

namespace monkey\base;

use Monkey;

class Exception extends \Exception
{
    public function __construct(string $message = "")
    {
        if(MONKEY_DEBUG){
            Monkey::$app->log->error($message);
        }
        parent::__construct($message);
    }
}