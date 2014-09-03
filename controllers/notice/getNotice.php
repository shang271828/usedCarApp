<?php
class GetNotice extends MY_Controller
{
	function __construct()
	{
		parent ::__construct();
		$this->load->database();
		$this->load->model("notice_model");
	}

	function index()
	{
		//unite test
		$body = new stdClass;
		$body->pageNumber    = 2;
		$body->numberPerPage = 4; 
		$body->sortStr       = "counter_view";

		//work space
		$this->pageNumber    = $body->pageNumber;
		$this->numberPerPage = $body->numberPerPage;
		$this->sortStr       = $body->sortStr;
		
		$is_param_ok = $this->notice_param_check();
		
		if($is_param_ok)
		{
			$notice_list = $this->notice_model->get_notice($this->pageNumber,
													 $this->numberPerPage,
													 $this->sortStr);
			if (! $notice_list)
			{
				$this->output->set_body("result",0);
				$this->output->set_body("description","null notice");
			}
			else
			{
				$this->output->set_body("result",1);
				$this->output->set_body("description","notice get");
				$this->output->set_body("notice_list", $notice_list);
			}
		}
	}

	function notice_param_check()
	{
		$is_param_ok = TRUE;
		$is_param_missing  = ! ($this->pageNumber&&$this->numberPerPage&&$this->sortStr);
		$is_param_nonnum   = ! (is_integer($this->pageNumber)
			                  &&is_integer($this->numberPerPage));
		$is_param_val_error = ($this->pageNumber<1) || ($this->numberPerPage>20);
		$sortStrList = array(1=>"counter_view",2=>"counter_follow",3=>"counter_praise");
		$is_param_str_error = ! array_search("counter_view",$sortStrList);

		do
		{
			if ($is_param_missing)
			{
				$is_param_ok = FALSE;
				$this->output->set_body("result",0);
				$this->output->set_body("description","parameter missing");
				break;
			}
			if($is_param_nonnum)
			{
				$is_param_ok = FALSE;
				$this->output->set_body("result",0);
				$this->output->set_body("description","parameter's type is wrong ");
				break;
			}
			if($is_param_val_error)
			{
				$is_param_ok = FALSE;
				$this->output->set_body("result",0);
				$this->output->set_body("description","parameter's value is wrong ");
				break;
			}
			if($is_param_str_error )
			{
				$is_param_ok = FALSE;
				$this->output->set_body("result",0);
				$this->output->set_body("description","sortStr's value is wrong ");
				break;
			}
		}while(FALSE);

	return $is_param_ok;
	}
}
//getNotice.php end