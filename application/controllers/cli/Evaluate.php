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
	public function user_evaluate_2_redis($form = 'mysql', $to = 'redis'){
		//获取用户id
		$user_ids = $this->get_user_id();
		//根据用户id获取该用户的article_ids
		$article_ids = $this->get_article_ids($user_ids);
		if ($form == 'mysql' && $to == 'redis'){
			//根据article_ids获取evaluate表的点赞关系
			$evaluate_users = $this->get_evaluate_users($article_ids);
			//点赞数同步redis
			$this->sync_evaluate_2_redis($evaluate_users);
		}
		if ($form == 'redis'&& $to == 'mysql'){
			$redis_evaluate_set = $this->get_redis_exists_evaluate_set($article_ids);
			$this->sync_evaluate_2_mysql($redis_evaluate_set);
		}
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
			exit;
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

	/**
	 * 获取redis中存在的点赞文章集合
	 * @param $evaluate_users
	 */
	private function get_redis_exists_evaluate_set($evaluate_users){
		if (empty($evaluate_users) ||!is_array($evaluate_users)){
			echo "没有同步的文章id\n";
			exit;
		}
		$all_article_redis_key = $is_exists_arr = [];
		$res = [];
		//获取redis中存在的文章点赞集合名称
		foreach ($evaluate_users as $key => $value){
			if ($value && is_array($value)){
				$this->myredis->pipeline();
				foreach ($value as $key1 => $value1){
					$redis_key_name = "user_evaluate:".$key.':'.$value1;
					array_push($all_article_redis_key,$redis_key_name);
					$is_exists = $this->myredis->exists($redis_key_name);
				}
				$res[] = $this->myredis->exec();
			}
		}
		//将结果转为一维数组
		if ($res && is_array($res)){
			foreach ($res as $key){
				if ($key && is_array($key)){
					$is_exists_arr = array_merge($is_exists_arr,$key);
				}
			}
		}
		//去除redis不存在的文章点赞集合名称
		if (count($all_article_redis_key) == count($is_exists_arr)){
			$count = count($all_article_redis_key);
			for ($i = 0; $i < $count; $i++){
				if (empty($is_exists_arr[$i])){
					unset($all_article_redis_key[$i]);
				}
			}
		}
		return $all_article_redis_key;
	}

	/**
	 * redis中点赞集合数据同步mysql
	 * @param $redis_evaluate_set
	 */
	private function sync_evaluate_2_mysql($redis_evaluate_set){
		if (empty($redis_evaluate_set) || !is_array($redis_evaluate_set)){
			echo "没有同步数据\n";
			exit;
		}
		//获取redis点赞集合的成员
		$this->myredis->pipeline();
		foreach ($redis_evaluate_set as $key => $value){
			$tmp  = $this->myredis->sMembers($value);
		}
		$redis_evaluate_set_value = $this->myredis->exec();
		//获取文章数量
		$count = sizeof($redis_evaluate_set);
		//重置文章数量数组索引
		$redis_evaluate_set = array_values($redis_evaluate_set);
		//数据同步
		for ($i = 0; $i<$count; $i++){
			$article_info = explode(':',$redis_evaluate_set[$i]);
			if (isset($redis_evaluate_set_value[$i]) && !empty($redis_evaluate_set_value[$i])){
				foreach ($redis_evaluate_set_value[$i] as $key){
					$data=[
						'user_id' => $key,
						'article_user_id'=> $article_info[1],
						'article_id' => $article_info[2],
					];
					$evaluate_info = $this->user_evaluate_info_db->select('id',$data);
					$evaluate_info = array_column($evaluate_info,'id');
					if (empty($evaluate_info)){
						$res = $this->user_evaluate_info_db->insert($data);
						if ($res){
							echo "点赞用户 {$key} 同步至mysql成功\n";
						}else{
							echo "点赞用户 {$key} 同步至mysql失败\n";
						}
					}else{
						echo "点赞用户 {$key} 在mysql中已经存在\n";
					}
				}
			}
			sleep(1);
		}
		echo "\n数据同步完毕\n";
	}
}