<?php
/**
 * Created by PhpStorm.
 * User: Lzw
 * Date: 2020/2/7
 * Time: 23:47
 */

namespace monkey\web;

use monkey\base\ObjectMonkey;

class HeaderCollection extends ObjectMonkey
{
    private $headers = [];

    public function getCount()
    {
        return count($this->headers);
    }

    /**
     * 获取headers
     * @return array
     */
    public function getAll()
    {
        return $this->headers;
    }

    /**
     * 获取headers元素
     * @param string $name
     * @param mixed $default
     * @param bool $first
     * @return mixed
     */
    public function get(string $name,$default = null,$first = true)
    {
        $name = strtolower($name);
        if(isset($this->headers[$name])){
            return $first ? reset($this->headers[$name]) : $this->headers[$name];
        }
        return $default;
    }

    /**
     * 添加新的header
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function set(string $name,string $value)
    {
        $name = strtolower($name);
        $this->headers[$name] = (array)$value;

        return $this;
    }

    /**
     * 添加新的header
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function add(string $name,string $value)
    {
        $name = strtolower($name);
        $this->headers[$name][] = $value;

        return $this;
    }

    /**
     * headers是否有$name元素
     * @param $name
     * @return bool
     */
    public function has($name)
    {
        $name = strtolower($name);
        return  isset($this->headers[$name]);
    }

    /**
     * 删除headers元素并返回值
     * @param $name
     * @return mixed|null
     */
    public function remove($name)
    {
        $name = strtolower($name);
        if(isset($this->headers[$name])){
            $value = $this->headers[$name];
            unset($this->headers[$name]);
            return $value;
        }

        return null;
    }

    /**
     * 删除headers
     */
    public function removeAll()
    {
        $this->headers = [];
    }
}