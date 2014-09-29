<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User_model extends CI_model
{

	function __construct()
	{
		parent::__construct();
		;$this->load->database();
		; $this->define()
		;
	
	}
	function test($data=0)
	{

	}

	function is_uid_exist($uid)
	{ 
		; $sql = "SELECT EXISTS (
							SELECT `uid` FROM `".$this->table."` 
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
					  ->get_where($this->table,
					  			  array('username'=> $userName));		
		$userInfo = $query->row_array();
		return $userInfo;

	}
	function is_phone_exist($phone)
	{ 
		; $sql = "SELECT EXISTS (
							SELECT `username` FROM `".$this->table."` 
							WHERE phone ='".$phone."'
							) 
						AS 	`is_phone_exist`";

		; $query =
			$this->db->query($sql);
		; $result = $query->row();
		;
		return $result->is_phone_exist;
	}
	function addUser($userName, $password, $phone,$code)
	{
		; $data 
			= array(
					 'username' 		=> $userName
					,'password'			=> $password
					,'phone'			=> $phone
					,'captcha'          => $code
					,'register_time'	=> date('Y-m-d H:i:s')

					)
		; $this->db->insert($this->table, $data)
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
		; $this->db->update($this->table, $data)
		;
	}

	function delete_user($uid)
	{
		;$this->db->delete($this->table, array('uid' => $uid)); 
		;
	}

	function get_all_user()
	{
		;$query = $this->db->get($this->table);
		;$user_list = $query->row_array();
		;return $user_list
		;
	}

	function update_password( $phone,$password)
	{
		; $data 
			= array(
					'password'			=> $password
					)
		;$this->db->where('phone',$phone)

		; $this->db->update($this->table, $data)
		;
	}
	
	function updateCaptcha($phone,$code)
	{
		; $data = array(
					 'phone'	        => $phone
					,'captcha'			=> $code
					)
		; $this->db->where('phone', $phone);
		; $this->db->update($this->table, $data)
		;
	}

	function addCaptcha($phone,$code)
	{
		; $data = array(
					 'phone'	        => $phone
					,'captcha'			=> $code
					)
		; $this->db->insert($this->table, $data)
		;
	}

	function get_captcha($phone)
	{
		;$query = $this->db->get_where($this->table,array("phone"=>$phone))
		;$captcha = $query->row()->{'captcha'};
		;return $captcha
		;
	}


	function add_user_tel($uid,$tel_dir,$do_type)
	{
		if ($do_type == "add")
		{
			foreach ($tel_dir as $tel) 
			{
				$data = array(
							  "uid"         => $uid,
							  "tel"         => $tel,
							  "update_time" => $this->input->sysTime
							  );
				$this->db->insert("prefix_user_tel", $data); 
			}	
		}
		else 
		{
			foreach ($tel_dir as $tel) 
			{
				$this->db->delete("prefix_user_tel", array('uid' => $uid,
													       "tel" => $tel)); 
			}			
		}	
	}


	function get_phone_dir($uid)
	{
		$this->db->select("phone_dir");
		$query = $this->db
					  ->get_where($this->table,
					  	          array('uid'=> $uid));
		$phone_dir = $query->row()->{'phone_dir'};
		// $phone_dir ='{"shang":"13705185091","f":"11"}';
		 var_dump($phone_dir);
		// // $phone_dir = array('shang' => '13705185091', 
		// // 					'f'=>'11');
		// // $phone_dir =json_encode($phone_dir);
		// // var_dump($phone_dir);
		$phone_dir =json_decode($phone_dir);

		var_dump($phone_dir);

	}

	function select_userinfo($get_uid)
	{
		$this->db->select('username,signature,avatar_url,login_state');
		$this->db->from($this->table);
		$this->db->join('prefix_user_state', 
						$this->table.'.uid = prefix_user_state.uid');
		$this->db->where("prefix_user_state.uid",$get_uid);
		$query = $this->db->get();
		$userInfo = $query->row();
		return $userInfo;
	}

	function get_userinfo($get_uid)
	{
		$query = $this->db->get_where($this->table,
									  array('uid' =>$get_uid));
		$userInfo = $query->row();

		return $userInfo;
	}

	private function define()
	{
		; $this->table = 'prefix_user';
		; $this->columns 
			= array( 'uid'
					,'username'
					,'password'
					,'register_time'
					,'register_email'
					,'login_time'
					,'login_coordinate'
					,'notice_praise_list'
					)
		; 
	}

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