<?php
class GetMessage extends MY_Controller
{
	function __construct()
	{
		parent ::__construct();
		$this->load->database();
		$this->load->helper("form");
		$this->load->model("message_model");
		$this->load->model("user_alert_model");
	}

	function index()
	{
		//unite test
		$body = new stdClass;
		$body->pageNumber    = "2";
		$body->numberPerPage = "4"; 
		

		//work space
		$this->pageNumber    = $body->pageNumber;
		$this->numberPerPage = $body->numberPerPage;
		

		 $is_param_ok = $this->message_param_check();
		
		if($is_param_ok)
		{
			$message_list  = $this->message_model
							  	->get_message($this->pageNumber,
											  $this->numberPerPage
										      );
			if (! $message_list)	
			{
				$this->output->set_body("result",1);
				$this->output->set_body("description","null message!");
				$this->output->set_body("message_list", $message_list);
			}
			else
			{
				$this->output->set_body("result",0);
				$this->output->set_body("description","get message list!");
				$this->output->set_body("message_list", $message_list);
			}			
		}
	}	
						
		// if ($this->is_fetched == 1)
		// {
		// 	$message_all = $this->message_model->get_unread_message();
		// }

		// foreach ($message_all as $value)
		// {
		// 	if($value["is_fetched"] == 0)
		// 	$this->unread_list = substr_replace($this->unread_list,
		// 										',"'.$value["mid"].'"',
		// 										-1,
		// 										0);
		// }
		// var_dump($this->unread_list);
		// $this->user_alert_model->update($this->unread_list);
	

	function view_test()
	{	
		$this->load->view('message/get_message_view');		
	}
	function message_param_check()
	{
		$is_param_ok = TRUE;
		$is_param_missing  = ! ($this->pageNumber&&$this->numberPerPage);
		$is_param_nonnum   = ! (is_integer($this->pageNumber+0)
			                  &&is_integer($this->numberPerPage+0));
		$is_param_val_error = ($this->pageNumber<1) || ($this->numberPerPage>20);

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
		}while(FALSE);

	return $is_param_ok;
	}
}

/*
{
 "head":{  
   "uid"          : "1",  
   "time"         : "2014-08-03 03:08:05", 
   "token"        : "9fd98454b511ce20120ecb593ed177e3"
  },
 "body":{  
  "pageNumber"    : "2",  
  "numberPerPage" : "4"
  }
}
*/