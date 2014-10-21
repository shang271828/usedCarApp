<?php
header("Content-Type: text/html; charset=utf-8");
class GetArticleDetail extends MY_Controller
{

	function __construct()
	{
		parent ::__construct();
		$this->load->database();
		$this->load->helper("form");
		$this->load->model("article_model");		
	}

	function index()
	{
		//unite test
		$body = $this->input->body;

		@$this->aid  = $body->aid;


		$is_param_ok = $this->article_param_check();
		
		if($is_param_ok)
		{

			$article_detail = $this->article_model
								->get_article_detail($this->aid);
	 		
			if (! $article_detail)	
			{
				$this->output->set_body("result",1);
				$this->output->set_body("description",NULL_ARTICLE);
				$this->output->set_body("article_detail", $article_detail);
			}
			else
			{
				$this->output->set_body("result",0);
				$this->output->set_body("description",GET_ARTICLE_DETAIL);
				$this->output->set_body("article_detail", $article_detail);
			}

		}
	}


	function view_test()
	{	
		$this->load->view('notice/get_article_detail_view');
	}

	function article_param_check()
	{
		$is_param_ok = TRUE;
		$is_param_missing  = ! ($this->aid);

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
//getarticle.php end
/*input :
{
 "head":{  
   "uid"          : "1",  
   "time"         : "2014-08-03 03:08:05", 
   "token"        : "9fd98454b511ce20120ecb593ed177e3"
  },
 "body":{  
  "aid"    : "1"

  }
}
*/