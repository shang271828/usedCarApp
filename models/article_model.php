<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Article_model extends CI_model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->define();
	}

	function insert_article($author,														
							$title,
							$content	
					        )
	{
		$time = $this->input->sysTime;
		$SQL = "INSERT INTO `prefix_article` 
		       ( `author`, `title`, `content`,`image`, `time`, `counter_view`, `counter_follow`, `counter_praise`, `user_list_view`, `user_list_follow`, `user_list_praise`) 
		       VALUES ('".$author."','".$title."', '".$content."','http:\/\/xdream.co\/CI_API\/upload_dir\/ea5520048c4c1464cfb42ac0c9ec646c.png','".$time."' , '0', '0', '0', '[]', '[]', '[]')";

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

	
	function get_article_list($pageNumber,$numberPerPage,$pageType = "tips" )
	{
		$this->articleNumber  = ($pageNumber-1)*$numberPerPage;
		$this->numberPerPage = $numberPerPage;
		

				$SQL = "SELECT `aid`, `title`, `author`, `summary`,`content`,`image`, `time`, `counter_view`, `counter_follow`, `counter_praise`";
				$SQL .=" FROM (`prefix_article`)";

		$LIMIT = "ORDER BY `time` desc
		 		LIMIT ".$this->articleNumber.",".$this->numberPerPage;

		switch ($pageType) 
		{
			case 'all':
				$SQL .= $LIMIT;	
				$query = $this->db->query($SQL);
				$this->articleList = $query->result_array();
				break;

			case 'trends':
				$SQL .= " WHERE article_type = 'trends'";
				$SQL .= $LIMIT;	
				$query = $this->db->query($SQL);
				$this->articleList = $query->result_array();
				break;

			case 'new_cars':
				$SQL .= "WHERE article_type = 'new_cars'";	
				$SQL .= $LIMIT;
				$query = $this->db->query($SQL);
				$this->articleList = $query->result_array();
				break;

			case 'guide':
				$SQL .= "WHERE article_type = 'guide'";
				$SQL .= $LIMIT;
				$query = $this->db->query($SQL);
				$this->articleList = $query->result_array();
				break;
			
			case 'car_tips':
				$SQL .= "WHERE article_type = 'car_tips'";	
				$SQL .= $LIMIT;
				$query = $this->db->query($SQL);
				$this->articleList = $query->result_array();
				break;

			case 'dailys':
				$SQL .= "WHERE article_type = 'dailys'";	
				$SQL .= $LIMIT;
				$query = $this->db->query($SQL);
				$this->articleList = $query->result_array();
				break;

			case 'car_life':
				$SQL .= "WHERE article_type = 'car_life'";	
				$SQL .= $LIMIT;
				$query = $this->db->query($SQL);
				$this->articleList = $query->result_array();
				break;

			default:
				echo "error";
				break;
		}

		
		//$this->articleList = $this->img_decode($this->articleList);
		return $this->articleList;
	}

	function get_article_detail($aid)
	{
		//$SQL = "SELECT `aid`, `title`, `author, `content`, `time`, `counter_view`, `counter_follow`, `counter_praise`, `username`, `signature`, `avatar_url`";
		$SQL = "SELECT `aid`, `title`, `author`, `summary`,`content`,`image`, `time`, `counter_view`, `counter_follow`, `counter_praise`";
		$SQL .=" FROM (`prefix_article`)";
		//$SQL .=	"JOIN `prefix_user` ON `uid` = `a_uid`",
		$SQL .=	"WHERE `aid` =  '".$aid."'"; 
		$query = $this->db->query($SQL);
		$this->articleDetail = $query->row_array();

		return $this->articleDetail;
	}

	private function get_tips_list()
	{
		// $SQL ="SELECT `aid`, `title`, `author`, `time`, `counter_view`, `counter_follow`, `counter_praise`, `username`, `signature`, `avatar_url`
		// 		FROM (`prefix_article`)
		// 		JOIN `prefix_user` ON `uid` = `a_uid`
		// 		ORDER BY `time` desc
		// 		LIMIT ".$this->articleNumber.",".$this->numberPerPage;
		$SQL ="SELECT `aid`, `title`, `author`, `content`,`image`, `time`, `counter_view`, `counter_follow`, `counter_praise`
		 		FROM (`prefix_article`)
		 		ORDER BY `time` desc
		 		LIMIT ".$this->articleNumber.",".$this->numberPerPage;
		$query = $this->db->query($SQL);
		$this->articleList = $query->result_array();
			
	}

	 function update_article($aid, $article)
    {
        $this->db->where('aid', $aid);
        $result = $this->db->update($this->table, $article);

        if ($result)
        {
            return $aid;
        }
        else
        {
            return $result;
        }
    }

	function delete_article($aid)
	{
		$this->db->where('aid', $aid);
        $result = $this->db->delete($this->table);

        return $result;
	}

	function get_total_row($where = null,  $like = null)
    {
        if (isset($where))
        {
            $this->db->where($where); 
        }
        if (isset($like))
        {
            $this->db->or_like($like); 
        }
        ; $this->db->select('count(aid) as total_row')
        // ; $this->db->where($where)
        ; $query 
            = $this->db->get($this->table)
        ; return $query->row()->total_row
        ;
    }

     function img_decode($array)
	{
		foreach ($array as &$value) 
    	{
    		
    		if ($value["image"] == '[""]')
    		{
    			$img_list = array("http://xdream.co/CI_API/upload_dir/225e97394f00e2bf3c42f34e665553c3.jpg");
    			$value["image"] = $img_list;
    		}
    		else
    		{
   
    			$img_list = json_decode($value["image"]);
    	
    			$value["image"] = $img_list;
    		}
    	}
    	return $array;
	}

	function define()
	{
		; $this->table = 'prefix_article'
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