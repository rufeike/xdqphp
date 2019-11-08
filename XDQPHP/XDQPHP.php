<?php
/**
 * Created by 。
 * user: rufeike
 * email: rufeike@163.com
 * Date：2019/11/4
 * Time: 16:06
 */

/**
 * 核心加载类
 * Class XDQPHP
 */
final class XDQPHP{
   //自动运行类
   public static function run(){
       self::_set_const();//常量配置方法
       self::_create_dir();//自动创建用户应用目录方法
       self::_import_file();//自动引入文件方法
       Application::run();
   }

    /**
     * 配置框架常量方法
     * 统一风格，常量后面不带资源分割符
     */
   private static function _set_const(){
       //统一控制分隔符为'/'
       $path = str_replace('\\','/',__FILE__);

       define('XDQPHP_PATH',dirname($path));//框架根目录路径
       define('CONFIG_PATH',XDQPHP_PATH.'/Config');//框架默认配置目录路径
       define('DATA_PATH',XDQPHP_PATH.'/Data');//框架默认资源目录路径
       define('TPL_PATH',DATA_PATH.'/Tpl');//框架默认模板文件目录路径
       define('LIB_PATH',XDQPHP_PATH.'/Lib');//框架默认核心目录路径
       define('CORE_PATH',LIB_PATH.'/Core');//框架默认核心目录路径
       define('FUNCTION_PATH',LIB_PATH.'/Function');//框架默认方法目录路径

       //用户应用相关路径
       define('ROOT_PATH',dirname(XDQPHP_PATH));//项目根路径
       define('TEMP_PATH',ROOT_PATH.'/'.'Temp');//临时目录路径
       define('LOG_PATH',TEMP_PATH.'/'.'Log');//日记存储目录路径

       define('APP_PATH',ROOT_PATH.'/'.APP_NAME);//应用目录路径 APP_NAME需要在入口文件处定义
       define('APP_CONFIG_PATH',APP_PATH.'/'.'Config');//用户应用配置目录路径
       define('APP_CONTROLLER_PATH',APP_PATH.'/'.'Controller');//用户应用控制器目录路径
       define('APP_TPL_PATH',APP_PATH.'/'.'Tpl');//用户应用模板目录路径
       define('APP_PUBLIC_PATH',APP_TPL_PATH.'/'.'Public');//应用静态资源目录路径

       //框架第三方拓展和框架工具类相关
       define('EXTENDS_PATH',XDQPHP_PATH.'/'.'Extends');
       define('TOOL_PATH',EXTENDS_PATH.'/'.'Tool');//框架工具类
       define('ORG_PATH',EXTENDS_PATH.'/'.'Org');//第三方拓展类

       //公共目录相关
       define('COMMON_PATH',ROOT_PATH.'/'.'Common');//公共文件目录路径
       define('COMMON_CONFIG_PATH',COMMON_PATH.'/'.'Config');//公共文件配置目录路径
       define('COMMON_MODEL_PATH',COMMON_PATH.'/'.'Model');//公共文件模型目录路径
       define('COMMON_LIB_PATH',COMMON_PATH.'/'.'Lib');//公共库文件目录路径

       //动态定义方法方式常量
       define('IS_POST',($_SERVER['REQUEST_METHOD']=='POST')?true:false);//是否为post提交
       //根据S_SERVER['HTTP_X_REQUESTED_WITH']否为ajax提交,ajax提交时，$_SERVER系统数组变量中会产生一个HTTP_X_REQUESTED_WITH键，值为XMLHttpRequest
       if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])&&$_SERVER['HTTP_X_REQUESTED_WITH']=='XMLHttpRequest'){
           define('IS_AJAX',true);
       }else{
           define('IS_AJAX',false);
       }

   }

   /*
    * 自动创建用户目录方法
    */
   private static function _create_dir(){
       $arr = array(
           COMMON_PATH,
           COMMON_CONFIG_PATH,
           COMMON_MODEL_PATH,
           COMMON_LIB_PATH,
           APP_PATH,
           APP_CONFIG_PATH,
           APP_CONTROLLER_PATH,
           APP_TPL_PATH,
           APP_PUBLIC_PATH,
           TEMP_PATH,
           LOG_PATH
       );

       foreach($arr as $v){
           //判读目录是否存在，不存在则创建
           //mkdir() 0777给予最高权限，true递归创建项目
           is_dir($v) || mkdir($v,0777,true);
       }

       //初始化引入成功或失败跳转模板文件
       is_file(APP_TPL_PATH.'/'.'success.html') || copy(TPL_PATH.'/'.'success.html',APP_TPL_PATH.'/'.'success.html');
       is_file(APP_TPL_PATH.'/'.'error.html') || copy(TPL_PATH.'/'.'error.html',APP_TPL_PATH.'/'.'error.html');
   }

    /**
     * 自动引入文件方法
     */
    private static function _import_file(){
        $arr = array(
            FUNCTION_PATH.'/'.'function.php',//框架自定义方法
            CORE_PATH.'/'.'Log.class.php',//引入日志记录类
            CORE_PATH.'/'.'Controller.class.php',//用户应用类父类
            CORE_PATH.'/'.'Application.class.php',//用户应用入口类
        );

        foreach($arr as $v){
            require_once($v);
        }
    }


}

XDQPHP::run();//执行核心类

?>
