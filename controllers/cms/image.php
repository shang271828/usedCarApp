<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Image extends  CI_Controller
{
	function __construct()
	{
		parent :: __construct();
		; $this->load->library('upload')
		; $this->load->helper('url')
		;
		; $this->upload->set_upload_dir('./upload_dir')
		;
	}

	function index()
	{
		; $file_name = 'pic'
		; $data = array('action' => './image/postImage/'.$file_name
						,'file_name' =>$file_name)
		; $this->load->view('upload/upload_view.php',$data)
		;
	}

	function postImage($fileName)
	{
		; $res = $this->upload->fetch_img_by_name($fileName)
		;
		if($res)
		{
			; echo base_url().'/upload_dir/',$res['full_name']
			; 
		}
		else
		{
			; echo 'upload error'
			; var_dump($this->upload->error)
			;
		}
	}
}