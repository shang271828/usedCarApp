<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Message_model extends CI_model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->define();
	}

	function insert_message($content, 
							$img_list, 
							$destination_list
					        )
	{

		; $data 
			= array(
					 'content'			=>$content
					,'img_list'			=>$img_list
					,'destination_list'	=>$destination_list
					,'type'				=>'permanent'		
								
					,'uid'				=>$this->input->uid
					,'time'				=>$this->input->sysTime
					,'coordinate'		=>$this->input->coordinate
					,'is_fetched'       =>'0'
					,
				)
		; $this->db->insert($this->table, $data);
	}

	function get_message($pageNumber,$numberPerPage)
	{
		$messageNumber = ($pageNumber-2)*$numberPerPage;
		$this->db->order_by("is_fetched", "asc"); 
		$this->db->select("mid,
						   content,
		 				   img_list,
		 				   destination_list,
		 				   uid,
		 				   time,
		 				   coordinate,
						   is_fetched" 
						  );
		$query = $this->db->get($this->table, $numberPerPage, $messageNumber);		
		$messageList = $query->result_array();
		return $messageList;
	}

	function get_unread_message()
	{
		$this->db->select("mid,
						   content,
		 				   img_list,
		 				   destination_list,
		 				   uid,
		 				   time,
		 				   coordinate,
						   is_fetched" );
		$query = $this->db->get($this->table);		
		$messageList = $query->result_array();
		return $messageList;
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