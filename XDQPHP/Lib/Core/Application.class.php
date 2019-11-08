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
        self::_user_import_file();//自动加载用户自定义扩展类
        self::_set_url();//设置外部访问路径
        spl_autoload_register(array(__CLASS__,'_autoload'));//注册类自动加载
        self::_create_demo();//自动创建Demo文件
        self::_app_run();//自动实例化和运行访问方法
    }


    /**
     * 自动实例化和运行目录指定控制器和方法
     */
    private static function _app_run(){
        $c = isset($_GET[C('ACCESS_CONTROLLER')])?$_GET[c('ACCESS_CONTROLLER')]:"Index";
        $a = isset($_GET[C('ACCESS_ACTION')])?$_GET[c('ACCESS_ACTION')]:"index";

        define('CONTROLLER',$c);//定义代表控制器的常量
        define('ACTION',$a);//定义代表方法的常量

        $c .= 'Controller';//组合类全称
        $obj = new $c();//实例化控制器
        $obj->$a();//调用方法
    }


    /**
     *  创建默认访问路径和演示demo
     */
    private static function _create_demo(){
        $path = APP_CONTROLLER_PATH.'/'.'IndexController.class.php';
        $str = <<<str
<?php
class IndexController extends Controller{
    public function index(){
        echo "欢迎使用XDQPHP框架 (:";
    }
}

?>
str;
        //判断文件是否存在，不存在则创建对应的文件
        is_file($path) || file_put_contents($path,$str);

    }

    /**
     * 类自动加载处理方法
     */
    private static function _autoload($className){
        //根据实例化类名称判读为控制器还是类文件
        switch(true){
            case strlen($className)>10 && substr($className,-10)=='Controller':
                //根据实例化控制器类名称，查找对应的类文件引入
                $filePath = APP_CONTROLLER_PATH.'/'.$className.'.class.php';
                if(file_exists($filePath)){
                    require_once($filePath);
                }else{
                    halt($filePath.'控制器不存在');
                }
                break;
            default:
                //根据实例化控制器类名称，查找对应的类文件引入
                $filePath = TOOL_PATH.'/'.$className.'.class.php';
                if(file_exists($filePath)){
                    require_once($filePath);
                }else{
                    halt($filePath.'类文件不存在');
                }
        }

    }

    /**
     * 初始化框架
     */
    private static function _init(){
        //加载系统默认配置项
        C(include CONFIG_PATH.'/'.'config.php');

        //加载公共配置项
        $commonPath = COMMON_CONFIG_PATH.'/'.'config.php';
        $commonConfg=<<<str
<?php
/**
* 公共配置项目
*/

return array(
    //配置项 => 配置值
);

?>
str;
        //判断公共配置文件是否存在，不存在则创建
        is_file($commonPath) || file_put_contents($commonPath,$commonConfg);
        //加载配置
        C(include $commonPath);



        //加载用户配置项
        $userPath = APP_CONFIG_PATH.'/'.'config.php';
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

        //调试模式设置 默认关闭
        !C('DEBUG') && error_reporting(0);//抑制所有错误
    }


    /**
     * 设置外部访问路径常量,方便框架使用
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


    }


    /*
     * 用户自定义拓展引入
     */
    private static function _user_import_file(){
        $fileArr = C('AUTO_LOAD_FILE');
        if(is_array($fileArr) && !empty($fileArr)){
            foreach($fileArr as $v){
                $path = COMMON_LIB_PATH.'/'.$v;
                is_file($path) && require_once($path);
            }
        }
    }

}


?>
