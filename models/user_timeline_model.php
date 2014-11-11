<?php
class User_timeline_model extends CI_Model
{
	function __construct()
	{
		parent ::__construct();
		$this->load->database();
		$this->define();
	}

	function insert($uid,$nid,$timeline_type)
	{
		$time = $this->input->sysTime;

		$SQL ="INSERT INTO `prefix_user_timeline` 
				(`uid`, `nid`, `timeline_type`, `is_sticky`, `time`) 
			   VALUES ('".$uid."', '".$nid."', '".$timeline_type."', '0', '".$time."')";
		$this->db->query($SQL);
	}

	function set_sticky($nid)
	{
		$this->db->select('is_sticky');
		$this->db->where('nid',$nid);
		$query = $this->db->get($this->table);
		$array = $query->row_array();
		$is_sticky = $array['is_sticky'];
		
		if($is_sticky==0)
		{
			$is_sticky = 1;
		}
		else
		 	$is_sticky = 0;
		
		$this->db->where('nid',$nid);
		$this->db->set('is_sticky', $is_sticky); 
		$this->db->update($this->table);

		return $is_sticky;
	}

	function define()
	{
		$this->table  = "prefix_user_timeline";
		$this->column = array
		(
			"uid",
			"nid",
			"timeline_type",
			"is_sticky",
			"time"	 
        );
	}


}
