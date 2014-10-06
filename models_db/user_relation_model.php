<?php
class User_relation_model extends CI_Model
{
	var $notice_list_following ;
	function __construct()
	{
		parent ::__construct();
		$this->load->database();
		$this->define();
	}

	function addUser($uid)
	{
		; $data 
			= array(
					'uid'  => $uid
					,'friend_list_initial'    => "[]"
					,'friend_list_secondary'  => "[]"
					,'notice_list_following'  => "[]"									
					,'user_list_following'	  => "[]"		
					,'disgust_list'			  => "[]"
					)
		; $this->db->insert($this->table, $data)
		;
		
	}

	function get_friend_list($get_uid)
	{
		$this->db->select("friend_list_initial,friend_list_secondary");
		$query = $this->db->get_where($this->table,array("uid"=>$get_uid));
		$friend_list = $query->row();
		return $friend_list;
	}

	//fuid: follow_uid
	function update_user_list_following($uid,$fuid)
	{
		$is_fuid_exist = $this->is_fuid_exist($uid,$fuid);

		if($is_fuid_exist == 0)
		{
			$this->add_user_list_following($uid,$fuid);
			$is_shield =1;
		}
		else
		{
			$this->reduce_user_list_following($uid,$fuid,$is_fuid_exist);
			$is_shield =0;
		}
		return $is_shield;
	}

	function add_user_list_following($uid,$fuid)
	{

		$this->user_list_following = substr_replace($this->user_list_following,
		 								   	 $this->str_fuid,
		 								     -1,0);

		$data = array("user_list_following" => $this->user_list_following,
					  "uid" =>$uid);

        $this->db->where("uid", $uid);
		$this->db->update($this->table, $data); 
	}		

	function reduce_user_list_following($uid,$fuid,$is_fuid_exist)
	{
 		$this->user_list_following = substr_replace($this->user_list_following,
 											 "",
 											 $is_fuid_exist,
 											 strlen($this->str_fuid));

 		$data = array("user_list_following" => $this->user_list_following,
 			          		   "uid" => $uid);

        $this->db->where("uid", $uid);
		$this->db->update($this->table, $data); 
	}
	
				

	function is_fuid_exist($uid,$fuid)
	{
		$fuid_num = $this->fuid_num($uid,$fuid);

		switch ($fuid_num) {
			case '0':
				$is_fuid_exist = FALSE;
				break;
			case '1':
				$is_fuid_exist = strpos( $this->user_list_following ,
								         $this->str_fuid );
				if (! $is_fuid_exist)
					$this->str_fuid = ",".$this->str_fuid;
				break;				
			default:
				$is_fuid_exist = strpos( $this->user_list_following,
								         $this->str_fuid );
				break;
		}
	
		return 	$is_fuid_exist;
	}	

	function fuid_num($uid,$fuid)
	{
		$this->db->select("user_list_following");
		$query = $this->db->get_where($this->table,
			                          array('uid' => $uid));
		$userInfo = $query->row_array();
		$this->user_list_following = $userInfo["user_list_following"];

		$this->str_fuid = "'".$fuid."'";

		if(!$this->user_list_following 
		  ||$this->user_list_following == "[]")
		{
			$this->user_list_following = "[]";
			$user_list_following = 0; 	  
		}

		else if(!strrchr(",",$this->user_list_following))
		{
			$user_list_following = 1; 
		}
		else
		{
			$this->str_fuid = ",".$this->str_fuid;
			$user_list_following  = 2;
		}

		return $user_list_following;
	}
	
	//suid: shield_uid
	function update_disgust_list($uid,$suid)
	{
		$is_suid_exist = $this->is_suid_exist($uid,$suid);

		if($is_suid_exist == 0)
		{
			$this->add_disgust_list($uid,$suid);
			$is_shield =1;
		}
		else
		{
			$this->reduce_disgust_list($uid,$suid,$is_suid_exist);
			$is_shield =0;
		}
		return $is_shield;
	}

	function add_disgust_list($uid,$suid)
	{

		$this->disgust_list = substr_replace($this->disgust_list,
		 								   	 $this->str_suid,
		 								     -1,0);

		$data = array("disgust_list" => $this->disgust_list,
					  "uid" =>$uid);

        $this->db->where("uid", $uid);
		$this->db->update($this->table, $data); 
	}		

	function reduce_disgust_list($uid,$suid,$is_suid_exist)
	{
 		$this->disgust_list = substr_replace($this->disgust_list,
 											 "",
 											 $is_suid_exist,
 											 strlen($this->str_suid));

 		$data = array("disgust_list" => $this->disgust_list,
 			          		   "uid" => $uid);

        $this->db->where("uid", $uid);
		$this->db->update($this->table, $data); 
	}
	
				

	function is_suid_exist($uid,$suid)
	{
		$suid_num = $this->suid_num($uid,$suid);

		switch ($suid_num) {
			case '0':
				$is_suid_exist = FALSE;
				break;
			case '1':
				$is_suid_exist = strpos( $this->disgust_list ,
								         $this->str_suid );
				if (! $is_suid_exist)
					$this->str_suid = ",".$this->str_suid;
				break;				
			default:
				$is_suid_exist = strpos( $this->disgust_list,
								         $this->str_suid );
				break;
		}
	
		return 	$is_suid_exist;
	}	

	function suid_num($uid,$suid)
	{
		$this->db->select("disgust_list");
		$query = $this->db->get_where($this->table,
			                          array('uid' => $uid));
		$userInfo = $query->row_array();
		$this->disgust_list = $userInfo["disgust_list"];

		$this->str_suid = "'".$suid."'";

		if(!$this->disgust_list 
		  ||$this->disgust_list == "[]")
		{
			$this->disgust_list = "[]";
			$disgust_list = 0; 	  
		}

		else if(!strrchr(",",$this->disgust_list))
		{
			$disgust_list = 1; 
		}
		else
		{
			$this->str_suid = ",".$this->str_suid;
			$disgust_list  = 2;
		}

		return $disgust_list;
	}

	function update_notice_list($uid,$nid,$is_followed)
	{
		$is_nid_exist = $this->is_notice_exist($uid,$nid);

		if($is_followed == 0)
			$this->add_notice_list_following($uid,$nid);
		else
			$this->reduce_notice_list_following($uid,$nid,$is_nid_exist);
	}
	/*更新notice_list_following
	* 向notice_list_following中添加nid，
	*
	*/
	function add_notice_list_following($uid,$nid)
	{

		$this->notice_list_following = substr_replace($this->notice_list_following,
		 								   			 $this->str_nid,
		 										     -1,0);

		$data = array("notice_list_following" => $this->notice_list_following,
					  "uid" =>$uid);

        $this->db->where("uid", $uid);
		$this->db->update($this->table, $data); 
	}		
	/*更新notice_list_following
	* 从notice_list_following中移除nid，
	*
	*/
	function reduce_notice_list_following($uid,$nid,$is_nid_exist)
	{
 		$this->notice_list_following = substr_replace($this->notice_list_following,
 													  "",
 													  $is_nid_exist,
 													  strlen($this->str_nid));

 		$data = array("notice_list_following" => $this->notice_list_following,
 			          					"uid" => $uid);

        $this->db->where("uid", $uid);
		$this->db->update($this->table, $data); 
	}
	
				

	function is_notice_exist($uid,$nid)
	{
		$notice_following_num = $this->notice_following_num($uid,$nid);

		switch ($notice_following_num) {
			case '0':
				$is_nid_exist = FALSE;
				break;
			case '1':
				$is_nid_exist = strpos( $this->notice_list_following ,
								        $this->str_nid );
				if (! $is_nid_exist)
					$this->str_nid = ",".$this->str_nid;
				break;				
			default:
				$is_nid_exist = strpos( $this->notice_list_following ,
								        $this->str_nid );
				break;
		}
	
		return 	$is_nid_exist;
	}	

	function notice_following_num($uid,$nid)
	{
		$this->db->select("notice_list_following");
		$query = $this->db->get_where($this->table,
			                          array('uid' => $uid));
		$userInfo = $query->row_array();

		$this->notice_list_following = $userInfo["notice_list_following"];

		$this->str_nid = "'".$nid."'";

		if(!$this->notice_list_following 
		  ||$this->notice_list_following == "[]")
		{
			$this->notice_list_following = "[]";
			$notice_list_following = 0; 	//0个关注者  
		}

		else if(!strrchr(",",$this->notice_list_following))
		{
			$notice_list_following = 1; //1个关注者 
		}
		else
		{
			$this->str_nid = ",".$this->str_nid;
			$notice_list_following = 2;//2个及2个以上关注者 
		}

		return $notice_list_following;
	}


	function define()
	{
		$this->table  = "prefix_user_relation";
		$this->column = array
		(
			"uid",
			"friend_list_initial",
			"friend_list_secondary",
			"notice_list_following" ,
			"user_list_following" ,
			"summoning_list",
			"disgust_list"
        );
	}
}

/****
CREATE TABLE IF NOT EXISTS `prefix_user_relation` (
  `uid` int(30) NOT NULL,
  `friend_list_initial` varchar(512) DEFAULT NULL,
  `friend_list_secondary` varchar(512) DEFAULT NULL,
  `notice_list_following` varchar(512) DEFAULT NULL,
  `user_list_following` varchar(512) DEFAULT NULL,
  `summoning_list` varchar(512) DEFAULT NULL,
  `disgust_list` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


*/