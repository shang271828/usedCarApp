<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Detail extends CI_Controller 
{

	function __construct()
	{
		parent::__construct();
		$this->load->helper("form");
		$this->load->helper("url");
		$this->load->model('notice_model');
		$this->data['header'] = $this->load->view('header_view');

	}

	function index($nid)
	{
		$article = $this->notice_model->get_notice_detail($nid);

		$this->data = array_merge($this->data, $article);

		$this->load->view('detail_view', $this->data);
	}
}