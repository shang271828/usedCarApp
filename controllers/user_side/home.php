<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
header("content-type:text/html; charset=utf8");

class Home extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->helper("form");
		$this->load->helper("url");

		$this->load->model("notice_model");	
		$this->data['header'] = $this->load->view('header_view');
	}

	function index()
	{
		$this->data['article_list'] =  $this->notice_model
											->get_notice_list('1','5','mainpage');

		$this->load->view('home_view', $this->data);

		//var_dump($this->data);
	}

	function search()
	{
		$search = $this->input->post('search');    

		$data['articles'] = $this->article_model->get_article_list(1, 20);

		$this->load->view('home_view', $data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */