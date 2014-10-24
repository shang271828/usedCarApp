<?php
class EditNormalNotice extends MY_Controller
{

	function __construct()
	{
		parent ::__construct();
		$this->load->helper( array("url","form"));
		$this->load->model("notice_model");
		$this->load->library("upload"); 		
	}	

	function index()
	{	
		$body = $this->input->body;
		$this->nid      = $body->nid;
		$this->title    = $body->title;
		$this->content  = $body->content;
		$this->img_list = $body->img_list;

		$is_param_ok = $this->notice_param_check();
		if($is_param_ok)
		{
			$nid = $this->notice_model
				        ->update_normal_notice(  $this->nid
				        						,$this->title
												,$this->content
												,$this->img_list
										);

			$this->output->set_body("result",0);
			$this->output->set_body("description","notice published");
		}

	}

	function view_test()
	{	
		$this->load->view('notice/edit_normal_notice_view');
	}
	
	function notice_param_check()
	{
		$is_param_ok = TRUE;
		
		$is_param_missing = ! ($this->nid&&$this->title && $this->content);
		
		do
		{
			if ($is_param_missing)
			{
				$is_param_ok = FALSE;
				$this->output->set_body("result",1);
				$this->output->set_body("description","parameter missing");
				break;
			}

			// if(!$this->img_list)
			// {
			// 	$is_param_ok = FALSE;
			// 	$this->output->set_body("result",2);
			// 	$this->output->set_body("description","image missing");
			// 	break;				
			// }
		}while(FALSE);
		return $is_param_ok;
	}
}
/*
{
 "head": {  
    "uid"  : "1", 
   "time"  : "2014-08-03 03:08:05", 
   "token" : "9fd98454b511ce20120ecb593ed177e3"
  },
 "body":{   
 "nid"              :"1", 
  "title"           : "my_title",
  "content"         : "my_content",
  "img_list"        : ["http:\/\/xdream.co\/CI_API\/application\/upload_dir\/4.jpg ",
  					   "http:\/\/xdream.co\/CI_API\/application\/upload_dir\/4.jpg "]

  }
}
*/