<?php
/**
 * Created by 框架使用函数文件。
 * user: rufeike
 * email: rufeike@163.com
 * Date：2019/11/4
 * Time: 16:06
 */


function dump($var){
    if(is_bool($var)){
        dump("<pre style=''>".$var."</pre>");
    }else if(is_string($var)){

    }

}

/**
 * 格式输出函数
 * @param $var 打印参数 可以为数组、对象、字符串等
 * @param bool $strict 默认为false 设置true后 与var_dump()形式输出
 * @param bool $echo 默认为true 打印输出。 设置false后，返回格式处理的内容
 * @param null $label  可以增加打印内容的颜色、或其他标识内容
 * @return false|mixed|string|string[]|null
 */
function dump_t($var,$strict=false, $echo=true, $label=null)
{
    //打印函数
    $label = ($label === null) ? '' : rtrim($label) . ' ';
    if (!$strict) {
        if (ini_get('html_errors')) {
            $output = print_r($var, true);
            $output = '<pre>' . $label . $output . '</pre>';
        } else {
            $output = $label . print_r($var, true);
        }
    } else {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        if (!extension_loaded('xdebug')) {
            $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
            $output = '<pre>' . $label . $output . '</pre>';
        }
    }
    if ($echo) {
        echo($output);
        return null;
    } else{
        return $output;
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
