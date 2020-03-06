<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/3/6
 * Time: 11:56
 */

namespace monkey\exception;


use monkey\base\Exception;

class InvalidCallException extends Exception
{
    public function __construct($message = "")
    {
        $message = MONKEY_DEBUG ? $message : "unable to find view";
        parent::__construct($message);
    }
}