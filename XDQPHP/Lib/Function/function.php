<?php
/**
 * Created by 框架使用函数文件。
 * user: rufeike
 * email: rufeike@163.com
 * Date：2019/11/4
 * Time: 16:06
 */

/**
 * 自定义打印带格式的打印函数
 * @param $var
 * @param $type 是否需要带格式，默认带格式输出
 */
function dump($var,$type=false){
    if(!$type){
        echo "<pre style='padding:5px 15px;line-height:30px;border:1px solid #f5f5f5;border-radius:5px;background-color: #f5f5f5'>";
        if (is_string($var)) {
            echo $var;
        } else if (is_array($var)) {
            print_r($var);
        } else {
            var_dump($var);
        }
        echo "</pre>";
    }else{
        if (is_string($var)) {
            echo $var;
        } else if (is_array($var)) {
            print_r($var);
        } else {
            var_dump($var);
        }
    }
}


/**
 * 读取配置项函数
 * 功能1：一个数组参数，加载配置项 C($sysConfig) C($userConfig);
 * 功能2：一个字符串参数，读取指定配置项 C('DEBUG');
 * 功能3：两个参数，动态设置配置项目 C('DEBUG',true);
 * 功能4：不传参数，直接读取所有配置项 C();
 *
 * @param null $ck 指定配置项键名或存储数组
 * @param null $cv  自定配置项的值
 * @return null || array()
 */
function C($ck=null,$cv=null){
    static $config = array();

    //如果第一个参数是数组，则加载配置，合并系统配置和用户配置
    if(is_array($ck)){
        $config = array_merge($config,array_change_key_case($ck,CASE_UPPER));
        return;
    }

    //如果第一个参数是字符串
    if(is_string($ck)){
        $var = strtoupper($ck);
        //两个参数的情况
        if(!is_null($cv)){
            $config[$ck] = $cv;
            return;
        }

        //只有一个字符串参数的情况下，返回指定的配置项。注意：不存在的参数时，返回null;
        return isset($config[$ck])?$config[$ck]:null;
    }

    //参数为空时，返回所有配置项目
    if(is_null($ck) && is_null($ck)){
        return $config;
    }

}

/**
 * 跳转函数
 * @param $url 跳转地址
 * @param int $time 跳转时间
 * @param string $msg 跳转信息
 */
function go($url,$time=0,$msg=''){
    //检测头部是否已返送
    if(!headers_sent()){
        $time = 0 ? header('Location:'.$url):header("refresh:{$time};url={$url}");
        die();//终止后续程序
    }else{//头部已发送的情况
        echo "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
        if($time) die("<div style='margin:100px auto;text-align: center'>".$msg."</div>");
    }
}

/**
 * 代码追踪测试
 * @param $error
 * @param string $level
 * @param int $type
 * @param null $dest
 */
function halt($error,$level='ERROR',$type=3,$dest=null){
    //本地记录日志
    if(is_array($error)){
        Log::write($error['message'],$level,$type,$dest);
    }else{
        Log::write($error,$level,$type,$dest);
    }

    //错误信息存储
    $e = array();

    if(C('DEBUG')){//调试开启时
        if(!is_array($error)){
            $trace = debug_backtrace();
            $e['message'] = $error;//错误信息
            $e['file'] = $trace[0]['file'];//错误文件路径
            $e['line'] = $trace[0]['line'];//错误行号
            $e['class'] = isset($trace[0]['class'])?$trace[0]['class']:'';//错误类
            $e['function'] = isset($trace[0]['function'])?$trace[0]['function']:'';//错误方法

            //获取执行追踪路径信息,开启缓存，在缓存区中输出，再获取缓存区的数据
            ob_start();
            debug_print_backtrace();
            $e['trace'] = htmlspecialchars(ob_get_clean());//对应数据实体化，防止破坏页面布局
        }else{
            $e=$error;
        }
    }else{
        //如果用户填写了错误跳转地址，则跳转，否则页面输出错误信息
        if($url = C('ERROR_URL')){
           go($url);
        }else{
           $e['message'] = C('ERROR_MSG');
        }
    }

    //引入代码追踪页面
    require(TPL_PATH.'/'.'halt.html');
    die();//终止后续代码执行
}
