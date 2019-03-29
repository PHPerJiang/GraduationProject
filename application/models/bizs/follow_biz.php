<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author: jiangyu01
 * @Time: 2019/2/12 9:50
 * @property Myredis myredis
 * @property User_follow_info_db user_follow_info_db
 * @property User_evaluate_info_biz user_evaluate_info_biz
 * @property User_person_info_biz user_person_info_biz
 */
class Follow_biz extends CI_Model{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('myredis');
		$this->load->model('db/user_follow_info_db');
		$this->load->model('bizs/user_evaluate_info_biz');
		$this->load->model('bizs/user_person_info_biz');
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
			//维护用户粉丝集合
			$this->myredis->sAdd('user_fans_info:'.$user_follow_id, $user_id);
		}elseif ($action == 'unfollow'){
			$this->myredis->srem($redis_key_name,$user_follow_id);
			$this->myredis->srem('user_fans_info:'.$user_follow_id, $user_id);
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
	 * 根据用户id查询个人feed流
	 * @param $user_id
	 */
	public function get_person_feed_info_by_user_id($user_id,$option = []){
		$data = [];
		//获取缓存里的数据
		$size = isset($option['size']) ? (!empty($option['size']) ? $option['size'] : 9):9;
		$offset = isset($option['offset']) ? (!empty($option['offset']) ? $option['offset'] : 0):0;
		$person_feed_infos = $this->myredis->zRevRange('person_feed:'.$user_id,$offset, $size);
		//获取集体的信息数据
		if (!empty($person_feed_infos) && is_array($person_feed_infos)){
			foreach ($person_feed_infos as $key => $value){
				$redis_info = explode(':',$value);
				$res = $this->myredis->hGet('user_articles:'.$redis_info[0],$redis_info[1]);
				if (!empty($res)){
					$data[] = json_decode($res,true);
				}
			}
		}
		if (!empty($data)){
			foreach ($data as $key => $value){
				$data[$key]['good_num'] = $this->user_evaluate_info_biz->get_user_evaluate(0,$value['id'],$value['user_id']);
			}
		}
		return $data;
	}

	/**
	 * 根据用户id查询用户已关注的用户列表
	 * @param $user_id
	 */
	public function get_follower_list_by_user_id($user_id){
		//获取缓存中的关注关系集合
		$follower_list = $this->myredis->sMembers('user_follow_info:'.$user_id);
		$person_info = [];
		if (!empty($follower_list)){
			foreach ($follower_list as $key => $value){
				//根据用户id获取用户基本信息
				$tmp = $this->user_person_info_biz->get_person_info($value);
				if(!empty($tmp) && isset($tmp[0]) && !empty($tmp[0])){
					$person_info[] = $tmp[0];
				}
			}
		}
		return $person_info;
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