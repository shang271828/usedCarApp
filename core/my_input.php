<?php
class MY_Input extends CI_Input
{
	function __construct()
	{
		parent :: __construct();
		; ini_set('date.timezone','Asia/Shanghai')
		; $this->sys_input()
		; $this->_my_input()
		;
	}
	
	function _my_input()
	{
		if(IS_DEBUG_INPUT)
		{
			; $this->json_package 
				= json_decode(
					$this->debug_input()
					)
			;
		}
		else
		{
			; $this->json_package
				= json_decode(
					$this->post('json_package')
					)
			;
		}


		; $json_package = & $this->json_package
		; $this->head   = & $json_package->head
		; $this->body   = & $json_package->body
		;
		if(is_object($this->head))
		{	
			if(property_exists($this->head, 'uid'))
			{
				; $this->uid  = & $json_package->head->uid
				;
			}
			if(property_exists($this->head, 'time'))
			{
				; $this->userTime = & $json_package->head->time
				;
			}
			if(property_exists($this->head, 'token'))
			{
				; $this->token    = & $json_package->head->token
				;
			}
			if(property_exists($this->head, 'terminal'))
			{
				; $this->terminal    = & $json_package->head->terminal
				;
			}
			else
			{
				; $this->terminal = 'unknown'
				;
			}
			if(property_exists($this->head, 'coordinate'))
			{
				; $this->coordinate    = & $json_package->head->coordinate
				;
			}
			else
			{
				; $this->coordinate = '0,0'
				;
			}

	
		}


		/////**optional input**/////

		// ; $this->verify()
		// ; 
		// ;
	}

	// private function verify()
	// {
	// 	if(!$this->terminal)
	// 	{
	// 		; $this->terminal = 'unknown'
	// 		;
	// 	}
	// 	if(!$this->coordinate)
	// 	{
			
	// 	}
	// }

	private function sys_input()
	{
		; $this->sysTime = date('Y-m-d H:i:s')
		;
	}

	private function debug_input()
	{
		$uid    	 = "1";
		$userTime    = "2014-10-28 13:52:05";
		$password    = "123456";
		$token       = md5($uid
							.substr($userTime,0,-6) // '14-08-30 20:'
							.$password);
		$debug_struct = array
		(
			"head" => array 
			(
				"uid"  => $uid,
			    "userTime"  => $userTime,
			    "token"     => $token
			),
			"body" => array
			(
				"img_var_list"  => "http://xdream.co/upload/compressed_image/Newton.png",
			    "imageName" => "Newton.png"
			)
		);
		; return json_encode($debug_struct)
		;
	}
}
// end