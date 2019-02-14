<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author: jiangyu01
 * @Time: 2019/2/13 16:48
 * @property User_base_info_db user_base_info_db
 * @property User_person_info_db user_person_info_db
 * @property User_evaluate_info_db user_evaluate_info_db
 * @property User_article_db user_article_db
 * @property Myredis myredis
 */
class Evaluate extends CI_Controller{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('myredis');
		$this->load->model('db/user_base_info_db');
		$this->load->model('db/user_person_info_db');
		$this->load->model('db/user_evaluate_info_db');
		$this->load->model('db/user_article_db');
	}

	/**
	 * 用户文章点赞数同步redis
	 * cli : php D:/wamp/www/GraduationProject/index.php cli/evaluate user_evaluate_2_redis
	 */
	public function user_evaluate_2_redis(){
		//获取用户id
		$user_ids = $this->get_user_id();
		//根据用户id获取该用户的article_ids
		$article_ids = $this->get_article_ids($user_ids);
		//根据article_ids获取evaluate表的点赞关系
		$evaluate_users = $this->get_evaluate_users($article_ids);
		//点赞数同步redis
		$this->sync_evaluate_2_redis($evaluate_users);
	}

	/**
	 * 获取用户id
	 * @return array|mixed
	 */
	private function get_user_id(){
		$user_ids = $this->user_base_info_db->select('id',['status' => 1],'id desc',0,10000);
		$user_ids = empty($user_ids) ? [] : array_column($user_ids,'id');
		return $user_ids;
	}

	/**
	 * 根据用户id获取该用户的article_ids
	 * @param $user_ids
	 * @return array
	 */
	private function get_article_ids($user_ids){
		if (empty($user_ids) || !is_array($user_ids)) return [];
		$data = [];
		foreach ($user_ids as $user_id){
			$user_articles = $this->user_article_db->select($user_id,'id',['article_status' => 1]);
			if (!empty($user_articles)){
				$article_ids = array_column($user_articles,'id');
				$data[$user_id] = $article_ids;
			}
		}
		return $data;
	}

	/**
	 * 根据article_ids获取evaluate表的点赞关系
	 * @param $article_ids
	 * @return array
	 */
	private function get_evaluate_users($article_ids){
		if (empty($article_ids) || !is_array($article_ids)) return [];
		$data = [];
		foreach ($article_ids as $key => $value){
			if (!empty($value) && is_array($value)){
				foreach ($value as $key1 => $value1){
					$evaluate_users = $this->user_evaluate_info_db->select('user_id',['article_user_id'=>$key, 'article_id'=> $value1],'id desc',0,100000);
					if (!empty($evaluate_users)){
						$evaluate_users = array_column($evaluate_users,'user_id');
						$data[$key.':'.$value1] = $evaluate_users;
					}
				}
			}
		}
		return $data;
	}

	/**
	 * mysql点赞数同步redis
	 * @param $evaluate_users
	 */
	private function sync_evaluate_2_redis($evaluate_users){
		if (empty($evaluate_users) || !is_array($evaluate_users)){
			echo "没有数据同步\n";
		}
		foreach ($evaluate_users as $key => $value){
			$redis_key_name = 'user_evaluate:'.$key;
			$this->myredis->pipeline();
			if (!empty($value) && is_array($value)){
				foreach ($value as $key1){
					$this->myredis->sAdd($redis_key_name.'_tmp',$key1);
					echo "点赞用户{$key1} ===> {$redis_key_name}_tmp success\n";
				}
				$this->myredis->reName($redis_key_name.'_tmp',$redis_key_name);
				echo "".$redis_key_name."  sync [ mysql => redis ] success\n\n";
				sleep(1);
			}
			$this->myredis->exec();
		}
		echo "\nmysql => redis 数据同步完毕\n";
	}
}