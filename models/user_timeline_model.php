<?php
class User_timeline_model extends CI_Model
{
	function __construct()
	{
		parent ::__construct();
		$this->load->database();
		$this->define();
	}

	function insert($nid)
	{
		$data = array
		(
			"uid"       => $this->input->head->uid,
       		"nid"       => $nid,
       		"is_sticky" => "0",
       		"time"		=> $this->input->sysTime
		);
		$this->db->insert($this->table,$data);
	}

	function define()
	{
		$this->table  = "prefix_user_timeline";
		$this->column = array
		(
			"uid",
			"nid",
			"is_sticky",
			"time"	 
        );
	}


}
