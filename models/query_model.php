
<?php
/*
input:    id
output:   row
*/
class query_model extends CI_model{

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function query($tableName,$keyName,$value)
	{
		$sql = "SELECT * FROM $tableName WHERE $keyName = '$value' "; 
		$query=$this->db->query($sql);
		$result=$query->row();
		return  $result;//$this->data[$value];
	}
}
?>
