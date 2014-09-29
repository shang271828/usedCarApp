<?php
header("Content-Type: text/html; charset=utf-8");
class GetArticleList extends MY_Controller
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

		@$this->pageNumber    = $body->pageNumber;
		@$this->numberPerPage = $body->numberPerPage;
		@$this->pageType      = $body->pageType;

		$is_param_ok = $this->article_param_check();
		
		if($is_param_ok)
		{

			$article_list = $this->article_model
								->get_article_list($this->pageNumber,
										 	      $this->numberPerPage,
										 	      $this->pageType);
			// if ($this->pageType == "mainpage")
			// {
			// 	$total_article_row = $this->article_model
			// 				   		 	 ->get_total_car_row($this->pageType);
			// 	$this->output->set_body("total_article_row", $total_article_row);
			// }
	 		
			if (! $article_list)	
			{
				$this->output->set_body("result",1);
				$this->output->set_body("description","null article!");
				$this->output->set_body("article_list", $article_list);
			}
			else
			{
				$this->output->set_body("result",0);
				$this->output->set_body("description","get article list:".$this->pageType."!");
				$this->output->set_body("article_list", $article_list);
			}
			
			//$this->load->view("output_view");

		}
	}


	function view_test()
	{	
		$this->load->view('notice/get_article_list_view');
	}

	function article_param_check()
	{
		$is_param_ok = TRUE;
		$is_param_missing  = ! ($this->pageNumber&&$this->numberPerPage&&$this->pageType);
		$is_param_nonnum   = ! (is_integer($this->pageNumber+0)
			                  &&is_integer($this->numberPerPage+0));
		$is_param_val_error = ($this->pageNumber<1) || ($this->numberPerPage>20);
		$pageTypeList = array(1=>"tips");
		$is_param_str_error = ! array_search($this->pageType,$pageTypeList);

		do
		{
			if ($is_param_missing)
			{
				$is_param_ok = FALSE;
				$this->output->set_body("result",2);
				$this->output->set_body("description","parameter missing");
				break;
			}
			if($is_param_nonnum)
			{
				$is_param_ok = FALSE;
				$this->output->set_body("result",3);
				$this->output->set_body("description","parameter's type is wrong ");
				break;
			}
			if($is_param_val_error)
			{
				$is_param_ok = FALSE;
				$this->output->set_body("result",4);
				$this->output->set_body("description","parameter's value is wrong ");
				break;
			}
			if($is_param_str_error )
			{
				$is_param_ok = FALSE;
				$this->output->set_body("result",5);
				$this->output->set_body("description","pageType's value is wrong ");
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
  "pageNumber"    : "1",  
  "numberPerPage" : "8",
  "pageType"       : "timeline"
  }
}
*/