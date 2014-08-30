<?php
class MY_Controller extends CI_Controller
{
	function __construct()
	{
		parent :: __construct();
		$this->load->library("checkuser_lib");

		$this->input->my_input();

		$userInfo = $this->input->json_package;
		$userCode = $this->checkuser_lib->check($userInfo);
		$this->output->setReturnCode($userCode);

		$this->output->my_output();

	}
}