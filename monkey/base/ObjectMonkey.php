<?php
/**
 * Created by PhpStorm.
 * User: Lzw
 * Date: 2020/1/2
 * Time: 0:43
 * Use : DI基类
 */

namespace monkey\base;

use Monkey;
use monkey\log\Log;

class ObjectMonkey
{
    public function __construct(array $config = [])
    {
        if(!empty($config)){
            Monkey::config($this,$config);
        }
        $this->init();
    }

    public function init(){}

    /**
     * 自动getter
     * @param string $name
     * @return mixed
     * @throws \Exception
     */
    public function __get(string $name)
    {
        $getter = 'get' . ucfirst($name);
        if(method_exists($this,$getter)){
            return $this->$getter();
        }else if(method_exists($this,'set' . ucfirst($name))){
            $message = '该属性 write-only：' . get_class($this) . '::' . $name;
            Monkey::error($message);
            throw new \BadMethodCallException($message);
        }else{
            $message = '未知属性：' . get_class($this) . '::' . $name;
            Monkey::error($message);
            throw new \Exception($message);
        }
    }

    /**
     * 自动setter
     * @param string $name
     * @param mixed $value
     * @return mixed
     * @throws \Exception
     */
    public function __set(string $name, $value)
    {
        $setter = 'set' . ucfirst($name);
        if(method_exists($this,$setter)){
            return $this->$setter($value);
        }else if(method_exists($this,'get' . ucfirst($name))){
            $message = '该属性 read-only：' . get_class($this) . '::' . $name;
            Monkey::error($message);
            throw new \BadMethodCallException($message);
        }else{
            $message = '未知属性：' . get_class($this) . '::' . $name;
            Monkey::error($message);
            throw new \Exception($message);
        }
    }

    /**
     *自动判断isset
     * @param string $name
     * @return bool
     */
    public function __isset(string $name)
    {
        $getter = 'get' . ucfirst($name);
        if(method_exists($this,$getter)){
            return $this->$getter() !== null;
        }
        return false;
    }

    public function __unset(string $name)
    {
        $setter = 'set' . ucfirst($name);
        if(method_exists($this,$setter)){
            $this->$setter(null);
        }else if (method_exists($this,'get' . ucfirst($name))){
            $message = '该属性 read-only：' . get_class($this) . '::' . $name;
            Monkey::error($message);
            throw new \BadMethodCallException($message);
        }
    }

    /**
     * 是否可以设置属性
     * @param string $name
     * @param bool   $check 是否将成员变量视为属性
     * @return bool
     */
    public function canSetProperty(string $name,bool $check = true)
    {
        return method_exists($this,'set' . ucfirst($name)) || $check && property_exists($this,$name);
    }

    /**
     * 是否可以获取属性
     * @param $name
     * @param bool   $check 是否将成员变量视为属性
     * @return bool
     */
    public function canGetProperty(string $name,bool $check = true)
    {
        return method_exists($this,'get' . ucfirst($name)) || $check && property_exists($this,$name);
    }
}