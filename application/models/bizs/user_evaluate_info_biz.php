<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author: jiangyu01
 * @Time: 2019/2/12 14:40
 * @property Myredis myredis
 * @property User_evaluate_info_db user_evaluate_info_db
 */
class User_evaluate_info_biz extends CI_Model{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('myredis');
		$this->load->model('db/user_evaluate_info_db');
	}

	/**
	 * 用户点赞
	 * @param $user_id
	 * @param $article_id
	 * @param $article_user_id
	 */
	public function user_evaluate($user_id,$article_id,$article_user_id,$evaluate_status = 'add'){
		if (empty($user_id) || empty($article_user_id) || empty($article_id)){
			return FALSE;
		}
		$this->sync_2_redis($user_id,$article_id,$article_user_id,$evaluate_status);
		return $this->sync_2_mysql($user_id,$article_id,$article_user_id,$evaluate_status);
	}

	/**
	 * 获取文章点赞数
	 * @param $user_id
	 * @param $article_id
	 * @param $article_user_id
	 */
	public function get_user_evaluate($user_id,$article_id,$article_user_id){
		if (empty($article_user_id) || empty($article_id)){
			return FALSE;
		}
		return $this->myredis->scard('user_evaluate:'.$article_user_id.':'.$article_id);
	}

	/**
	 * 判断用户是否已经点赞
	 * @param $user_id
	 * @param $article_id
	 * @param $article_user_id
	 */
	public function user_is_evaluate($user_id,$article_id,$article_user_id){
		if (empty($user_id) || empty($article_user_id) || empty($article_id)){
			return FALSE;
		}
		return $this->myredis->sIsMEMBER('user_evaluate:'.$article_user_id.':'.$article_id, $user_id);
	}
	/**
	 * 同步到redis
	 * @param $user_id
	 * @param $article_id
	 * @param $article_user_id
	 * @param $action
	 */
	private function sync_2_redis($user_id,$article_id,$article_user_id,$action = 'add'){
		$redis_key_name = 'user_evaluate:'.$article_user_id.':'.$article_id;
		if ($action == 'add'){
			$this->myredis->sAdd($redis_key_name,$user_id);
		}elseif ($action == 'del'){
			$this->myredis->srem($redis_key_name,$user_id);
		}
	}

	/**
	 * 同步到数据库
	 * @param $user_id
	 * @param $article_id
	 * @param $article_user_id
	 * @param $action
	 */
	private function sync_2_mysql($user_id,$article_id,$article_user_id,$action = 'add'){
		$data = [
			'user_id'=> $user_id,
			'article_id' => $article_id,
			'article_user_id' => $article_user_id,
		];
		if ($action == 'add'){
			return $this->user_evaluate_info_db->insert($data);
		}elseif ($action == 'del'){
			return $this->user_evaluate_info_db->delete($data);
		}
	}
}