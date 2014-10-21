<?php
header("Content-Type: text/html; charset=utf-8");
class SearchNotice extends MY_Controller
{
	function __construct()
	{
		parent ::__construct();
		$this->load->database();
		$this->load->helper("form");
		$this->load->model("notice_model");		
		$this->data['header'] = $this->load->view('header_view');
		var_dump($this->data['header']);
	}

	function index()
	{
		/************************
		*test
		*****************************/
		$body = new StdClass;
		$body->pageNumber = '1';
		$body->numberPerPage = '5';
		$body->searchStr = 'price:0-100';
		$body->location = '杭州';
		/************************
		*body
		*****************************/
		//$body = $this->input->body;
		
		@$this->pageNumber     = $body->pageNumber;
		@$this->numberPerPage  = $body->numberPerPage;
		$searchStr = explode(":", $body->searchStr);
		$this->location = $body->location;
		@$this->searchType     = $searchStr[0];
		@$this->searchValue    = $searchStr[1];

		$is_param_ok = $this->notice_param_check();
		
		if($is_param_ok)
		{
			$notice_list = $this->notice_model
								->search_notice_list($this->pageNumber,
										 	         $this->numberPerPage,
										 	         $this->location,
										 	         $this->searchType,
										 	         $this->searchValue);
	 		
			if (! $notice_list)	
			{
				$this->output->set_body("result",1);
				$this->output->set_body("description",NO_CORRESPONDING_NOTICE);
				$this->output->set_body("notice_list", $notice_list);
			}
			else
			{
				//$this->output->set_body("result",0);
				//$this->output->set_body("description",GET_NOTICE);
				//$this->output->set_body("notice_list", $notice_list);
				$this->data['article_list'] = $notice_list;
				
				var_dump($this->data);
				$this->load->view('category_view', $this->data);
			}
		}
	}


	function view_test()
	{	
		$this->load->view('notice/search_notice_view');
	}

	function notice_param_check()
	{
		$is_param_ok = TRUE;
		$is_param_missing  = ! ($this->pageNumber
							  &&$this->numberPerPage
							  &&$this->searchType
			 				  &&$this->searchValue);
		$is_param_nonnum   = ! (is_integer($this->pageNumber+0)
			                  &&is_integer($this->numberPerPage+0));
		$typeList = array(1=>"price",
						  2=>"speed_box",
						  3=>"is_recommended",
						  4=>"brand");
		$is_param_val_error = ! array_search($this->searchType,$typeList)
							  || ($this->pageNumber<1) 
		                      || ($this->numberPerPage>20);

		do
		{
			if ($is_param_missing)
			{
				$is_param_ok = FALSE;
				$this->output->set_body("result",2);
				if(!$this->pageNumber)
					$this->output->set_body("description",PARAMETER_MISSING."pageNumber");
				elseif (!$this->numberPerPage) 
					$this->output->set_body("description",PARAMETER_MISSING."numberPerPage");
				elseif (!$this->searchType)
					$this->output->set_body("description",PARAMETER_MISSING."searchType");
				elseif(!$this->searchValue)
					$this->output->set_body("description",PARAMETER_MISSING."searchValue");
				//$this->output->set_body("description","parameter missing");
				break;
			}



			if($is_param_nonnum)
			{
				$is_param_ok = FALSE;
				$this->output->set_body("result",3);
				$this->output->set_body("description",WRONG_TYPE);
				break;
			}
			if($is_param_val_error)
			{
				$is_param_ok = FALSE;
				$this->output->set_body("result",4);
				$this->output->set_body("description",WRONG_VALUE);
				break;
			}

		}while(FALSE);

	return $is_param_ok;
	}
}
//searchNotice.php end
/*input :
{
 "head":{  
   "uid"          : "1",  
   "time"         : "2014-08-03 03:08:05", 
   "token"        : "9fd98454b511ce20120ecb593ed177e3"
  },
 "body":{  
  "pageNumber"    : "1",  
  "numberPerPage" : "2",
  "location" : "杭州",
  "searchStr"    : "brand:Benz"
  }
}
*/