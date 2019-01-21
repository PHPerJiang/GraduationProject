<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author: jiangyu01
 * @Time: 2019/1/18 17:52
 * @property User_person_info_db_biz user_person_info_db_biz
 */
class User_person_info_biz extends CI_Model{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('db/user_person_info_db_biz');
	}

	/**
	 * 用户信息处理
	 * @param $user_id 用户id
	 * @param array $userinfo 用户信息
	 */
	public function save_user_info($user_id, $userinfo = []){
		if (!is_numeric($user_id)){
			return FALSE;
		}
		$userinfo['user_id'] = $user_id;
		$default_params = [
			'user_id' => '',
			'name' => '',
			'nickname' => '',
			'phone' => '',
			'description' =>  '',
		];
		$params = array_merge($default_params, $userinfo);
		$user_info = $this->user_person_info_db_biz->select('id',['user_id' => $user_id]);
		if ($user_info){
			$result = $this->user_person_info_db_biz->update($params, ['user_id' => $user_id]);
		}else{
			$result = $this->user_person_info_db_biz->insert($params);
		}
		return $result;
	}

	/**
	 * 获取用户个人信息
	 */
	public function get_person_info($user_id){
		if (!is_numeric($user_id)){
			return FALSE;
		}
		$result = $this->user_person_info_db_biz->select('*',['user_id' => $user_id]);
		return $result;
	}
}