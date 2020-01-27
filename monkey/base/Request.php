<?php
/**
 * Created by PhpStorm.
 * User: Lzw
 * Date: 2020/1/27
 * Time: 17:41
 */

namespace monkey\base;


abstract class Request
{
    abstract public function resolve();
}