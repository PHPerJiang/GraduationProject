<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author: jiangyu01
 * @Time: 2019/1/28 17:28
 * @property User_article_db user_article_db
 * @property Myredis myredis
 * @property User_person_info_db user_person_info_db
 */
class User_article_biz extends CI_Model {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('db/user_article_db');
		$this->load->model('db/user_person_info_db');
		$this->load->library('myredis');
	}

	/**
	 * 根据文章id存储数据
	 * @param $article_id
	 * @param array $params
	 * @return array|bool
	 */
	public function save_article($article_id, $params = []){
		$result = $this->user_article_db->save($article_id, $params);
		if ($params['article_status'] == 1 && $result){
			$res = $this->user_article_db->select($params['user_id'],'*',['article_status' => 1],'id desc',0,1);
			$res = !empty($res) ? $res[0] : [];
			if ($res){
				//获取用户简介及头像
				$user_person_info = $this->user_person_info_db->select('*',['user_id' => $res['user_id']],'id desc',0,1);
				$user_person_info = isset($user_person_info[0]) ? $user_person_info[0] : [];
				$res['description'] = isset($user_person_info['description']) ? $user_person_info['description'] : '';
				$res['image'] = isset($user_person_info['image']) ? $user_person_info['image'] : '';
				//同步缓存
				$this->sync_article_2_redis($res['user_id'],$res['id'],$res,'add');
			}
		}
		return $result;
	}

	/**
	 * 根据用户id查询文章
	 * @param $user_id
	 * @param array $where
	 * @return mixed
	 */
	public function find_articles_by_user_id($user_id,$where = []){
		$user_id = is_numeric($user_id) ? $user_id : 0;
		$where = ['user_id' => $user_id];
		$result = $this->user_article_db->select($user_id,'*',$where,'id desc',0,10000);
		return $result;
	}

	/**
	 * 根据用户id删除信息
	 * @param $user_id
	 * @param $article_id
	 * @param array $option
	 */
	public function del_article_by_user_id($user_id, $article_id, $option = []){
		$user_id = is_numeric($user_id) ? $user_id : 0;
		$result = $this->user_article_db->delete($user_id,['id' => $article_id]);
		if ($result){
			$this->sync_article_2_redis($user_id,$article_id,'','del');
		}
		return $result;
	}

	/**
	 * 同步信息数据入redis
	 * @param $user_id
	 * @param $article_id
	 * @param $article
	 * @param string $action
	 */
	public function sync_article_2_redis($user_id,$article_id,$article,$action = 'add'){
		if ($action == 'add'){
			//同步用户信息表
			$this->myredis->hSet('user_articles:'.$user_id,$article_id,json_encode($article));
			//同步信息流表
			$this->myredis->zAdd('feed_articles',strtotime($article['modification_time']),$user_id.':'.$article_id );
		}elseif ($action == 'del'){
			//删除信息流中的数据
            $this->myredis->zRem('feed_articles',$user_id.':'.$article_id);
            //删除用户信息表中的数据
			$this->myredis->hdel('user_articles:'.$user_id,$article_id);
		}
	}
}