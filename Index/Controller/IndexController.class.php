<?php
class IndexController extends Controller{
    public function index(){
        if(!$this->is_cached()){
            //重新连接数据库更新数据
        }

        $this->assign('x','xiao');
        $this->display();
    }
}

?>