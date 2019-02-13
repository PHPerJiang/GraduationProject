<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author: jiangyu01
 * @Time: 2019/2/12 9:50
 * @property Myredis myredis
 * @property User_follow_info_db user_follow_info_db
 */
class Follow_biz extends CI_Model{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('myredis');
		$this->load->model('db/user_follow_info_db');
	}

	/**
	 * 用户关注
	 * @param $user_id
	 * @param $user_follow_id
	 * @return bool
	 */
	public function user_follow($user_id, $user_follow_id){
		if (empty($user_id) || empty($user_follow_id)){
			return FALSE;
		}
		$action = 'follow';
		$this->sync_2_redis($user_id, $user_follow_id,$action);
		$this->sync_2_follow_event_list($user_id, $user_follow_id,$action);
		return $this->sync_2_mysql($user_id, $user_follow_id,$action);
	}

	/**
	 * 取消关注
	 * @param $user_id
	 * @param $user_follow_id
	 * @return bool
	 */
	public function user_unfollow($user_id, $user_follow_id){
		if (empty($user_id) || empty($user_follow_id)){
			return FALSE;
		}
		$action = 'unfollow';
		$this->sync_2_redis($user_id, $user_follow_id,$action);
		$this->sync_2_follow_event_list($user_id, $user_follow_id,$action);
		return $this->sync_2_mysql($user_id, $user_follow_id,$action);
	}

	/**
	 * 判断用户是否已关注
	 * @param $user_id
	 * @param $user_follow_id
	 */
	public function user_is_followed($user_id,$user_follow_id){
		if (empty($user_id) || empty($user_follow_id)){
			return FALSE;
		}
		$redis_key_name = 'user_follow_info:'.$user_id;
		return $this->myredis->sIsMEMBER($redis_key_name, $user_follow_id);
	}

	/**
	 * 同步数据到redis
	 * @param $user_id
	 * @param $user_follow_id
	 */
	private function sync_2_redis($user_id, $user_follow_id ,$action){
		$redis_key_name = 'user_follow_info:'.$user_id;
		if ($action == 'follow'){
			$this->myredis->sAdd($redis_key_name,$user_follow_id);
		}elseif ($action == 'unfollow'){
			$this->myredis->srem($redis_key_name,$user_follow_id);
		}

	}

	/**
	 * 同步数据到mysql
	 * @param $user_id
	 * @param $user_follow_id
	 */
	private function sync_2_mysql($user_id, $user_follow_id,$action){
		if ($action == 'follow'){
			$data = [
				'user_id' => $user_id,
				'user_follow_id' => $user_follow_id,
				'status' => 1,
			];
			return $this->user_follow_info_db->insert($data);
		}elseif ($action == 'unfollow'){
			return $this->user_follow_info_db->delete(['user_id' => $user_id, 'user_follow_id' => $user_follow_id]);
		}
	}

	/**
	 * 将关注事件打入关注事件监控队列
	 * @param $user_id
	 * @param $user_follow_id
	 * @param $action
	 */
	private function sync_2_follow_event_list($user_id, $user_follow_id,$action){
		$redis_key_value = $user_id.':'.$user_follow_id.':'.$action;
		$redis_key_name = 'follow_event_list';
		$this->myredis->lPush($redis_key_name,$redis_key_value);
	}

}