<?php
class User_test extends CI_Controller
{
	function __construct()
	{
		parent ::__construct();
		$this->load->database();
		$this->load->helper("form");
		$this->load->model("message_model");
		$this->load->model("user_model");
	}

	function index()
	{
		//$body = $this->input->body;
		//$this->uid = $body->uid;
		$this->uid = 1;
		$is_param_ok = true;
		
		if($is_param_ok)
		{
			$userInfo = $this->user_model
							 ->all_userinfo($this->uid);
			
			$this->output->set_body('result', '0');
			$this->output->set_body('description', GET_USERINFO);
			$this->output->set_body('userInfo',$userInfo);
		}
	}	

}