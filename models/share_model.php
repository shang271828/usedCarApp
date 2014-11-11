<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Share_model extends CI_model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->define();
	}

	function insert_share($nid,
						  $share_type
						  )
	{
		
		$notice_info = $this->get_notice_info($nid);
		$data = array(
					'share_uid'  => $this->input->head->uid,
					'author_uid' => $notice_info['uid'],

					'nid'	     => $nid,
					'time'       => $this->input->sysTime,	
					'image'      => $notice_info['img_list'][0],
					'summary'  	 => $notice_info['title'],	
					'share_type' => $share_type							
				);
		 $this->db->insert($this->table, $data);

	}

	function get_notice_info($nid)
	{
		$SQL = "SELECT `img_list`,`title`,`content`,`uid`
				FROM (prefix_notice)
				WHERE `nid` = ".$nid;
		$query = $this->db->query($SQL);
		$notice_info = $query->row_array();
		return $notice_info;
	}


	function define()
	{
		; $this->table = 'prefix_share'
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
/*
CREATE TABLE IF NOT EXISTS `prefix_share` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `share_uid` int(11) DEFAULT NULL,
  `author_uid` int(11) DEFAULT NULL,
  `nid` int(11) DEFAULT NULL, 
  `time` datetime DEFAULT NULL,
  `image` varchar(512) DEFAULT NULL,
  `summary`  varchar(512) DEFAULT NULL,

  PRIMARY KEY (`sid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
*/
