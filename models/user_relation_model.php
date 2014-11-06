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

		; $SQL = "INSERT INTO `prefix_user_relation` (`uid`, 
			`friend_list_initial`, `friend_list_secondary`, `notice_list_following`,`user_list_following`, `disgust_list`) 
				  VALUES (".$uid.", '[]', '[]', '[]', '[]','[]')";
		; $this->db->query($SQL);
		;
		
	}

	function insert_friend($phone_list)
	{
		$friend_list_initial =  array();

		foreach ($phone_list as $value) 
		{
			$uid = $this->is_phone_exist($value);

			if ($uid && !is_numeric(array_search($uid, $friend_list_initial)))
				$friend_list_initial[] = $uid;
		}
		//如果一度人脉存在，则根据一度人脉判断相应的二度人脉
		if($friend_list_initial)
		{
			$friend_list_secondary = $this->friend_list_secondary($friend_list_initial); //创建二度人脉

			$friend_list_initial   = json_encode($friend_list_initial);
			$friend_list_secondary = json_encode($friend_list_secondary);		
		}

		$data = array("friend_list_initial"  =>$friend_list_initial,
					  "friend_list_secondary"=>$friend_list_secondary);

		$this->db->where('uid', $this->input->uid);
		$this->db->update($this->table,$data);
	}

	private function friend_list_secondary($uid_list)
	{

		$uid_str = "('".implode("','", $uid_list)."')";
		$SQL = "SELECT `friend_list_initial` FROM `prefix_user_relation`
				where `uid` in ".$uid_str;
		$query = $this->db->query($SQL);
		$friend_list = $query->result_array();
		$friend_list_secondary  = array();
		foreach ($friend_list as  $value) 
		{
			$friend_list_initial = json_decode($value["friend_list_initial"]);

			foreach ( $friend_list_initial as  $value) 
			{

				if (!is_numeric(array_search($value, $friend_list_secondary))
					&& !is_numeric(array_search($value,$uid_list)))
					$friend_list_secondary[] = $value;
			}
			 
		}

		return $friend_list_secondary;

	}

	private function is_phone_exist($phone)
	{ 
		; $sql = "SELECT `uid` FROM `prefix_user` 
					WHERE phone ='".$phone."'";

		; $query =
			$this->db->query($sql);
		; $result = $query->row();

		; 
		if($result)
			$uid = $result->uid;
		else
			$uid = FALSE;
		return $uid;
	}

	function get_friend_initial_info($get_uid)
	{
		$friend_list_initial = $this->get_friend_list_initial($get_uid);
		$this->db->select('uid,username,avatar_url');
		if($friend_list_initial)
		{
			$this->db->where_in('uid',$friend_list_initial);
			$query = $this->db->get('prefix_user');
	
			$friendInfo = $query->result_array();
		}
		else
			$friendInfo = '[]';
		
		return $friendInfo;
	}

	function get_friend_secondary_info($get_uid)
	{
		$friend_list_secondary = $this->get_friend_list_secondary($get_uid);
		$this->db->select('uid,username,avatar_url');
		if($friend_list_secondary)
		{
			$this->db->where_in('uid',$friend_list_secondary);
	
			$query = $this->db->get('prefix_user');
	
			$friendInfo = $query->result_array();
		}
		else
			$friendInfo = '[]';
		return $friendInfo;
	}

	private function get_friend_list_initial($get_uid)
	{
		$this->db->select("friend_list_initial");
		$query = $this->db->get_where($this->table,array("uid"=>$get_uid));
		
		$friend_list = $query->row_array();

		$friend_list_initial = json_decode($friend_list['friend_list_initial']);
	
		return $friend_list_initial;
	}

	private function get_friend_list_secondary($get_uid)
	{
		$this->db->select("friend_list_secondary");
		$query = $this->db->get_where($this->table,array("uid"=>$get_uid));
		
		$friend_list = $query->row_array();
	
		$friend_list_secondary = json_decode($friend_list['friend_list_secondary']);
		return $friend_list_secondary;
	}

	//fuid: follow_uid

	function update_user_list_following($uid,$fuid)
	{
		$SQL = "SELECT `user_list_following`
				FROM (`prefix_user_relation`)
				WHERE `uid` =  ".$uid;
		$query = $this->db->query($SQL);
		$noticeInfo = $query->row_array();
		$user_list_following = $noticeInfo['user_list_following'];

		if($user_list_following == NULL || $user_list_following == 'null' )
			$user_list_following = '[]';
		$user_list_following = json_decode($user_list_following,true);

		$key = array_search($fuid, $user_list_following);
		if($key === FALSE)
		{
			$user_list_following[] = $fuid;
			$is_followed = 1;
		}
		else
		{
			array_splice($user_list_following,$key,1);
			$is_followed = 0;
		}
		$user_list_following = json_encode($user_list_following);

		$data = array(
			"user_list_following" => $user_list_following
			);
		$this->db->update($this->table,$data,array('uid' =>$uid));
		return $is_followed;
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
	
	function judge_user_follow($get_uid)
	{
		$this->db->select('user_list_following');
		$this->db->where('uid',$this->input->uid);
		$query = $this->db->get($this->table);
		$user_list_following = $query->row_array();
		$user_list_following = $user_list_following['user_list_following'];
		if($user_list_following == '[]')
		{
			$is_followed = 0;
		
		}
		else
		{
			$user_array = json_decode($user_list_following,true);
			
			$bool = array_search($get_uid, $user_array);
			
			if($bool == false)
				$is_followed = 0;
			else
			$is_followed = 1;
		}
		return $is_followed;
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
// update notice_list_following
	function update_notice_list_following($uid,$nid)
	{
		$SQL = "SELECT `notice_list_following`
				FROM (`prefix_user_relation`)
				WHERE `uid` =  ".$uid;
		$query = $this->db->query($SQL);
		$noticeInfo = $query->row_array();
		$notice_list_following = $noticeInfo['notice_list_following'];

		if($notice_list_following == NULL || $notice_list_following == 'null' )
			$notice_list_following = '[]';
		$notice_list_following = json_decode($notice_list_following,true);

		$key = array_search($nid, $notice_list_following);

		if($key === FALSE || $key === false)
		{
			$notice_list_following[] = $nid;
		}
		else
		{
			array_splice($notice_list_following,$key,1);
		}
		$notice_list_following = json_encode($notice_list_following);

		$data = array(
			"notice_list_following" => $notice_list_following
			);
		$this->db->update($this->table,$data,array('uid' =>$uid));
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