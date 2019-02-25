<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author: jiangyu01
 * @Time: 2019/2/25 16:57
 * @property Myredis myredis
 *
 */
class Common_biz extends CI_Model{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('myredis');
	}

	/**
	 * 获取用户是否填写个人信息
	 * @param $user_id
	 */
	public function validate_user_has_person($user_id){
		$redis_key_name = "person_info:{$user_id}";
		$person_info = $this->myredis->exists($redis_key_name);
		$person_info = !empty($person_info) ? json_decode($this->myredis->get($redis_key_name),true) : false;
		return $person_info;
	}
}