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

		$this->destination_list = $body->destination_list;
		$this->content 	   		= $body->content;

		if (property_exists ( $body, 'img_list'))
			$this->img_list  = $body->img_list;

		

		$is_param_ok = $this->param_check();
		
		if($is_param_ok)
		{
			$this->message_model
			  ->insert_message($this->destination_list	,
								$this->content 	   		,	
								$this->img_list     	   		 
							);
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
			 $is_param_missing = !( $this->destination_list										
								       			
								  &&$this->content 	   				   	   		
				                   ); 
			if( $is_param_missing )
			{
				$is_param_ok = false;
				$this->output->set_body('result', '1');
				$this->output->set_body('description', 'parameter error!');
				break;
			}
			if(!is_array($this->img_list))	
			{
				$is_param_ok = false;
				$this->output->set_body('result', '1');
				$this->output->set_body('description', 'parameter error!');
				break;
			}
		

			// if(strlen($this->content)<6) 
			// {	
			// 	$is_param_ok = false;
			// 	$this->output->set_body('result', '2');
			// 	$this->output->set_body('description', 'content length  error! too short!');
			// 	break;
			// }

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
  "content"         : "my_message",
  "img_list"        : ["http://xdream.co/CI_API/upload_dir/1303bcfae0b6c8f859bcc2aafcb2ee23.jpg","http://xdream.co/CI_API/upload_dir/225e97394f00e2bf3c42f34e665553c3.jpg"],
  "destination_list":["2"]
  }
}

*/
