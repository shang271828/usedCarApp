<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class GetUid extends CI_Controller
{
	function __construct()
	{
		; parent :: __construct()
		; $this->load->database()		
		; $this->load->model('user_model')
		//; $this->load->model('user_alert_model')
		;
	}

	function index()
	{
		$body = $this->input->body;

		$this->username = $body->userName;
		
		$is_param_ok = true;

		if($is_param_ok)
		{
			$uid = $this->user_model
							 ->get_uid($this->username);
			if($uid == 'error')
			{
				$this->output->set_body('result', '1');
				$this->output->set_body('description', '用户名不存在');
			}
			else
			{
				$this->output->set_body('result', '0');
				$this->output->set_body('description', GET_USERINFO);
				$this->output->set_body('uid',$uid);
			}				
		}
	}

	function view_test()
	{	
		$this->load->view('user/get_uid_view');
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
  "userName"         : "hou"
  }
 }
 */