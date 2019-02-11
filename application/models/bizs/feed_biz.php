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
		return $data;
	}
}