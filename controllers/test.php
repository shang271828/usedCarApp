<?php

class Test extends MY_Controller
{
	function __construct()
	{
		parent ::__construct();
		$this->load->database();
		$this->load->helper("form");
		$this->load->model('user_model');
		$this->load->model('user_relation_model');
	}

	 function view_test()
    {
        $this->load->view("notice/upload_image_view");        
    }

}