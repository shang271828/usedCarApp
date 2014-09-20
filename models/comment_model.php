<?php
class Comment_model extends CI_Model
{
	function __construct()
	{
		parent ::__construct();
		$this->load->database();
		$this->define();
	}

	function insert_comment($content,$pid,$parent_type)
	{
		$data = array
		(            
			"content"     => $content,     
			"parent_type" => $parent_type,      
			"pid"         => $pid,
			
			"uid"         => $this->input->uid,
			"time"        => $this->input->sysTime,
			"coordinate"  => $this->input->coordinate
		);

		$this->db->insert($this->table,$data);
	}

	function get_comment($pageNumber,$numberPerPage,$sortStr)
	{
		$noticeNumber = ($pageNumber-1)*$numberPerPage;
		$this->db->order_by($sortStr, "desc"); 
		$this->db->select("title,
		 				   content,
		 				   img_list ,
		 				   coordinate"
						  );
		$query = $this->db->get($this->table, $numberPerPage, $noticeNumber);		
		$noticeList = $query->result_array();
		return $commentList;
	}

	function define()
	{
		$this->table  = "prefix_comment";
		$this->column = array
		(
			"cid",
			"title",
			"content",
			"img_list" ,
			"parent_type",
			"pid",
			"uid" ,
			"time",
			"coordinate"
        );
	}
}

/*
CREATE TABLE IF NOT EXISTS `prefix_comment` (
  `cid` int(30) NOT NULL AUTO_INCREMENT,
  `title` tinytext,
  `content` text,
  `img_list` varchar(512) DEFAULT NULL,
  `parent_type` varchar(512) DEFAULT NULL,
  `pid` int(30) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  `coordinate` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
*/