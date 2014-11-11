<?php
header("Content-Type: text/html; charset=utf-8");
class SearchNotice extends CI_Controller
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
		
		$this->pageNumber     = $body->pageNumber;
		$this->numberPerPage  = $body->numberPerPage;
		if (property_exists ( $body, 'location'))
			$this->location  = $body->location;
		else						
			$this->location  = '杭州';

		if (property_exists ( $body, 'searchValue'))
			$this->searchValue  = $body->searchValue;
		else						
			$this->searchValue  = '0';

		if (property_exists ( $body, 'filterValue'))
		{
			$this->filterValue = $body->filterValue ;
	
		}
		else						
			$this->filterValue = '0';

		if (property_exists ( $body, 'sortValue'))
			$this->sortValue = $body->sortValue			;
		else						
			$this->sortValue = '0';

		$is_param_ok = $this->notice_param_check();
		
		if($is_param_ok)
		{
			$notice_list = $this->notice_model
								->search_notice_list(
										 	         $this->pageNumber    
										 	        , $this->numberPerPage 
										 	        , $this->location
										 	        , $this->searchValue 
										 	        , $this->filterValue
										 	        , $this->sortValue
										 	         );
	 		
			if (! $notice_list)	
			{
				$this->output->set_body("result",1);
				$this->output->set_body("description",NO_CORRESPONDING_NOTICE);
				$this->output->set_body("notice_list", $notice_list);
			}
			else
			{
				$this->output->set_body("result",0);
				$this->output->set_body("description",GET_NOTICE);
				$this->output->set_body("notice_list", $notice_list);
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
						);
		$is_param_nonnum   = ! (is_integer($this->pageNumber+0)
			                  &&is_integer($this->numberPerPage+0));
		// $typeList = array(1=>"price",
		// 				  2=>"speed_box",
		// 				  3=>"is_recommended",
		// 				  4=>"brand");
		// $is_param_val_error = ! array_search($this->searchType,$typeList)
		// 					  || ($this->pageNumber<1) 
		//                       || ($this->numberPerPage>20);

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

				break;
			}

			if($is_param_nonnum)
			{
				$is_param_ok = FALSE;
				$this->output->set_body("result",3);
				$this->output->set_body("description",WRONG_TYPE);
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
  "searchValue"   : "杭州",
  "filterValue"   :{
					  "brand"    : "Benz",
					  "price"   : "2-50",
					  "age" :"1-3",
					  "mileage" :"0-5",
					  "speedBox" :"auto",  	
					  "carType" :""	
  					},
  	"sortValue"    : "price"
  }
}
*/
