<?php
class Invitation_code_model extends CI_Model
{
	function __construct()
	{
		parent ::__construct();
		$this->load->database();
		$this->define();
	}

	function addCode($uid)
	{
		// $this->db->insert($this->table,$data);
		$code = $this->generate_password();
		$SQL = "INSERT INTO `prefix_invitation_code` 
		(`host_uid`, `send_uid`, `invitation_code`, `is_used`,`code_type`) 
				VALUES (".$uid.", '',  '".$code."','0','1')";
		$this->db->query($SQL);
		return $code;
	}

	function updateCode($code)
	{
		$data = array(
               'send_uid' => $this->input->uid,
               'is_used' => 1,
               'code_layer' => 1
            );
		$this->db->where('invitation_code',$code);
		$this->db->update('prefix_invitation_code', $data); 
	}

	function generate_password( $length = 6 ) 
	{  
		// 密码字符集，可任意添加你需要的字符  
		$chars  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		//$chars .='abcdefghijklmnopqrstuvwxyz';
		//$chars .='0123456789';
		//$chars .='!@#$%^&*()-_ []{}<>~`+=,.;:/?|'; 
		$password = '';  
		for ( $i = 0; $i < $length; $i++ )  
		{  
		// 这里提供两种字符获取方式  
		// 第一种是使用 substr 截取$chars中的任意一位字符；  
		// 第二种是取字符数组 $chars 的任意元素  
		 // $password .= substr($chars, mt_rand(0, strlen($chars) – 1), 1);  
		 $password .= $chars[ mt_rand(0, strlen($chars) - 1) ];  
		 }  
		 return $password;  
	 } 


	function is_code_wrong($code)
	{
		 $SQL = "SELECT `host_uid` 
		 		 FROM  `prefix_invitation_code` 
				 WHERE `invitation_code` ='".$code."'";
		$query = $this->db->query($SQL);

		$result = $query->row();

		$bool = $result->host_uid;
		return !$bool;
	}

	function define()
	{
		$this->table_name  = "prefix_invitation_code";
		$this->column = array
		(
			"host_uid",
			"send_uid" ,
			"invitation_code",
			"is_used"
        );
	}


}
