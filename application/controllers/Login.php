<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author: jiangyu01
 * @Time: 2018/12/25 17:16
 * @property Login_biz login_biz
 * @property Mycaptcha mycaptcha
 * @property CI_Session session
 */
class Login extends CI_Controller {

	private $error_code = 0;
	private $error_msg = 'success';

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('url'));
		$this->load->model('bizs/login_biz');
		$this->load->library('mycaptcha');
		$this->load->library('session');
	}
	//加载登录、注册、忘记密码页面
	public function index(){
		$this->load->view('web/login/index');
	}

	//展示验证码
	public function get_code(){
		$captcha= $this->mycaptcha->getCaptcha();  //生成的验证码值
		$this->mycaptcha->showImg();               //生成验证码图片
		$this->session->set_userdata('captcha_code', strtolower($captcha));  //转换成小写并保存验证码到session中
	}

	/**
	 * 验证码校验
	 */
	public function validate_captcha(){
		$data = [];
		$captcha_code = $this->input->post('captcha_code');
		if (strtolower($captcha_code) != $this->session->userdata('captcha_code')){
			$this->error_code = 3;
			$this->error_msg = '验证码错误';
		}
		$this->resp($data);
	}

	//登录
	public function login(){
		$login_info = $this->input->post();

		$this->resp($login_info);
	}

	//注册
	public function register(){
		$account = $this->input->post('account');
		$password = $this->input->post('password');
		if ($account != NULL && $password != NULL && ca)
		$this->resp();
	}

	//忘记密码
	public function retrieve(){

	}

	/**
	 * 数据输出
	 * @param array $data
	 * @param string $total
	 */
	private function resp($data = []) {
		header('Content-type: application/json');
		echo json_encode([
			'error_code' => $this->error_code,
			'error_msg'  => $this->error_msg,
			'data'       => $data,
		]);
		return;
	}
}