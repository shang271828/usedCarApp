<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Message_model extends CI_model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->define();
	}

	function insert_message($destination_list,
							$content    ,
							$img_list = '[]'  								
					        )
	{
		$img_str = json_encode($img_list);
		$destination_str = json_encode($destination_list);
		$data = array(
					'destination_list'  =>$destination_str,
					'content'			=>$content,
					'img_list'			=>$img_str,
					'is_fetched'        =>'0',	
					'uid'		        =>$this->input->uid,
					'time'				=>$this->input->sysTime,
					'coordinate'		=>$this->input->coordinate					
				);
		 $this->db->insert($this->table, $data);
		$nid = $this->db->query("SELECT LAST_INSERT_ID()")->row_array();
		return $nid["LAST_INSERT_ID()"];

	}

	function insert_system_message(
									$content    ,
									$img_list = '[]'  								
					        		)
	{
		$img_str = json_encode($img_list);

		$data = array(
					'destination_list'  =>'["sys"]',
					'content'			=>$content,
					'img_list'			=>$img_str,
					'is_fetched'        =>'0',		
					'uid'		        =>'7',
					'time'				=>$this->input->sysTime,
					'coordinate'		=>$this->input->coordinate					
				);
		$this->db->insert($this->table, $data);
		$nid = $this->db->query("SELECT LAST_INSERT_ID()")->row_array();
		return $nid["LAST_INSERT_ID()"];
	}

	function get_message($pageNumber,$numberPerPage,$pair)
	{
		$this->messageNumber = ($pageNumber-1)*$numberPerPage;
		$this->numberPerPage = $numberPerPage;
		if(is_array($pair))
		{

			$this->get_message_detail($pair);
		}
		elseif($pair == 'sys')
		{
			$this->get_sys_message();
		}
		elseif ($pair == 'all') 
		{
			$this->get_message_list();
		}
		return $this->message;
	}

	function get_message_detail($pair)
	{		
		$uid = $this->input->uid;
		$match = '"'.$uid.'"';

		$SQL  = $this->select_sql;
		$SQL .=	"FROM (`prefix_message`)
				JOIN `prefix_user` ON `prefix_user`.`uid`=`prefix_message`.`uid`";
		
		$SQL .= "WHERE (`prefix_message`.`uid` =  ".$uid." 
						OR `destination_list`  LIKE '".'%'.$match.'%'."')";
		
		$pair_bracket = array('["'.$pair[0].'"]','["'.$pair[1].'"]');
		
		$SQL .= " AND ((`prefix_message`.`uid` = '".$pair[0]."' AND `destination_list` =  '".$pair_bracket[1]."')
				  OR (`prefix_message`.`uid`   = '".$pair[1]."'  AND `destination_list` =  '".$pair_bracket[0]."'))";
				
		$SQL .= " ORDER BY `time` desc ";

		$query  = $this->db->query($SQL);
		$tmp 	= $query->result_array();	
		$this->total_row = count($tmp);	
	
		$SQL .= " LIMIT ".$this->messageNumber.",".$this->numberPerPage;

		$query = $this->db->query($SQL);
		$messageList = $query->result_array();

		$messageList = $this->str_decode($messageList,'img_list');
		$messageList = $this->str_decode($messageList,'destination_list');

		$this->read_message($messageList);

		$this->message = $messageList;
	}

	function get_sys_message()
	{
		$SQL  = $this->select_sql;
		$SQL .=	"FROM (`prefix_message`)
				JOIN `prefix_user` ON `prefix_user`.`uid`=`prefix_message`.`uid`";
		
		$SQL .= "WHERE (`prefix_message`.`uid` = 7)";

		$query  = $this->db->query($SQL);
		$tmp 	= $query->result_array();	
		$this->total_row = count($tmp);	
	
		$SQL .= " LIMIT ".$this->messageNumber.",".$this->numberPerPage;
		$query = $this->db->query($SQL);
		$messageList = $query->result_array();
		$messageList = $this->str_decode($messageList,'img_list');		
		$messageList = $this->str_decode($messageList,'destination_list');

		$this->read_message($messageList);
		$this->message = $messageList;
	}

	function get_message_list()
	{		
		$uid = $this->input->uid;
		$match = '"'.$uid.'"';

		$SQL  = "SELECT `mid`, `destination_list`, `content`, `img_list`, 
								`prefix_message`.`uid`,`time`, `coordinate`";
		$SQL .=	"FROM (`prefix_message`)";
		
		$SQL .= "WHERE (`prefix_message`.`uid` =  ".$uid." 
						 OR `destination_list`  LIKE '".'%'.$match.'%'."')";

		$SQL .= "AND `prefix_message`.`uid` != 7";

		$SQL .= " ORDER BY `time` asc ";	
	
		$query = $this->db->query($SQL);
		$messageList = $query->result_array();
		if($messageList)
			$messageList = $this->get_list($messageList);
	
		$this->total_row = count($messageList);

		$messageList = $this->str_decode($messageList,'img_list');		
		$messageList = $this->str_decode($messageList,'destination_list');
		
		$this->message = $messageList;
	}
	private function add_userinfo($uid)
	{
		$SQL ="SELECT `username`,`signature`,`avatar_url`
				FROM prefix_user
				WHERE uid =".$uid;
		$query = $this->db->query($SQL);
		$tmp = $query->row_array();

		return $tmp;
	}
	//返回最新的一条私信

	private function get_list($messageList)
	{
		$uid = $this->input->head->uid;
		foreach ($messageList as $key=> $message) 
		{
			$host_uid = $message['uid'];
			if($message['destination_list'] != '["all"]')
			{
				
				$destination_list = json_decode($message['destination_list']);
				
				foreach ($destination_list as  $value) 
				{
					if ($value == $uid)
						unset($messageList[$key]);
					elseif($uid == $host_uid)
						$pair = $value;
					else
						$pair = $host_uid;
				}
				//添加id=$pair对应的用户信息
				$userinfo = $this->add_userinfo($pair);

				$message['username']   = $userinfo['username'];
				$message['signature']  = $userinfo['signature'];
				$message['avatar_url'] = $userinfo['avatar_url'];
				$tmp[$pair] = $message;
			}		
		}

		if($tmp)
			$tmp = $this->array_key_unique($tmp);
		else 
			$tmp = '';
		return $tmp;
			
	}

	//消除重复键值
	private function array_key_unique($array)
	{
		
		$tmp = array();
		foreach ($array as $key => $value) 
		{
			
			if(!in_array($key, $tmp))
			{
				$return_array[] = $value;
			}
			$tmp[] = $key;
		}
		
		return $return_array;
	}
	
	function get_total_row($pair)
	{
		$this->get_message(1,20,$pair);
		return $this->total_row;
	}


	function read_message($messageList)
	{
		$data = array('is_fetched'=>'1');
		foreach ($messageList as $key => $value) 
		{
			$this->db->update($this->table, $data, "mid = ".$value['mid']); 
		}
	}

	function get_unread_message_num($uid)
	{
		$this->db->select("is_fetched");
		$SQL = 'SELECT `is_fetched`
				FROM (prefix_message)
				WHERE `is_fetched`= 0
				AND(`destination_list`= \'["'.$uid.'"]\'
					OR `destination_list`= \'["sys"]\' )';

		$query = $this->db->query($SQL);		
		$messageList = $query->result_array();

		return count($messageList);
	}

	private function str_decode($array,$type)
	{
		foreach ($array as &$value) 
    	{
    		$img_list = json_decode($value[$type]);
    		$value[$type] = $img_list;
    	}
    	return $array;
	}


	function delete_message($mid)
	{
		$this->db->where('mid', $mid);
        $result = $this->db->delete($this->table);

        return $result;
	}

	function define()
	{
		; $this->table = 'prefix_message'
		; $this->select_sql = "SELECT `mid`, `destination_list`, `content`, `img_list`, 
								`prefix_message`.`uid`, `username`, `signature`, 
								`avatar_url`, `time`, `coordinate`"
		; $this->colomn 
			= array(
					 'mid'
					,'content'
					,'img_list'
					,'destination_list'
					,'uid'
					,'time'
					,'coordinate'
					,'type'
					,'is_fetched'
					)
		;
	}
}


/*****

CREATE TABLE IF NOT EXISTS `prefix_message` (
  `mid` int(30) NOT NULL AUTO_INCREMENT,
  `title` varchar(512),
  `content` text,
  `img_list` varchar(512) DEFAULT NULL,
  `destination_list` varchar(512) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  `coordinate` varchar(64) DEFAULT NULL,
  `type` varchar(32) DEFAULT NULL,
  `is_fetched` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`mid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
*/