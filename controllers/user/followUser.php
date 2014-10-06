

<?php
class FollowUser extends MY_Controller
{
	function __construct()
	{
		parent ::__construct();
		$this->load->database();
		$this->load->helper("form");
		$this->load->model("user_relation_model");
	}
	//uid 关注 follow_uid
	function index()
	{
	    $body = $this->input->body;
		$this->uid = $this->input->head->uid;
		@$this->follow_uid = $body->follow_uid;
		
		$is_param_ok = $this->notice_param_check();

		if($is_param_ok)
		{
		
			$is_followed = $this->user_relation_model
					 			->update_user_list_following($this->uid,
					 	                  			         $this->follow_uid
					 	             				 		 );
					 			
			$this->output->set_body("result",0);
			if ($is_followed == 1)
		 	   	$this->output->set_body("description",FOLLOW);
		 	else
		 		$this->output->set_body("description",FOLLOW_CANCEL);
		}
	}

	function view_test()
	{	
		$this->load->view('user/follow_user_view');
	}

	
	function notice_param_check()
	{
		$is_param_ok      = TRUE;
		$is_param_missing = ! ($this->uid&&$this->follow_uid);
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
  "follow_uid"    : "1"
  }
}
*/