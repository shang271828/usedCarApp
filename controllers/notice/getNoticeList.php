<?php
header("Content-Type: text/html; charset=utf-8");
class GetNoticeList extends MY_Controller
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
		//unite test
		$body = $this->input->body;

		@$this->pageNumber    = $body->pageNumber;
		@$this->numberPerPage = $body->numberPerPage;
		@$this->pageType      = $body->pageType;

		$is_param_ok = $this->notice_param_check();
		
		if($is_param_ok)
		{

			$notice_list = $this->notice_model
								->get_notice_list($this->pageNumber,
										 	      $this->numberPerPage,
										 	      $this->pageType);
			// if ($this->pageType == "mainpage")
			// {
			// 	$total_notice_row = $this->notice_model
			// 				   		 	 ->get_total_car_row($this->pageType);
			// 	$this->output->set_body("total_notice_row", $total_notice_row);
			// }
	 		
			if (! $notice_list)	
			{
				$this->output->set_body("result",1);
				$this->output->set_body("description","null notice!");
				$this->output->set_body("notice_list", $notice_list);
			}
			else
			{
				$this->output->set_body("result",0);
				$this->output->set_body("description","get notice list:".$this->pageType."!");
				$this->output->set_body("notice_list", $notice_list);
			}
			
			//$this->load->view("output_view");

		}
	}


	function view_test()
	{	
		$this->load->view('notice/get_notice_list_view');
	}

	function notice_param_check()
	{
		$is_param_ok = TRUE;
		$is_param_missing  = ! ($this->pageNumber&&$this->numberPerPage&&$this->pageType);
		$is_param_nonnum   = ! (is_integer($this->pageNumber+0)
			                  &&is_integer($this->numberPerPage+0));
		$is_param_val_error = ($this->pageNumber<1) || ($this->numberPerPage>20);
		$pageTypeList = array(1=>"mainpage",2=>"discovery",3=>"timeline",4=>"friendPage");
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
//getNotice.php end
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