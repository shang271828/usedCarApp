<?php
class GetNotice extends MY_Controller
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
		@$this->sortStr       = $body->sortStr;
		
		$is_param_ok = $this->notice_param_check();
		
		if($is_param_ok)
		{

			$notice_list = $this->notice_model
								->get_notice($this->pageNumber,
										 	 $this->numberPerPage,
										 	 $this->sortStr);
	 
			if (! $notice_list)	
			{
				$this->output->set_body("result",1);
				$this->output->set_body("description","null notice");
				$this->output->set_body("notice_list", $notice_list);
			}
			else
			{
				$this->output->set_body("result",0);
				$this->output->set_body("description","notice get");
				$this->output->set_body("notice_list", $notice_list);
			}
		}
	}

	function view_test()
	{	
		$this->load->view('notice/get_notice_view');
	}

	function notice_param_check()
	{
		$is_param_ok = TRUE;
		$is_param_missing  = ! ($this->pageNumber&&$this->numberPerPage&&$this->sortStr);
		$is_param_nonnum   = ! (is_integer($this->pageNumber+0)
			                  &&is_integer($this->numberPerPage+0));
		$is_param_val_error = ($this->pageNumber<1) || ($this->numberPerPage>20);
		$sortStrList = array(1=>"counter_view",2=>"counter_follow",3=>"counter_praise");
		$is_param_str_error = ! array_search("counter_view",$sortStrList);

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
				$this->output->set_body("description","sortStr's value is wrong ");
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
  "numberPerPage" : "2",
  "sortStr"       : "counter_view"
  }
}
*/