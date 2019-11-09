<?php
/**
 * Created by 数据库模型类。
 * user: rufeike
 * email: rufeike@163.com
 * Date：2019/11/9
 * Time: 9:18
 */

class Model{
    //保存数据库连接资源
    public static $link = null;
    protected $table = null;//表名
    private $option;//查询条件
    public static $sqls = array();//查询sql记录

    //数据库连接
    public function __construct($table=null){
        //初始表名
        $prefix = C('DB_PREFIX');//表前缀
        $this->table = is_null($table) ? $prefix.$this->table:$prefix.$table;
        //连接数据库
        $this->_connect();

        //初始化表查询属性
        $this->_option();
    }

    /**
     * 数据库连接
     */
    private function _connect(){
        if(is_null(self::$link)){
            //检测是否已配置数据库
            $database = C('DB_DATABASE');
            if(empty($database)){
               halt("请先配置数据库");
            }
            //实例化数据库
            $link = new mysqli(C('DB_HOST'),C('DB_USER'),C('DB_PASSWORD'),C('DB_DATABASE'),C('DB_PORT'));

            //判断是否连接成功
            if($link->connect_errno){
                halt('数据库连接错误，请检测配置项');
            }

            //设置字符集
            $link->set_charset(C('DB_CHARSET'));

            //存入静态属性中
            self::$link = $link;

        }

    }

    /**
     * 初始化表查询属性
     */
    private function _option(){
        $this->option = array(
            'field' => '*',//表字段名
            'where' => '',//查询条件
            'group' => '',//分组查询条件
            'having' => '',//聚合筛选条件
            'order' => '',//排序条件
            'limit' => '',//查询行数
        );
    }

    /**
     * 查询和获取结果集
     * @param $sql
     * @return array
     */
    public function query($sql){
        self::$sqls[] = $sql;//记录查询sql语句
        $link = self::$link;
        $result = $link->query($sql);//调用mysqli的query()方法执行
        if($link->errno){//根据mysqli属性errno判断是否成功
            halt('mysql错误：'.$link->error.'<br/>SQL:'.$sql);
        }
        //处理结果集，返回数组
        $rows = array();
        while($row = $result->fetch_assoc()){//从结果集中获取关联数组
            $rows[] = $row;
        }

        $result->free();//释放结果集
        $this->_option();//初始化查询条件
        return $rows;
    }

    /**
     * 无结果集方法sql处理方法
     * @param $sql
     * @return mixed
     */
    public function exec($sql){
        self::$sqls[] = $sql;
        $link = self::$link;
        $bool = $link->query($sql);//通过mysqli的query方法执行sql是否存在结果集
        $this->_option();//重置数据库查询条件
        if(is_object($bool)){
            halt("请使用query方法发送sql语句");
        }

        if($bool){
            //把执行结果返回
            return $link->insert_id ? $link->insert_id : $link->affected_rows;
        }else{
            halt("mysql错误".$link->error.'<br/>SQL：'.$sql);
        }
    }

    /*
     *查找单条数据
     */
    public function one(){
        $data = $this->limit(1)->all();
        //把二维数组变成一维数组
        $data = current($data);

        return $data;
    }

    /**
     * 查找单条别名
     */
    public function findOne(){
        return $this->one();
    }

    /**
     * 末端方法
     * 查询所有数据
     */
    public function all(){
        //根据用户传递的查询条件，组装查询sql语句
        $option = $this->option;
        $sql = 'SELECT ';
        $sql .= $option['field'];
        $sql .= ' FROM ';
        $sql .= $this->table;
        if($option['where']!=''){
            $sql .= ' WHERE ';
            $sql .= $option['where'];
        }
        $sql .= ' ';
        if($option['group']!=''){
            $sql .= 'GROUP BY ';
            $sql .= $option['group'];
            $sql .= ' ';
        }
        $sql .= $option['having'];
        $sql .= ' ';
        if($option['order']!=''){
            $sql .= 'ORDER BY ';
            $sql .= $option['order'];
            $sql .= ' ';
        }
        if($option['limit']!=''){
            $sql .= 'LIMIT ';
            $sql .= $option['limit'];
            $sql .= ' ';
        }

        return $this->query($sql);
    }

    /**
     * all的别名findAll方法
     */
    public function findAll(){
        return $this->all();
    }

    /**
     * 指定查询字段
     * @param $field
     * @return $this
     */
    public function field($field){
        $this->option['field'] = $field;
        return $this;
    }


    /**
     * 指定查询条件
     * @param $where
     * @return $this
     */
    public function where($where){
        $this->option['where']= $where;
        return $this;
    }


    /**
     * 分组查询条件
     * @param $group
     * @return $this
     */
    public function group($group){
        $this->option['group']= $group;
        return $this;
    }

    /**
     * 指定查询条数
     * @param $limit
     * @return $this
     */
    public function limit($limit){
       $this->option['limit'] = $limit;
       return $this;
    }

    /**
     * 指定排序
     * @param $order
     * @return $this
     */
    public function order($order){
        $this->option['order'] = $order;
        return $this;
    }

    /**
     * 根据指定条件删除数据
     */
    public function delete(){
        if(empty($this->option['where'])){
            halt("删除语句必须有where条件");
        }
        $sql  = "DELETE FROM ".$this->table." WHERE ".$this->option['where'];
        return $this->exec($sql);
    }

    /**
     * 数据库字符串安全处理，对数据进行实体化处理
     * @param $str
     * @return mixed
     */
    private function _safe_str($str){
        //判读系统是否开启数据转义
        if(get_magic_quotes_gpc()){//如果开启，需要反转转义
            $str = stripslashes($str);
        }
        return self::$link->real_escape_string($str);
    }


    /**
     * 添加数据
     * @param array $data
     * @return mixed
     */
    public function add($data =array()){
        if(empty($data)){
            $data = $_POST;
        }
        if(empty($data)){
            halt('请添加需要插入的数据');
        }

        $fields = '';
        $values= '';
        //拼装字段名和字段值
        foreach($data as $pk => $pv){
            $fields.='`'.$this->_safe_str($pk).'`,';
            $values.="'".$this->_safe_str($pv)."',";
        }
        //去除多余的','
        $fields = trim($fields,',');
        $values = trim($values,',');

        //组装添加数据的sql
        $sql = "INSERT INTO ".$this->table.'('.$fields.') VALUES ('.$values.')';

        return $this->exec($sql);

    }

    /**
     * 更新数据
     * @param array $data
     * @return mixed
     */
    public function update($data=array()){
         //需要增加更改条件
        if(empty($this->option['where'])){
            halt('更新语句需要与where条件配合使用');
        }
        if(empty($data)){
            $data = $_POST;
        }

        if(empty($data)){
            halt('请添加需要插入的数据');
        }

        $values = '';
        foreach($data as $dk => $dv){
            $values .="`".$this->_safe_str($dk)."`='".$this->_safe_str($dv)."',";
        }
        $values = trim($values,',');//去除多余逗号
        $sql = "UPDATE ".$this->table.' SET '.$values.' WHERE '.$this->option['where'];

        return $this->exec($sql);
    }

}
