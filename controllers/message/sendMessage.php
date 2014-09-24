<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SendMessage extends MY_Controller
{
						
	function __construct()
	{
		parent :: __construct();
		$this->load->helper("form");
		$this->load->model('message_model');
		
	}

	function index()
	{
		$body = $this->input->body;
		var_dump($body);
		@$this->receive_uid = $body->receive_uid;
		@$this->receive_nid = $body->receive_nid;
		@$this->content   	= $body->content;
		@$img_array   	    = $body->img_list;
		@$this->messageType = $body->messageType;
		
		$this->img_list = $img_array[0].','.$img_array[1];
		var_dump($this->img_list);
		$is_param_ok = $this->param_check();
		
		if($is_param_ok)
		{
			$this->message_model
			  ->insert_message($this->receive_uid,
							$this->receive_nid,
							$this->content ,  	
							$this->img_list ,  
							$this->messageType);
			$this->output->set_body('result', 0);
			$this->output->set_body('description', 'message sent!');
		}
	}

	function view_test()
	{	
		;$this->load->view('message/send_message_view')
		;
	}

	private function param_check()
	{
	    $is_param_ok = true;
	   
		do
		{
			 $is_param_missing = !($this->receive_uid											
								  &&$this->receive_nid		
								  &&$this->content   			
								  &&$this->img_list 	
								  &&$this->messageType	
				                   ); 
			if( $is_param_missing )
			{
				$is_param_ok = false;
				$this->output->set_body('result', '1');
				$this->output->set_body('description', 'parameter error!');
				break;
				
			}

			if(strlen($this->content)<6) 
			{	
				$is_param_ok = false;
				$this->output->set_body('result', '2');
				$this->output->set_body('description', 'content length  error! too short!');
				break;
			}

		}while(false);
		return $is_param_ok;		
	}
}

/*
{
 "head": {  
   "uid"  : "1", 
   "time"  : "2014-08-03 03:08:05", 
   "token" : "9fd98454b511ce20120ecb593ed177e3"
  },
 "body":{
  "receive_uid"     : "2",
  "receive_nid"     : "2",
  "content"         : "my_message",
  "img_list"        :  ["http:\/\/xdream.co\/CI_API\/application\/upload_dir\/3.jpg","http:\/\/xdream.co\/CI_API\/application\/upload_dir\/4.jpg"],
  "messageType"     : "private"
  }
}
*/
