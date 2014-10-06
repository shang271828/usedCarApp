<?php
class User_alert_model extends CI_Model
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
			"uid"                    => $uid,
       		"user_list_following"    => "",
       		"summoning_list"         =>	"",
       		"msg_list_unread"		 =>	""
			);
		$this->db->insert($this->table,$data);
	}

	function update($msg_list_unread)
	{
		$data = array
		(
       		"user_list_following" => "",
       		"summoning_list"	  => "",	
       		"msg_list_unread"     => $msg_list_unread,
       		"update_time"	      => ""      		
		);
		$this->db->where('uid',$this->input->uid);

		$this->db->update($this->table,$data); 
	}

	function define()
	{
		$this->table  = "prefix_user_alert";
		$this->column = array
		(
			"uid",
			"user_list_following",
			"summoning_list",
			"msg_list_unread" ,
			"update_time" 
        );
	}
}

/*
	CREATE TABLE IF NOT EXISTS `prefix_user_alert` (
  `uid` int(11) NOT NULL,
  `user_list_following` text CHARACTER SET utf8 NOT NULL,
  `summoning_list` text CHARACTER SET utf8 NOT NULL,
  `msg_list_unread` text CHARACTER SET utf8 NOT NULL,
  `update_time` datetime NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
*/