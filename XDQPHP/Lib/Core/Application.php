<?php
/**
 * Created by 框架应用类。
 * user: rufeike
 * email: rufeike@163.com
 * Date：2019/11/4
 * Time: 16:06
 */

final class Application{
    // 对外接口方法
    public static function run(){
        self::_init();//初始化框架
        self::_set_url();//设置外部访问路径
    }

    /**
     * 初始化框架
     */
    private static function _init(){
        //加载系统默认配置项
        C(include CONFIG_PATH.'/'.'config.php');

        //加载用户配置项
        $userPath = APP_CONFIG_PATH.'/config.php';
        $userConfg=<<<str
<?php
/**
* 用户配置项目
*/

return array(
    //配置项 => 配置值
);

?>
str;
        //注意：如果用户配置项已存在，则直接加载，不存在则创建后再加载
        is_file($userPath) || file_put_contents($userPath,$userConfg);
        //加载用户配置项目
        C(include $userPath);

        //设置默认时区
        date_default_timezone_set(C('DEFAULT_TIME_ZONE'));

        //是否开启session
        C('SESSION_AUTO_START') && session_start();

    }


    /**
     * 设置外部访问路径产量
     */
    private static function _set_url(){
        //获取访问协议类型
        $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
        $path = $http_type.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
        //处理资源分隔符问题
        $path = str_replace('\\','/',$path);
        define('__APP__',$path);//当前文件路径
        define('__ROOT__',dirname($path));//项目根目录
        define('__TPL__',__ROOT__.'/'.APP_NAME.'/'.'Tpl');//项目资源目录路径
        define('__PUBLIC__',__TPL__.'/'.'Public');//项目静态资源目录路径
        dump(__APP__);
        dump(__ROOT__);
    }

}