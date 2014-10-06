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
		// $data = array
		// (   
		// 	"uid"        => $uid,
  //      		"price"      => "",
  //      		"mileage"    =>	"",
  //      		"brand"		 =>	"",
  //      		"age"		 => "",
  //      		"car_type"   => "",
  //      		"speed_box"  => ""

		// 	);
		// $this->db->insert($this->table,$data);
		$SQL = "INSERT INTO `prefix_user_preference` (`uid`, `price`, `mileage`, `brand`,`age`, `car_type`,`speed_box`) 
				  VALUES (".$uid.", '', '', '', '','','')";
		$this->db->query($SQL);
	}



	function insert($price,
					$mileage,
					$brand,
					$age,		
					$car_type,  
					$speed_box
					)
	{
		// $this->db->select("uid");
		// $query = $this->db->get($this->table);
		// $uid_list = $query->row_array();
		
		// $str = $this->db->last_query();
		// var_dump($str);
		$SQL = "SELECT `uid`
				FROM (`prefix_user_preference`)";
		$query = mysql_query($SQL);
		while($row = mysql_fetch_array($query))
		{
			$uid_list[] = $row['uid'];
		}
		//$uid_list = $query->result_array() ;
		$uid = $this->input->head->uid;

		// $data = array
		// (
		// 	"uid"        => $uid,
  //      		"price"      => $price,
  //      		"mileage"    =>	$mileage,
  //      		"brand"		 =>	$brand,
  //      		"age"        => $age,
  //      		"car_type"   =>$car_type,
  //      		"speed_box"  =>$speed_box
		// );

		$search = array_search($uid,$uid_list);

		if (!($search === FALSE))
		{
			// $this->db->where("uid",$uid);
			// $this->db->update($this->table,$data);
			$SQL = "UPDATE `prefix_user_preference` 
					SET `uid` = '".$uid."', `price` = '".$price."', `mileage` = '".$mileage."', `brand` = '".$brand."', 
					`age` = '".$age."', `car_type` = '".$car_type."', `speed_box` = '".$speed_box."' 
					WHERE `uid` = '".$uid."'";
			$this->db->query($SQL);

		}
		else
		// 	$this->db->insert($this->table,$data);
		// $str = $this->db->last_query();
		// var_dump($str);

		$SQL = "INSERT INTO `prefix_user_preference` 
				(`uid`, `price`, `mileage`, `brand`, `age`, `car_type`, `speed_box`) 
				VALUES ('".$uid."', '".$price."', '".$mileage."', '".$brand."', '".$age."', '".$car_type."', '".$speed_box."')";
		$this->db->query($SQL);
		

	}



	function define()
	{
		$this->table  = "prefix_user_preference";
		$this->column = array
		(
			"uid",
			"price" ,
			"mileage",
			"brand",
			"age"     , 
			"car_type" ,
			"speed_box"

        );
	}


}
