<?php
/**
 * Created by 系统配置项目。
 * user: rufeike
 * email: rufeike@163.com
 * Date：2019/11/4
 * Time: 16:30
 */


return array(
    //配置项 => 配置值
    'DEBUG' => true,//调试模式 默认打开
    'DEFAULT_TIME_ZONE' => 'PRC',//设置默认时区
    'SESSION_AUTO_START' => true,//是否可开启session 默认开启

    'ACCESS_CONTROLLER' => 'c',//默认控制器访问符号
    'ACCESS_ACTION' => 'a',//默认方法访问符号

    'SAVE_LOG' => true,//是否开启日志记录 默认为开启

    'ERROR_URL' => '',//错误跳转路径
    'ERROR_MSG' => '网站出错了，请稍后再试。。。',//错误提醒信息

    'AUTO_LOAD_FILE' => array(),//自动载入Common/Lib目录中的文件，可以定义多个文件
);

