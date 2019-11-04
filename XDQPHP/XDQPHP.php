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
       define('LIB_PATH',XDQPHP_PATH.'/Lib');//框架默认核心目录路径
       define('CORE_PATH',LIB_PATH.'/Core');//框架默认核心目录路径
       define('FUNCTION_PATH',LIB_PATH.'/Function');//框架默认方法目录路径

       //用户应用相关路径
       define('ROOT_PATH',dirname(XDQPHP_PATH));//项目根路径
       define('APP_PATH',ROOT_PATH.'/'.APP_NAME);//应用目录路径 APP_NAME需要在入口文件处定义
       define('APP_CONFIG_PATH',APP_PATH.'/'.'Config');//用户应用配置目录路径
       define('APP_CONTROLLER_PATH',APP_PATH.'/'.'Controller');//用户应用控制器目录路径
       define('APP_TPL_PATH',APP_PATH.'/'.'Tpl');//用户应用模板目录路径
       define('APP_PUBLIC_PATH',APP_TPL_PATH.'/'.'Public');//应用静态资源目录路径

   }

   /*
    * 自动创建用户目录方法
    */
   private static function _create_dir(){
       $arr = array(
           APP_PATH,
           APP_CONFIG_PATH,
           APP_CONTROLLER_PATH,
           APP_TPL_PATH,
           APP_PUBLIC_PATH
       );

       foreach($arr as $v){
           //判读目录是否存在，不存在则创建
           //mkdir() 0777给予最高权限，true递归创建项目
           is_dir($v) || mkdir($v,0777,true);
       }
   }

    /**
     * 自动引入文件方法
     */
    private static function _import_file(){
        $arr = array(
            FUNCTION_PATH.'/'.'function.php',//框架自定义方法
            CORE_PATH.'/'.'Application.php',//用户应用类
        );

        foreach($arr as $v){
            require_once($v);
        }
    }


}

XDQPHP::run();//执行核心类

?>
