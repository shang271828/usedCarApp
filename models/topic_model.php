<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Topic_model extends CI_model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->define();
	}

	function get_topic_list($pageNumber,$numberPerPage)
	{
		$topicNumber = ($pageNumber-1)*$numberPerPage;
		$SQL = "SELECT `tid`, `topic_title`, `topic_description`, 
				`image`, `avatar_url`, `notice_list`
				FROM (`prefix_topic`)";
		$SQL   .= " ORDER BY `time` desc ";
		$query  = $this->db->query($SQL);
		$tmp 	= $query->result_array();	
		$this->total_row = count($tmp);	
		$SQL .= " LIMIT ".$topicNumber.",".$numberPerPage;
		$query = $this->db->query($SQL);

		$topic_list = $query->result_array();
		$topic_list = $this->str_decode($topic_list,'notice_list');
		//$topic_list = json_encode($topic_list);
		
		return $topic_list;
	}

	function get_total_row($pageNumber,$numberPerPage)
	{
		$this->get_topic_list($pageNumber,$numberPerPage);
		return $this->total_row;
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

	function define()
	{
		; $this->table = 'prefix_topic'
		; $this->colomn 
			= array(
					 'tid'
					,'topic_title'
					,'topic_description'
					,'image'
					,'avatar_url'
					,'notice_list'
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