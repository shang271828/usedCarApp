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
		; $this->load->model('user_relation_model')
		; $this->load->model('menu_model')
		; $this->load->model('topic_model')
		; $this->load->model('message_model')
		; $this->load->library('javascript')
		//; include("Snoopy.php")
		;
	}

	function index()
	{
		// $uid_list = ['1','2','3'];
		// $this->user_relation_model->friend_list_secondary($uid_list);
		$this->message_model->get_sys_message();


	}

	function insert()
	{
		$this->topic_model->get_topic_list('1','2');
		
	}

	function invitation_code()
	{

		for ($i=0; $i < 50 ; $i++) 
		{ 
			$this->invitation_code_model->addCode('7',0);

		}
		var_dump($i);


	}

}
