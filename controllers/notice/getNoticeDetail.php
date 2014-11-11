<?php
class GetNoticeDetail extends CI_Controller
{

	function __construct()
	{
		parent ::__construct();
		$this->load->database();
		$this->load->helper("form");
		$this->load->model("notice_model");
		
	}

	function index()
	{
		$body = $this->input->body;

		@$this->nid = $body->nid;
		
		$is_param_ok = $this->notice_param_check();
		
		if($is_param_ok)
		{
			$notice_detail = $this->notice_model
								->get_notice_detail($this->nid);
	 		
			if (! $notice_detail)	
			{
				$this->output->set_body("result",1);
				$this->output->set_body("description",NULL_NOTICE);
				$this->output->set_body("notice_detail", $notice_detail);
			}
			else
			{
				$this->output->set_body("result",0);
				$this->output->set_body("description",GET_NOTICE_DETAIL);
				$this->output->set_body("notice_detail", $notice_detail);
			}			
		}
	}

	function view_test()
	{	
		$this->load->view('notice/get_notice_detail_view');
	}

	function notice_param_check()
	{
		$is_param_ok = TRUE;
		$is_param_missing  = ! ($this->nid);
		do
		{
			if ($is_param_missing)
			{
				$is_param_ok = FALSE;
				$this->output->set_body("result",2);
				$this->output->set_body("description",PARAMETER_MISSING);
				break;
			}
		}while(FALSE);

	return $is_param_ok;
	}
}
//getNoticeDetail.php end
/*input :
{
 "head":{  
   "uid"          : "1",  
   "time"         : "2014-08-03 03:08:05", 
   "token"        : "9fd98454b511ce20120ecb593ed177e3"
  },
 "body":{  
  "nid"           : "2"
  }
}
*/