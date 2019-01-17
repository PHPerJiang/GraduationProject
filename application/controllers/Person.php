<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author: jiangyu01
 * @Time: 2019/1/15 13:16
 * @property CI_Session session
 */
class Person extends CI_Controller{

	private $error_code = 0;
	private $error_msg = 'success';

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('session');
	}

	/**
	 * 个人主页首页
	 */
	public function index(){
		if (!$this->session->is_login()){
			redirect('login/index');
		}else{
			$this->load->view('web/person/index');
		}
	}

	/**
	 * 昵称查重
	 */
	public function validate_nickname(){
		$this->resp();
	}

	/**
	 * 手机号查重
	 */
	public function validate_phone(){
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