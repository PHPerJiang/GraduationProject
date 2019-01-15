<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author: jiangyu01
 * @Time: 2019/1/15 13:16
 * @property CI_Session session
 */
class Person extends CI_Controller{
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
			$this->load->view('web/person/index');
		}
	}
}