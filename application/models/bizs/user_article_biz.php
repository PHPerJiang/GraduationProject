<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author: jiangyu01
 * @Time: 2019/1/28 17:28
 * @property User_article_db user_article_db
 */
class User_article_biz extends CI_Model {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('db/user_article_db');
	}

	/**
	 * 根据文章id存储数据
	 * @param $article_id
	 * @param array $params
	 * @return array|bool
	 */
	public function save_article($article_id, $params = []){
		$result = $this->user_article_db->save($article_id, $params);
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
		return $result;
	}
}