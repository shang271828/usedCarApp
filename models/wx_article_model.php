<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Wx_article_model extends CI_model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->define();
	}

	function insert($data)
	{
		$time = $this->input->sysTime;
		$SQL = "INSERT INTO `wx_article` 
		       ( `title`, `name`,`phone`, `region`, `price`, `description`,`speedbox`,`brand`,`version`, `run_distance`,`register_date`,`valid_date`,`images`) 
		       VALUES ('".$data['title']."','".$data['name']."','".$data['phone']."', '".$data['region']."','".$data['price']."',
		       		  '".$data['description']."','".$data['speedbox']."','".$data['brand']."','".$data['version']."',
		       		  '".$data['run_distance']."','".$data['register_date']."','".$data['valid_date']."','".$data['images']."')";

		$result = $this->db->query($SQL);


		if ($result)
        {
           	$nid = $this->db->query("SELECT LAST_INSERT_ID()")->row_array();
			return $nid["LAST_INSERT_ID()"];			
        }
        else
        {
            return $result;
        }

	}


	function define()
	{
		; $this->table = 'wx_article'
		; $this->colomn 
			= array(
					'aid'              => "",            
					'author'              => "",                     
					'title'            => "",                 
					'content'          => "",                  
					'time'             => "",        
					"counter_view"     => "0",     
					"counter_follow"   => "0",    
					"counter_praise"   => "0",      
					"user_list_view"   => "[]",       
					"user_list_follow" => "[]",      
					"user_list_praise" => "[]"     
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