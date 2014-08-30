<?php

class Checkuser extends MY_Controller
{
						
	function __construct()
	{
		parent :: __construct();
		$this->load->database();
		$this->check(); 		
	}

	function details()
	{
		echo "details";   			            				           			      	
	}

}

//code end