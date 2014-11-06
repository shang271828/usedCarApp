<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Login extends MY_Controller
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

		$this->username = $body->username;
		
		$is_param_ok = $this->user_param_check();

		if($is_param_ok)
		{
			$userInfo = $this->user_model
							 ->select_userinfo($this->get_uid);
			if($this->get_uid == $this->input->uid)
			{
			
				$userInfo['is_followed'] = $this->user_relation_model
								 				->judge_user_follow($this->get_uid);
				$userInfo['unread_message_num'] = $this->message_model	
														->get_unread_message_num($this->get_uid);	 
				$userInfo['total_num'] = $this->notice_model	
													->get_total_notice_num($this->get_uid);
			}	 
			$this->output->set_body('result', '0');
			$this->output->set_body('description', GET_USERINFO);
			$this->output->set_body('userInfo',$userInfo);
		}
	}
}