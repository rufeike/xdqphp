<?php
/**
 * Created by 所有用户定义应用类的父类。
 * user: rufeike
 * email: rufeike@163.com
 * Date：2019/11/7
 * Time: 11:03
 */

class Controller{
    private $param = array();
    public function __construct(){
        if(method_exists($this,'__init')){
            $this->__init();
        }
        if(method_exists($this,'__auto')){
            $this->__auto();
        }
    }

    /**
     * 模板变量传输方法
     * @param $key
     * @param $value
     */
    protected function assign($key,$value){
        $this->param[$key] = $value;
    }

    /**
     * 自动引入模板
     * @param null $tpl
     */
    protected function display($tpl=null){
        if(is_null($tpl)){
            $path = APP_TPL_PATH.'/'.CONTROLLER.'/'.ACTION.'.html';
        }else{
            //判断用户是否定义引入文件后缀名
            $suffix = strrchr($tpl,'.');
            $tpl = empty($suffix)?$tpl.'.html':$tpl;
            $path = APP_TPL_PATH.'/'.CONTROLLER.'/'.$tpl;
        }

        //判断模板文件是否存在
        if(!is_file($path)){
            halt($path.'模板文件不存在');
        }

        //把用户自定义的变量转成模板使用变量
        extract($this->param);

        require_once($path);
    }


    /**
     * 成功跳转方法
     * @param $msg 跳转信息
     * @param null $url 跳转地址
     * @param int $time 跳转时间
     */
    protected function success($msg,$url=null,$time=3){
        $url = $url?"window.location.href='".$url."'":'window.history.back(-1)';

        //引入跳转页面
        include(APP_TPL_PATH.'/'.'success.html');
        die();
    }

    /**
     * 失败跳转方法
     * @param $msg 跳转信息
     * @param null $url 跳转地址
     * @param int $time 跳转时间
     */
    protected function error($msg,$url=null,$time=3){
        $url = $url?"window.location.href='".$url."'":'window.history.back(-1)';

        //引入跳转页面
        include(APP_TPL_PATH.'/'.'error.html');
        die();
    }


}

?>
