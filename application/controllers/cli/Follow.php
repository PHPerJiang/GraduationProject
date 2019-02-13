<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author: jiangyu01
 * @Time: 2019/2/13 10:10
 * @property User_article_db user_article_db
 * @property Myredis myredis
 */
class Follow extends CI_Controller{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('myredis');
		$this->load->model('db/user_article_db');
	}

	/**
	 * cli : php D:/wamp/www/GraduationProject/index.php cli/follow follow_event_list
	 * 启动关注队列监控程序
	 */
	public function follow_event_list(){
		while (1){
			$redis_key_name = 'follow_event_list';
			//获取监控队列中的信息并解析
			$user_follow_info = $this->myredis->rPop($redis_key_name);
			//根据解析后的信息查询用户文章信息
			if ($user_follow_info){
				$user_follow_info = explode(':',$user_follow_info);
				//获取redis中用户信息
				$user_article_info = $this->myredis->hGetAll('user_articles:'.$user_follow_info[1]);
				//关注流程
				if ($user_follow_info[2] == 'follow'){
					if ($user_article_info && is_array($user_article_info)){
						//将查询到的信息打入个人feed流中
						$this->myredis->pipeline();
						foreach ($user_article_info as $key => $value){
							$value = json_decode($value,true);
							$this->myredis->zAdd('person_feed:'.$user_follow_info[0],strtotime($value['modification_time']),$value['user_id'].':'.$value['id']);
							echo 'person_feed:'.$user_follow_info[0].'  add   user : '.$value['user_id'].' 的atricle : '.$value['id'].'  success '."\n";
						}
						$this->myredis->exec();
					}
				}
				//取关流程
				if ($user_follow_info[2] == 'unfollow'){
					$this->myredis->pipeline();
					foreach ($user_article_info as $key => $value){
						$value = json_decode($value,true);
						$this->myredis->zRem('person_feed:'.$user_follow_info[0],$user_follow_info[1].':'.$key);
						echo 'person_feed:'.$user_follow_info[0].'  remove   user : '.$value['user_id'].' 的atricle : '.$value['id'].'  success '."\n";
					}
					$this->myredis->exec();
				}
			}
			echo "list is empty.\n";
			sleep(3);
		}
	}
}