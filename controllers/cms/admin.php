<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends  CI_Controller
{
	function __construct()
	{
		header("Content-type: text/html; charset=utf-8");
		parent :: __construct();
		; $this->load->helper('url')
		; $this->load->model('cms/user_model'	,'user_model')
		; $this->load->model('cms/article_model','article_model')
		; $this->load->model('cms/notice_model'	,'notice_model')
		; $this->load->model('cms/message_model','message_model')

		; $this->define()
		; $this->init()
		;
	}

	function main()
	{
		; $this->load->view('cms/admin_view', $this->data)
		;
	}
	//user
	function add_user()
	{
		; $this->load->view('cms/user/add_user_view', $this->data)
		;
	}

	function user_list()
	{
		; $user_list 
			= $this->user_model->get_all_user()

		; $this->data['user_list'] = $user_list
		; $this->load->view('cms/user/user_list_view',$this->data)
		;
	}

	function modify_passwd()
	{
		; $this->data['action'] = 'cms/user/reset_passwd/';
		; $this->load->view('cms/user/chang_passwd_view',$this->data)
		;
	}
	//articles
	function display_articles($pageNum=1)
	{
		$this->data['article_list'] = $this->article_model->get_article_list($pageNum, $this->numPerPage);
		// ; echo $this->table->generate($car_list)
		
		$url 	   = 'cms/admin/display_articles';
		$total_row = $this->article_model->get_total_row();

		$this->data['pagination'] = $this->get_pagination($url, $total_row);

		$this->load->view('cms/article/article_list_view', $this->data);
	}

	function display_single_article($aid)
	{
		$article = $this->article_model->get_article_detail($aid);

		$this->data = array_merge($this->data, $article);

		$this->load->view('cms/article/single_article_view', $this->data);
	}

	function edit_article()
	{

		; $this->data['article_action'] = 'cms/admin/insert_article'

		; $this->load->view('cms/article/edit_article_view',$this->data)
		;
	}

	function update_article($aid)
	{
		$article = $this->article_model->get_article_detail($aid);

		$this->data['article_action'] = 'cms/admin/update_success';
		$this->data['aid'] = $aid;
		$this->data = array_merge($this->data, $article);

		$this->load->view('cms/article/edit_article_view', $this->data);
	}

	function insert_article()
	{
		$post = $_POST;
		$this->data['type'] = 'article';
		$this->data['result'] = $this->article_model
			 						 ->insert_article($post['author'],
									 			  	$post['title'],
									 			  	$post['content']);
			 						 
		$this->load->view('cms/edit_result_view',$this->data);
	}	

	function update_success()
	{
		// echo '<pre>';
		$post = $_POST;
		$aid = $post['aid'];

		$this->data['result'] = $this->article_model->update_article($aid, $post);
		$this->data['type'] = 'article';
		$this->load->view('cms/edit_result_view', $this->data);
	}

	function delete_article($aid = NULL)
	{
		$result = $this->article_model->delete_article($aid);

		if ($result)
		{
			$this->data['result'] = 'delete';
		}

		header("location:".site_url('cms/admin/display_articles'));
		// $this->load->view('cms/edit_result_view', $this->data);
	}	


	// car_notice
	function display_car_notice_list($pageNum='1')
	{
		 $this->data['car_notice_list'] = $this->notice_model->get_notice_list($pageNum, $this->numPerPage,'mainpage');

	
		$url 	   = 'cms/admin/display_car_notice_list';
		$total_row = $this->notice_model->get_total_row();

		$this->data['pagination'] = $this->get_pagination($url, $total_row);

		$this->load->view('cms/car_notice/car_notice_list_view', $this->data);
	}

	function display_car_notice_detail($nid)
	{
		$car_notice = $this->notice_model->get_notice_detail($nid);

		$this->data = array_merge($this->data, $car_notice);

		$this->load->view('cms/car_notice/car_notice_detail_view', $this->data);
	}

	function edit_car_notice()
	{

		; $file_name = 'pic'
		; $this->data['action']    = base_url().'index.php/cms/image/postImage' .'/'.$file_name
		; $this->data['file_name'] = $file_name

		; $this->data['car_notice_action'] = 'cms/admin/insert_car_notice'

		; $this->load->view('cms/car_notice/edit_car_notice_view',$this->data)
		;
	}


	function update_car_notice($nid)
	{
		
		$car_notice = $this->notice_model->get_notice_detail($nid);
		$this->data['car_notice_action'] = 'cms/admin/update_car_notice_success';
		$this->data['nid'] = $nid;
		$this->data = array_merge($this->data, $car_notice);

		$this->load->view('cms/car_notice/edit_car_notice_view', $this->data);
	}

	function insert_car_notice()
	{
		$post = $_POST;

		
		$this->data['type'] = 'car_notice';

		$this->data['result'] = $this->notice_model
			 						 ->insert_normal_notice($post['title'],
			 						 						$post['content'],
			 						 						$post['img_list'],
			 						 						$this->data['type']);

		$car_notice = array 
						(
							"price" 			=>$post['price']
							,"market_price"        			=>$post['market_price']
							,"location" 			=>$post['province'].$post['city'].$post['district']
							,"brand" 			=>$post['brand']
							,"recency" 			=>'80'
							,"registration_time"   			=>$post['registration_time']
							,"speed_box"     			=>$post['speed_box']
							,"car_number" 			=>$post['car_number']
							,"valid_date"      	=>$post['valid_date']	
							,"insurance_date"      => $post['insurance_date']
							,"commerce_insurance_date"      => $post['commerce_insurance_date']
							,"exchange_time"      => $post['exchange_time']
							,"mileage"     	 	=>$post['mileage']

						)   ;
		$car_notice = json_encode($car_notice);

		$car_notice = json_decode($car_notice);
		$this->notice_model
		->insert_car_notice($this->data['result'],
							$car_notice);
			 						 
		$this->load->view('cms/edit_result_view',$this->data);
	}	

	

	function update_car_notice_success()
	{
		// echo '<pre>';
		$post = $_POST;
		

		$notice_detail = $this->notice_model->update_car_notice_detail($post);
		$this->data['result'] = $notice_detail['nid'];
		$this->data['type'] = 'car_notice';

		$this->load->view('cms/edit_result_view', $this->data);
	}

	function delete_car_notice($nid = NULL)
	{
		$result = $this->notice_model->delete_car_notice($nid);

		if ($result)
		{
			$this->data['result'] = 'delete';
		}

		header("location:".site_url('cms/admin/display_car_notices'));

	}	
	function list_by_search($search = null, $pageNum = 1)
	{
		if (null != $this->input->post('search'))
		{
			$search = $this->input->post('search');
		}
		else
		{
			$search = urldecode($search);
		}

		$this->data['article_list'] = $this->article_model->search_article($pageNum, $this->numPerPage, $search);
		
		$like = array(
				        'title' 	=> $search,
        				'region' 	=> $search,
        				'brand'		=> $search,
				        'version'	=> $search,
				        'description' => $search,
				        'speedbox'	=> $search
        				);

		$total_row = $this->article_model->get_total_row(null, $like);

		$this->data['pagination'] = $this->get_search_pagination('cms/admin/list_by_search/'.$search, $total_row);

		$this->load->view('cms/article_list_view', $this->data);
	}

	function list_by_price_a($pageNum=1)
	{
		// echo $this->car_model->get_total_row();
		$price1 = 0;
		$price2 = 5;

		$this->data['article_list'] = $this->article_model->get_article_by_price($pageNum, $this->numPerPage, $price1, $price2);
		// ; echo $this->table->generate($article_list)
		
		$where = array(
						'price >=' => $price1,
				        'price <'  => $price2
        				);
		$total_row = $this->article_model->get_total_row($where, null);

		$this->data['pagination'] = $this->get_pagination('admin/list_by_price_a', $total_row);

		$this->load->view('cms/article_list_view', $this->data);
	}


	function list_by_price_b($pageNum=1)
	{
		// echo $this->car_model->get_total_row();
		$price1 = 5;
		$price2 = 10;

		$this->data['article_list'] = $this->article_model->get_article_by_price($pageNum, $this->numPerPage, $price1, $price2);
		// ; echo $this->table->generate($article_list)
		
		$where = array(
						'price >=' => $price1,
				        'price <'  => $price2
        				);
		$total_row = $this->article_model->get_total_row($where, null);

		$this->data['pagination'] = $this->get_pagination('admin/list_by_price_b', $total_row);

		$this->load->view('cms/article_list_view', $this->data);
	}


function list_by_price_c($pageNum=1)
	{
		// echo $this->car_model->get_total_row();
		$price1 = 10;
		$price2 = 20;

		$this->data['article_list'] = $this->article_model->get_article_by_price($pageNum, $this->numPerPage, $price1, $price2);
		// ; echo $this->table->generate($article_list)
		
		$where = array(
						'price >=' => $price1,
				        'price <'  => $price2
        				);
		$total_row = $this->article_model->get_total_row($where, null);

		$this->data['pagination'] = $this->get_pagination('admin/list_by_price_c', $total_row);

		$this->load->view('cms/article_list_view', $this->data);
	}


function list_by_price_d($pageNum=1)
	{
		// echo $this->car_model->get_total_row();
		$price1 = 20;
		$price2 = 50;

		$this->data['article_list'] = $this->article_model->get_article_by_price($pageNum, $this->numPerPage, $price1, $price2);
		// ; echo $this->table->generate($article_list)
		
		$where = array(
						'price >=' => $price1,
				        'price <'  => $price2
        				);
		$total_row = $this->article_model->get_total_row($where, null);

		$this->data['pagination'] = $this->get_pagination('admin/list_by_price_d', $total_row);

		$this->load->view('cms/article_list_view', $this->data);
	}


function list_by_price_e($pageNum=1)
	{
		// echo $this->car_model->get_total_row();
		$price1 = 50;
		$price2 = 100000;

		$this->data['article_list'] = $this->article_model->get_article_by_price($pageNum, $this->numPerPage, $price1, $price2);
		// ; echo $this->table->generate($article_list)
		
		$where = array(
						'price >=' => $price1,
				        'price <'  => $price2
        				);
		$total_row = $this->article_model->get_total_row($where, null);

		$this->data['pagination'] = $this->get_pagination('admin/list_by_price_e', $total_row);

		$this->load->view('cms/article_list_view', $this->data);
	}

	function list_by_run_distance_a($pageNum=1)
	{
		// echo $this->car_model->get_total_row();
		$run_distance_1 = 10000;
		$run_distance_2 = 50000;

		$this->data['article_list'] = $this->article_model->get_article_by_run_distance($pageNum, $this->numPerPage, $run_distance_1, $run_distance_2);
		// ; echo $this->table->generate($article_list)
		
		$where = array(
						'run_distance >=' => $run_distance_1,
				        'run_distance <'  => $run_distance_2
        				);

		$total_row = $this->article_model->get_total_row($where, null);

		$this->data['pagination'] = $this->get_pagination('admin/list_by_run_distance_a', $total_row);

		$this->load->view('cms/article_list_view', $this->data);
	}

	function list_by_run_distance_b($pageNum=1)
	{
		// echo $this->car_model->get_total_row();
		$run_distance_1 = 50000;
		$run_distance_2 = 80000;

		$this->data['article_list'] = $this->article_model->get_article_by_run_distance($pageNum, $this->numPerPage, $run_distance_1, $run_distance_2);
		// ; echo $this->table->generate($article_list)
		
		$where = array(
						'run_distance >=' => $run_distance_1,
				        'run_distance <'  => $run_distance_2
        				);
		
		$total_row = $this->article_model->get_total_row($where, null);

		$this->data['pagination'] = $this->get_pagination('admin/list_by_run_distance_b', $total_row);

		$this->load->view('cms/article_list_view', $this->data);
	}

	function list_by_run_distance_c($pageNum=1)
	{
		// echo $this->car_model->get_total_row();
		$run_distance_1 = 80000;
		$run_distance_2 = 100000;

		$this->data['article_list'] = $this->article_model->get_article_by_run_distance($pageNum, $this->numPerPage, $run_distance_1, $run_distance_2);
		// ; echo $this->table->generate($article_list)
		
		$where = array(
						'run_distance >=' => $run_distance_1,
				        'run_distance <'  => $run_distance_2
        				);
		
		$total_row = $this->article_model->get_total_row($where, null);

		$this->data['pagination'] = $this->get_pagination('admin/list_by_run_distance_c', $total_row);

		$this->load->view('cms/article_list_view', $this->data);
	}

	function list_by_run_distance_d($pageNum=1)
	{
		// echo $this->car_model->get_total_row();
		$run_distance_1 = 100000;
		$run_distance_2 = 200000;

		$this->data['article_list'] = $this->article_model->get_article_by_run_distance($pageNum, $this->numPerPage, $run_distance_1, $run_distance_2);
		// ; echo $this->table->generate($article_list)
		
		$where = array(
						'run_distance >=' => $run_distance_1,
				        'run_distance <'  => $run_distance_2
        				);
		
		$total_row = $this->article_model->get_total_row($where, null);

		$this->data['pagination'] = $this->get_pagination('admin/list_by_run_distance_d', $total_row);

		$this->load->view('cms/article_list_view', $this->data);
	}

	function list_by_region_a($pageNum=1)
	{
		// echo $this->car_model->get_total_row();
		$region = '杭州';

		$this->data['article_list'] = $this->article_model->get_article_by_region($pageNum, $this->numPerPage, $region);
		// ; echo $this->table->generate($article_list)
		
		$like = array(
						'region' => $region
        				);

		$total_row = $this->article_model->get_total_row(null, $like);

		$this->data['pagination'] = $this->get_pagination('admin/list_by_region_a', $total_row);

		$this->load->view('cms/article_list_view', $this->data);
	}

	function list_by_region_b($pageNum=1)
	{
		// echo $this->car_model->get_total_row();
		$region = '浙江';

		$this->data['article_list'] = $this->article_model->get_article_by_region($pageNum, $this->numPerPage, $region);
		// ; echo $this->table->generate($article_list)
		
		$like = array(
						'region' => $region
        				);

		$total_row = $this->article_model->get_total_row(null, $like);

		$this->data['pagination'] = $this->get_pagination('admin/list_by_region_b', $total_row);

		$this->load->view('cms/article_list_view', $this->data);
	}

	function list_by_region_c($pageNum=1)
	{
		// echo $this->car_model->get_total_row();
		$region = '浙江';

		$this->data['article_list'] = $this->article_model->get_article_by_other_region($pageNum, $this->numPerPage, $region);
		// ; echo $this->table->generate($article_list)
		
		$like = array(
						'region' => $region
        				);

		$total_row = $this->article_model->get_total_row_region($like);

		$this->data['pagination'] = $this->get_pagination('admin/list_by_region_c', $total_row);

		$this->load->view('cms/article_list_view', $this->data);
	}

	//message
	function send_message()
	{
		$this->data['action'] = 'cms/admin/send_message_success';
		$this->load->view('cms/message/send_message_view',$this->data);
	}

	function send_message_success()
	{
		$post = $_POST;

		$post['destination_list'] = json_encode(explode(',',$post['destination_list'])) ;

		$result = $this->message_model->insert_message($post['destination_list'],
											 $post['title'],
										     $post['content']
													);
		$this->data['result'] = $result;
		$this->data['type'] ='message';
		$this->load->view('cms/edit_result_view',$this->data);
	}

	function get_message_list()
	{
		$pageNumber    = '1';
		$numberPerPage = '8';
	
		$this->data['message_list'] = $this->message_model->get_message_list($pageNumber,$numberPerPage);
		
		$this->load->view('cms/message/message_list_view',$this->data);
	}


	function display_single_message($mid)
	{
		$message = $this->message_model->get_message_detail($mid);
		
		$this->data = array_merge($this->data, $message);

		$this->load->view('cms/message/message_detail_view', $this->data);
	}

	function delete_message($mid = NULL)
	{
		$result = $this->message_model->delete_message($mid);

		if ($result)
		{
			$this->data['result'] = 'delete';
		}

		header("location:".site_url('cms/admin/get_message_list'));

	}	

	function test_data()
	{
		
		
		//$data['init_data'] = $this->data;
		$data['header'] = $this->data['header'];
		$data['footer'] = $this->data['footer'];
		$this->load->view('cms/test/test_data_view',$data);
	}

	private function define()
	{
		; $this->position 
			= array('1'=>'管理员'
					,'2'=>'编辑'
					,'3'=>'注册用户'	
					)

		; $this->numPerPage = 20
		;
	}

	private function init()
	{
		; $this->uid 
			= $this->input->cookie('uid')
		; $this->level 
			= $this->input->cookie('level')
		; $this->status 
			= $this->position[$this->level]
		; $this->username
			= $this->input->cookie('username')

		;		
		; $this->data = array( 'uid'=>$this->uid
						, 'level'=>$this->level
						, 'status'=>$this->status
						, 'username'=>$this->username
						, 'position'=>$this->position
						)
		; $sidebar = $this->load->view('cms/sidebar_view', $this->data, true)
		; $this->data['sidebar'] = $sidebar
		; $header = $this->load->view('cms/header_view', $this->data, true)
		; $this->data['header'] = $header
		; $footer = '<div id="footer">
    					<small>
					    &#169; Copyright 2014 换车吧
					</small> 
					</div>'

  		;
  		; $this->data['footer'] = $footer;
  		;

		if(!$this->uid)
		{
			header("location:".site_url('cms/login'));
		}
	}

	private function get_pagination($url, $total_row)
	{
		$this->load->library('pagination');
		$config['uri_segment'] = 3;
		$config['num_links'] = 2;
		$config['base_url'] = site_url($url);
		$config['total_rows'] = $total_row;
		$config['per_page'] = $this->numPerPage; 
		$config['use_page_numbers'] = TRUE;

		$this->pagination->initialize($config); 

		return $this->pagination->create_links();
	}

	private function get_search_pagination($url, $total_row)
	{
		$this->load->library('pagination');
		$config['uri_segment'] = 4;
		$config['num_links'] = 2;
		$config['base_url'] = site_url($url);
		$config['total_rows'] = $total_row;
		$config['per_page'] = $this->numPerPage; 
		$config['use_page_numbers'] = TRUE;

		$this->pagination->initialize($config); 

		return $this->pagination->create_links();
	}
}