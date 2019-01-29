<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author: jiangyu01
 * @Time: 2019/1/28 17:36
 */
class User_article_db extends CI_Model{
	//数据表
	private $table = 'user_article_';
	//数据表字段
	private $fields = [
		'id' => NULL,
		'user_id' => NULL,
		'article_name' => NULL,
		'article_intro' => NULL,
		'article_author' => NULL,
		'article_content' => NULL,
		'article_status' => NULL,
	];

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	/**
	 * 根据文章id存储数据
	 * @param $article_id
	 * @param array $params
	 * @return array|bool
	 */
	public function  save($article_id, $params = []){
		$article_info = $this->select($params['user_id'],'id',['id' => $article_id]);
		if ($article_info){
			$result = $this->update($params, ['id' => $article_id]);
		}else{
			$result = $this->insert($params);
		}
		return $result;
	}

	/**
	 * 条件查询
	 * @param int $user_id
	 * @param string $fields
	 * @param array $where
	 * @param string $order
	 * @param int $size
	 * @param int $limit
	 * @return mixed
	 */
	public function select($user_id, $fields = '*', $where = ['id >' => 0],$order = 'id desc', $limit = 0, $size = 100){
		$this->table = $this->table.($user_id % 10);
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
	 * 数据删除
	 * @param $user_id
	 * @param $where
	 * @return bool
	 */
	public function delete($user_id,$where){
		$this->table = $this->table.($user_id % 10);
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