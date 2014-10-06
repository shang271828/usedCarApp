<?php
class Image_model extends CI_Model
{
	function __construct()
	{
		parent ::__construct();
		$this->load->database();
		$this->define();
	}

	function insert_picture($content,$pid,$parent_type)
	{
		$data = array
		(            
			"nid"     => $content,     
			"pic_url" => $parent_type,      
			
			"uid"         => $this->input->uid,
			"time"        => $this->input->sysTime,
			"coordinate"  => $this->input->coordinate
		);

		$this->db->insert($this->table,$data);
	}

	function define()
	{
		$this->table  = "prefix_picture";
		$this->column = array
		(
			"pid",
			"nid",
			"pic_url",
			"uid" ,
			"time",
			"coordinate"
        );
	}
}

/*
CREATE TABLE IF NOT EXISTS `prefix_picture` (
  `pid` int(30) NOT NULL AUTO_INCREMENT,
  `nid` int(11)DEFAULT NULL,
  `pic_url` varchar(512) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  `coordinate` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
*/