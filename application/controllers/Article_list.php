<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author: jiangyu01
 * @Time: 2019/1/29 11:11
 * @property CI_Session session
 * @property User_article_biz user_article_biz
 * @property User_evaluate_info_biz user_evaluate_info_biz
 */
class Article_list extends CI_Controller{

	private $error_code = 0;
	private $error_msg = 'success';

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->model('bizs/user_article_biz');
		$this->load->model('bizs/user_evaluate_info_biz');
	}

	public function index(){
		if (!$this->session->is_login()){
			redirect('login/index');
		}else{
			$user_id = $this->session->userdata['user_id'];
			$articles_info = $this->user_article_biz->find_articles_by_user_id($user_id);
			//获取文章点赞数
			if (!empty($articles_info) && is_array($articles_info)){
				foreach ($articles_info as $key => $value){
					$articles_info[$key]['good_num'] = $this->user_evaluate_info_biz->get_user_evaluate($user_id,$value['id'],$value['user_id']);
				}
			}
			$data['articles_info'] = empty($articles_info) ? [] : $articles_info;
			$data['user_image'] = isset($_SESSION['user_image']) ? $_SESSION['user_image'] : site_url('assets/images/user-pic.png');
			$data['article_edit'] = site_url('article/edit');
			$data['article_del'] = site_url('article/del');
			$this->load->view('web/article_list/index', $data);
		}
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
			'rdata'       => $data,
		]);
		return;
	}
}