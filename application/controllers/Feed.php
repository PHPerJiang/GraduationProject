<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author: jiangyu01
 * @Time: 2019/1/8 9:09
 * @property CI_Session session
 * @property Myredis myredis
 * @property Feed_biz feed_biz
 */
class Feed extends CI_Controller{
	private $error_code = 0;
	private $error_msg = '';
	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('url'));
		$this->load->library('session');
		$this->load->model('bizs/feed_biz');
	}

	/**
	 * feed流首页
	 */
	public function index(){
		//判断是否已登录
		if (!$this->session->is_login()){
			redirect('login/index');
		}else{
			$user_id = $this->session->userdata['user_id'];
			$feed_infos = $this->feed_biz->get_feed_info($user_id);
			$data['articles_info'] = empty($feed_infos) ? [] : $feed_infos;
			$data['user_image'] = isset($_SESSION['user_image']) ? $_SESSION['user_image'] : site_url('assets/images/user-pic.png');
			$this->load->view('web/feed/index',$data);
		}
	}

	/**
	 *获取更多的feed信息
	 */
	public function more_feed_info(){
		//判断是否已登录
		if (!$this->session->is_login()){
			redirect('login/index');
		}else{
			$user_id = $this->session->userdata['user_id'];
			$offset = $this->input->post('offset');
			$size = $this->input->post('size');
			$options = [
				'offset' => empty($offset) ? 0 : $offset,
				'size'  => empty($size) ? 0 : $size,
			];
			$more_feed_info = $this->feed_biz->get_feed_info($user_id, $options);
			if (!empty($more_feed_info)){
				foreach ($more_feed_info as $key => $value){
					$more_feed_info[$key]['image'] = site_url('assets/'.$value['image']);
					$more_feed_info[$key]['jump_to'] = site_url('article/read').'?article_id='.$value['user_id'].':'.$value['id'];
 				}
			}
			$this->resp($more_feed_info);
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