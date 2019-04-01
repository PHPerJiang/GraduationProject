<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author: jiangyu01
 * @Time: 2019/1/31 14:38
 * @property Myredis myredis
 * @property User_article_index_db user_article_index_db
 */
class User_article_index_biz extends CI_Model{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('myredis');
		$this->load->model('db/user_article_index_db');
	}

	/**
	 * 根据文章名称进行模糊搜索
	 * @param string $search_name
	 * @Author: jiangyu01
	 * @Time: 2019/4/1 13:16
	 */
	public function search_article_by_name($search_name = ''){
		if (empty($search_name)) return false;
		$article_infos = $this->user_article_index_db->get_all("article_name like '%{$search_name}%'");
		$data = [];
		if (!empty($article_infos) && is_array($article_infos)){
			foreach ($article_infos as $key =>$value){
				$article_info = $this->myredis->hGet('user_articles:'.$value['user_id'],$value['article_id']);
				$article_info = json_decode($article_info, true);
				if (!empty($article_info)){
					$data[] = [
							'user_id' => $article_info['user_id'],
							'article_id' => $article_info['id'],
							'article_name' => $article_info['article_name']
						];
				}
			}
		}
		return$data;
	}
}