<?php
/**
 * Created by 。
 * user: rufeike
 * email: rufeike@163.com
 * Date：2019/11/9
 * Time: 15:18
 */

class ImgModel extends Model{
    public $table = 'img';
    public function get_all_data(){
        return $this->all();
    }
}
