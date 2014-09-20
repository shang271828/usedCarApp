<?php

class Test extends MY_Controller
{
	function __construct()
	{
		parent ::__construct();
		$this->load->database();
		$this->load->model('user_model');
		$this->load->model('user_relation_model');
	}

	function index()
	{
		$this->user_model->get_phone_dir(2);
		
	}
}