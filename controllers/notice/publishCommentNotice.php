<?php
class publishCommentNotice extends MY_Controller
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
		@$this->title       = $body->title;
		@$this->content     = $body->content;
		@$this->p_nid       = $body->p_nid  ;
		if (property_exists ( $body, 'img_list'))
			$this->img_list  = $body->img_list;
		else						
			$this->img_list  = '[]';
		if (property_exists ( $body, 'commentType'))
			$this->commentType  = $body->commentType;
		else						
			$this->commentType  = 'public';
	

		$is_param_ok = $this->notice_param_check();
		if($is_param_ok)
		{
			$nid = $this->notice_model
				 		->insert_normal_notice($this->title
												,$this->content
												,$this->img_list
												,"comment_notice"
												);
				 		
			if (!$this->commentType)
				$this->notice_model
				 ->insert_comment_notice($nid ,
				 						$this->p_nid  
				 						);	
			else
				$this->notice_model
					 ->insert_comment_notice($nid        ,
					 						$this->p_nid  ,
											$this->commentType
					 						);	
			$this->user_timeline_model->insert($nid,"my_comment");
			$this->output->set_body("result",0);
			$this->output->set_body("description",PUBLISH_COMMENT_NOTICE);
		}

	}

	function view_test()
	{	
		$this->load->view('notice/publish_comment_notice_view');
	}
	
	function notice_param_check()
	{
		$is_param_ok = TRUE;
		
		$is_param_missing = ! ($this->title && $this->content && $this->img_list);
		
		do
		{
			if ($is_param_missing)
			{
				$is_param_ok = FALSE;
				$this->output->set_body("result",1);
				$this->output->set_body("description",PARAMETER_MISSING);
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
  "title"           : "my_title",
  "content"         : "my_content",
  "img_list"        : ["http://xdream.co/CI_API/upload_dir/1303bcfae0b6c8f859bcc2aafcb2ee23.jpg","http://xdream.co/CI_API/upload_dir/225e97394f00e2bf3c42f34e665553c3.jpg"],
  "p_nid"  :"5",
  "commentType"   :"private"
  }
}
*/