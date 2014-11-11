<?php
class Invitation_code_model extends CI_Model
{
	function __construct()
	{
		parent ::__construct();
		$this->load->database();
		$this->define();
	}

	function addCode($uid,$code_type = 0)
	{
		// $this->db->insert($this->table,$data);
		$code = $this->generate_password();
		$is_code_exist = $this->is_code_exist($code);

		if(!$is_code_exist)
		{
			$SQL = "INSERT INTO `prefix_invitation_code` 
			(`host_uid`, `send_uid`, `invitation_code`, `is_used`,`code_type`) 
					VALUES (".$uid.", '',  '".$code."','0','".$code_type."')";
			$this->db->query($SQL);
		}
		return $code;
	}

	function judge_code_type($invitation_code)
	{
		$SQL = "SELECT `code_type`
				FROM `prefix_invitation_code`
				WHERE `invitation_code` = '".$invitation_code."'";
		$query  = $this->db->query($SQL);
		$result = $query->row_array();
	

		return $result;

	}

	function updateCode($uid,$code)
	{
		$data = array(
			   'host_uid'   => $uid,
               'send_uid'   => $this->input->uid,
               'is_used'    => 1,
               'code_layer' => 1
            );
		$this->db->where('invitation_code',$code);
		$this->db->update('prefix_invitation_code', $data); 
	}

	function getCode($host_uid)
	{
		$SQL = "SELECT  `invitation_code`				
				FROM `prefix_invitation_code`
				WHERE `is_used` = 0
				AND `code_type` = 1
				ORDER BY RAND()
				LIMIT 3";				
		$query = $this->db->query($SQL);
		$result = $query->result_array();
	
		$code_str = '(';
		foreach ($result as  $key=>$value) 
		{
			$code_array[] = $value['invitation_code'];
			$code_str    .= '"'.$value['invitation_code'].'"';
			if($key != 2)
				$code_str .= ',';
		}
		$code_str .=  ')';

		$SQL = "UPDATE `prefix_invitation_code` 
				SET host_uid = ".$host_uid."
				,is_used  = 1
				WHERE `invitation_code` IN ".$code_str;
		$this->db->query($SQL);
		return $code_array;

		// $SQL = "SELECT `invitation_code`,`host_uid` ,`is_used`
		// 		FROM `prefix_invitation_code`
		// 		WHERE `invitation_code` IN ".$code_str;
		// $query = $this->db->query($SQL);
		// var_dump($query->result_array());
		
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

		$result = $query->row_array();
		if($result)
			$bool = !$result['host_uid'];
		else
			$bool = true;
		return $bool;
	}


	//管理员函数
	function set_value()
	{
		
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
