

<?php
class PraiseNotice extends MY_Controller
{
	function __construct()
	{
		parent ::__construct();
		$this->load->database();
		$this->load->helper("form");
		$this->load->model("notice_model");	
		$this->load->model("user_timeline_model");		
	}

	function index()
	{
	    $body = $this->input->body;
		$this->uid = $this->input->head->uid;
		$this->nid = $body->nid;
		
		$is_param_ok = $this->notice_param_check();

		if($is_param_ok)
		{
		
			$is_praised = $this->notice_model
					 			->update_praise_list($this->uid,
					 	                  			 $this->nid);
					 			
			$this->output->set_body("result",0);
			if ($is_praised == 1)
			{
				$this->user_timeline_model->insert($this->nid,"my_praise");
		 	   	$this->output->set_body("description",PRAISE);
		 	}

		 	else
		 	{
		 		//$this->user_timeline_model->insert($this->nid,"my_praise_canceled");
		 		$this->output->set_body("description",PRAISE_CANCEL);
		 	}
		}
	}
	
	function view_test()
	{	
		$this->load->view('notice/praise_notice_view');
	}

	function notice_param_check()
	{
		$is_param_ok      = TRUE;
		$is_param_missing = ! ($this->uid&&$this->nid);
		if ($is_param_missing)
		{
			$is_param_ok = FALSE;
			$this->output->set_body("result",1);
			$this->output->set_body("description",PARAMETER_MISSING);
		}
		return $is_param_ok;

	}
}
//input json:
/*
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