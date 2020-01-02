<?php
/**
 * Created by PhpStorm.
 * User: Lzw
 * Date: 2019/12/25
 * Time: 11:26
 * Use : 路由管理器
 */

namespace monkey\url;


class UrlManager
{
    private static $instance;
    // 是否管理url，默认false
    public $enableManagerUrl = false;

    // 保存路由规则
    public $rules = [];

    // 路由后缀，仅 $enableManagerUrl = true 时生效
    public $suffix;

    // url是否保留 index.php
    public $showScriptName = true;

    private $_baseUrl;
    private $_scriptUrl;
    private $_hostInfo;

    private function __construct(array $config)
    {
        $this->init($config);
    }

    public static function getInstance(array $config)
    {
        if(is_null(self::$instance) || !self::$instance instanceof UrlManager){
            self::$instance = new UrlManager($config);
        }
        return self::$instance;
    }

    /**
     * 初始化 UrlManager
     * @param array $config
     */
    private function init(array $config)
    {
        if(!$this->enableManagerUrl || empty($this->rules)){
            return;
        }
        $this->rules = $this->buildUrl($this->rules);
    }

    /**
     * 创建路由规则
     * @param $rules array 路由规则
     */
    private function buildUrl($rules)
    {

    }
}