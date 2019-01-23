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
		$result = $this->user_person_info_db_biz->save($user_id, $params);
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

	/**
	 * 图片路径入库
	 * @param $user_id
	 * @param $image_url
	 */
	public function user_image_2_base($user_id, $image_url){
		$params = [
			'user_id' => $user_id,
			'image' => $image_url,
		];
		$result = $this->user_person_info_db_biz->save($user_id, $params);
		return $result;
	}
}