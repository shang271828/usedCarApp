

<?php
class ShieldUser extends MY_Controller
{
	function __construct()
	{
		parent ::__construct();
		$this->load->database();
		$this->load->helper("form");
		$this->load->model("user_relation_model");
	}
	//uid 屏蔽 get_uid
	function index()
	{
	    $body = $this->input->body;
		$this->uid = $this->input->head->uid;
		@$this->get_uid = $body->get_uid;
		
		$is_param_ok = $this->notice_param_check();

		if($is_param_ok)
		{
		
			$is_shielded = $this->user_relation_model
					 			->update_disgust_list($this->uid,
					 	                  			 $this->get_uid
					 	             				  );
					 			
			$this->output->set_body("result",0);
			if ($is_shielded == 1)
		 	   	$this->output->set_body("description","user shielded");
		 	else
		 		$this->output->set_body("description","shield canceled");
		}
	}
	
	function view_test()
	{	
		$this->load->view('user/shield_user_view');
	}

	function notice_param_check()
	{
		$is_param_ok      = TRUE;
		$is_param_missing = ! ($this->uid&&$this->get_uid);
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
  "shield_uid"    : "2"
  }
}
*/
