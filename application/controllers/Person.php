<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author: jiangyu01
 * @Time: 2019/1/15 13:16
 * @property CI_Session session
 * @property  User_person_info_biz user_person_info_biz
 */
class Person extends CI_Controller{

	private $error_code = 0;
	private $error_msg = 'success';

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->model('bizs/user_person_info_biz');
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
	 * 保存用户信息
	 */
	public function save_info(){
		$user_id = $this->input->post('user_id');
		$name = $this->input->post('name');
		$nickname = $this->input->post('nickname');
		$phone = $this->input->post('phone');
		$description = $this->input->post('description');
		if (!$user_id || !is_numeric($user_id) || !$name || !$nickname || !$phone){
			$this->error_code = 3;
			$this->error_msg = '参数错误';
			$result = FALSE;
			goto END;
		}
		$params = [
			'name' => $name,
			'nickname' => $nickname,
			'phone' => $phone,
			'description' => $description,
		];
		$result = $this->user_person_info_biz->save_user_info($user_id, $params);
		END:
		$this->resp($result);
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