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

		// $this->db->insert($this->table,$data);
		$SQL = "INSERT INTO `prefix_user_preference` (`uid`, `login_state`, `show_state`, `latest_coordinate`,`login_record`, `update_time`) 
				  VALUES (".$uid.", '在线', '', '', '','','')";
		$this->db->query($SQL);
	}




	function define()
	{
		$this->table  = "prefix_user_state";
		$this->column = array
		(
			"uid",
			"login_state" ,
			"show_state",
			"latest_coordinate",
			"login_record",
			"update_time",

        );
	}


}
