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

			
			$this->output->set_body('result', '0');
			$this->output->set_body('description', GET_USERINFO);
			$this->output->set_body('uid',$uid);
		}
	}

	function view_test()
	{	
		$this->load->view('user/get_uid_view');
	}
}