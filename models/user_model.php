<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User_model extends CI_Model
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
		; $sql = "SELECT EXISTS (
							SELECT `username` FROM `".$this->table_name."` 
							WHERE phone ='".$phone."'
							) 
						AS 	`is_phone_exist`";

		; $query =
			$this->db->query($sql);
		; $result = $query->row();
		;
		return $result->is_phone_exist;
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
		// ; var_dump($str);

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
		// ; var_dump($str);
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
		// ; var_dump($str);
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
		; var_dump($str)
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
		;$query = $this->db->get_where($this->table_name,array("phone"=>$phone))
		;$captcha = $query->row()->{'captcha'};
		;return $captcha
		;
	}

	//通讯录api调用
	function add_user_tel($uid,$tel_dir,$action)
	{
		$this->table_name  = "prefix_user_tel";
		$this->primary_key = "uid";
		$this->db->select("tel");
		$query = $this->db->get("prefix_user_tel");
		$old_tel_dir = $query->row_array();
		$old_tel_dir = $old_tel_dir['tel'];
		
		$old_tel_dir = unserialize($old_tel_dir);

		if ($action == "add")
		{
			$tel_dir = array_merge($tel_dir,$old_tel_dir); 
			$tel_dir = array_unique($tel_dir);
			var_dump($tel_dir);
						 
		}
		elseif($action == "delete") 
		{				
			$tel_dir = array_diff($old_tel_dir,$tel_dir);			
		}
		$tel_dir = serialize($tel_dir);
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
	// 	 var_dump($phone_dir);
	// 	// // $phone_dir = array('shang' => '13705185091', 
	// 	// // 					'f'=>'11');
	// 	// // $phone_dir =json_encode($phone_dir);
	// 	// // var_dump($phone_dir);
	// 	$phone_dir =json_decode($phone_dir);

	// 	var_dump($phone_dir);

	// }

	function select_userinfo($get_uid)
	{
		$this->db->select('username,signature,avatar_url,login_state,notice_list_following,user_list_following');
		$this->db->from($this->table_name);
		$this->db->join('prefix_user_state', 
						$this->table_name.'.uid = prefix_user_state.uid');
		$this->db->join('prefix_user_relation', 
						$this->table_name.'.uid = prefix_user_relation.uid');
		$this->db->where("prefix_user_state.uid",$get_uid);
		$query = $this->db->get();
		$userInfo = $query->row_array();
		$notice_list_following = json_decode($userInfo['notice_list_following'],true);
		$user_list_following = json_decode($userInfo['user_list_following'],true);
		$userInfo['follow_notice_num'] = count($notice_list_following);
		$userInfo['user_follow_num'] = count($user_list_following);
		unset($userInfo['notice_list_following']);
		unset($userInfo['user_list_following']);

		return $userInfo;
	}

	function compare_user($username,$password)
	{
		; $query = 
			$this->db->get_where($this->table_name,
								array('username'=> $username
									 ,'password'=>$password)
								)
		; $res = $query->row()
		;

		if($res) 
		{	
			return $res;
		}
		else
		{
			return false;
		}
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