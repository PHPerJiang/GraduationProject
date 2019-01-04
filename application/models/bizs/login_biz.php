<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 登录、注册、忘记密码业务处理
 * @Author: jiangyu01
 * @Time: 2018/12/25 17:29
 * @property User_base_info_db user_base_info_db
 */

class Login_biz extends CI_Model {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('db/user_base_info_db');
	}

	/**
	 * 登录业务
	 */
	public function login($account = '', $password = ''){
		if (empty($account) || empty($password)){
			return FALSE;
		}
		$is_login = FALSE;
		$result = $this->user_base_info_db->select('password,salt',['account'=>$account,'status'=>1]);
		$result = !empty($result) ? (isset($result[0]) ? (!empty($result[0]) ? $result[0] : []) : []) :[];
		if (!empty($result) && isset($result['salt']) && isset($result['password'])){
			$is_login = (crypt($password,$result['salt']) === $result['password']) ?  TRUE : FALSE;
		}
		return $is_login;
	}

	/**
	 * 注册业务
	 */
	public function register($params = []){
		if (empty($params)) return FALSE;
		$default_params = [
			'account'     => NULL,
			'password'    => NULL,
			'status'      => 1,
			'salt'         => rand(1000,9999),
			'ip'           => NULL,
			'last_login_time' => time(),
		];
		$params = array_merge($default_params, $params);
		$params['password'] = crypt($params['password'],$params['salt']);
		$result = $this->user_base_info_db->insert($params);
		$result = !empty($result) ? $result : [];
		return $result;
	}

	/**
	 * 忘记密码业务
	 */
	public function retrieve(){

	}
}