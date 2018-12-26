<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author: jiangyu01
 * @Time: 2018/12/25 17:16
 * @property Login_biz login_biz
 */
class Login extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('url'));
		$this->load->model('bizs/login_biz');
	}

	//加载登录、注册、忘记密码页面
	public function index(){
		$this->load->view('web/login/index');
	}

	//登录
	public function login(){
		$login_info = $this->input->post();
		var_dump($login_info);
	}

	//注册
	public function register(){

	}

	//忘记密码
	public function retrieve(){

	}
}