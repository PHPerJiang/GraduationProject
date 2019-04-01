<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author: jiangyu01
 * @Time: 2019/1/31 14:38
 * @property Myredis myredis
 * @property User_evaluate_info_biz user_evaluate_info_biz
 */
class Feed_biz extends CI_Model{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('myredis');
		$this->load->model('bizs/user_evaluate_info_biz');
	}

	/**
	 * 获取用户的关注流
	 * @param $user_id
	 * @param array $option
	 */
	public function get_feed_info($user_id, $option = []){
		$data = [];
		//获取缓存里的数据
		$size = isset($option['size']) ? (!empty($option['size']) ? $option['size'] : 9):9;
		$offset = isset($option['offset']) ? (!empty($option['offset']) ? $option['offset'] : 0):0;
		$feed_infos_from_redis = $this->myredis->zRevRange('feed_articles',$offset, $size);
		//获取集体的信息数据
		if (!empty($feed_infos_from_redis) && is_array($feed_infos_from_redis)){
			foreach ($feed_infos_from_redis as $key => $value){
				$redis_info = explode(':',$value);
				$res = $this->myredis->hGet('user_articles:'.$redis_info[0],$redis_info[1]);
				if (!empty($res)){
					$data[] = json_decode($res,true);
				}
			}
		}
		if (!empty($data)){
			foreach ($data as $key => $value){
				$data[$key]['good_num'] = $this->user_evaluate_info_biz->get_user_evaluate(0,$value['id'],$value['user_id']);
			}
		}
		return $data;
	}

	/**
	 * @param $user_id
	 * @param $article_id
	 * @Author: jiangyu01
	 * @Time: 2019/4/1 14:50
	 */
	public function get_search_article_info($user_id, $article_id){
		$article_info = [];
		$article_info = $this->myredis->hGet('user_articles:'.$user_id,$article_id);
		$article_info = json_decode($article_info, true);
		unset($article_info['good']);
		if (!empty($article_info)){
			$article_info['good_num'] = $this->user_evaluate_info_biz->get_user_evaluate(0,$article_info['id'],$article_info['user_id']);
		}
		return [$article_info];
	}
}