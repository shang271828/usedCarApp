<?php
class FollowNotice extends MY_Controller
{
	function __construct()
	{
		parent ::__construct();
		$this->load->database();
		$this->load->helper("form");
		$this->load->model("notice_model");
		$this->load->model("user_relation_model");
	}

	function index()
	{
	    $body = $this->input->body;
		@$this->uid = $this->input->head->uid;
		@$this->nid = $body->nid;
		
		$is_param_ok = $this->notice_param_check();

		if($is_param_ok)
		{
		
			$is_followed = $this->notice_model
					 			->update_follow_list($this->uid,
					 	                  			 $this->nid);
			$this->user_relation_model
				 ->update_notice_list($this->uid,
				     	              $this->nid,
				     	              $is_followed);

			$this->output->set_body("result",0);
			if ($is_followed == 0)
		 	   	$this->output->set_body("description","notice followed");		 	   	
		 	else
		 		$this->output->set_body("description","follow canceled");
		 	$this->output->set_body("is_followed",$is_followed);
		}
	}

	function view_test()
	{	
		$this->load->view('notice/follow_notice_view');
	}

	function notice_param_check()
	{
		$is_param_ok      = TRUE;
		$is_param_missing = ! ($this->uid&&$this->nid);
		if ($is_param_missing)
		{
			$is_param_ok = FALSE;
			$this->output->set_body("result",1);
			$this->output->set_body("description","parameter missing");
		}
		return $is_param_ok;
	}
}
//followNotice.php end
/* input:
{
 "head":{  
   "uid"          : "1",  
   "time"         : "2014-08-03 03:08:05", 
   "token"        : "9fd98454b511ce20120ecb593ed177e3"
  },
 "body":{    
   "nid"          : "1"
  }
}
*/