<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Register extends MY_Controller
{
						
	function __construct()
	{
		; parent :: __construct()
		; $this->load->database()
		; $this->load->helper("form")		
		; $this->load->model('user_model')
		; $this->load->model('user_relation_model')
		; $this->load->model('user_preference_model')
		;	$this->load->model('invitation_code_model')
		//; $this->load->model('user_alert_model')
		;
	}

	function index()
	{
		; $body = $this->input->body
		; $this->userName         = $body->userName
		; $this->password         = $body->password
		; $this->phone	   		  = $body->phone
		; $this->code     		  = $body->code
		;
		// if (property_exists ( $body, 'invitation_code'))
		// {
		// 	; $this->invitation_code  = $body->invitation_code
		// 	;
		// }
		// else	
		// {					
		// 	; $this->invitation_code  = 'BRYCFJ'
		// 	;
		// }
		
		; $is_param_ok = $this->register_param_check()
		;
		if($is_param_ok)
		{
			// ; $this->sysCode = $this->user_model->get_captcha($this->phone)
			// ; $is_code_error = !($this->code == $this->sysCode) 
			; $is_code_error = !($this->code == "1234") 
			;
			if($is_code_error)
			{
				; $this->output->set_body('result', '4')
				; $this->output->set_body('description', CAPTCHA_ERROR)
				; //function end
			}
			else
			{
				; $uid = $this->user_model->addUser($this->userName, 
											 		$this->password, 
											 		$this->phone,
											 		$this->code)
				; $this->user_relation_model->addUser($uid); 
				; $this->user_preference_model->addUser($uid);
				//; $this->user_state_model->addUser($uid);
				//; $this->invitation_code_model->updateCode($this->invitation_code)
				;
				// for ($i=0;$i<3;$i++)
				// {
				// 	$code_array[] = $this->invitation_code_model->addCode($this->input->uid);
				// }
				//; $this->message_model->send()
				//;

				//; $this->user_alert_model->addUser($nid);
				; $this->output->set_body('result', '0')
				; $this->output->set_body('description', USER_ADD)
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
		//$init_code = array('DYKTPE','XHOZGJ','BBIEDX','JTHWJW','HTZQXR');
		do
		{
			; $is_param_missing = !($this->userName
								  &&$this->password
								  &&$this->phone)
			; 
			// ; $is_non_invitation = !$this->invitation_code
			// ;
			if( $is_param_missing )
			{
				; $is_param_ok = false
				; $this->output->set_body('result', '1')
				; $this->output->set_body('description', PARAMETER_MISSING)
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
				; $this->output->set_body('description', NAME_EXIST)
				; break
				; //function end
			}	

			; $is_phone_exist 
				= $this->user_model->is_phone_exist($this->phone)
			;

			if($is_phone_exist)
			{
				; $is_param_ok = false
				; $this->output->set_body('result', '3')
				; $this->output->set_body('description', PHONE_EXIST)
				; break
				; //function end
			}
			// if($is_non_invitation)
			// {
			// 	; $is_param_ok = false
			// 	; $this->output->set_body('result', '4')
			// 	; $this->output->set_body('description', '未被邀请')
			// 	; break
			// 	; //function end
			// }
			// ; $is_code_wrong = 		!(array_search($this->invitation_code, $init_code))				
			// 						&&
			// 						$this->invitation_code_model
			// 						->is_code_wrong($this->invitation_code)
			// ;
			// if($is_code_wrong)
			// {
			// 	; $is_param_ok = false
			// 	; $this->output->set_body('result', '5')
			// 	; $this->output->set_body('description', '邀请码错误')
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
  "code"          : "1234",
  "invitation_code": "DYKTPE"
  }
}
*/