<?php
class publish extends MY_Controller
{
	var $title;
	var $content;
	var $img_var_list;
	var $img_list;
	function __construct()
	{
		parent ::__construct();
		$this->load->helper( array("url","form") );
        header("Content-type:text/html;charset=utf-8"); 
		$this->load->model("notice_model");
		$this->load->library("upload_lib"); 
	}	

	function index_success()
	{
		$this->load->view("upload_status_view");
				$this->load->view("upload_status_success_view");

	}
		
	


	function index()
	{	
		/*
		 * union test
		 *
		 **/
		//$body = new stdClass;

		//$body->title         = "aa";
		//$body->content       = "bbb";
		//$body->img_var_list  = "pic0";

		//$this->input->body =&$body;

		$this->input->uid        = "111";
		$this->input->sysTime    = date("Y-m-d H:i:s");
		$this->input->coordinate = "124.51,32.04";

		/* work space
		 *
		 *
		 */
		@$this->title        = $this->input->body->title;
		@$this->content      = $this->input->body->content;

		$this->img_var_list  = $this->input->body->img_var_list;
		$this->img_list = array('http://x.co/img1.jpg','http://x.co/img2.jpg');//$this->upload_lib->do_upload_one($this->img_var_list);

		$is_param_ok = $this->notice_param_check();
		if($is_param_ok)
		{
			$this->notice_model
				->insert($this->title
						,$this->content
						,$this->img_list = json_encode($this->img_list )
						);

			$this->output->set_body("result",1);
			$this->output->set_body("description","notice published");
		}

		//echo "TITLE:";
		//var_dump($this->title);
		//echo "CONTENT:";
		//var_dump($this->content);
	}

	function notice_param_check()
	{
		$is_param_ok = TRUE;
		
		$is_param_missing = ! ($this->title && $this->content && $this->img_list);
		
		do
		{
			if ($is_param_missing)
			{
				$is_param_ok = FALSE;
				$this->output->set_body("result",0);
				$this->output->set_body("description","parameter missing");
				break;
			}
		}while(FALSE);
		return $is_param_ok;
	}
}