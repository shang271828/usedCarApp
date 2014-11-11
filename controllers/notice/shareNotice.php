<?php
class ShareNotice extends MY_Controller
{
	function __construct()
	{
		parent ::__construct();
		$this->load->database();
		$this->load->helper("form");
		$this->load->model("notice_model");	
		$this->load->model("share_model");	
		$this->load->model("user_timeline_model");		
	}

	function index()
	{
	    $body = $this->input->body;

		$this->nid = $body->nid;
		$this->share_type  = $body->share_type;

		$is_param_ok = $this->notice_param_check();

		if($is_param_ok)
		{
			switch ($this->share_type) 
			{
				case 'friend':
					$this->share_model
					 	 ->insert_share($this->nid,
					 	 				  $this->share_type);
		
					 $this->output->set_body("result",0);
		 			$this->output->set_body('description','已分享到朋友圈');
					break;
				case 'copy':
					# code...
					break;
				case 'timeline':
					$this->user_timeline_model
					 	 					->insert($this->input->head->uid,
					 	 							 $this->nid,
					 	 		 					 '3');
					$this->output->set_body("result",0);
		 			$this->output->set_body('description','已分享到时间线');
					break;
				default:
					# code...
					break;
			}
				 			
			
			
		}
	}
	
	function view_test()
	{	
		$this->load->view('notice/share_notice_view');
	}

	function notice_param_check()
	{
		$is_param_ok      = TRUE;
		$is_param_missing = ! ($this->nid&&$this->share_type);
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
   "nid"          : "1",
   "share_type"      : "timeline"
  }
}
*/