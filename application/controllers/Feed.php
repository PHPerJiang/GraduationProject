<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author: jiangyu01
 * @Time: 2019/1/8 9:09
 * @property CI_Session session
 */
class Feed extends CI_Controller{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('url'));
		$this->load->library('session');
	}

	/**
	 * feed流首页
	 */
	public function index(){
		//判断是否已登录
		if (!$this->session->is_login()){
			redirect('login/index');
		}else{
			$this->load->view('web/feed/index');
		}
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
			'rdata'       => $data,
		]);
		return;
	}
}