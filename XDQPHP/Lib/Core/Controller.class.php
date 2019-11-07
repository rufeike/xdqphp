<?php
/**
 * Created by 所有用户定义应用类的父类。
 * user: rufeike
 * email: rufeike@163.com
 * Date：2019/11/7
 * Time: 11:03
 */

class Controller{
    public function __construct(){
        if(method_exists($this,'__init')){
            $this->__init();
        }
        if(method_exists($this,'__auto')){
            $this->__auto();
        }
    }


    /**
     * 成功跳转方法
     * @param $msg 跳转信息
     * @param null $url 跳转地址
     * @param int $time 跳转时间
     */
    public function success($msg,$url=null,$time=3){
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
    public function error($msg,$url=null,$time=3){
        $url = $url?"window.location.href='".$url."'":'window.history.back(-1)';

        //引入跳转页面
        include(APP_TPL_PATH.'/'.'error.html');
        die();
    }


}

?>
