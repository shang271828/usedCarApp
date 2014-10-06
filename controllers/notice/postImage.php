<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PostImage extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        ; $this->load->library('upload')
        ; $this->load->helper('url')
        ; $this->load->helper('form')
        ; $upload_path = realpath('./upload_dir')
        ; $this->upload->my_upload_path($upload_path)
        ; $this->upload_url = base_url().'upload_dir/'
        ;
    }

    function index()
    {
        
    	; @$file_name = $this->input->body->file_name

    	; $file_info = $this->upload->fetch_img_by_name($file_name)
    	; 
        ;
    	if($file_info)
    	{
    		; $this->output->set_body('result','0')
    		; $this->output->set_body('description',UPLOAD_IMAGE)
    		; $file_url = $this->upload_url.$file_info['full_name']
    		; $this->output->set_body('URL',$file_url)
    		;
    	}
    	else
    	{
    		; $this->output->set_body('result','1')
    		; $error = $this->upload->error
    		; $this->output->set_body('description', $error)
    		;
    	}
    }

    function view_test()
    {
        ;$this->load->view("notice/post_image_view")
        ;
    }

}
/*
{ 
 "head":{  
   "uid"          : "123456",  
   "time"         : "2014-08-03 03:08:05", 
   "token"        : "9fd98454b511ce20120ecb593ed177e3"
  },
 "body":{    
   "file_name"      : "pic0"
  }
}
*/