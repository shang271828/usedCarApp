<?php
header("Content-Type: text/html; charset=utf-8");
class MultiSearchNotice extends MY_Controller
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
		
		@$this->pageNumber     = $body->pageNumber;
		@$this->numberPerPage  = $body->numberPerPage;

		 $this->searchBrand  	= $body->searchBrand  	;
		 $this->searchPrice  	= $body->searchPrice  	;
		 $this->searchAge	   = $body->searchAge	;
		 $this->searchMileage  = $body->searchMileage 	;
		 $this->searchSpeedBox = $body->searchSpeedBox;
		 $this->searchCarType  = $body->searchCarType;


		$is_param_ok = $this->notice_param_check();
		
		if($is_param_ok)
		{
			$notice_list = $this->notice_model
								->multi_search_notice_list(
										 	         $this->pageNumber    
										 	        , $this->numberPerPage 
													, $this->searchBrand  
													, $this->searchPrice  
													, $this->searchAge	  
													, $this->searchMileage 
													, $this->searchSpeedBox
										 	        , $this->searchCarType 

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
		$this->load->view('notice/multi_search_notice_view');
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
				// elseif (!$this->searchType)
				// 	$this->output->set_body("description",PARAMETER_MISSING."searchType");
				// elseif(!$this->searchValue)
				// 	$this->output->set_body("description",PARAMETER_MISSING."searchValue");
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
			// if($is_param_val_error)
			// {
			// 	$is_param_ok = FALSE;
			// 	$this->output->set_body("result",4);
			// 	$this->output->set_body("description",WRONG_VALUE);
			// 	break;
			// }

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
  "searchBrand"    : "Benz",
  "searchPrice"   : "2-5",
  "searchAge" :"1-3",
  "searchMileage" :"0-5",
  "searchSpeedBox" :"auto",
  "searchCarType" :""
  }
}
*/
