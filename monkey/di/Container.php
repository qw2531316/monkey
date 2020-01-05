<?php
/**
 * Created by PhpStorm.
 * User: Lzw
 * Date: 2020/1/3
 * Time: 16:44
 * Use : Ioc容器
 */

namespace monkey\di;

use monkey\base\Component;

class Container extends Component
{
    /**
     * 注册单例对象容器
     * @var array
     */
    private $_instance = [];

    /**
     * 注册非单例对象容器
     * @var array
     */
    private $_class = [];

    /**
     * 保存构造器参数
     * @var array
     */
    private $_params = [];

    /**
     * ReflectionClass 对象缓存容器
     * @var array
     */
    private $_reflection = [];

    /**
     * 依赖信息缓存容器
     * @var array
     */
    private $_dependency = [];

    /**
     * 返回请求类的实例
     * @param string $className 类名
     * @param array $params 构造器参数，应按顺序提供
     * @param array $config 用于初始化对象属性 [key => value]
     * @return object
     * @throws \Exception
     */
    public function get(string $className,array $params = [],array $config = [])
    {
        // 已有单例实例
        if(isset($this->_instance[$className])){
            return $this->_instance[$className];
        }else if(!isset($this->_dependency[$className])){
            return $this->build($className,$params,$config);
        }
        $dependency = $this->_dependency[$className];
        if(is_callable($dependency,true)){
            // 依赖是回调函数，直接调用
            $params = $this->dealDependency($this->mergeParams($className,$params));
            $object = call_user_func($dependency,$this,$params,$config);
        }else if(is_array($dependency)){
            // 依赖是数组，合并配置数组
            $config = array_merge($dependency,$config);
            $params = $this->mergeParams($className,$params);
            $class = $dependency['class'];
            unset($dependency['class']);

            if($class == $className){
                $object = $this->build($className,$params,$config);
            }else{
                // 递归
                $object = $this->get($className,$params,$config);
            }
        }else if(is_object($dependency)){
            // 依赖是对象，保存到单例容器
            return $this->_instance[$className] = $dependency;
        }else{
            throw new \Exception('不能实例化的类型【' . gettype($dependency) . '】');
        }
        // 已注册的单例依赖，将其对象保存到单例容器中
        if(array_key_exists($className,$this->_instance)){
            $this->_instance[$className] = $object;
        }
        return $object;
    }

    /**
     * 注册依赖
     * @param string $className
     * @param array $definition
     * @param array $params
     * @return $this
     * @throws \Exception
     */
    public function set(string $className,array $definition = [],array $params = [])
    {
        // 规范化类定义并写入 $_class[$className]
        $this->_class[$className] = $this->getStandard($className,$definition);
        // 绑定构造器参数
        $this->_params[$className] = $params;
        // 删除已注册的单例容器，保证依赖关系定义唯一
        unset($this->_instance[$className]);
        return $this;
    }

    /**
     * 注册单例依赖
     * @param string $className
     * @param array $definition
     * @param array $params
     * @return $this
     * @throws \Exception
     */
    public function setInstance(string $className,array $definition = [],array $params = [])
    {
        // 规范化类定义并写入 $_class[$className]
        $this->_class[$className] = $this->getStandard($className,$definition);
        // 绑定构造器参数
        $this->_params[$className] = $params;
        // 绑定单例注册容器，实例化见 $this->build()
        $this->_instance[$className] = null;
        return $this;
    }

    /**
     * 规范化依赖项
     * @param string $className 类名
     * @param string|array|callable $definition 类定义
     * @return array ['class' => 类定义]
     * @throws \Exception
     */
    public function getStandard(string $className,$definition)
    {
        if(empty($definition)){
            return ['class' => $className];
        }else if(is_string($definition)){
            return ['class' => $definition];
        }else if(is_callable($definition,true) || is_object($definition)){
            return $definition;
        }else if(is_array($definition)){
            if(!isset($definition['class'])){
                if(strpos($className,'\\')){
                    $definition['class'] = $className;
                }else{
                    throw new \Exception('不是有效的类名：【' . $className . '】');
                }
            }
            return $definition;
        }
        throw new \Exception('不支持的定义类型：【' . $className . '】-' . gettype($definition));
    }

    /**
     * 解析已注册的依赖
     * @param string $className
     * @return array [ReflectionClass,$dependency]
     * @throws \Exception
     */
    public function analysisDependency(string $className)
    {
        // 已缓存依赖
        if(isset($this->_reflection[$className])){
            return [$this->_reflection[$className],$this->_dependency[$className]];
        }
        $dependency = [];
        try{
            // 获取 $className 相关信息
            $reflection = new \ReflectionClass($className);
        }catch (\Exception $e){
            throw new \Exception('实例化类失败：【' . $className . '】');
        }
        // 反射类的构造器
        $constructor = $reflection->getConstructor();
        if(!empty($constructor)){
            // 返回构造器参数
            foreach ($constructor->getParameters() as $parameter){
                if($parameter->isDefaultValueAvailable()){
                    // 将默认值作为依赖
                    $dependency[] = $parameter->getDefaultValue();
                }else{
                    // 注册类名依赖
                    $dependency[] = ['id' => $parameter->getName()];
                }
            }
        }
        // 写入缓存容器
        $this->_dependency[$className] = $dependency;
        $this->_reflection[$className] = $reflection;
        return [$reflection,$dependency];
    }

    /**
     * 处理已注册的依赖
     * @param array $dependency
     * @return array
     * @throws \Exception
     */
    public function dealDependency(array $dependency)
    {
        foreach($dependency as $key => $value){
            if(!empty($value['id'])){
                $dependency[$key] = $this->get($value['id']);
            }else{
                throw new \Exception('实例化缺少参数：【' . $value['id'] . '】');
            }
        }
        return $dependency;
    }

    /**
     * 实例化依赖
     * @param string $className
     * @param array $params
     * @param array $config
     * @return object
     * @throws \Exception
     */
    public function build(string $className,array $params,array $config)
    {
        // 获取依赖信息
        /**
         * @var \ReflectionClass $reflection
         */
        list($reflection,$dependency) = $this->analysisDependency($className);
        // 是否可实例化
        if(!$reflection->isInstantiable()){
            throw new \Exception('类【' . $reflection->name . '】不可实例化');
        }

        foreach($params as $key => $value){
            $dependency[$key] = $value;
        }

        $dependency = $this->dealDependency($dependency);
        // 处理已注册的依赖
        // 依赖不为空且继承了 ObjectMonkey 类
        if(!empty($dependency) && is_a($className,'monkey\base\ObjectMonkey',true)){
            // config 为最后一个参数
            $dependency[count($dependency) - 1] = $config;
            // 实例化
            return $reflection->newInstanceArgs($dependency);
        }
        $object = $reflection->newInstanceArgs($dependency);
        foreach ($params as $key => $value){
            $object->key = $value;
        }
        return $object;
    }

    /**
     * 合并 指定的构造器参数 与 [[set()]] 注册的参数
     * @see set()
     * @param $className
     * @param $params
     * @return mixed
     */
    protected function mergeParams($className,$params)
    {
        if(empty($this->_params[$className])){
            return $params;
        }else if(empty($params)){
            return $this->_params[$className];
        }
        $param = $this->_params[$className];
        foreach ($params as $key => $value){
            $params[$key] = $value;
        }
        return $param;
    }
}