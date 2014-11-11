<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User_model extends MY_Model
{

	function __construct()
	{
		;parent::__construct()
		;$this->load->database()
		;$this->table_name = 'prefix_user'
    	;$this->primary_key = 'uid'
		;
	
	}

	function is_uid_exist($uid)
	{ 
		; $sql = "SELECT EXISTS (
							SELECT `uid` FROM `".$this->table_name."` 
							WHERE uid ='".$uid."'
							) 
						AS 	`is_username_exist`";

		; $query =
			$this->db->query($sql);
		; $result = $query->row();
		;
		return $result->is_username_exist;
	}

	function is_username_exist($userName)
	{ 
		$this->db->select('username');
		$query = $this->db
					  ->get_where($this->table_name,
					  			  array('username'=> $userName));		
		$userInfo = $query->row_array();
		return $userInfo;
	}
	function is_phone_exist($phone)
	{ 
		; $sql = "SELECT `username`,`phone` 
				  FROM `".$this->table_name."` 
				  WHERE phone ='".$phone."'";

		; $query =
			$this->db->query($sql);
		; 
		; $result = $query->row_array();
		; 
		// 电话号码匹配且username不为空时返回true，
		// 否则返回false
		if($result)
			$bool = !(!$result['username']);
		else
			$bool = false;
		return $bool;
	}

	function get_uid($username)
	{
		$this->db->select('uid');
		$this->db->where('username',$username);
		$query = $this->db->get($this->table_name);
		$result = $query->row_array();
		if($result)
			return $result['uid'];
		else
			return 'error';
	}

	function addUser($userName, $password, $phone,$code ,$level = 3)
	{
		$time = $this->input->sysTime;
		// ; $data 
		// 	= array(
		// 			 'username' 		=> $userName
		// 			,'password'			=> $password
		// 			,'phone'			=> $phone
		// 			,'captcha'          => $code
		// 			,'register_time'	=> date('Y-m-d H:i:s')

		// 			)
		// ; $this->db->insert($this->table_name, $data)
		// ; $str = $this->db->last_query()


		; $SQL = "INSERT INTO `prefix_user` (`username`, `password`, `phone`, `captcha`, `register_time`,`level`) 
				  VALUES ('".$userName."', '".$password."', '".$phone."', '".$code."', '".$time."', '".$level."')";
		;$this->db->query($SQL);
		;$nid = $this->db->query("SELECT LAST_INSERT_ID()")->row_array()
		;return $nid["LAST_INSERT_ID()"]
		;

	}
	function updateUser($userName, $password, $phone)
	{
		; $data 
			= array(
					 'username' 		=> $userName
					,'password'			=> $password
					,'register_time'	=> date('Y-m-d H:i:s')
					)
		;$this->db->where('phone',$phone)
		; $this->db->update($this->table_name, $data)
		;
	}

	function delete_user($uid)
	{
		;$this->db->delete($this->table_name, array('uid' => $uid)); 
		;
	}

	function get_all_user()
	{
		;$query = $this->db->get($this->table_name);
		;$user_list = $query->result_array();
		;return $user_list
		;
	}
	//重设密码API调用此函数
	function update_password($phone,$password)
	{
		// ; $data 
		// 	= array(
		// 			'password'			=> $password
		// 			)
		// ;$this->db->where('phone',$phone)

		// ; $this->db->update($this->table_name, $data)
		// ; $str = $this->db->last_query();

		; $SQL = "UPDATE `prefix_user` 
				  SET `password` = '".$password."' 
				  WHERE `phone` =  '".$phone."'"
		;$this->db->query($SQL)
		;
	}

	function update_password_uid($uid,$password)
	{
		// ; $data 
		// 	= array(
		// 			'password'			=> $password
		// 			)
		// ;$this->db->where('phone',$phone)

		// ; $this->db->update($this->table_name, $data)
		// ; $str = $this->db->last_query();

		; $SQL = "UPDATE `prefix_user` 
				  SET `password` = '".$password."' 
				  WHERE `uid` =  '".$uid."'"
		;$this->db->query($SQL)
		;
	}
	
	function updateCaptcha($phone,$code)
	{
		; $data = array(
					 'phone'	        => $phone
					,'captcha'			=> $code
					)
		; $this->db->where('phone', $phone);
		; $this->db->update($this->table_name, $data)
		;
		; $str = $this->db->last_query()

		;
	}

	function addCaptcha($phone,$code)
	{
		; $data = array(
					 'phone'	        => $phone
					,'captcha'			=> $code
					)
		; $this->db->insert($this->table_name, $data)
		;
	}

	function get_captcha($phone)
	{
		$query = $this->db->get_where($this->table_name,array("phone"=>$phone));
		$result = $query->row();
		
		if($result)
			$captcha = $result->{'captcha'};
		else
			$captcha = '1234';
		return $captcha;
		
	}

	//通讯录api调用
	//$tel_dir为json数组
	function add_user_tel($uid,$tel_dir,$action)
	{
		$this->table_name  = "prefix_user_tel";
		$this->primary_key = "uid";
		$this->db->select("tel");
		$query = $this->db->get("prefix_user_tel");
		$old_tel_dir = $query->row_array();
		$old_tel_dir = $old_tel_dir['tel'];

		if (!$old_tel_dir )
		{
			$old_tel_dir = json_decode($old_tel_dir,TRUE);

			if ($action == "add")
			{
				$tel_dir = array_merge($tel_dir,$old_tel_dir); 
				$tel_dir = array_unique($tel_dir);										 
			}
			elseif($action == "delete") 
			{				
				$tel_dir = array_diff($old_tel_dir,$tel_dir);			
			}
		}
			$tel_dir = json_encode($tel_dir);
		
		$data = array(
					    "uid" => $uid,
					    "tel" => $tel_dir
					  );	
		$this->add($data,"prefix_user_tel","uid",$uid); 
	}


	// function get_phone_dir($uid)
	// {
	// 	$this->db->select("phone_dir");
	// 	$query = $this->db
	// 				  ->get_where($this->table_name,
	// 				  	          array('uid'=> $uid));
	// 	$phone_dir = $query->row()->{'phone_dir'};
	// 	// $phone_dir ='{"shang":"13705185091","f":"11"}';

	// 	// // $phone_dir = array('shang' => '13705185091', 
	// 	// // 					'f'=>'11');
	// 	// // $phone_dir =json_encode($phone_dir);

	// 	$phone_dir =json_decode($phone_dir);



	// }

	function select_userinfo($get_uid)
	{

		$this->db->select('prefix_user.uid,username,gender,signature,user_location,user_age,
						user_car,avatar_url,notice_list_following,user_list_following,user_list_follower');
		$this->db->from($this->table_name);

		$this->db->join('prefix_user_relation', 
						$this->table_name.'.uid = prefix_user_relation.uid');

		$this->db->where($this->table_name.".uid",$get_uid);
		$query = $this->db->get();
		$userInfo = $query->row_array();
	
		$notice_list_following = json_decode($userInfo['notice_list_following'],true);
		$user_list_following = json_decode($userInfo['user_list_following'],true);
		$user_list_follower = json_decode($userInfo['user_list_follower'],true);
		$userInfo['follow_notice_num'] = count($notice_list_following);
		$userInfo['user_follow_num']   = count($user_list_following);
		$userInfo['follower_num']      = count($user_list_following);
		unset($userInfo['notice_list_following']);
		unset($userInfo['user_list_following']);
		unset($userInfo['user_list_follower']);

		return $userInfo;
	}
	function all_userinfo($get_uid)
	{
		$this->db->select('username,signature,user_location,user_age,
							user_car,avatar_url,
							friend_list_initial,friend_list_secondary,
						notice_list_following,user_list_following,
						notice_list_interested');
		$this->db->from($this->table_name);
		// $this->db->join('prefix_user_state', 
		// 				$this->table_name.'.uid = prefix_user_state.uid');
		$this->db->join('prefix_user_relation', 
						$this->table_name.'.uid = prefix_user_relation.uid');

		$this->db->where($this->table_name.".uid",$get_uid);
		$query = $this->db->get();
		$userInfo = $query->row_array();
		$userInfo['notice_list_following'] = json_decode($userInfo['notice_list_following'],true);
		$userInfo['user_list_following'] = json_decode($userInfo['user_list_following'],true);
		$userInfo['friend_list_initial'] = json_decode($userInfo['friend_list_initial'],true);
		$userInfo['friend_list_secondary'] = json_decode($userInfo['friend_list_secondary'],true);
		$userInfo['notice_list_interested'] = json_decode($userInfo['notice_list_interested'],true);
		return $userInfo;
	}

	function compare_user($time,$password)
	{
		;$uid =$this->input->uid
		; $this->db->select('password')
		; $query = $this->db->get_where('prefix_user',array('uid'=>$uid))
		; $res = $query->row_array()	
		; $controlToken 
			= md5($this->input->uid
				.$time
				.$res['password'])
	    ; $bool = ($password == $controlToken)
		; return $bool
		;

	}

	function get_userinfo($get_uid)
	{
		$query = $this->db->get_where($this->table_name,
									  array('uid' =>$get_uid));
		$userInfo = $query->row();

		return $userInfo;
	}

	// private function define()
	// {
	// 	; $this->table_name = 'prefix_user';
	// 	; $this->columns 
	// 		= array( 'uid'
	// 				,'username'
	// 				,'password'
	// 				,'register_time'
	// 				,'register_email'
	// 				,'login_time'
	// 				,'login_coordinate'
	// 				,'notice_praise_list'
	// 				)
	// 	; 
	// }

}

/****
database table : user
"CREATE TABLE IF NOT EXISTS `prefix_user` (
  `uid` int(30) NOT NULL COMMENT '主键',
  `username` varchar(32) DEFAULT NULL,
  `password` varchar(256) DEFAULT NULL,
  `phone`	varchar(16) DEFAULT NULL,
  `register_time` datetime DEFAULT NULL,
  `register_email` varchar(128) DEFAULT NULL,
  `login_time` datetime DEFAULT NULL,
  `login_coordinate` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;"




*/