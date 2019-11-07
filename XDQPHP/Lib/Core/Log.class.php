<?php
/**
 * Created by 错误日志记录类。
 * user: rufeike
 * email: rufeike@163.com
 * Date：2019/11/7
 * Time: 15:36
 */

class log{
    /**
     * 错误日志写入方法
     * @param $msg 错误信息
     * @param string $level 错误等级
     * @param int $type 错误记录方式 默认为3，写入文件方式
     * @param null $dest 错误日记记录路径
     */
    public static function write($msg,$level='ERROR',$type=3,$dest=null){
        //判断日记记录是否开启
        if(!C('SAVE_LOG')){
            return;
        }

        //判断是否指定存储路径
        if(is_null($dest)){
            $dest = LOG_PATH.'/'.date('Y_m_d').".log";
        }

        $log_msg = "[TIME]:".date('Y-m-d H:i:s')." [LEVEL]:{$level} [MSG]:{$msg}\r\n";
        if(is_dir(LOG_PATH)){
            //使用系统error_log()记录日志;
            //error_log('错误方式',"错误记录类型","记录地址","")
            error_log($log_msg,$type,$dest);
        }else{
            mkdir(LOG_PATH,0777,true);
            error_log($log_msg,$type,$dest);
        }
    }

}

?>
