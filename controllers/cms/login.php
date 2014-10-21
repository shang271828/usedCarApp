<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends  CI_Controller
{
	function __construct()
	{
		header("Content-type: text/html; charset=utf-8");
		parent :: __construct();
		; $this->load->helper('url')
		; $this->load->model('user_model')
		; $this->init()
		;
	}

	function index()
	{
		; $this->load->view('cms/user/login_view', $this->data)
		;
	}

	function check()
	{
		; $username
			= $this->input->post('username')
		; $password
			= $this->input->post('password')
		;

		; $user
			= $this->user_model->compare_user($username, $password)
	
		;
		if($user)
		{
			; $this->input->set_cookie($name = 'level'
									, $value = $user->level
									, $expire = 0 // before close browser
									);
			; $this->input->set_cookie($name = 'uid'
									, $value = $user->uid
									, $expire = 0 // before close browser
									);
			; $this->input->set_cookie($name = 'username'
									, $value = $username
									, $expire = 0 // before close browser
									);			
			;
			$this->data['msg'] = '欢迎'.$username;
			$this->data['jump_alt'] = '点击继续';
			$this->data['jump_link'] =site_url('cms/admin/main')
			;			
		}
		else
		{
			$this->data['msg'] = '用户名或密码错误';
			$this->data['jump_alt']= '返回';
			$this->data['jump_link'] = site_url('cms/login/index')
			;
		}

		; $this->load->view('cms/user/login_check_view',$this->data)
		;
	}

	private function init()
	{
		; $header = $this->load->view('cms/header_view')
		; $this->data['header'] = $header
		;

	}

}
//end