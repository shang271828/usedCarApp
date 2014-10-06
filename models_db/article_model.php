<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Article_model extends CI_model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->define();
	}

	function insert_article($uid,														
							$title,
							$content	
					        )
	{
		$this->colomn['uid']            = $uid;
		$this->colomn['title']          = $title;
		$this->colomn['content']        = $content;
		$this->colomn['time']           = $this->input->sysTime;

		$this->db->insert($this->table, $this->colomn);
		
		$this->db->last_query();
		$str = $this->db->last_query();
		var_dump($str);

	}

	
	function get_article_list($pageNumber,$numberPerPage,$pageType)
	{
		$this->articleNumber  = ($pageNumber-1)*$numberPerPage;
		$this->numberPerPage = $numberPerPage;
		$uid = $this->input->head->uid;

		switch ($pageType) 
		{
			case "tips":
				$this->get_tips_list();
				break;
			
			default:
				echo "error";
				break;
		}
		return $this->articleList;
	}

	function get_article_detail($aid)
	{

		$this->db->select( "aid,
						   title,"
		 				   .$this->table.".uid,	
		 				   content,
		 				   time,	 				
		 				   counter_view,
		 				   counter_follow,
		 				   counter_praise,
		 				   username,
      					   signature,
      					   avatar_url,
						" );
		$this->db->from($this->table);
		$this->db->where("aid",$aid);
		$this->db->join("prefix_user", $this->table.'.uid = prefix_user.uid');
		$query = $this->db->get(); 

		$this->articleDetail = $query->result_array();	

		return $this->articleDetail;
	}

	private function get_tips_list()
	{
		$this->db->select( "aid,
						   title,"
		 				   .$this->table.".uid,	
		 				   time,	 				
		 				   counter_view,
		 				   counter_follow,
		 				   counter_praise,
		 				   username,
      					   signature,
      					   avatar_url,
							" );
		$this->db->from($this->table);
		$this->db->order_by("time", "desc"); 
		$this->db->join("prefix_user", $this->table.'.uid = prefix_user.uid');
		$this->db->limit($this->numberPerPage,$this->articleNumber);
		$query = $this->db->get(); 

		$this->articleList = $query->result_array();				
	}

	function define()
	{
		; $this->table = 'prefix_article'
		; $this->colomn 
			= array(
					'aid'              => "",            
					'uid'              => "",                     
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