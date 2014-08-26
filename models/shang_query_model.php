<?php
class shang_query_model extends CI_model{
	var $data;

	function __construct()
	{
		$this->load->database();
	}
	public function shang_query($uid)
	{
		$query=$this->db->query('select * from `userinfot` where `user_id`='.$uid);
		$result=$query->row();
		$this->data['shang'] = $result;

		return  $this->data['shang'];

	}

	//public function get_shang()
	//{

	//}
}
?>
