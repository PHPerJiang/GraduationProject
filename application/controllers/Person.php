<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author: jiangyu01
 * @Time: 2019/1/15 13:16
 * @property CI_Session session
 * @property  User_person_info_biz user_person_info_biz
 * @property Myupload myupload
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
		$this->load->library('myupload');
	}

	/**
	 * 个人主页首页
	 */
	public function index(){
		if (!$this->session->is_login()){
			redirect('login/index');
		}else{
			$person_info = $this->user_person_info_biz->get_person_info($this->session->userdata('user_id'));
			$data['data'] = isset($person_info[0]) ? $person_info[0] : [];
			$data['data']['image'] = isset($data['data']['image']) ? site_url('assets/'.$data['data']['image']) : [];
			$this->load->view('web/person/index',$data);
		}
	}

	/**
	 * 更换头像
	 */
	public function update_image(){
		$user_id = $this->input->post('user_id');
		if (empty($user_id) || !is_numeric($user_id)){
			$this->error_code = -3;
			$this->error_msg = '无效的用户';
			$this->resp();
		}
		$image_info = $_FILES['file'];
		$result = $this->myupload->up($image_info);
		//头像入库
		if($result['error_code'] == 0){
			$this->user_person_info_biz->user_image_2_base($user_id, $result['data']);
			$result['data'] = site_url('assets/'.$result['data']);
			$this->session->set_userdata('user_image',$result['data']);
		}
		$this->error_msg = $result['error_msg'];
		$this->error_code = $result['error_code'];
		$this->resp($result['data']);
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
		$this->session->set_userdata('user_nickname',$nickname);
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
			'rdata'       => $data,
		]);
		return;
	}
}