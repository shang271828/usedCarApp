<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Test_model extends CI_model
{
	function __construct()
	{
		parent::__construct();
	}
	function test($data=0)
	{
		$query = $this->db->query('select (1+1) as a');
		$result = $query->result();
	}

}