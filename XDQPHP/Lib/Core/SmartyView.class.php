<?php
/**
 * Created by 该类用于对接Smarty模板引擎类。
 * user: rufeike
 * email: rufeike@163.com
 * Date：2019/11/9
 * Time: 16:18
 */

class SmartyView{
    private static $smarty = null;
    public function __construct(){
        //判断是否已存在，已存在则不再实例化
        if(!is_null(self::$smarty)) return;
        $smarty = new Smarty();
        //框架自定义smarty配置
        $smarty->template_dir = APP_TPL_PATH.'/'.CONTROLLER.'/';//配置模板目录
        $smarty->compile_dir = APP_COMPILE_PATH;//配置编译目录
        $smarty->cache_dir = APP_CACHE_PATH;//配置缓存路径
        $smarty->left_delimiter = C('LEFT_DELIMITER');//配置左定界符
        $smarty->right_delimiter = C('RIGHT_DELIMITER');//配置右定界符
        $smarty->caching = C('CACHE_ON');//配置是否开启缓存
        $smarty->cache_lifetime = C('CACHE_TIME');//配置缓存时间

        //存入静态属性
        self::$smarty = $smarty;
    }


    /**
     * 定义自动载入模板方法
     * @param $tpl
     * @throws SmartyException
     */
    protected function display($tpl){
        self::$smarty->display($tpl);
    }

    /**
     * 定义模板变量传递方法
     * @param $key
     * @param $value
     */
    protected function assign($key,$value){
        self::$smarty->assign($key,$value);
    }


    /**
     * 判断是否时缓存
     * @param null $tpl
     * @return bool
     * @throws SmartyException
     */
    protected function is_cached($tpl=null){
        if(!C('SMARTY_ON')){
            halt("请先开启smarty");
        }
        $tplPath = $this->get_tpl($tpl);

        return self::$smarty->isCached($tplPath,$_SERVER['REQUEST_URI']);
    }
}

?>