<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author: jiangyu01
 * @Time: 2019/1/25 13:44
 */
class Article extends CI_Controller{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('session');
	}

	public function index(){
		//判断是否已登录
		if (!$this->session->is_login()){
			redirect('login/index');
		}else{
			$data['user_image'] = isset($_SESSION['user_image']) ? $_SESSION['user_image'] : '';
			$this->load->view('web/article/index', $data);
		}

	}
}