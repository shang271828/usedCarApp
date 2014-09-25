<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class GetUserInfo extends MY_Controller
{
						
	function __construct()
	{
		parent :: __construct();
		$this->load->database();
		$this->load->helper("form");
		$this->load->model('user_model');		
	}

	function index()
	{
		$body = $this->input->body;
		@$this->get_uid = $body->get_uid;
		$is_param_ok = $this->user_param_check();

		if($is_param_ok)
		{
			$userInfo = $this->user_model
							 ->select_userinfo($this->get_uid);
			$this->output->set_body('result', '0');
			$this->output->set_body('description', 'get userinfo!');
			$this->output->set_body('userInfo',$userInfo);
		}
	}

	function view_test()
	{	
		$this->load->view('user/get_user_info_view');
	}

	function user_param_check()
	{
		$is_param_ok = TRUE;
		$is_param_missing  = ! ($this->get_uid);

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
{
 "head":{  
   "uid"          : "1",  
   "time"         : "2014-08-03 03:08:05", 
   "token"        : "9fd98454b511ce20120ecb593ed177e3"
  },
 "body":{  
  "get_uid"       : "1"
  }
}
*/
