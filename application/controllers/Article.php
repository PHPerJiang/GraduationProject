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
	}

	public function index(){
		$this->load->view('web/article/index');
	}
}