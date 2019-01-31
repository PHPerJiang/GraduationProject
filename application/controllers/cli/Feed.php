<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author: jiangyu01
 * @Time: 2019/1/30 15:08
 * @property Myredis myredis
 * @property User_article_db user_article_db
 * @property User_base_info_db user_base_info_db
 */
class Feed extends CI_Controller{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('myredis');
		$this->load->model('db/user_article_db');
		$this->load->model('db/user_base_info_db');
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
		//根据用户id查询对应的已发布信息
		foreach ($user_ids as $key => $user_id){
			//查询用户信息
			$articles_info = $this->user_article_db->select($user_id,'*',['article_status' => 1,'user_id' => $user_id],'modification_time desc',0,10000);
			if (empty($articles_info)){
				continue;
			}else{
				$redis_tmp_key = 'article_feed_tmp';        //临时key
				$redis_key = 'article_feed';                   //持久key
				foreach ($articles_info as $key1 => $article_info){
					$res = $this->myredis->zAdd($redis_tmp_key,strtotime($article_info['modification_time']),json_encode($article_info));
					if ($res){
						echo "信息: {$article_info['article_name']} ==> 作者: {$article_info['article_author']}, 存入feed流成功\n";
					}else{
						echo "信息: {$article_info['article_name']} ==> 作者: {$article_info['article_author']}, 存入feed流失败\n";
					}
				}
				if ($this->myredis->reName($redis_tmp_key, $redis_key)){
					echo "\n用户: $user_id  ， 信息入流完毕！\n\n";
				}
			}
		}
		//执行管道
		$this->myredis->exec();
		echo "\n\n所有信息打入feed流完毕\n\n";
	}
}