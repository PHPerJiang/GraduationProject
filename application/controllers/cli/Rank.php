<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author: jiangyu01
 * @Time: 2019/2/18 13:58
 * @property Myredis myredis
 * @property User_base_info_db user_base_info_db
 * @property User_article_db user_article_db
 */
class Rank extends CI_Controller{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('myredis');
		$this->load->model('db/user_base_info_db');
		$this->load->model('db/user_article_db');
	}

	/**
	 * 用户排行榜
	 * cli: php D:/wamp/www/GraduationProject/index.php cli/rank get_user_ranking
	 */
	public function get_user_ranking(){
		//获取有效用户id
		$user_ids = $this->get_user_ids();
		//获取用户的follower
		$user_follower_num = $this->get_user_follower($user_ids);
		//获取用户文章的点赞总数
		$user_evaluate_num = $this->get_user_evaluate_num($user_ids);
		//分值计算
		$user_scores = $this->get_user_scores($user_follower_num,$user_evaluate_num);
		//分数同步进入redis
		$this->sync_user_score_2_redis($user_scores);
	}

	//获取有效用户id
	private function  get_user_ids(){
		$user_infos = $this->user_base_info_db->select('id',['status'=>1],'id desc',0,100000);
		$user_ids =[];
		if (!empty($user_infos)){
			$user_ids = array_column($user_infos,'id');
		}
		return $user_ids;
	}

	//获取用户的follower
	private function get_user_follower($user_ids){
		if (empty($user_ids) || !is_array($user_ids)) return [];
		//获取有用户关注的用户
		$this->myredis->pipeline();
		foreach ($user_ids as $user_id){
			$redis_user_follow_key_name = "user_follow_info:{$user_id}";
			$is_exists = $this->myredis->exists($redis_user_follow_key_name);
		}
		$user_is_exists_follower = $this->myredis->exec();
		$user_follow = [];
		//获取被关注的用户有多少粉丝
		if (count($user_ids) == count($user_is_exists_follower)){
			for ($i=0; $i<count($user_ids); $i++){
				if (!empty($user_is_exists_follower[$i])){
					$user_follow[$user_ids[$i]] = $this->myredis->scard("user_follow_info:{$user_ids[$i]}");
				}else{
					$user_follow[$user_ids[$i]] = 0;
				}
			}
		}
		return $user_follow;
	}

	//获取用户文章的点赞总数
	private function get_user_evaluate_num($user_ids){
		if (empty($user_ids) || !is_array($user_ids)) return [];
		//获取用户文章id
		$user_article_info = $user_evaluate = $user_evaluate_sum = [];
		foreach ($user_ids as $user_id){
			$user_article_ids = $this->user_article_db->select($user_id,['id'],['article_status'=>1],'id desc',0,10000);
			if (!empty($user_article_ids)){
				$user_article_info[$user_id] = array_column($user_article_ids,'id');
			}
		}
		//根据文章id获取文章点赞数
		if (!empty($user_article_info)){
			foreach ($user_article_info as $key => $value){
				if (!empty($value) && is_array($value)) {
					$this->myredis->pipeline();
					foreach ($value as $key1 => $value1){
						$redis_user_evaluate_key = "user_evaluate:{$key}:{$value1}";
						$redis_user_evaluate_num = $this->myredis->scard($redis_user_evaluate_key);
					}
					$user_evaluate[$key] = $this->myredis->exec();
				}else{
					continue;
				}
			}
			//计算每篇文章的点赞数
			if (!empty($user_evaluate)){
				foreach ($user_evaluate as $key => $value){
					$user_evaluate_sum[$key] = array_sum($value);
				}
			}
		}
		return $user_evaluate_sum;
	}

	//计算用户分值
	private function get_user_scores($user_follower_num,$user_evaluate_num){
		if (empty($user_evaluate_num) && empty($user_follower_num)) return [];
		$user_scores = [];
	    foreach ($user_follower_num as $key => $value){
	    	foreach ($user_evaluate_num as $key1 => $value1){
	    		if ($key == $key1){
					$user_scores[$key] = $value * 0.6 + $value1 * 0.4;
			    }
		    }
	    }
	    return $user_scores;
	}

	//同步分值至redis
	private  function sync_user_score_2_redis($user_scores){
		if (empty($user_scores)){
			echo "没有同步数据\n";
			exit;
		}
		$this->myredis->pipeline();
		foreach ($user_scores as $key => $value){
			$redis_key_name = 'user_rank_scores';
			$this->myredis->zAdd($redis_key_name,$value,$key);
			echo "用户： {$key}  =>  {$redis_key_name} 存储成功\n";
			sleep(1);
		}
		$this->myredis->exec();
		echo "\n数据同步完毕\n";
	}
}