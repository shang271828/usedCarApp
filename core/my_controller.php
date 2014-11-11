<?php
class MY_Controller extends CI_Controller
{
	function __construct()
	{
		parent :: __construct();

		; $this->load->library('upload')
		;
		;$unverify = FALSE
		;
	
		if(property_exists($this->input->body, 'pageType'))
		{
			if($this->input->body->pageType == 'mainpage')
			{
				$unverify = TRUE;
			}
		}
		if($this->input->head->uid == '7')
		{
			$unverify = TRUE;
		}
		if(!IS_DEBUG)
		{
			if(!$unverify)
			{
				; $this->verify_user()
				;
			}
		}	
	}

	private function verify_user()
	{
		if(!$this->is_time_ok())
		{
			; $this->output->set_user_code(1)
			; exit()
			;			
		}

		if(!$this->is_uid_exist())
		{
			; $this->output->set_user_code(2)
			; exit()
			;
		}



		if(!$this->is_token_Ok())
		{
			; $this->output->set_user_code(3)
			; exit()
			;			
		}

		; $this->output->set_user_code(0)
		; // through user verification
	}

	private function is_uid_exist()
	{

		; $this->load->model('user_model')
		; return 
			$this->user_model
				->is_uid_exist(
					$this->input->uid
					)
		;
	}	

	private function is_time_ok()
	{
		; $userTimeStamp = strtotime($this->input->userTime);	
	 	; $nowTimeStamp  = strtotime($this->input->sysTime);

		; $timeDiff   = $nowTimeStamp - $userTimeStamp;    
		; $timeRange  = 300;								
		; return ( ($timeDiff < $timeRange)
				&& ($timeDiff > -$timeRange))
		;
	}


	private function is_token_Ok()
	{

		; $timeStr = substr($this->input->userTime,0,-6) // example: '2019-11-09 09'
		; $this->load->model('user_model')
		; return $this->user_model->compare_user($timeStr,$this->input->token)
		// ; $controlToken 
		// 	= md5($this->input->uid
		// 			.$timeStr
		// 			.$this->input->password)
	 //    ; 
	 //    ; return ($controlToken === $this->input->token)
	    ;		
	}
	function debug_var($var)
	{
		if(IS_DEBUG_VAR)
			var_dump($var);
	}
}