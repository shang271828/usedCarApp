<?php
class User_preference_model extends CI_Model
{
	function __construct()
	{
		parent ::__construct();
		$this->load->database();
		$this->define();
	}

	function insert($price,
					$mileage,
					$brand)
	{
		var_dump($price);
		$data = array
		(
			"uid"        => $this->input->head->uid,
       		"price"      => $price,
       		"mileage"    =>	$mileage,
       		"brand"		 =>	$brand
		);
		$this->db->insert($this->table,$data);
	}

	function define()
	{
		$this->table  = "prefix_user_preference";
		$this->column = array
		(
			"uid",
			"price" ,
			"mileage",
			"brand"	 
        );
	}


}
