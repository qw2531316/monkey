<?php
/**
 * Created by PhpStorm.
 * User: Lzw
 * Date: 2020/1/3
 * Time: 14:17
 * Use : 属性、行为、事件的基类
 */

namespace monkey\base;

use Monkey;

class Component extends ObjectMonkey
{
    private $_events = [];

    private $_behavios;

    public function __set(string $name, $value)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            // set property
            $this->$setter($value);

            return;
        }
    }

    public function __get(string $name)
    {
        $getter = 'get' . ucfirst($name);
        if(method_exists($this,$getter)){
            return $this->$getter();
        }

    }
}