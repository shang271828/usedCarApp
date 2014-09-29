<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Register extends CI_Controller
{
						
	function __construct()
	{
		;parent :: __construct()
		;$this->load->database()
		;$this->load->helper("form")
		
		; $this->load->model('user_model')
		;
	}

	function index()
	{
		// work code

		$body = $this->input->body;
		; @$this->userName = $body->userName
		; @$this->password = $body->password
		; @$this->phone	   = $body->phone
		; @$this->code     = $body->code
		;

		; $is_param_ok = $this->register_param_check();
		if($is_param_ok)
		{
			//; $this->sysCode = $this->user_model->get_captcha($this->phone)
			//; $is_code_error = !($this->code == $this->sysCode) 
			; $is_code_error = !($this->code == "1234") 
			;
			if($is_code_error)
			{

				; $this->output->set_body('result', '4')
				; $this->output->set_body('description', 'code error!')
				; //function end
			}
			else
			{
				; $this->user_model->addUser($this->userName, 
											 $this->password, 
											 $this->phone,
											 $this->code)
	
				; $this->output->set_body('result', '0')
				; $this->output->set_body('description', 'user added!')
				;
			}
		}
	}
	function view_test()
	{	
		$this->load->view('user/register_view');
	}

	
	private function register_param_check()
	{
		$is_param_ok = true;

		do
		{
			; $is_param_missing = !($this->userName
								  &&$this->password
								  &&$this->phone)
			; 
		
			if( $is_param_missing )
			{
				; $is_param_ok = false
				; $this->output->set_body('result', '1')
				; $this->output->set_body('description', 'parameter error!')
				; break
				; //function end
			}

			; $is_username_exist 
				= $this->user_model->is_username_exist($this->userName)
			;

			if($is_username_exist)
			{
				; $is_param_ok = false
				; $this->output->set_body('result', '2')
				; $this->output->set_body('description', 'userName exist!')
				; break
				; //function end
			}	

			// ; $is_phone_exist 
			// 	= $this->user_model->is_phone_exist($this->phone)
			// ;

			// if($is_phone_exist)
			// {
			// 	; $is_param_ok = false
			// 	; $this->output->set_body('result', '3')
			// 	; $this->output->set_body('description', 'phone exist!')
			// 	; break
			// 	; //function end
			// }					

		}while(false)

		; return $is_param_ok
		;		
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
  "userName"      : "hou",  
  "password"      : "4",
  "phone"         : "123",
  "code"          : ""
  }
}
*/