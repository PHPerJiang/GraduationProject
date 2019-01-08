<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author: jiangyu01
 * @Time: 2019/1/8 9:09
 */
class Feed extends CI_Controller{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('url'));
	}
	public function index(){
		$this->load->view('web/feed/index');
	}
}