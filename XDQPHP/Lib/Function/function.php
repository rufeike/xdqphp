<?php
/**
 * 格式输出函数
 * @param $var 打印参数 可以为数组、对象、字符串等
 * @param bool $strict 默认为false 设置true后 与var_dump()形式输出
 * @param bool $echo 默认为true 打印输出。 设置false后，返回格式处理的内容
 * @param null $label  可以增加打印内容的颜色、或其他标识内容
 * @return false|mixed|string|string[]|null
 */
function dump($var,$strict=false, $echo=true, $label=null){
    //打印函数
    $label = ($label === null) ? '' : rtrim($label) . ' ';
    if (!$strict){
        if (ini_get('html_errors')){
            $output = print_r($var, true);
            $output = '<pre>' . $label . $output . '</pre>';
        } else{
            $output = $label . print_r($var, true);
        }
    }else{
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        if (!extension_loaded('xdebug')){
            $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
            $output = '<pre>' . $label . $output. '</pre>';
        }
    }
    if ($echo){
        echo($output);
        return null;
    }else
        return $output;
}

