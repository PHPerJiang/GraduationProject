<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author: jiangyu01
 * @Time: 2018/12/25 17:16
 * @property Login_biz login_biz
 * @property Mycaptcha mycaptcha
 */
class Login extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('url'));
		$this->load->model('bizs/login_biz');
		$this->load->library('mycaptcha');
	}

	//加载登录、注册、忘记密码页面
	public function index(){
		$this->load->view('web/login/index');
	}

	//展示验证码
	public function get_code(){
		$captcha= $this->mycaptcha->getCaptcha();  //生成的验证码值
		$this->mycaptcha->showImg();               //生成验证码图片
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