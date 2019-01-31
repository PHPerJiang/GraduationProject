<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author: jiangyu01
 * @Time: 2019/1/31 14:38
 * @property Myredis myredis
 */
class Feed_biz extends CI_Model{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('myredis');
	}

	/**
	 * 获取用户的关注流
	 * @param $user_id
	 * @param array $option
	 */
	public function get_feed_info($user_id, $option = []){
		$data = [];
		$feed_infos_from_redis = $this->myredis->zRevRange('article_feed',0,-1);
		if (!empty($feed_infos_from_redis) && is_array($feed_infos_from_redis)){
			foreach ($feed_infos_from_redis as $key => $value){
				$data[] = json_decode($value,true);
			}
		}
		return $data;
	}
}