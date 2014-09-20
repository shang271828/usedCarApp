<?php
header("Content-Type: text/html; charset=utf-8");
class GetUserPhoneDir extends MY_Controller
{

	function __construct()
	{
		parent ::__construct();

		$this->load->database();
		$this->load->helper("form");
		$this->load->model("user_model");
	}

	function index()
	{
		$body = $this->input->body;
		var_dump($body);
		$head = $this->input->head;
		var_dump(json_encode($body->phone_dir)) ;
		$this->phone_dir =json_encode($body->phone_dir);
		
		
		$this->uid       = $head->uid;

		$this->user_model
			 ->add_phone_dir($this->uid,
							 $this->phone_dir);

	}

	function view_test()
	{
		$this->load->view("user/get_phone_dir_view");
	}

}
/*
{  "head":
	{      
	"uid"          : "1",      
	"time"         : "2014-08-03 03:08:05",     
	"token"        : "9fd98454b511ce20120ecb593ed177e3"   
	},  
	"body":
	{        
	"phone_dir"     :{"许通"："18537727752","道长"："15803820735","侯华生"："13521722086"}  
	} 
}
*/