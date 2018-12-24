<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author: jiangyu01
 * @Time: 2018/12/24 9:27
 * @property User_base_info_db user_base_info_db
 */
class User_base_info_biz extends CI_Model {

	private $error_code = 1;
	private $error_msg = '';

	public function __construct()
	{
		$this->load->model('db/user_base_info_db');
	}

	/**
	 * 数据获取
	 */
	public function  get_all(){
		$result = $this->user_base_info_db->select();
		$result = !empty($result) ? $result : [];
		$this->resp($result);
	}

	/**
	 * 数据存储
	 * @param array $params
	 * @return bool
	 */
	public function data_set($params = []){
		if (empty($params)) return FALSE;
		$result = $this->user_base_info_db->insert($params);
		$result = !empty($result) ? $result : [];
		$this->resp($result);
	}

	/**
	 * 数据更新
	 * @param array $params
	 * @param array $where
	 * @return bool
	 */
	public function data_update($params = [], $where = []){
		if (empty($params)) return FALSE;
		$result = $this->user_base_info_db->update($params, $where);
		$result = !empty($result) ? $result : [];
		$this->resp($result);
	}

	/**
	 * 数据删除
	 * @param array $where
	 * @return bool
	 */
	public function data_del($where = []){
		if (empty($where)) return FALSE;
		$result = $this->user_base_info_db->delete($where);
		$result = !empty($result) ? $result : [];
		$this->resp($result);
	}

	/**
	 * 数据输出
	 * @param array $data
	 * @param string $total
	 */
	private function resp($data = []) {
		header('Content-type: application/json');
		echo json_encode([
			'error_code' => $this->error_code,
			'error_msg'  => $this->error_msg,
			'data'       => $data,
		]);
		return;
	}

}