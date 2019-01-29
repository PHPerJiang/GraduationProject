<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author: jiangyu01
 * @Time: 2019/1/29 11:11
 * @property CI_Session session
 * @property User_article_biz user_article_biz
 */
class Article_list extends CI_Controller{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->model('bizs/user_article_biz');
	}

	public function index(){
		if (!$this->session->is_login()){
			redirect('login/index');
		}else{
			$user_id = $this->session->userdata['user_id'];
			$articles_info = $this->user_article_biz->find_articles_by_user_id($user_id);
			$data['articles_info'] = empty($articles_info) ? [] : $articles_info;
			$data['user_image'] = isset($_SESSION['user_image']) ? $_SESSION['user_image'] : '';
			$this->load->view('web/article_list/index', $data);
		}
	}
}