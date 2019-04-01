<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author: jiangyu01
 * @Time: 2019/1/8 9:09
 * @property CI_Session session
 * @property Myredis myredis
 * @property Feed_biz feed_biz
 * @property Common_biz common_biz
 * @property User_article_index_biz user_article_index_biz
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
		$this->load->model('bizs/common_biz');
		$this->load->model('bizs/user_article_index_biz');
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
			$person_info = $this->common_biz->validate_user_has_person($user_id);
			if (!$person_info){
				redirect('person/index');
			}
			//查询的关键词
			$search_article_info = $this->input->post('search_article_info');
			$feed_infos = [];
			if (empty($search_article_info)){
				//没关键词则走feed
				$feed_infos = $this->feed_biz->get_feed_info($user_id);
			}else{
				//有管检测就搜索
				$feed_infos = explode(':',$search_article_info);
				$feed_infos = $this->feed_biz->get_search_article_info($feed_infos[0], $feed_infos[1]);
			}
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
					$more_feed_info[$key]['good_pic']  = site_url('assets/images/gooded.png');
 				}
			}
			$this->resp($more_feed_info);
		}

	}

	/**
	 * 根据文章名称模糊搜索
	 * @Author: jiangyu01
	 * @Time: 2019/4/1 11:42
	 */
	public function search_article(){
		$search_article_name = $this->input->get('search_article_name');
		$data = [];
		$data = $this->user_article_index_biz->search_article_by_name($search_article_name);
		$this->resp($data);
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