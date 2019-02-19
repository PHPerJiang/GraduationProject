<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author: jiangyu01
 * @Time: 2019/2/18 11:37
 * @property CI_Session session
 * @property User_rank_biz user_rank_biz
 */
class Rank extends CI_Controller{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->model('bizs/user_rank_biz');
	}

	/**
	 * 排行榜首页
	 */
	public function index(){
		if (!$this->session->is_login()){
			redirect('login/index');
		}else{
			$data['user_image'] = isset($_SESSION['user_image']) ? $_SESSION['user_image'] : site_url('assets/images/user-pic.png');
			$data['user_rank_infos'] = $this->user_rank_biz->get_user_rank();
			$this->load->view('web/rank/index',$data);
		}
	}
}