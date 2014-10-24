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

	function insert_system_message($destination_list,
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
					'uid'		        =>'1',
					'time'				=>$this->input->sysTime,
					'coordinate'		=>$this->input->coordinate					
				);
		 $this->db->insert($this->table, $data);
		$nid = $this->db->query("SELECT LAST_INSERT_ID()")->row_array();
		return $nid["LAST_INSERT_ID()"];

	}

	function get_message_list($pageNumber,$numberPerPage,$pair)
	{
		$messageNumber = ($pageNumber-1)*$numberPerPage;

		
		$this->db->select("mid,			              
		               	  destination_list,
					      content	,	
		 			      img_list	,			 				   		
		 			      ".$this->table.".uid	,
		 			      username,
		 			      signature,
   					      avatar_url,	      
		 			      time	,		
					      coordinate "
					  );	
		$this->db->from($this->table); 	
		$this->db->join('prefix_user',"prefix_user.uid=".$this->table.".uid");

		switch ($pair) 
		{
			case 'all':
				$this->db->order_by("time", "desc"); 
				$this->db->where('destination_list','["'.$this->input->uid.'"]');
				break;

			case 'sys':
				$this->db->order_by("time", "desc"); 
				$this->db->where('destination_list','["02"]');
				break;

			case 'register':
				$this->db->order_by("time", "desc"); 
				$this->db->where('destination_list','["01"]');
				break;

			
			default:
				$this->db->order_by("time", "asc"); 
				$pair_bracket = array('["'.$pair[0].'"]','["'.$pair[1].'"]');
				$array_f = array($this->table.'.uid'=>$pair[0],'destination_list'=>$pair_bracket[1]);
				$array_s = array($this->table.'.uid'=>$pair[1],'destination_list'=>$pair_bracket[0]);
	
				$this->db->where($array_f);
				$this->db->or_where($array_s); 
				break;
		}
		
    	
		$this->db->limit($numberPerPage,$messageNumber);
		$query = $this->db->get();
	
		$messageList = $query->result_array();
		
		$messageList = $this->str_decode($messageList,'img_list');
		$messageList = $this->str_decode($messageList,'destination_list');
		$this->read_message($messageList);
		return $messageList;
	}


	function get_message_detail($mid)
	{
		//$SQL = "SELECT `mid`, `title`, `destination_list, `content`, `time`, `counter_view`, `counter_follow`, `counter_praise`, `username`, `signature`, `avatar_url`";
		$SQL = "SELECT `mid`, `title`, `destination_list`, `content`, `time`";
		$SQL .=" FROM (`prefix_message`)";
		//$SQL .=	"JOIN `prefix_user` ON `uid` = `a_uid`",
		$SQL .=	"WHERE `mid` =  '".$mid."'"; 
		$query = $this->db->query($SQL);
		$this->messageDetail = $query->row_array();

		return $this->messageDetail;
	}
	// function update_img_list($messageList)
	// {
	// 	$i = 0;
	// 	foreach ($messageList as $value) 
	// 	{
	// 		$img_array   = explode(",", $value["img_list"]);
	// 		$messageList[$i]["img_list"] = $img_array;
	// 		$i = $i + 1;
	// 	}
	// 	return $messageList;
	// }
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
		$this->db->where('is_fetched','0');

		$query = $this->db->get($this->table);		
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