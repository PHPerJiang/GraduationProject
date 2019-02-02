<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author: jiangyu01
 * @Time: 2019/1/30 15:08
 * @property Myredis myredis
 * @property User_article_db user_article_db
 * @property User_base_info_db user_base_info_db
 * @property User_person_info_db user_person_info_db
 */
class Feed extends CI_Controller{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('myredis');
		$this->load->model('db/user_article_db');
		$this->load->model('db/user_base_info_db');
		$this->load->model('db/user_person_info_db');
	}

	/**
	 * 信息流数据入redis
	 * cli : php D:/wamp/www/GraduationProject/index.php cli/feed article_from_sql_2_redis
	 */
	public function article_from_sql_2_redis(){
		//获取有效的用户id
		$result = $this->user_base_info_db->select('id',['status' => 1],'id desc',0,10000);
		$user_ids = !empty($result) ? array_column($result,'id') : [];
		if (empty($user_ids)){
			echo "没有有效的用户存在\n";
			exit;
		}
		//开启管道模式
		$this->myredis->pipeline();
		foreach ($user_ids as $key => $user_id){
			$user_articles_key = 'user_articles:'.$user_id;
			$feed_articles_key = 'feed_articles';
			//查询用户信息
			$articles_info = $this->user_article_db->select($user_id,'*',['article_status' => 1,'user_id' => $user_id],'modification_time desc',0,10000);
			if (empty($articles_info)){
				continue;
			}else{
				$user_person_info = $this->user_person_info_db->select('*',['user_id' => $user_id],'id desc',0,1);
				$user_person_info = isset($user_person_info[0]) ? $user_person_info[0] : [];
				foreach ($articles_info as $key1 => $article_info){
					$articles_info[$key1]['description'] = isset($user_person_info['description']) ? $user_person_info['description'] : '';
					$articles_info[$key1]['image'] = isset($user_person_info['image']) ? $user_person_info['image'] : '';
//					打入用户信息表
					$res_user_articles = $this->myredis->hSet($user_articles_key,$article_info['id'],json_encode($articles_info[$key1]));
//					打入信息时间表
					$this->myredis->zAdd($feed_articles_key,strtotime($article_info['modification_time']),($user_id.':'.$article_info['id']));
					if ($res_user_articles){
						echo "信息: {$article_info['article_name']} ==> 作者: {$article_info['article_author']}, 存入redis成功\n";
					}else{
						echo "信息: {$article_info['article_name']} ==> 作者: {$article_info['article_author']}, 存入redis失败\n";
					}
				}
			}
		}
		$this->myredis->exec();
		echo "\n\n所有用户信息存储完毕\n\n";
	}
}