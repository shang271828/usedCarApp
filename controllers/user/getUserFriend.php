<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class GetUserFriend extends MY_Controller
{

						
	function __construct()
	{
		parent :: __construct();
		$this->load->database();
		$this->load->helper("form");
		$this->load->model('user_relation_model');	
		$this->load->model("user_model");	
	}

	function index()
	{
		// work code
		$body = $this->input->body;
		@$this->get_uid = $body->get_uid;
		
		$is_param_ok = $this->user_param_check();

		if($is_param_ok)
		{
			$friend_initial_info   = $this->user_relation_model
							    	->get_friend_initial_info($this->get_uid);
							   
			$friend_secondary_info = $this->user_relation_model
							    	->get_friend_secondary_info($this->get_uid);
			
			//$friend_list_initial = json_decode($friend_list_initial);
			$this->output->set_body('result', '0');
			$this->output->set_body('description', GET_FRIEND_LIST);
			$this->output->set_body('friend_initial_list',$friend_initial_info);
			$this->output->set_body('friend_secondary_list',$friend_secondary_info);
		}
	}

	function view_test()
	{	
		$this->load->view('user/get_user_friend_view');
	}

	function user_param_check()
	{
		$is_param_ok = TRUE;
		$is_param_missing  = ! ($this->get_uid);

		if ($is_param_missing)
		{
			$is_param_ok = FALSE;
			$this->output->set_body("result",1);
			$this->output->set_body("description",PARAMETER_MISSING);
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
  "get_uid"       : "2" 
  }
}
*/