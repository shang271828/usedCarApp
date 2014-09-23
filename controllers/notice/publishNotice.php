<?php
class publishNotice extends MY_Controller
{
	var $title;
	var $content;
	var $img_var_list;
	var $img_list;
	function __construct()
	{
		parent ::__construct();
		$this->load->helper( array("url","form") );
		$this->load->model("notice_model");
		$this->load->library("upload"); 		
	}	


	function index()
	{	
		$body = $this->input->body;
		@$this->title   = $body->title;
		@$this->content = $body->content;
		@$this->img_url = $body->img_url;

		$is_param_ok = $this->notice_param_check();
		if($is_param_ok)
		{
			$this->notice_model
				->insert($this->title
						,$this->content
						,$this->img_url
						);

			$this->output->set_body("result",0);
			$this->output->set_body("description","notice published");
		}

	}

	function view_test()
	{	
		$this->load->view('notice/publish_notice_view');
	}
	
	function notice_param_check()
	{
		$is_param_ok = TRUE;
		
		$is_param_missing = ! ($this->title && $this->content);
		
		do
		{
			if ($is_param_missing)
			{
				$is_param_ok = FALSE;
				$this->output->set_body("result",1);
				$this->output->set_body("description","parameter missing");
				break;
			}

			if(!$this->img_url)
			{
				$is_param_ok = FALSE;
				$this->output->set_body("result",2);
				$this->output->set_body("description","image missing");
				break;				
			}
		}while(FALSE);
		return $is_param_ok;
	}
}