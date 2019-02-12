<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author: jiangyu01
 * @Time: 2019/2/12 9:17
 * @property CI_Session session
 * @property Follow_biz follow_biz
 */
class Follow extends CI_Controller{
	private $error_code = 0;
	private $error_msg = 'success';
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->model('bizs/follow_biz');
	}

	/**
	 * 用户关注
	 */
	public function user_follow(){
		if (!$this->session->is_login()){
			redirect('login/index');
		}else{
			$user_id = $this->session->userdata('user_id');
			$user_follow_id = $this->input->post('user_follow_id');
			$this->follow_biz->user_follow($user_id, $user_follow_id);
			$this->resp();
		}
	}

	/**
	 * 用户关注
	 */
	public function user_unfollow(){
		if (!$this->session->is_login()){
			redirect('login/index');
		}else{
			$user_id = $this->session->userdata('user_id');
			$user_follow_id = $this->input->post('user_follow_id');
			$this->follow_biz->user_unfollow($user_id, $user_follow_id);
			$this->resp();
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