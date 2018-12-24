<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: jiangyu01
 * Date: 2018/12/19
 * Time: 13:21
 * @property  Myredis myredis
 * @property Mycaptcha mycaptcha
 * @property Myupload myupload
 * @property User_base_info_biz user_base_info_biz
 */
class Test extends CI_Controller{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url','download'));
    }

    //测试链接Mysql
    public function connect_mysql(){
        $this->load->model('test_db');
        $res = $this->test_db->get_all();
        var_dump($res);
    }

    //测试链接Redis
    public function connect_redis(){
        $this->load->library('myredis');
        var_dump($this->myredis->set('name','PHP'));
        var_dump($this->myredis->get('name'));
        var_dump($this->myredis->close());
    }

    //测试获取验证码
    public function get_captcha(){
        $this->load->library('mycaptcha');
        $captcha_code = $this->mycaptcha->getCaptcha();
//        var_dump($captcha_code);
        $this->mycaptcha->showImg();
    }

    //文件上传、下载
    public function upload(){
        $file_info = $_FILES;
        if (!$file_info){
            $this->load->view('upload/upload');
        }else{
            $this->load->library('myupload');
            $file_name = $this->myupload->up($file_info['image']);
            if (!empty($file_info)){
                echo APPPATH.$file_name;
                force_download(APPPATH.$file_name,NULL);
            }
        }
    }

    public function new_model_db(){
    	$this->load->model('bizs/user_base_info_biz');
    	//数据插入
	    $param = [
		    'name' => 'Python',
		    'mobile' => '124124123',
		    'account' => 'testtest3',
		    'password' => '111111',
	    ];
		var_dump($this->user_base_info_biz->data_set($param));
    	//数据查询
    	var_dump($this->user_base_info_biz->get_all());
    }
}