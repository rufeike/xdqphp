<?php
class IndexController extends Controller{
    public function index(){
        $model = new ImgModel();
        $rel=$model->get_all_data();
//        $rel=M('img')->all();
        dump($rel);
    }
}

?>