<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author: jiangyu01
 * @Time: 2019/1/29 11:11
 * @property CI_Session session
 */
class Article_list extends CI_Controller{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('session');
	}

	public function index(){
		if (!$this->session->is_login()){
			redirect('login/index');
		}else{
			$data['user_image'] = isset($_SESSION['user_image']) ? $_SESSION['user_image'] : '';
			$this->load->view('web/article_list/index', $data);
		}
	}
}