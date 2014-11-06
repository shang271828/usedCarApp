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

		$this->pageNumber     = $body->pageNumber;
		$this->numberPerPage  = $body->numberPerPage;
		$this->pageType 	  = $body->pageType;

		$is_param_ok = $this->notice_param_check();
		
		if($is_param_ok)
		{

			$notice_list = $this->notice_model
								->get_notice_list($this->pageNumber,
										 	      $this->numberPerPage,
										 	      $this->pageType);
			//简化location
			foreach ($notice_list as  &$value) 
			{
				$tmp = explode(' ', $value['car_location']);
				$value['car_location'] = $tmp[1];
			}
			//获得总信息条数
			 $total_row = $this->notice_model
			 			   		 	 ->get_total_row($this->pageType);
			 
			 
			 $this->output->set_body("total_row",$total_row );
				
			if (! $notice_list)	
			{
				$this->output->set_body("result",1);
				$this->output->set_body("description",NULL_NOTICE);
				$this->output->set_body("notice_list", $notice_list);
			}
			else
			{

				$this->output->set_body("result",0);
				//$this->output->set_body("description","get notice list:".$this->pageType."!");
				$this->output->set_body("description",GET_NOTICE);
				$this->output->set_body("notice_list", $notice_list);
			}			
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
		$is_param_val_error = ($this->pageNumber<1) || ($this->numberPerPage>50);
		$pageTypeList = array(1=>"mainpage",2=>"discovery",3=>"timeline",4=>"friendPage",5=>"collection");
		$is_param_str_error = ! array_search($this->pageType,$pageTypeList);

		do
		{
			if ($is_param_missing)
			{
				$is_param_ok = FALSE;
				$this->output->set_body("result",2);
				$this->output->set_body("description",PARAMETER_MISSING);
				break;
			}
			if($is_param_nonnum)
			{
				$is_param_ok = FALSE;
				$this->output->set_body("result",3);
				$this->output->set_body("description",WRONG_TYPE);
				break;
			}
			if($is_param_val_error || $is_param_str_error)
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