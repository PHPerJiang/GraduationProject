<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author: jiangyu01
 * @Time: 2019/2/18 11:37
 * @property CI_Session session
 */
class Rank extends CI_Controller{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('session');
	}

	/**
	 * 排行榜首页
	 */
	public function index(){
		if (!$this->session->is_login()){
			redirect('login/index');
		}else{
			$this->load->view('web/rank/index');
		}
	}
}