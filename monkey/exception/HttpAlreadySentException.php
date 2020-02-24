<?php
/**
 * Created by PhpStorm.
 * User: Lzw
 * Date: 2020/2/8
 * Time: 0:16
 */

namespace monkey\exception;

use monkey\base\Exception;
use Throwable;

class HttpAlreadySentException extends Exception
{
    public function __construct(string $file,string $line)
    {
        $message = MONKEY_DEBUG ? "Headers already sent in $file on line $line" : "Headers already sent";
        parent::__construct($message);
    }
}