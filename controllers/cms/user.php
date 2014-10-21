<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller
{
						
	function __construct()
	{
		header("Content-type: text/html; charset=utf-8");
		parent :: __construct();
		; $this->load->helper('url')
		; $this->load->model('user_model')
		;
	}


	function add_user()
	{
		; @$this->userName        = $this->input->post('username')
		; @$this->password        = $this->input->post('password')
		; @$this->level	   		  = $this->input->post('level')
		; $this->phone            = '123456789'
		;  $this->captcha         = '1234'   
		; $is_param_ok = $this->register_param_check();
		if($is_param_ok)
		{

			
			; $this->user_model->addUser($this->userName
								 		, $this->password
								 		, $this->phone
								 		, $this->captcha 
								 		, $this->level
							 		)
			;
			header("location:".site_url('cms/admin/user_list'));
		}
		; $this->load->view('cms/jump_view'
							,array('msg'=> $this->msg
									,'link'=>site_url('cms/admin/add_user')
								)
							)	
		;
	}

	function del_user($uid)
	{
		; $this->user_model->delete_user($uid)
		; header("location:".site_url('admin/user_list'));	
	}

	function reset_passwd($uid)
	{
		$password = $this->input->post('password');

		$this->user_model
				->update_password_uid($uid
									,$password
									);
		; $this->load->view('cms/jump_view'
							,array('msg'=> '密码已修改'
									,'link'=>site_url('cms/admin/main')
								)
							)	
		;						
	}



	private function register_param_check()
	{
		$is_param_ok = true;

		do
		{
			; $is_param_missing = !($this->userName
								  &&$this->password
								  )
			; 
			
			if( $is_param_missing )
			{
				; $is_param_ok = false
				; $this->msg = '缺少用户名或密码!'
				; break
				; //function end
			}

			; $is_username_exist 
				= $this->user_model->is_username_exist($this->userName)
			;

			if($is_username_exist)
			{
				; $is_param_ok = false
				; $this->msg = '用户已经存在'
				; break
				; //function end
			}	

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