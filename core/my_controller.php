<?php
class MY_Controller extends CI_Controller
{
	function __construct()
	{
		parent :: __construct();
		$this->load->library("checkuser_lib");
	}
	function check()
	{
		$userInfo=$this->input->my_input();
		$returnInfo = $this->checkuser_lib->check($userInfo);
		$this->output->setReturnCode($returnInfo["head"]["returnCode"]);
		$this->output->my_output($returnInfo);
	}

}