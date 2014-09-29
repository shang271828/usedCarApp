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

	function insert_normal_notice($title,$content,$img_list,$notice_type)
	{
		$img_str = $this->array_to_str($img_list);
		$data = array
		(
       		"title"          => $title,
       		"content"        =>	$content,
       		"img_list"		 =>	$img_str,	
       		"notice_type"    => $notice_type,

       		"uid"            => $this->input->uid,
       		"time"			 =>	$this->input->sysTime,
       		"coordinate"	 => $this->input->coordinate,

       		"counter_view"   =>	0,
       		"counter_follow" => 0,
       		"counter_praise" => 0
		);
		$this->db->insert($this->table,$data);
		$nid = $this->db->query("SELECT LAST_INSERT_ID()")->row_array();
		return $nid["LAST_INSERT_ID()"];
	}

	function insert_car_notice( $nid,
								$price      ,
								$region_code,
								$brand      ,
								$recency	  ,
								$mileage 	)
	{
		$data = array
		(
			"nid"         => $nid,
			"price"       => $price      ,			
			"region_code" => $region_code,
			"brand"       => $brand      ,
			"recency"     => $recency	, 
			"mileage "    => $mileage 	
		);
		$this->db->insert("prefix_car_notice",$data);

	}

	function insert_comment_notice( $nid ,
				 					$p_nid,
									$commentType = "public"
				 					)
	{
		$layer =$this->count_layer($p_nid);
		$data = array
		(
			"nid"         => $nid        ,
			"p_nid"       => $p_nid      ,	
			"layer"	      => $layer,
			"commentType" => $commentType	
		);
		$this->db->insert("prefix_comment",$data);
	}

	function count_layer($p_nid)
	{
		if ($p_nid == 0)
			$layer = 1;
		else
		{
			$query = $this->db->get_where($this->table,array("nid"=>$p_nid));
			$notice_array = $query->row_array();
			$notice_type = $notice_array["notice_type"];
			if($notice_type == "comment_notice")
			{
				$query = $this->db->get_where("prefix_comment",array("nid"=>$p_nid));
				$comment_array = $query->row_array();
				$p_layer = $comment_array["layer"];
				$layer = $p_layer + 1;
			}
			else
				$layer = 1;
		}
		return $layer;
	}

	function array_to_str($img_list)
	{
		$i = 0;
		$img_str = $img_list[$i];
		foreach ($img_list as $value) 
		{
			if ($i > 0)				
				$img_str = $img_str.",".$img_list[$i];
			$i ++;
		}
		return $img_str;
	}
	//*******************************************************
	//API:getNoticeList & getNoticeDetail
	//****************************************************
	function get_notice_list($pageNumber,$numberPerPage,$pageType)
	{
		$this->noticeNumber  = ($pageNumber-1)*$numberPerPage;
		$this->numberPerPage = $numberPerPage;

		switch ($pageType) 
		{
			case "mainpage":
				$this->get_mainpage();
				break;
			case "discovery":
				$this->get_discovery();
				break;
			case "timeline":
				$this->get_timeline();
				break;
			default:
				echo "error";
				break;
		}

		return $this->noticeList;
	}

	function get_notice_detail($nid)
	{
		$this->db->select("nid,
						   title,
		 				   content,
		 				   uid,
		 				   coordinate,
		 				   counter_view,
		 				   counter_follow,
		 				   counter_praise,
		 				   notice_type" );
		$query = $this->db->get_where($this->table,	
								      array('nid'=> $nid));

		$noticeArray = $query->row_array();
		$type = $noticeArray["notice_type"];
		switch ($type) {
			case "normal_notice":
				
				break;
			case "car_notice":
				$noticeArray = $this->update_car_notice_detail($noticeArray);
				break;
			case "comment_notice":
				$noticeArray = $this->update_comment_notice_detail($noticeArray);
				break;			
			default:
				echo "error";
				break;
		}
		return $noticeArray;
	}

	private function get_mainpage()
	{
		$this->db->select("nid,
						   title,
		 				   uid,
		 				   counter_view,
		 				   counter_follow,
		 				   counter_praise" );
		
		$query = $this->db->get($this->table, 
								$this->numberPerPage,
								$this->noticeNumber
								);
		$this->noticeList = $query->result_array();

		$this->add_userinfo_to_notice_list($this->noticeList);
	}

	private function get_discovery()
	{
		$this->db->select("nid,
						   title,		 				
		 				   uid,
		 				   counter_view,
		 				   counter_follow,
		 				   counter_praise" );
		
		$query = $this->db->get($this->table, 
								$this->numberPerPage,
								$this->noticeNumber
								);
		$this->noticeList = $query->result_array();

		$this->add_userinfo_to_notice_list($this->noticeList);
	}

	private function get_timeline()
	{
		$this->db->select("prefix_user_timeline.nid,
						   title,
						   content,
		 				   prefix_user_timeline.uid,
		 				   prefix_user_timeline.time,
		 				   counter_view,
		 				   counter_follow,
		 				   counter_praise,
		 				   notice_type" );
		$this->db->from('prefix_user_timeline');
		$this->db->order_by("time", "desc"); 
		$this->db->join($this->table, $this->table.'.nid = prefix_user_timeline.nid');
		$this->db->limit($this->numberPerPage,$this->noticeNumber);
		//$this->db->where("prefix_user_timeline.uid",$this->input->head->uid);
		$query = $this->db->get(); 
		$this->noticeList = $query->result_array();

		$this->add_userinfo_to_notice_list($this->noticeList);
		$this->judge_notice_type();
			
	}

	private function judge_notice_type()
	{
		$i = 0;
		foreach ($this->noticeList as $value) 
		{

			switch ($value["notice_type"]) 
			{
			case 'comment_notice':
				$this->add_comment_to_notice_list($i,$value["nid"]);
				break;
			case 'car_notice':
				$this->add_carinfo_to_notice_list($i,$value["nid"]);
				break;
			case 'normal_notice':

				break;
			default:
				echo "error";
				break;
			}
			
			$i = $i + 1;
		}		
	}
	private function update_comment_notice_detail($noticeArray)
	{
		$nid = $noticeArray["nid"];
		$uid = $noticeArray["uid"];
		$img_info  = $this->get_img_info ($nid);
		$user_info = $this->get_user_info($uid);
		$comment_info  = $this->get_comment_info ($nid);
		$noticeArray["pid"]        = $img_info["pid"];
	 	$noticeArray["pic_url"]    = $img_info["pic_url"];
		$noticeArray["uid"]        = $user_info["uid"];
		$noticeArray["username"]   = $user_info["username"];
		$noticeArray["signature"]  = $user_info["signature"];
		$noticeArray["avatar_url"] = $user_info["avatar_url"];
		$noticeArray["p_nid"]      = $comment_info["p_nid"];
		$noticeArray["layer"]      = $comment_info["layer"];
		$noticeArray["commentType"]      = $comment_info["commentType"];

		return $noticeArray;			
	}
	private function update_car_notice_detail($noticeArray)
	{
		$nid = $noticeArray["nid"];
		$uid = $noticeArray["uid"];
		$img_info  = $this->get_img_info ($nid);
		$user_info = $this->get_user_info($uid);
		$car_info  = $this->get_car_info ($nid);
		$noticeArray["pid"]        = $img_info["pid"];
	 	$noticeArray["pic_url"]    = $img_info["pic_url"];
		$noticeArray["uid"]        = $user_info["uid"];
		$noticeArray["username"]   = $user_info["username"];
		$noticeArray["signature"]  = $user_info["signature"];
		$noticeArray["avatar_url"] = $user_info["avatar_url"];
		$noticeArray["price"]      = $car_info["price"];
		$noticeArray["mileage"]    = $car_info["mileage"];
		$noticeArray["brand"]      = $car_info["brand"];
		$noticeArray["recency"]    = $car_info["recency"];
		return $noticeArray;			
	}
	private function add_comment_to_notice_list($i,$nid )
	{

		$comment_info = $this->get_comment_info($nid);

		$this->noticeList[$i]["p_nid"]   = $comment_info["p_nid"];
		$this->noticeList[$i]["layer"]   = $comment_info["layer"];
		$this->noticeList[$i]["commentType"] = $comment_info["commentType"];
	}

	private function add_carinfo_to_notice_list($i,$nid )
	{

		$car_info = $this->get_car_info($nid);
		$this->noticeList[$i]["price"]      = $car_info["price"];
		$this->noticeList[$i]["mileage"]    = $car_info["mileage"];
		$this->noticeList[$i]["brand"]      = $car_info["brand"];
		$this->noticeList[$i]["recency"]    = $car_info["recency"];
	}

	private function add_userinfo_to_notice_list()
	{
	$i = 0;
		foreach ($this->noticeList as $value) 
		{
			$user_info = $this->get_user_info($value["uid"]);
			$this->noticeList[$i]["uid"]        = $user_info["uid"];
			$this->noticeList[$i]["username"]   = $user_info["username"];
			$this->noticeList[$i]["signature"]  = $user_info["signature"];
			$this->noticeList[$i]["avatar_url"] = $user_info["avatar_url"];
			
			$i = $i + 1;
		}
	}

	private function get_img_info($nid)
	{
		$query = $this->db->get_where("prefix_picture",
									  array('nid' => $nid));
		$img_info = $query->row_array();

	return $img_info;
	}

	private function get_comment_info($nid)
	{
		$query = $this->db->get_where("prefix_comment",
									  array('nid' => $nid));
		$img_info = $query->row_array();

	return $img_info;
	}

	private function get_user_info($uid)
	{
		$this->db->select("uid,username,signature,avatar_url");
		$query = $this->db->get_where("prefix_user",
									  array('uid' => $uid));

		$user_info = $query->row_array();
	return $user_info;
	}

	private function get_car_info($nid)
	{
		$query = $this->db->get_where("prefix_car_notice",
									  array('nid' => $nid));
		$car_info = $query->row_array();
	return $car_info;
	}

    //*******************************************************
	//API:searchNotice
	//****************************************************
	function search_notice_list($pageNumber,
								$numberPerPage,
								$searchType,
								$searchValue)
	{
		$this->noticeNumber  = ($pageNumber-1)*$numberPerPage;
		$this->numberPerPage = $numberPerPage;

		if ($searchType == "region_code" ||
		    $searchType == "brand"       ||
		    $searchType == "recency")
		    $this->search_single_str($searchType,$searchValue);
		elseif ($searchType == "price" ||
		        $searchType == "mileage")
			$this->search_complex_str($searchType,$searchValue);
		
		return $this->noticeList;
	}

	private function search_single_str($searchType,$searchValue)
	{

		$this->db->select("prefix_car_notice.nid,
						   title,
		 				   uid,
		 				   counter_view,
		 				   counter_follow,
		 				   counter_praise"
						  );

		$this->db->from('prefix_car_notice');
		$this->db->join($this->table, 
			            $this->table.'.nid = prefix_car_notice.nid');
		$this->db->where("prefix_car_notice.".$searchType,$searchValue);
		$this->db->limit($this->numberPerPage,
						 $this->noticeNumber);
		$query = $this->db->get();

		$this->noticeList = $query->result_array();

		$i = 0;
		foreach ($this->noticeList as $value) 
		{
			$noticeArray = $this->noticeList[$i];
			$this->noticeList[$i] = $this->update_car_notice_detail($noticeArray);
			$i = $i + 1;
		}		
	}

	private function search_complex_str($searchType,$searchValue)
	{

		$value = explode("-", $searchValue);
		$this->db->select("prefix_car_notice.nid,
						   title,
		 				   uid,
		 				   counter_view,
		 				   counter_follow,
		 				   counter_praise"
						  );

		$this->db->from('prefix_car_notice');
		$this->db->join($this->table, 
			            $this->table.'.nid = prefix_car_notice.nid');
		$this->db->where("prefix_car_notice.".$searchType." <=",$value[1]);
		$this->db->where("prefix_car_notice.".$searchType." >=",$value[0]);
		$this->db->limit($this->numberPerPage,
						 $this->noticeNumber);
		$query = $this->db->get();

		$this->noticeList = $query->result_array();

		$i = 0;
		foreach ($this->noticeList as $value) 
		{
			$noticeArray = $this->noticeList[$i];
			$this->noticeList[$i] = $this->update_car_notice_detail($noticeArray);
			$i = $i + 1;
		}		
	}
	//*******************************************************
	//API:praiseNotice& followNotice
	//****************************************************
	//更新user_list_praise和counter_praise
	function update_str_list($uid,$nid,$str)
	{
		$is_uid_exist = $this->is_user_str_exist($uid,$nid);

		if ($is_uid_exist)
		{
			$this->reduce_user_str_list($uid,$nid,$is_uid_exist);
			$is_done = 0;
		}
		else
		{
			$this->add_user_str_list($uid,$nid);
			$is_done = 1;
		}
		return $is_done;
	}

	/*
	* 向user_list_praise中添加uid，
	* counter_praise +1
	*/
	private function add_user_str_list($uid,$nid,$str)
	{
		if
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
		echo '111111111111';
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