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
        ; var_dump($this->upload_url)
        ;
    }

    function index()
    {
    	; @$file_name = $this->input->body->fileName

    	; $file_info = $this->upload->fetch_img_by_name($file_name)
    	; 
        ;
    	if($file_info)
    	{
    		; $this->output->set_body('result','0')
    		; $this->output->set_body('description','图片上传成功')
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
