<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author: jiangyu01
 * @Time: 2019/1/25 13:44
 * @property User_article_biz user_article_biz
 * @property CI_Session session
 */
class Article extends CI_Controller{
	private $error_code = 0;
	private $error_msg = 'success';
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->model('bizs/user_article_biz');
	}

	//加载首页
	public function index(){
		//判断是否已登录
		if (!$this->session->is_login()){
			redirect('login/index');
		}else{
			$user_id = $this->session->userdata['user_id'];
			$data['user_image'] = isset($_SESSION['user_image']) ? $_SESSION['user_image'] : '';
			$data['user_nickname'] = isset($_SESSION['user_nickname']) ?  $_SESSION['user_nickname'] : '用户'.$user_id;
			$this->load->view('web/article/index', $data);
		}
	}

	//编辑
	public function edit(){
		$article_id = $this->input->get('article_id');
		if (!$this->session->is_login()){
			redirect('login/index');
		}else{
			if (!$article_id){
				$this->index();
			}else{
				$user_id = $this->session->userdata['user_id'];
				$articles_info = $this->user_article_biz->find_articles_by_user_id($user_id,['id' => $article_id]);
				$data['articles_info'] = empty($articles_info) ? [] : (isset($articles_info[0]) ? $articles_info[0] : []);
				$data['user_image'] = isset($_SESSION['user_image']) ? $_SESSION['user_image'] : '';
				$data['user_nickname'] = isset($_SESSION['user_nickname']) ?  $_SESSION['user_nickname'] : '用户'.$user_id;
				$this->load->view('web/article/index', $data);
			}
		}
	}

	/**
	 * 存储
	 */
	public function save_article(){
		//获取信息
		$article_name = $this->input->post('article_name');
		$article_intro = $this->input->post('article_intro');
		$article_author = $this->input->post('article_author');
		$article_content = $this->input->post('article_content');
		$article_status = $this->input->post('article_status');
		$user_id = $this->input->post('user_id');
		$article_id = $this->input->post('article_id');
		$article_id = empty($article_id) ? 0 : $article_id;

		//校验用户
		if (!$user_id){
			$this->error_msg = '用户不存在';
			$this->error_code = 1;
			goto END;
		}

		//参数拼凑
		$params = [
			'user_id' => $user_id,
			'article_name' => isset($article_name) ? $article_name : '',
			'article_intro' => isset($article_intro) ? $article_intro : '',
			'article_author' => isset($article_author) ? $article_author : '',
			'article_content' => isset($article_content) ? $article_content : '',
			'article_status' => isset($article_status) ? $article_status : 0,
		];
		$data = [];
		$result = $this->user_article_biz->save_article($article_id,$params);
		if (!$result){
			$this->error_code = 1;
			$this->error_msg = '网络错误';
		}
		END:
		$this->resp();
	}

	public function del(){
		$article_id = $this->input->get('article_id');
		if (!$this->session->is_login()){
			redirect('login/index');
		}else{
			if (!$article_id){
				redirect('article_list/index');
			}else{
				$user_id = $this->session->userdata['user_id'];
				$articles_info = $this->user_article_biz->del_article_by_user_id($user_id,$article_id);
				redirect('article_list/index');
			}
		}
	}

	/**
	 * 用户读文章
	 */
	public function read(){
		//判断是否已登录
		if (!$this->session->is_login()){
			redirect('login/index');
		}else{
			$article_id = $this->input->get('article_id');
			if ($article_id && is_string($article_id)){
				$params = explode(':',$article_id);
				if (!empty($params) && count($params) == 2){
					$user_id = $params[0];
					$article_id = $params[1];
				}else{
					redirect('feed/index');
				}
			}
			$articles_info = $this->user_article_biz->find_articles_by_user_id($user_id,['id' => $article_id, 'article_status' => 1]);
			$data['articles_info'] = empty($articles_info) ? [] : (isset($articles_info[0]) ? $articles_info[0] : []);
			$data['user_image'] = isset($_SESSION['user_image']) ? $_SESSION['user_image'] : '';
			$data['user_nickname'] = isset($data['articles_info']['article_author']) ?  $data['articles_info']['article_author'] :  '用户'.$user_id;
			$this->load->view('web/article/read',$data);
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