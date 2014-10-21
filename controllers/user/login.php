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
		$body = $input->
	}
}