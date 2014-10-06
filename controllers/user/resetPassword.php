<?php
class ResetPassword extends MY_Controller
{

	function __construct()
	{
		parent ::__construct();
		$this->load->helper( "form");
		$this->load->model("user_model"); 		
	}	

	function index()
	{	
		$body = $this->input->body;

		$this->phone      = $body->phone;

		$this->password   = $body->reset_password;
 

		$is_param_ok = $this->user_param_check();
		if($is_param_ok)
		{
			$this->user_model
				->update_password($this->phone
							     ,$this->password						
							);

			$this->output->set_body("result",0);
			$this->output->set_body("description",RESET_PASSWORD);
		}
	}

	function view_test()
	{	
		$this->load->view('user/reset_password_view');
	}
	
	function user_param_check()
	{
		$is_param_ok = TRUE;
		
		$is_param_missing = ! ($this->password && $this->phone);

		do
		{
			if ($is_param_missing)
			{
				$is_param_ok = FALSE;
				$this->output->set_body("result",1);
				$this->output->set_body("description",PARAMETER_MISSING);
				break;
			}

		}while(FALSE);
		return $is_param_ok;
	}
}
/*
{
 "head":{  
   "uid"          : "123123",  
   "time"         : "2014-08-03 03:08:05", 
   "token"        : "9fd98454b511ce20120ecb593ed177e3"
  },
 "body":{  
  "phone"        : "12345678901",
  "reset_password" : "9fd98454b511ce20120ecb593ed177e3"
  }
}

*/