<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author: jiangyu01
 * @Time: 2018/12/25 17:16
 */
class Login extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('url'));

	}

	/**
	 * 登录、注册、忘记密码页面
	 */
	public function index(){
		$this->load->view('web/login/index');
	}
}