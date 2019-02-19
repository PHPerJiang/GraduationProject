<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author: jiangyu01
 * @Time: 2019/2/19 13:27
 * @property Myredis myredis
 * @property User_person_info_biz user_person_info_biz
 */
class User_rank_biz extends CI_Model{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('myredis');
		$this->load->model('bizs/user_person_info_biz');
	}

	/**
	 * 获取排行榜缓存信息
	 * @return array
	 */
	public function get_user_rank(){
		$user_rank= $this->myredis->zRevRange('user_rank',0,4);
		$user_rank_infos = [];
		if (!empty($user_rank)){
			foreach ($user_rank as $key => $value){
				$user_rank_infos[] = json_decode($value,true);
			}
		}
		return $user_rank_infos;
	}
}