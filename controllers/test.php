<?php
class test extends CI_Controller
{
	function __construct()
	{
		header("Content-type: text/html; charset=utf-8");
		parent :: __construct();
		; $this->load->helper('url')
		; $this->load->model('user_model')
		; $this->load->model('invitation_code_model')
		; $this->load->library('javascript')
		//; include("Snoopy.php")
		;
	}

	function index()
	{
		var_dump($this->invitation_code_model->addCode('1'));


	}

	function insert()
	{
		$article = $this->article_model->get_article_detail('2');
		var_dump($article);
	}

}
