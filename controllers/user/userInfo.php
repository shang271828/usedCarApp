<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UserInfo extends CI_Controller
{
						
	function __construct()
	{
		parent :: __construct();
		; $this->load->model('user_model')
		;
	}

	function index()
	{
		/**
		* union test 
		* 
		*/
		; $body = new stdClass
		; $body->uid = '1'
		;
		// work code

		; @$uid = $this->input->body->uid
		;

		; $is_param_ok = $this->register_param_check();
		if($is_param_ok)
		{

			// input ok!
			; $this->user_model->addUser($userName, $password, $phone)
			; $this->output->set_body('result', '1')
			; $this->output->set_body('description', 'user added!')
			;
		}
	}

	private function register_param_check()
	{
		$is_param_ok = true;

		; @$userName = $this->input->body->userName
		; @$password = $this->input->body->password
		; @$phone	 = $this->input->body->phone
		;
		do
		{
			; $is_param_missing = !($userName&&$password&&$phone)
			; 
			if( $is_param_missing )
			{
				; $is_param_ok = false
				; $this->output->set_body('result', '0')
				; $this->output->set_body('description', 'parameter error!')
				; break
				; //function end
			}

			; $is_username_exist 
				= $this->user_model->is_username_exist($userName)
			;

			if($is_username_exist)
			{
				; $is_param_ok = false
				; $this->output->set_body('result', '0')
				; $this->output->set_body('description', 'userName exist!')
				; break
				; //function end
			}	

			; $is_phone_exist 
				= $this->user_model->is_phone_exist($phone)
			;

			if($is_phone_exist)
			{
				; $is_param_ok = false
				; $this->output->set_body('result', '0')
				; $this->output->set_body('description', 'phone exist!')
				; break
				; //function end
			}	


		}while(false)

		; return $is_param_ok
		;		
	}



}
