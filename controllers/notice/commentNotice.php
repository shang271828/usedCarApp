<?php
class CommentNotice extends MY_Controller
{
	function __construct()
	{
		parent ::__construct();
		$this->load->helper("form");
		$this->load->model("comment_model");
	}

	function index()
	{
		/* work space
		 */
		$body = $this->input->body;
		@$this->content     = $body->content;
		@$this->nid         = $body->nid;    
		@$this->parent_type = "notice";
		$is_param_ok = $this->notice_param_check();
		if($is_param_ok)
		{
			$this->comment_model
				 ->insert_comment($this->content
						 		 ,$this->nid
						 		 ,$this->parent_type
						 		 );

			$this->output->set_body("result",0);
			$this->output->set_body("description","notice commented");
		}
	}

	function view_test()
	{	
		$this->load->view('notice/comment_notice_view');
	}


	function notice_param_check()
	{
		$is_param_ok = TRUE;
		
		$is_param_missing = ! ($this->content && $this->nid);
		
		do
		{
			if ($is_param_missing)
			{
				$is_param_ok = FALSE;
				$this->output->set_body("result",1);
				$this->output->set_body("description","parameter missing");
				break;
			}
		}while(FALSE);
		return $is_param_ok;
	}
}
//commentNotice.php end
/*
{
 "head":{  
   "uid"          : "1",  
   "time"         : "2014-08-03 03:08:05", 
   "token"        : "9fd98454b511ce20120ecb593ed177e3"
  },
 "body":{    
   "content"      : "Perfect!",
   "nid"          : "1"
  }
}
*/