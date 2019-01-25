<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 登录、注册、忘记密码业务处理
 * @Author: jiangyu01
 * @Time: 2018/12/25 17:29
 * @property User_base_info_db user_base_info_db
 * @property CI_Session session
 */

class Login_biz extends CI_Model {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('db/user_base_info_db');
		$this->load->library('session');
	}

	/**
	 * 登录业务
	 */
	public function login($account = '', $password = '', $option = []){
		if (empty($account) || empty($password)){
			return FALSE;
		}
		$is_login = FALSE;
		//查看是否已经存在此用户
		$result = $this->user_base_info_db->select('id,password,salt',['account'=>$account,'status'=>1]);
		$result = !empty($result) ? (isset($result[0]) ? (!empty($result[0]) ? $result[0] : []) : []) :[];
		if (!empty($result) && isset($result['salt']) && isset($result['password'])){
			if (isset($option['is_from']) && ($option['is_from'] == 'register')){
				$is_login = ($password === $result['password']) ? TRUE : FALSE;
			}else{
				//加盐加密验证
				$is_login = (crypt($password,$result['salt']) === $result['password']) ?  TRUE : FALSE;
			}
		}
		if ($is_login){
			//登录成功时保存user_id，并设置过期时间为30min
			$this->session->set_tempdata('user_id',$result['id'],1800);
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
		//加盐加密
		$params['password'] = crypt($params['password'],$params['salt']);
		$result = $this->user_base_info_db->insert($params);
		$is_register = !empty($result) ? TRUE : FALSE;
		if ($is_register){
			//注册成功则自动登录
			$this->login($params['account'],$params['password'] ,['is_from' => 'register']);
		}
		return $is_register;
	}

	/**
	 * 校验账号是否注册
	 * @param string $account
	 * @return bool
	 */
	public function validate_account($account = ''){
		if (empty($account)) return FALSE;
		$is_account = FALSE;
		$result = $this->user_base_info_db->select('id',['account'=>$account]);
		$result = !empty($result) ? (isset($result[0]) ? (!empty($result[0]) ? $result[0] : []) : []) :[];
		if (!$result){
			$is_account = TRUE;
		}
		return $is_account;
	}

	/**
	 * 忘记密码业务
	 */
	public function retrieve(){

	}
}