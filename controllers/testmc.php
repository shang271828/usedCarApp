<?php 
class Testmc extends CI_Controller 
{

	function __construct()
	{
		parent::__construct();
	}
	function index()
	{  
	  	$this->load->model('testmcmodel');  
		$this->load->helper('url');
		$this->testmcmodel->testMc();
		echo "<br>view-end";
		// $data = "testmc";
		// $this->load->view('testmcview',$data);
	} 
}