<?php
class Notice_model extends CI_Model
{
	var $user_list_follow ;
	var $str_uid;

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

	function get_notice($pageNumber,$numberPerPage,$pageType)
	{
		$this->noticeNumber  = ($pageNumber-1)*$numberPerPage;
		$this->numberPerPage = $numberPerPage;

		$coordinate = '["120","32"]';
		switch ($pageType) 
		{
			case "mainpage":
				$noticeList = $this->get_city_label($coordinate);
				break;
			case "discovery":
				$noticeList = $this->get_discovery($noticeNumber,
												   $numberPerPage);
				break;
			case "timeline":
				$noticeList = $this->get_timeline($noticeNumber,
												  $numberPerPage);
				break;
			default:
				echo "error";
				break;
		}
		return $noticeList;
	}

	function get_mainpage_list()
	{
		$mainpage_list = array("city"       =>"同城",
							   "friend"     =>"朋友圈",
							   "hot_degree" =>"人气");
		return $mainpage_list;
	}

	function get_city_label($coordinate)
	{
		//if (103<longitude<123 && 22<latitude<34 )
			//$zone = "south";
		//else if (103<longitude<135 && 34<latitude<53)
		//    $zone = "nouth";
		//elseif (73<longitude<123 && 37<latitude<50) 
		//	$zone = "nouthwest";
		//else(73<longitude<104 && 24<latitude<40)
		// $zone = "Tibet";
		$this->db->select("nid,
						   title,
		 				   content,
		 				   img_list,
		 				   uid,
		 				   coordinate,
		 				   counter_view,
		 				   counter_follow,
		 				   counter_praise" );
		$this->db->where("coordinate",$coordinate);
		$query = $this->db->get($this->table, 
								$this->noticeNumber, 
								$this->numberPerPage);
		$noticeList = $query->result_array();
		$noticeList = $this->update_notice_list($noticeList);
		var_dump($noticeList);
		return $noticeList;

	}
	// function get_notice($pageNumber,$numberPerPage,$sortStr)
	// {
	// 	$noticeNumber = ($pageNumber-1)*$numberPerPage;
	// 	$this->db->order_by($sortStr, "desc"); 
	// 	$this->db->select("nid,
	// 					   title,
	// 	 				   content,
	// 	 				   img_list,
	// 	 				   uid,
	// 	 				   coordinate,
	// 	 				   counter_view,
	// 	 				   counter_follow,
	// 	 				   counter_praise" );
	// 	$query = $this->db->get($this->table, $numberPerPage, $noticeNumber);		
	// 	$noticeList = $query->result_array();
	// 	$i = 0;
	// 	foreach ($noticeList as $value) 
	// 	{
	// 		$img_info  = $this->get_img_info($value["nid"]);
	// 		$user_info = $this->get_user_info($value["uid"]);
	// 		$noticeList[$i]["img_list"]  = $img_info;
	// 		$noticeList[$i]["user_info"] = $user_info;
	// 		$i = $i + 1;
	// 	}
	// 	return $noticeList;
	// }
	private function update_notice_list($noticeList)
	{
		$i = 0;

		foreach ($noticeList as $value) 
		{
			$img_info  = $this->get_img_info($value["nid"]);
			$user_info = $this->get_user_info($value["uid"]);
			$noticeList[$i]["img_list"]  = $img_info;
			$noticeList[$i]["user_info"] = $user_info;
			$i = $i + 1;
		}
		return $noticeList;
	}

	private function get_img_info($nid)
	{
		$query = $this->db->get_where("prefix_picture",
									  array('nid' => $nid));
		$img_info = $query->result_array();
		$img_list = $img_info;

	return $img_list;
	}

	private function get_user_info($uid)
	{
		$this->db->select("uid,username,signature,avatar_url");
		$query = $this->db->get_where("prefix_user",
									  array('uid' => $uid));
		$user_info = $query->result_array();
	return $user_info;
	}


	// function get_img_info($img_list)
	// {
	// 	$img_array = json_decode($img_list,TRUE);
	// 	foreach ($img_array as $pid) 
	// 	{
	// 		$query = $this->db->get_where("prefix_picture",
	// 									  array('pid' => $pid));
	// 		$img_info = $query->result_array();
	// 		$img_list = $img_info[0];
	// 	}
	// 	return $img_list;
	// }

	//更新user_list_praise和counter_praise
	function update_praise_list($uid,$nid)
	{
		$is_uid_exist = $this->is_user_praise_exist($uid,$nid);

		if ($is_uid_exist)
		{
			$this->reduce_user_praise_list($uid,$nid,$is_uid_exist);
			$is_praised = 0;
		}
		else
		{
			$this->add_user_praise_list($uid,$nid);
			$is_praised = 1;
		}
		return $is_praised;
	}

	/*
	* 向user_list_praise中添加uid，
	* counter_praise +1
	*/
	private function add_user_praise_list($uid,$nid)
	{
		$this->user_list_praise = substr_replace($this->user_list_praise,
		 								   		 $this->str_uid,
		 										-1,0);
		$this->counter_praise   = $this->counter_praise + 1;
		$data = array(
               "counter_praise"   => $this->counter_praise,
               "user_list_praise" => $this->user_list_praise
            );

        $this->db->where("nid", $nid);
		$this->db->update($this->table, $data); 
	}		
	/*
	* 从user_list_praise中移除uid，
	* counter_praise -1
	*/
	private function reduce_user_praise_list($uid,$nid,$is_uid_exist)
	{

 		$this->user_list_praise = substr_replace($this->user_list_praise,
 												"",
 												$is_uid_exist,
 												strlen($this->str_uid));
 		$this->counter_praise   = $this->counter_praise-1;
 		$data = array(
             "counter_praise"   => $this->counter_praise,
             "user_list_praise" => $this->user_list_praise
          	);

        $this->db->where("nid", $nid);
		$this->db->update($this->table, $data); 
	}
	
	//判断点赞者是否已经点过赞
	private function is_user_praise_exist($uid,$nid)
	{
		$user_praise_num = $this->user_praise_num($uid,$nid);

		switch ($user_praise_num) {
			case '0':
				$is_uid_exist = FALSE;
				break;
			case '1':
				$is_uid_exist = strpos( $this->user_list_praise ,
								        $this->str_uid );
				if (! $is_uid_exist)
					$this->str_uid = ",".$this->str_uid;
				break;				
			default:
				$is_uid_exist = strpos( $this->user_list_praise ,
								        $this->str_uid );
				break;
		}
		return 	$is_uid_exist;
	}	

	/*判断现有点赞者数目，分以下几种情况：
	* 0个点赞者  
	* 1个点赞者
	*2个及2个以上点赞者
	*/
	private function user_praise_num($uid,$nid)
	{
		$this->db->select("user_list_praise,counter_praise");
		$query = $this->db->get_where($this->table,
			                          array('nid' => $nid));
		$noticeInfo = $query->row_array();
		$this->user_list_praise = $noticeInfo["user_list_praise"];       	
		$this->counter_praise   = $noticeInfo["counter_praise"];
		
		$this->str_uid    = "'".$uid."'";
		if(!$this->user_list_praise 
		  ||$this->user_list_praise == "[]")
		{
			$this->user_list_praise = "[]";
			$user_praise_num = 0; 	//0个关注者  
		}

		else if(!strrchr($this->user_list_praise,","))
		{
			$user_praise_num = 1; //1个关注者 

		}
		else
		{
			$this->str_uid   = ",".$this->str_uid;
			$user_praise_num = 2;//2个及2个以上关注者 			
		}
		return $user_praise_num;
	}	


    //更新user_list_follow和counter_follow
	function update_follow_list($uid,$nid)
	{
		$is_uid_exist = $this->is_user_follow_exist($uid,$nid);

		if ($is_uid_exist)
		{
			$this->reduce_user_list($uid,$nid,$is_uid_exist);
			$is_followed = 1;
		}
		else
		{
			$this->add_user_list($uid,$nid);
			$is_followed = 0;
		}
		return $is_followed;
	}

	/*
	* 向user_list_follow中添加uid，
	* counter_follow +1
	* 返回follow_state = TRUE
	*
	*/
	private function add_user_list($uid,$nid)
	{
		$this->user_list_follow = substr_replace($this->user_list_follow,
		 								   		 $this->str_uid,
		 										-1,0);
		$this->counter_follow   = $this->counter_follow + 1;
		$data = array(
               "counter_follow"   => $this->counter_follow,
               "user_list_follow" => $this->user_list_follow
            );

        $this->db->where("nid", $nid);
		$this->db->update($this->table, $data); 
	}		
	/*
	* 从user_list_follow中移除uid，
	* counter_follow -1
	* 返回follow_state = FALSE
	*
	*/
	private function reduce_user_list($uid,$nid,$is_uid_exist)
	{

 		$this->user_list_follow = substr_replace($this->user_list_follow,
 												"",
 												$is_uid_exist,
 												strlen($this->str_uid));
 		$this->counter_follow   = $this->counter_follow-1;
 		$data = array(
             "counter_follow"   => $this->counter_follow,
             "user_list_follow" => $this->user_list_follow
          	);

        $this->db->where("nid", $nid);
		$this->db->update($this->table, $data); 
	}
	

	//判断关注者是否已经关注过
	private function is_user_follow_exist($uid,$nid)
	{
		$user_follow_num = $this->user_follow_num($uid,$nid);

		switch ($user_follow_num) {
			case '0':
				$is_uid_exist = FALSE;
				break;
			case '1':
				$is_uid_exist = strpos( $this->user_list_follow ,
								        $this->str_uid );
				if (! $is_uid_exist)
					$this->str_uid = ",".$this->str_uid;
				break;				
			default:
				$is_uid_exist = strpos( $this->user_list_follow ,
								        $this->str_uid );
				break;
		}
	
		return 	$is_uid_exist;
	}	

	/*判断现有关注者数目，分以下几种情况：
	* 0个关注者  
	* 1个关注者
	*2个及2个以上关注者
	*/
	private function user_follow_num($uid,$nid)
	{
		$this->db->select("user_list_follow,counter_follow");
		$query = $this->db->get_where($this->table,
			                          array('nid' => $nid));
		$noticeInfo = $query->row_array();
		$this->user_list_follow = $noticeInfo["user_list_follow"];       	
		$this->counter_follow   = $noticeInfo["counter_follow"];
		
		$this->str_uid    = "'".$uid."'";

		if(!$this->user_list_follow 
		  ||$this->user_list_follow == "[]")
		{
			$this->user_list_follow = "[]";
			$user_follow_num = 0; 	//0个关注者  
		}

		else if(!strrchr($this->user_list_follow,","))
		{
			$user_follow_num = 1; //1个关注者 

		}
		else
		{
			$this->str_uid   = ",".$this->str_uid;
			$user_follow_num = 2;//2个及2个以上关注者 			
		}
		return $user_follow_num;
	}	

	function define()
	{
		$this->table  = "prefix_notice";
		$this->column = array
		(
			"nid",
			"title",
			"content",
			"img_list" ,
			"uid" ,
			"time",
			"coordinate",
			"counter_view",
			"counter_follow",
			"counter_praise",
			"user_list_view",
			"user_list_follow",
			"user_list_praise"
        );
	}
}

/****
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
  `user_list_follow` text NOT NULL,
  `user_list_praise` text NOT NULL,
  `user_list_view` text NOT NULL,
  PRIMARY KEY (`nid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


*/