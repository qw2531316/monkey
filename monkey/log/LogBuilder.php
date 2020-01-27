<?php
/**
 * Created by PhpStorm.
 * User: Lzw
 * Date: 2019/12/25
 * Time: 19:53
 */

namespace monkey\log;


trait LogBuilder
{
    /**
     * 日志配置容器
     * @var array
     */
    protected $config = [];

    /**
     * 日志文件绝对路径
     * @var string
     */
    protected $absolutelyPath = '';

    /**
     * 日志文件类型
     * @var string
     */
    protected $levelType = 'info';

    /**
     * 解析日志配置
     * @param array $config
     */
    public function config(array $config)
    {
        if(empty($config) || empty($config['BasePath'])){
            return;
        }
        $this->config = $config;
    }

    /**
     * 创建日志文件绝对路径
     * @param string $relativePath  日志文件相对路径
     */
    private function buildPath(string $relativePath)
    {
        // 绝对路径
        $absolutelyPath = ROOT_PATH . $relativePath . DIRECTORY_SEPARATOR . date('Ymd');
        // 创建文件夹
        if(!is_dir($absolutelyPath)){
            mkdir($absolutelyPath, 0755, true);
        }
        $this->absolutelyPath = $absolutelyPath;
    }

    /**
     * 处理日志内容
     * @param string $content
     * @param string $levelType
     * @return string
     */
    private static function buildContent(string $content,string $levelType)
    {
        $date = date('Y-m-d H:i:s');
        $LogContent = "[ " . $date . " ] " . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "\r\n";
        $LogContent .= strtoupper($levelType) . " : 【" . $content ."】\r\n\r\n";
        return $LogContent;
    }

    /**
     * 创建文件并写入内容
     * @param string $filePath 文件绝对路径
     * @param string $content  处理过的内容
     */
    private static function buildFile(string $filePath,string $content)
    {
        $file = fopen($filePath,'a');
        fwrite($file,$content);
        fclose($file);
    }

    /**
     * 内容写入对应日志
     * @param string $content
     * @param string $levelType
     */
    private function writeLog(string $content,string $levelType)
    {
        $this->levelType = $levelType ?: 'info';
        // 配置文件中获取对应相对路径
        $relativePath = $this->config['BasePath'][$this->levelType];
        // 创建实际路径
        $this->buildPath($relativePath);
        // 文件绝对路径
        $filePath = $this->absolutelyPath . DIRECTORY_SEPARATOR . date('YmdH') . '.log';
        // 处理日志内容
        $content = $this->buildContent($content,$levelType);
        // 写入日志文件
        $this->buildFile($filePath,$content);
    }
}