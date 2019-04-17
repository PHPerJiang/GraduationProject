<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author: jiangyu01
 * @Time: 2019/1/18 17:52
 * @property User_person_info_db user_person_info_db
 * @property Myredis myredis
 */
class User_person_info_biz extends CI_Model{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('db/user_person_info_db');
		$this->load->library('myredis');
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
		$result = $this->user_person_info_db->save($user_id, $params);
		$this->sync_person_2_redis($user_id);
		return $result;
	}


	/**
	 * 同步个人信息到redis
	 * @Author: jiangyu01
	 * @Time: 2019/4/13 10:24
	 */
	public function sync_person_2_redis($user_id){
		$user_person_info = $this->user_person_info_biz->get_person_info($user_id);
		if (!empty($user_person_info) && isset($user_person_info[0])){
			$redis_key_name = "person_info:{$user_person_info[0]['user_id']}";
			$user_person_info[0]['image'] = site_url('assets/'.$user_person_info[0]['image']);
			$this->myredis->set($redis_key_name,json_encode($user_person_info[0]));
		}
	}

	/**
	 * 获取用户个人信息
	 */
	public function get_person_info($user_id){
		if (!is_numeric($user_id)){
			return FALSE;
		}
		$result = $this->user_person_info_db->select('*',['user_id' => $user_id]);
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
		$result = $this->user_person_info_db->save($user_id, $params);
		return $result;
	}
}