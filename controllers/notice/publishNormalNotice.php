<?php
class publishNormalNotice extends MY_Controller
{

	function __construct()
	{
		parent ::__construct();
		$this->load->helper( array("url","form"));
		$this->load->model("notice_model");
		$this->load->model("user_timeline_model");
		$this->load->library("upload"); 		
	}	

	function index()
	{	
		$body = $this->input->body;
		@$this->title   = $body->title;
		@$this->content = $body->content;
		if (property_exists ( $body, 'img_list'))
			$this->img_list  = $body->img_list;
		else						
			$this->img_list  = '[]';

		$is_param_ok = $this->notice_param_check();
		if($is_param_ok)
		{
			$nid = $this->notice_model
				        ->insert_normal_notice($this->title
											  ,$this->content
											  ,$this->img_list
											  ,"normal_notice"
											  );

			$this->user_timeline_model
									->insert($this->input->head->uid,
											 $nid,'1');
			$this->output->set_body("result",0);
			$this->output->set_body("description","动态已发布");
		}
	}

	function view_test()
	{	
		$this->load->view('notice/publish_normal_notice_view');
	}
	
	function notice_param_check()
	{
		$is_param_ok = TRUE;
		
		$is_param_missing = ! ($this->title && $this->content);
		
		do
		{
			if ($is_param_missing)
			{
				$is_param_ok = FALSE;
				$this->output->set_body("result",1);
				$this->output->set_body("description","parameter missing");
				break;
			}

			if(!$this->img_list)
			{
				$is_param_ok = FALSE;
				$this->output->set_body("result",2);
				$this->output->set_body("description","image missing");
				break;				
			}
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
  "title"           : "my_title",
  "content"         : "my_content",
  "img_list"        : ["http:\/\/xdream.co\/CI_API\/application\/upload_dir\/4.jpg ",
  					   "http:\/\/xdream.co\/CI_API\/application\/upload_dir\/4.jpg "]

  }
}
*/