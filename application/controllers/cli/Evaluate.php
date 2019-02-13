<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author: jiangyu01
 * @Time: 2019/2/13 16:48
 */
class Evaluate extends CI_Controller{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('myredis');
	}


}