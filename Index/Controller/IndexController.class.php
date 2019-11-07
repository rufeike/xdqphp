<?php
class IndexController extends Controller{
    public function index(){
        echo "欢迎使用XDQPHP框架 (:";
        Log::write('记录第一条日志');
    }
}

?>