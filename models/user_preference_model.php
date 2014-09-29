<?php
class User_preference_model extends CI_Model
{
	function __construct()
	{
		parent ::__construct();
		$this->load->database();
		$this->define();
	}

	function addUser($uid)
	{
		$data = array
		(   
			"uid"        => $uid,
       		"price"      => "",
       		"mileage"    =>	"",
       		"brand"		 =>	""
			);
		$this->db->insert($this->table,$data);
	}



	function insert($price,
					$mileage,
					$brand)
	{
		$this->db->select("uid");
		$query = $this->db->get($this->table);
		$uid_list = $query->row_array();
		
		$uid = $this->input->head->uid;
		$data = array
		(
			"uid"        => $uid,
       		"price"      => $price,
       		"mileage"    =>	$mileage,
       		"brand"		 =>	$brand
		);

		$search = array_search($uid,$uid_list);
		if (!($search === FALSE))
		{
			$this->db->where("uid",$uid);
			$this->db->update($this->table,$data);
		}
		else
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
