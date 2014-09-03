<?php
class Check extends MY_Controller
{
	function __construct()
	{
		parent ::__construct();
		$this->load->database();
		$this->load->model("check_model");
	}

	function index()
	{
		//unite test
		$body = new stdClass;
		$body->pageNumber    = "2";
		$body->numberPerPage = "8"; 
		$body->sort_str      = "counter_view";

		//work space
		$pageNumber    = $this->body->pageNumber;
		$numberPerPage = $this->body->numberPerPage;
		$sort_str      = $this->body->sort_str;

		$this->check_model->query($sort_str);
	}
}