<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: jiangyu01
 * Date: 2018/12/19
 * Time: 13:21
 * @property  Myredis myredis
 */
class Test extends CI_Controller{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('test_db');
        $this->load->library('myredis');
    }

    //测试链接Mysql
    public function connect_mysql(){
        $res = $this->test_db->get_all();
        var_dump($res);
    }

    //测试链接Redis
    public function connect_redis(){
        var_dump($this->myredis->set('name','PHP'));
        var_dump($this->myredis->get('name'));
        var_dump($this->myredis->close());
    }
}