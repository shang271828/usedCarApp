<?php
class MY_Controller extends CI_Controller
{
	function __construct()
	{
		parent :: __construct();
		$this->load->library("checkuser_lib");
		$postJson = $this->input->createJson();
		$this->input->my_input($postJson);

	}
}