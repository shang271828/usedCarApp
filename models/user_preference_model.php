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

		$SQL = "INSERT INTO `prefix_user_preference` (`uid`, `price`, `mileage`, `brand`,`age`, `car_type`,`speed_box`) 
				  VALUES (".$uid.", '', '', '', '','','')";
		$this->db->query($SQL);
	}



	function insert($userInfo)
	{

		$uid = $this->input->head->uid;

			$SQL = "UPDATE `prefix_user_preference` 
					SET `uid` = '".$uid."', `price` = '".$userInfo->price."', `mileage` = '".$userInfo->mileage."', 
					`brand` = '".$userInfo->brand."', `age` = '".$userInfo->age."', `car_type` = '".$userInfo->car_type."', 
					`speed_box` = '".$userInfo->speed_box."' 
					WHERE `uid` = '".$uid."'";
			$this->db->query($SQL);
			$SQL = "UPDATE `prefix_user`
					SET `avatar_url` = '".$userInfo->avatar_url."', `user_age` = '".$userInfo->user_age."',
					`user_car` = '".$userInfo->user_car."', `location` = '".$userInfo->location."',
					`gender` = '".$userInfo->gender."'
					WHERE `uid` = '".$uid."'";
			$this->db->query($SQL);


		// }
		// else
		// // 	$this->db->insert($this->table,$data);
		// // $str = $this->db->last_query();


		// $SQL = "INSERT INTO `prefix_user_preference` 
		// 		(`uid`, `price`, `mileage`, `brand`, `age`, `car_type`, `speed_box`) 
		// 		VALUES ('".$uid."', '".$price."', '".$mileage."', '".$brand."', '".$age."', '".$car_type."', '".$speed_box."')";
		// $this->db->query($SQL);
		

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
