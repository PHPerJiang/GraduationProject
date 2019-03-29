<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author: jiangyu01
 * @Time: 2019/2/13 10:10
 * @property User_article_db user_article_db
 * @property Myredis myredis
 * @property User_follow_info_db user_follow_info_db
 */
class Follow extends CI_Controller{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('myredis');
		$this->load->model('db/user_article_db');
		$this->load->model('db/user_follow_info_db');
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

	/**
	 * 文章推送程序
	 * cli : php D:/wamp/www/GraduationProject/index.php cli/follow article_push
	 * @Author: jiangyu01
	 * @Time: 2019/3/29 14:08
	 */
	public function article_push(){
		while (1){
			$push_article_info = $this->myredis->rPop('article_push_list');
			if (!empty($push_article_info)){
				$push_article_info = explode(':', $push_article_info);
				//获取用户粉丝列表
				$fans_list = $this->myredis->sMembers('user_fans_info:'.$push_article_info['0']);
				if (!empty($fans_list)){
					foreach ($fans_list as $key => $value){
						if ($push_article_info[3] == 'add'){
							//文章推送
							$this->myredis->zAdd('person_feed:'.$value,$push_article_info[2],$push_article_info[0].':'.$push_article_info[1]);
							echo "用户 {$push_article_info[0]} 的文章 {$push_article_info[1]} 已推送至用户 {$value} 的个人feed流中\n";
						}elseif ($push_article_info[3] == 'del'){
							//文章取消推送
							$this->myredis->zRem('person_feed:'.$value,$push_article_info[0].':'.$push_article_info[1]);
							echo "用户 {$push_article_info[0]} 的文章 {$push_article_info[1]} 从用户 {$value} 的个人feed流中移除\n";
						}
					}
				}
			}else{
				echo "article_push_list is empty.\n";
			}
			sleep(3);
		}
	}

	/**
	 * cli : php D:/wamp/www/GraduationProject/index.php cli/follow sync_user_fans_to_redis
	 * 将用户粉丝数维护进redis
	 * @Author: jiangyu01
	 * @Time: 2019/3/29 13:14
	 */
	public function sync_user_fans_to_redis(){
		$res = $this->user_follow_info_db->get_all([],'user_id,user_follow_id');
		if (!empty($res)){
			$this->myredis->pipeline();
				foreach ($res as $key => $value){
					$this->myredis->sAdd('user_fans_info:'.$value['user_follow_id'],$value['user_id']);
					echo "用户 {$value['user_id']} 已添加到用户 {$value['user_follow_id']} 的粉丝集合中\n";
					sleep(1);
				}
			$this->myredis->exec();
		}
	}
}