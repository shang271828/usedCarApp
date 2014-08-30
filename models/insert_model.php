<?php
/*
input:    id
output:   row
*/
class insert_model extends CI_model{

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
	public function insert($table_name,$data)
	{
		$this->db->insert($table_name, $data);	
		$str = $this->db->last_query();			
		return  $str;
	}

}
/*end*/