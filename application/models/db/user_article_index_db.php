<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author: jiangyu01
 * @Time: 2019/1/28 17:36
 */
class User_article_index_db extends CI_Model{
	//数据表
	private $table = 'user_article_index';
	//数据表字段
	private $fields = [
		'id' => NULL,
		'user_id' => NULL,
		'article_id' => NULL,
		'article_name' => NULL,
	];

	public function __construct()
	{
		parent::__construct();
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
	 * 获取全部信息
	 * @param array $where
	 * @param string $fields
	 * @param string $order
	 * @return mixed
	 * @Author: jiangyu01
	 * @Time: 2019/4/1 13:19
	 */
	public function get_all($where = [], $fields = '*', $order = 'id desc'){
		$query = $this->db
			->select($fields)
			->from($this->table)
			->where($where)
			->order_by($order)
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
	 * 数据修改
	 * @param $data
	 * @param $where
	 * @return array|bool
	 */
	public function update($data, $where){
		if (empty($where)){
			return FALSE;
		}
		$res = $this->field_check($data);
		if (empty($res)) goto END;
		$res = $this->db->set($res)->where($where)->update($this->table);
		END:
		return $res;
	}


	/**
	 * 索引信息修改
	 * @param array $params
	 * @return array|bool
	 * @Author: jiangyu01
	 * @Time: 2019/4/1 10:49
	 */
	public function  save($params = []){
		$user_info = $this->select('id',['user_id' => $params['user_id'], 'article_id' => $params['article_id']]);
		if ($user_info){
			$result = $this->update($params, ['user_id' => $params['user_id'], 'article_id' => $params['article_id']]);
		}else{
			$result = $this->insert($params);
		}
		return $result;
	}

	/**
	 * 数据删除
	 * @param $where
	 * @return bool
	 */
	public function delete($where){
		if (empty($where)){
			return FALSE;
		}
		$res = $this->db->where($where)->delete($this->table);
		return $res;
	}

	/**
	 * 字段校验
	 * @param $data
	 * @return array|bool
	 */
	private function field_check($data){
		if (!is_array($data)){
			return FALSE;
		}
		foreach ($data as $key => $value){
			if (in_array($key, $this->fields)){
				//转译入库数据
				$data[$key] = $this->db->escape($value);
			}
		}
		return $data;
	}
}