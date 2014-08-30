<?php
class create_table_model extends CI_model
{
	function __constract()
	{
		$this->load->database();
	}
	function create_table($data)
	{
		$this->dbforge->create_table('table_name', TRUE);
	}

}