<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category extends CI_Controller 
{

	function __construct()
	{
		parent::__construct();
		$this->numPerPage = 20;
		$this->load->model('notice_model');
		$this->data['header'] = $this->load->view('header_view');
	}

	function index($category = NULL)
	{

		$this->data['article_list'] = $this->notice_model->get_article_list();

		$this->load->view('category_view', $this->data);
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

		$this->data['article_list'] = $this->notice_model->search_article($pageNum, $this->numPerPage, $search);
		
		$like = array(
				        'title' 	=> $search,
        				'region' 	=> $search,
        				'brand'		=> $search,
				        'version'	=> $search,
				        'description' => $search,
				        'speedbox'	=> $search
        				);

		$total_row = $this->notice_model->get_total_row(null, $like);

		$this->data['pagination'] = $this->get_search_pagination('user_side/category/list_by_search/'.$search, $total_row);

		$this->load->view('category_view', $this->data);
	}

	function list_by_price_a($pageNum=1)
	{
		// echo $this->car_model->get_total_row();
		$price1 = 0;
		$price2 = 5;

		$this->data['article_list'] = $this->notice_model->get_article_by_price($pageNum, $this->numPerPage, $price1, $price2);
		// ; echo $this->table->generate($article_list)
		
		$where = array(
						'price >=' => $price1,
				        'price <'  => $price2
        				);
		$total_row = $this->notice_model->get_total_row($where, null);

		$this->data['pagination'] = $this->get_pagination('user_side/category/list_by_price_a', $total_row);

		$this->load->view('category_view', $this->data);
	}


	function list_by_price_b($pageNum=1)
	{
		// echo $this->car_model->get_total_row();
		$price1 = 5;
		$price2 = 10;

		$this->data['article_list'] = $this->notice_model->get_article_by_price($pageNum, $this->numPerPage, $price1, $price2);
		// ; echo $this->table->generate($article_list)
		
		$where = array(
						'price >=' => $price1,
				        'price <'  => $price2
        				);
		$total_row = $this->notice_model->get_total_row($where, null);

		$this->data['pagination'] = $this->get_pagination('user_side/category/list_by_price_b', $total_row);

		$this->load->view('category_view', $this->data);
	}


function list_by_price_c($pageNum=1)
	{
		// echo $this->car_model->get_total_row();
		$price1 = 10;
		$price2 = 20;

		$this->data['article_list'] = $this->notice_model->get_article_by_price($pageNum, $this->numPerPage, $price1, $price2);
		// ; echo $this->table->generate($article_list)
		
		$where = array(
						'price >=' => $price1,
				        'price <'  => $price2
        				);
		$total_row = $this->notice_model->get_total_row($where, null);

		$this->data['pagination'] = $this->get_pagination('user_side/category/list_by_price_c', $total_row);

		$this->load->view('category_view', $this->data);
	}


function list_by_price_d($pageNum=1)
	{
		// echo $this->car_model->get_total_row();
		$price1 = 20;
		$price2 = 50;

		$this->data['article_list'] = $this->notice_model->get_article_by_price($pageNum, $this->numPerPage, $price1, $price2);
		// ; echo $this->table->generate($article_list)
		
		$where = array(
						'price >=' => $price1,
				        'price <'  => $price2
        				);
		$total_row = $this->notice_model->get_total_row($where, null);

		$this->data['pagination'] = $this->get_pagination('admin/list_by_price_d', $total_row);

		$this->load->view('category_view', $this->data);
	}


function list_by_price_e($pageNum=1)
	{
		// echo $this->car_model->get_total_row();
		$price1 = 50;
		$price2 = 100000;

		$this->data['article_list'] = $this->notice_model->get_article_by_price($pageNum, $this->numPerPage, $price1, $price2);
		// ; echo $this->table->generate($article_list)
		
		$where = array(
						'price >=' => $price1,
				        'price <'  => $price2
        				);
		$total_row = $this->notice_model->get_total_row($where, null);

		$this->data['pagination'] = $this->get_pagination('admin/list_by_price_e', $total_row);

		$this->load->view('category_view', $this->data);
	}

	function list_by_run_distance_a($pageNum=1)
	{
		// echo $this->car_model->get_total_row();
		$run_distance_1 = 0;
		$run_distance_2 = 5;

		$this->data['article_list'] = $this->notice_model->get_article_by_run_distance($pageNum, $this->numPerPage, $run_distance_1, $run_distance_2);
		// ; echo $this->table->generate($article_list)
		
		$where = array(
						'run_distance >=' => $run_distance_1,
				        'run_distance <'  => $run_distance_2
        				);

		$total_row = $this->notice_model->get_total_row($where, null);

		$this->data['pagination'] = $this->get_pagination('admin/list_by_run_distance_a', $total_row);

		$this->load->view('category_view', $this->data);
	}

	function list_by_run_distance_b($pageNum=1)
	{
		// echo $this->car_model->get_total_row();
		$run_distance_1 = 5;
		$run_distance_2 = 8;

		$this->data['article_list'] = $this->notice_model->get_article_by_run_distance($pageNum, $this->numPerPage, $run_distance_1, $run_distance_2);
		// ; echo $this->table->generate($article_list)
		
		$where = array(
						'run_distance >=' => $run_distance_1,
				        'run_distance <'  => $run_distance_2
        				);
		
		$total_row = $this->notice_model->get_total_row($where, null);

		$this->data['pagination'] = $this->get_pagination('admin/list_by_run_distance_b', $total_row);

		$this->load->view('category_view', $this->data);
	}

	function list_by_run_distance_c($pageNum=1)
	{
		// echo $this->car_model->get_total_row();
		$run_distance_1 = 8;
		$run_distance_2 = 10;

		$this->data['article_list'] = $this->notice_model->get_article_by_run_distance($pageNum, $this->numPerPage, $run_distance_1, $run_distance_2);
		// ; echo $this->table->generate($article_list)
		
		$where = array(
						'run_distance >=' => $run_distance_1,
				        'run_distance <'  => $run_distance_2
        				);
		
		$total_row = $this->notice_model->get_total_row($where, null);

		$this->data['pagination'] = $this->get_pagination('admin/list_by_run_distance_c', $total_row);

		$this->load->view('category_view', $this->data);
	}

	function list_by_run_distance_d($pageNum=1)
	{
		// echo $this->car_model->get_total_row();
		$run_distance_1 = 10;
		$run_distance_2 = 300000;

		$this->data['article_list'] = $this->notice_model->get_article_by_run_distance($pageNum, $this->numPerPage, $run_distance_1, $run_distance_2);
		// ; echo $this->table->generate($article_list)
		
		$where = array(
						'run_distance >=' => $run_distance_1,
				        'run_distance <'  => $run_distance_2
        				);
		
		$total_row = $this->notice_model->get_total_row($where, null);

		$this->data['pagination'] = $this->get_pagination('admin/list_by_run_distance_d', $total_row);

		$this->load->view('category_view', $this->data);
	}

	function list_by_region_a($pageNum=1)
	{
		// echo $this->car_model->get_total_row();
		$region = '杭州';

		$this->data['article_list'] = $this->notice_model->get_article_by_region($pageNum, $this->numPerPage, $region);
		// ; echo $this->table->generate($article_list)
		
		$like = array(
						'region' => $region
        				);

		$total_row = $this->notice_model->get_total_row(null, $like);

		$this->data['pagination'] = $this->get_pagination('admin/list_by_region_a', $total_row);

		$this->load->view('category_view', $this->data);
	}

	function list_by_region_b($pageNum=1)
	{
		// echo $this->car_model->get_total_row();
		$region = '浙江';

		$this->data['article_list'] = $this->notice_model->get_article_by_region($pageNum, $this->numPerPage, $region);
		// ; echo $this->table->generate($article_list)
		
		$like = array(
						'region' => $region
        				);

		$total_row = $this->notice_model->get_total_row(null, $like);

		$this->data['pagination'] = $this->get_pagination('admin/list_by_region_b', $total_row);

		$this->load->view('category_view', $this->data);
	}

	function list_by_region_c($pageNum=1)
	{
		// echo $this->car_model->get_total_row();
		$region = '浙江';

		$this->data['article_list'] = $this->notice_model->get_article_by_other_region($pageNum, $this->numPerPage, $region);
		// ; echo $this->table->generate($article_list)
		
		$like = array(
						'region' => $region
        				);

		$total_row = $this->notice_model->get_total_row_region($like);

		$this->data['pagination'] = $this->get_pagination('admin/list_by_region_c', $total_row);

		$this->load->view('category_view', $this->data);
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

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */