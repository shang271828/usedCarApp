

<?php
class Sticky extends MY_Controller
{
	function __construct()
	{
		parent ::__construct();
		$this->load->database();
		$this->load->helper("form");
		$this->load->model("user_timeline_model");
	}

	function index()
	{
	    $body = $this->input->body;
		$this->nid = $body->nid;
		
		$is_param_ok = $this->notice_param_check();

		if($is_param_ok)
		{
		
			$is_sticky = $this->user_timeline_model->set_sticky($this->nid);
					 			
			$this->output->set_body("result",0);
			if ($is_sticky == 1)
			{
		 	   	$this->output->set_body("description","is_sticky");
		 	    $this->output->set_body("is_sticky",$is_sticky);
		 	}
		 	else
		 	{
		 		$this->output->set_body("description","is_sticky canceled");
		 		$this->output->set_body("is_sticky",$is_sticky);
		 	}
		}
	}

	function view_test()
	{	
		$this->load->view('notice/sticky_view');
	}

	
	function notice_param_check()
	{
		$is_param_ok      = TRUE;
		$is_param_missing = ! ($this->nid);
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
   "nid"          : "4"
  }
}
*/