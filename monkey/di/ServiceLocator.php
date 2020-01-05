<?php
/**
 * Created by PhpStorm.
 * User: Lzw
 * Date: 2020/1/3
 * Time: 11:14
 * Use : 服务定位
 */

namespace monkey\di;

use Monkey;
use monkey\base\Component;

class ServiceLocator extends Component
{
    // 缓存服务、组件 实例
    private $components = [];

    // 缓存服务、组件 定义，（一般为配置数组，用来创建实例）
    private $definitions = [];

    /**
     * 重载 __get
     * @param string $name
     * @return mixed
     * @throws \Exception
     */
    public function __get(string $name)
    {
        if($this->has($name)){
            return $this->get($name);
        }
        return parent::__get($name);
    }

    /**
     * 获取实例
     * @param string $id
     * @return callable|object
     * @throws \Exception
     */
    public function get(string $id)
    {
        if(isset($this->components[$id])){
            // 已经实例化
            return $this->components[$id];
        }
        // 有定义
        if(isset($this->definitions[$id])){
            // 是对象或回调
            if(is_object($this->definitions[$id]) || is_callable($this->definitions[$id],true)){
                $this->components[$id] = $this->definitions[$id];
            }else{
                // 实例化
                $this->components[$id] = Monkey::createObject($this->definitions[$id]);
            }
        }else{
            throw new \Exception("未知的组件/服务ID【 $id 】");
        }
        return $this->components[$id];
    }

    /**
     * 设置组件、服务
     * @param string $id         组件、服务 ID
     * @param mixed $definitions 组件、服务 定义
     * @throws \Exception
     */
    public function set(string $id,$definitions)
    {
        // 对象或回调函数，保存定义
        if(is_object($definitions) || is_callable($definitions,true)){
            $this->definitions[$id] = $definitions;
        }else if(is_array($definitions)){
            // 配置数组
            if(isset($definitions['class'])){
                $this->definitions[$id] = $definitions;
            }else{
                throw new \Exception("$id 组件配置必须包含 【class】 元素");
            }
        }else{
            throw new \Exception('未知的组件类型【id】 -> 【' . gettype($definitions) . '】');
        }
    }

    /**
     * 判断是否已定义/实例化
     * @param string $id       组件/服务 ID
     * @param bool $isInstance 用于 -- true : 判断是否已实例化，false : 判断是否已定义
     * @return bool
     */
    public function has(string $id,$isInstance = false)
    {
        return $isInstance ? isset($this->components[$id]) : isset($this->definitions[$id]);
    }

    /**
     * 删除组件/服务 定义和实例
     * @param string $id
     */
    public function delete(string $id)
    {
        unset($this->definitions[$id],$this->components[$id]);
    }

    /**
     * @param bool $returnComponents true : 返回实例，false : 返回定义
     * @return array
     */
    public function getComponents($returnComponents = true)
    {
        return $returnComponents ? $this->components : $this->definitions;
    }

    /**
     * 批量注册组件、服务
     * @param $components
     * @throws \Exception
     */
    public function setComponents($components)
    {
        foreach ($components as $id => $component){
            $this->set($id,$component);
        }
    }
}