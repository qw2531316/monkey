<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/3/6
 * Time: 10:29
 */

namespace monkey\base;

use Monkey;
use monkey\web\DisplayView;
use monkey\exception\InvalidCallException;

class View extends Component
{
    /**
     * @var string 视图文件默认后缀
     */
    public $suffix = 'html';

    /**
     * 输出html
     * @param string $view
     * @param array $params
     */
    public function view(string $view,array $params = [])
    {
        $viewFile = $this->findViewFile($view);
        return $this->displayFile($viewFile,$params);
    }

    /**
     * 查找视图文件路径
     * @param string $view
     * @return string 视图文件路径
     * @throws InvalidCallException
     */
    protected function findViewFile(string $view)
    {
        if(strncmp($view,'/',1) === 0){
            // eg. '/user/index'
            if(Monkey::$app->controller !== null){
                $file = Monkey::$app->controller->module->getViewPath() . DIRECTORY_SEPARATOR . ltrim($view,'/');
            }else{
                throw new InvalidCallException("no active controller,unable to find the view file【{$view}】");
            }
        }else {
            // eg. 'user/index'  controllerName/actionName 形式
            $file = $this->getViewPath() . Monkey::$app->getDefaultViewPath() . DIRECTORY_SEPARATOR . $view;
        }
        if(pathinfo($file, PATHINFO_EXTENSION) !== ''){
            return $file;
        }
        $path = $file . '.' . $this->suffix;
        if(!is_file($path)){
            throw new InvalidCallException("The 【{$view}】 does not exist");
        }
        return $path;
    }

    public function getViewPath()
    {
        return Monkey::getBasePath();
    }

    public function displayFile(string $_file,array $_params = [])
    {
        $_obInitialLevel_ = ob_get_level();
        ob_start();
        ob_implicit_flush(false);
        extract($_params, EXTR_OVERWRITE);
        try {
            require($_file);
            return ob_get_clean();
        } catch (\Exception $e) {
            while (ob_get_level() > $_obInitialLevel_) {
                if (!@ob_end_clean()) {
                    ob_clean();
                }
            }
            throw $e;
        } catch (\Throwable $e) {
            while (ob_get_level() > $_obInitialLevel_) {
                if (!@ob_end_clean()) {
                    ob_clean();
                }
            }
            throw $e;
        }
    }
}