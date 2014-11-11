

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

		$this->nid = $body->nid;
		$this->do_type  = $body->do_type;

		$is_param_ok = $this->notice_param_check();

		if($is_param_ok)
		{
			if($this->do_type == 'praise')
			{
				$is_done = $this->notice_model
					 			   ->update_praise_list($this->nid);
					 			
				$this->output->set_body("result",0);
				if ($is_done == 1)
				{
					//$this->user_timeline_model->insert($this->nid,"my_praise");
		 		   	$this->output->set_body("description",'已点赞！');
		 		}
	
		 		else
		 		{
		 			$this->output->set_body("description",'已取消');
		 		}
			}
			elseif ($this->do_type == 'worth')
			{
				$is_done = $this->notice_model
					 			   ->update_worth_list($this->nid);
					 			
				$this->output->set_body("result",0);
				if ($is_done == 1)
				{
					$this->user_timeline_model->insert($this->nid,"4");
		 		   	$this->output->set_body("description",'觉得你的车值');
		 		}
	
		 		else
		 		{
		 			$this->output->set_body("description",'已取消');
		 		}
			}

			elseif($this->do_type == 'worthless')
			{
				$is_done = $this->notice_model
					 			   ->update_worthless_list($this->nid);
					 			
				$this->output->set_body("result",0);
				if ($is_done == 1)
				{
					$this->user_timeline_model->insert($this->nid,"5");
		 		   	$this->output->set_body('description','觉得你的车不值');
		 		}
		 		else
		 		{
		 			$this->output->set_body('description','已取消');
		 		}
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
		$is_param_missing = ! ($this->nid&&$this->do_type);
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
   "do_type"      : "worth"
  }
}
*/