<?php
class Notice_model extends CI_Model
{
	function __construct()
	{
		parent ::__construct();
		$this->load->database();
		$this->define();
	}

	function insert($title,	$content, $img_list)
	{
		$data = array
		(
       		"title"          => $title,
       		"content"        =>	$content,
       		"img_list"		 =>	$img_list,	

       		"uid"            => $this->input->uid,
       		"time"			 =>	$this->input->sysTime,
       		"coordinate"	 => $this->input->coordinate,

       		"counter_view"   =>	0,
       		"counter_follow" => 0,
       		"counter_praise" => 0
		);
		$this->db->insert($this->table,$data);
	}


	function get_notice($pageNumber,$numberPerPage,$sortStr )
	{
		$noticeNumber = ($pageNumber-1)*$numberPerPage;
		var_dump($noticeNumber);
		$sql  = "SELECT nid,
						title,
						content,
						img_list,
						coordinate,
						counter_view,
						counter_follow,
						counter_praise
				FROM ".$this->table." ORDER BY ".$sortStr." DESC
		        limit  $noticeNumber,$numberPerPage";
		$query = $this->db->query($sql);
		
		$noticeInfo = $query->result_array();
		return $noticeInfo;
	}

	function define()
	{
		$this->table  = "prefix_notice";
		$this->column = array
		(
			"nid",
			"title",
			"content",
			"img_list",
			"uid",
			"time",
			"coordinate",
			"counter_view",
			"counter_follow",
			"counter_praise"
        );
	}
}

/****
database table : prefix_notice

CREATE TABLE IF NOT EXISTS `prefix_notice` (
  `nid` int(30) NOT NULL AUTO_INCREMENT,
  `title` tinytext,
  `content` text,
  `img_list` varchar(512) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  `coordinate` varchar(64) DEFAULT NULL,
  `counter_view` int(11) DEFAULT NULL,
  `counter_follow` int(11) DEFAULT NULL,
  `counter_praise` int(11) DEFAULT NULL,
  PRIMARY KEY (`nid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;



*/