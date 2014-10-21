<?php
class publishArticle extends MY_Controller
{

	function __construct()
	{
		parent ::__construct();
		$this->load->helper(array("url","form"));
		$this->load->model("article_model");		
	}	

	function index()
	{	
		
		// $body = $this->input->body;
		// $this->a_uid         = $body->a_uid;
		// $this->title       = $body->title;
		// $this->content     = $body->content;
		$post = $_POST;
		
		$this->author  = $post['author'] ;
		$this->title   = $post['title']  ;
		$this->content = $post['content'];
		$this->image   = $post['image'];

		$is_param_ok = $this->notice_param_check();
		if($is_param_ok)
		{
			$nid = $this->article_model
				 		->insert_article($this->author
		    							,$this->title  
										,$this->content);

			$this->output->set_body("result",0);
			$this->output->set_body("description",PUBLISH_ARTICLE);
		}

	}

	function view_test()
	{	
		$this->load->view('js_test_view');
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
				$this->output->set_body("description",PARAMETER_MISSING);
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
 "a_uid":"1",  
  "title"           : "my_title",
  "content"         : "my_content"
  }
}
*/