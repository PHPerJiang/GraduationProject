<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author: jiangyu01
 * @Time: 2019/2/21 16:47
 * @property User_person_info_db user_person_info_db
 * @property Myredis myredis
 */
class Person extends CI_Controller{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('db/user_person_info_db');
		$this->load->library('myredis');
		$this->load->helper('url');
	}

	/**
	 *将用户的信息打入redis做持久化存储
	 * cli:  php D:/wamp/www/GraduationProject/index.php cli/person person_info_2_redis
	 */
	public function person_info_2_redis(){
		$person_infos = $this->user_person_info_db->select('*',['id >'=> 0],'id desc',0,10000);
		if (!empty($person_infos)){
			$this->myredis->pipeline();
			foreach ($person_infos as $key => $value){
				$redis_key_name = "person_info:{$value['user_id']}";
				$person_infos[$key]['image'] = site_url('assets/'.$value['image']);
				$this->myredis->set($redis_key_name,json_encode($person_infos[$key]));
				echo "{$redis_key_name} ==> redis success\n";
				sleep(1);
			}
			$this->myredis->exec();
			echo "\n数据同步完毕\n";
		}else{
			echo "没有用户个人信息\n";
		}
	}
}