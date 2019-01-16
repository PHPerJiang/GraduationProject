<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author: jiangyu01
 * @Time: 2018/12/25 17:16
 * @property Login_biz login_biz
 * @property Mycaptcha mycaptcha
 * @property CI_Session session
 * @property User_base_info_biz user_base_info_biz
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
		$this->load->model('bizs/user_base_info_biz');
	}
	//加载登录、注册、忘记密码页面
	public function index(){
		if ($this->session->is_login()){
			redirect('feed/index');
		}else{
			$this->load->view('web/login/index');
		}
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
		$captcha_code = $this->input->post('code');
		if (strtolower($captcha_code) != $this->session->userdata('captcha_code')){
			$this->error_code = 3;
			$this->error_msg = '验证码错误';
		}
		$this->resp($data);
	}

	//登录
	public function login(){
		$account = $this->input->post('account');
		$password = $this->input->post('password');
		$res = $this->login_biz->login($account,$password);
		$this->error_code = $res ? 0 : 1;
		$this->error_msg = $res ? '登录成功' : '登录失败';
		$this->resp();
	}

	//注册
	public function register(){
		$account = $this->input->post('account');
		$password = $this->input->post('password');
		$ip = $_SERVER['REMOTE_ADDR'];
		$data = [];
		if ($account != NULL && $password != NULL){
			$params = [
				'account' => $account,
				'password' => $password,
				'ip' => $ip,
			];
			$res = $this->login_biz->register($params);
			if (!$res){
				$this->error_msg = 1;
				$this->error_code = 'Fail';
			}
		}
		$this->resp();
	}

	//账号校验
	public function validate_account(){
		$account = $this->input->post('account');
		$res = $this->login_biz->validate_account($account);
		if (!$res){
			$this->error_msg = 1;
			$this->error_code = 'Fail';
		}
		$this->resp();
	}
	//忘记密码
	public function retrieve(){

	}

	/**
	 * 退出登录
	 */
	public function logout(){
		$this->session->logout();
		redirect('login/index');
	}

	/**
	 * ajax 退出登录
	 */
	public function ajax_logout(){
		$this->session->logout();
		$this->resp();
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