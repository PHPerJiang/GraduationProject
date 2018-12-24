<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author: jiangyu01
 * @Time: 2018/12/24 9:27
 * @property User_base_info_db user_base_info_db
 */
class User_base_info_biz extends CI_Model {
	public function __construct()
	{
		$this->load->model('db/user_base_info_db');
	}
	public function  get_all(){
		$result = $this->user_base_info_db->select();
		if (!empty($result)) return $result;
	}

	public function data_set($param = []){
		if (empty($param)) return FALSE;
		$resule = $this->user_base_info_db->insert($param);
		if (!empty($resule)) return $resule;
	}

}