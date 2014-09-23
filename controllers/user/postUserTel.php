<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class PostUserTel extends MY_Controller
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
		$head = $this->input->head;

		$this->tel_dir     = $body->tel_dir;
		$this->do_type     = $body->do_type;				
		$this->uid         = $head->uid;


		$is_param_ok = $this->user_param_check();

		if($is_param_ok)
		{
			$this->user_model
			 	->add_user_tel($this->uid,
							   $this->tel_dir,
							   $this->do_type );

			$this->output->set_body('result', '0');
			$this->output->set_body('description', 'operation is done!');
		}
			
	}

	function view_test()
	{
		$this->load->view("user/post_user_tel_view");
	}

	function user_param_check()
	{
		$is_param_ok = TRUE;
		$is_param_missing  = ! ($this->tel_dir);

		if ($is_param_missing)
		{
			$is_param_ok = FALSE;
			$this->output->set_body("result",1);
			$this->output->set_body("description","parameter missing");
		}
	return $is_param_ok;
	}
}
/*
{ "head":
	{      
	"uid"          : "1",      
	"time"         : "2014-08-03 03:08:05",     
	"token"        : "9fd98454b511ce20120ecb593ed177e3"   
	},  
	"body":
	{        
	"tel_dir"     :["18537727752","15803820735","13521722086"],
	"do_type"     :"delete"
	} 
}
*/