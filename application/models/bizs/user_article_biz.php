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
}