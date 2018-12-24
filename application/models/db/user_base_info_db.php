<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author: jiangyu01
 * @Time: 2018/12/24 9:29
 */
class User_base_info_db extends CI_Model{
	private $table = 'user_base_info';
	public $fields = [
		'id' => NULL,
		'name' => '',
		'mobile' => '',
		'account' => '',
		'password' => '',
		'salt' => '',
		'status' => '',
		'ip' => '',
	];
	public function __construct()
	{
		$this->load->database();
	}

	/**
	 * 条件查询
	 * @param string $fields
	 * @param array $where
	 * @param string $order
	 * @param int $size
	 * @param int $limit
	 * @return mixed
	 */
	public function select($fields = '*', $where = ['id >' => 0],$order = 'id desc', $limit = 0, $size = 100){
		$query = $this->db
			->select($fields)
			->from($this->table)
			->where($where)
			->order_by($order)
			->limit($size,$limit)
			->get();
		$result = $query->result_array();
		$query->free_result();
		return $result;
	}

	/**
	 * 数据插入
	 * @param $data
	 * @return bool
	 */
	public function insert($data){
		if (empty($data)){
			return FALSE;
		}
		$res = $this->field_check($data);
		if (empty($res)) goto END;
		$res = $this->db->set($res)
			->insert($this->table);
		END:
		return $res;
	}

	/**
	 * 字段校验
	 * @param $data
	 * @return array|bool
	 */
	public function field_check($data){
		if (!is_array($data)){
			return FALSE;
		}
		$data = array_merge($this->fields, $data);
		foreach ($data as $key => $value){
			if (!isset($value)){
				unset($data[$key]);
			}
		}
		return $data;
	}

}