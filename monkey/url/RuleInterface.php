<?php
/**
 * Created by PhpStorm.
 * User: Lzw
 * Date: 2020/1/8
 * Time: 20:05
 * Use : 解析/生成 Url接口
 */

namespace monkey\url;


interface RuleInterface
{
    /**
     * 创建Url
     * @param UrlManager $manager
     * @param string $route
     * @return array
     */
    public function createUrl(UrlManager $manager,string $route);
}