<?php
class Notice_model extends MY_Model
{
	var $user_list_follow ;
	var $str_uid;

	function __construct()
	{
		parent ::__construct();
		$this->load->database();
		$this->define();
	}
	/*********************************************************
	API:publishNormalNotice publishCarNotice publishCommentNotice
	-1-
	************************************************************/


	//======================================================
	//插入普通信息字段
	//======================================================
	function insert_normal_notice($title,$content,$img_list,$notice_type)
	{

		$img_str 	= json_encode($img_list);
		$uid        = 	$this->input->uid;
		$sysTime    = 	$this->input->sysTime;
		$coordinate =   $this->input->coordinate;
		$SQL  		= "INSERT INTO `prefix_notice`
						(`title`, 
						`content`, 
						`img_list`, 
						`notice_type`, 
						`uid`, `time`,  
						`coordinate`, 
						`counter_view`, 
						`counter_follow`, 
						`counter_praise`, 
						`user_list_view`, 
						`user_list_follow`, 
						`user_list_praise`) 
			   VALUES ('".$title."','".$content."','".$img_str."','".$notice_type."','".$uid."','".$sysTime."','".$coordinate."', 0, 0, 0, '[]', '[]', '[]')";
		$this->db->query($SQL);
		$nid = $this->db->query("SELECT LAST_INSERT_ID()")->row_array();
	
		return $nid["LAST_INSERT_ID()"];
	}
	//=======================================================
	//在普通信息的基础上，插入额外的二手车信息字段
	//==========================================================
	function insert_car_notice( $nid,$car_notice)
	{
		$save_money = $car_notice->market_price - $car_notice->price;		
		$car_configuration = json_encode($car_notice->car_configuration);
		$SQL = "INSERT INTO `prefix_car_notice` 
				(`nid`, `price` , `market_price`, `save_money` , `location` , 
				`brand` , `recency` , `registration_time`, `speed_box` , 
				`car_number` , `valid_date` , `insurance_date` , 
				`commerce_insurance_date`, `exchange_time` , `mileage`,`sell_state`,`car_configuration` ) 
				VALUES ('".$nid."', '".$car_notice->price."', '".$car_notice->market_price."', 
					'".$save_money."','".$car_notice->location."', '".$car_notice->brand."', 
					'".$car_notice->recency."', '".$car_notice->registration_time."', 
					'".$car_notice->speed_box."','".$car_notice->car_number."', 
					'".$car_notice->valid_date."' ,  '".$car_notice->insurance_date."',
					'".$car_notice->commerce_insurance_date."','".$car_notice->exchange_time."',
					 '".$car_notice->mileage."','1','".$car_configuration."')";
		$this->db->query($SQL);
		$this->add_interested($nid,$car_notice);

	}
	//===============================================================
	//在普通信息的基础上，插入额外的评论信息字段
	//===================================================================
	function insert_comment_notice( $nid ,
				 					$p_nid,
									$commentType = "public"
				 					)
	{			
		$layer =$this->count_layer($p_nid);
		$r_nid =$this->count_root($p_nid,$layer);
		$SQL = "INSERT INTO `prefix_comment` (`nid`, `p_nid`, `r_nid`, `layer`, `commentType`) 
				VALUES ('".$nid."','".$p_nid."','".$r_nid."','".$layer."','".$commentType."')";
		$this->db->query($SQL);
	}
	//================================================================
	//更新信息
	//================================================================
	function update_normal_notice($nid,$title,$content,$img_list)
	{
		$img_str = json_encode($img_list);
		$SQL = "UPDATE `prefix_notice` 
				SET `title`     = '".$title."', 
					`content`   = '".$content."',
					`img_list`  = '".$img_str."'
				WHERE `nid` = '".$nid."'";
		$this->db->query($SQL);
		
	}

	function update_car_notice($nid,$car_notice)
	{
		$save_money = $car_notice->market_price - $car_notice->price;
		$SQL = "UPDATE `prefix_car_notice` 
				SET  `price`    			= '".$car_notice->recency."' ,
				 `market_price` 			= '".$car_notice->market_price."', 
				 `save_money`   			= '".$save_money."', 
				 `location`  				= '".$car_notice->location."',
				`brand`  					= '".$car_notice->brand."',
				`recency` 					= '".$car_notice->recency."',
				`registration_time`			= '".$car_notice->registration_time."',
				 `speed_box` 				= '".$car_notice->speed_box."',
				`car_number` 				= '".$car_notice->car_number."',
				`valid_date` 				= '".$car_notice->valid_date."',
				`insurance_date` 			= '".$car_notice->insurance_date."',
				`commerce_insurance_date` 	= '".$car_notice->commerce_insurance_date."',
				`exchange_time` 			= '".$car_notice->exchange_time."',
				`mileage`					= '".$car_notice->mileage."',
				`sell_state` 				= '".$car_notice->sell_state."'
				WHERE `nid` = '".$nid."'";
		$this->db->query($SQL);
		
	}

	//=================================================================
	//如果某条信息满足用户的偏好模型，则将此信息加入他的推送列表
	//==================================================================
	function add_interested($nid,$car_notice)
	{
		$query = $this->db->get("prefix_user_preference");
		$preference_array = $query->result_array();
		foreach ($preference_array as $key => $value) 
		{
			$bool = $this->judge_preference($value,$car_notice);
			if($bool)
				$this->add_notice_list_interested($value['uid'],$nid);
		}
	}
	//============================================================
	//判断一条信息是否符合用户偏好
	//=============================================================
	function judge_preference($preference,$car_notice)
	{
		
		if (! $preference['brand'] === null)
		{
			$brand   = json_encode($preference['brand']);
			$bool_brand  = !(array_search($car_notice->brand,$brand) === NULL);
		}
		else $bool_brand = TRUE;

		if (! $preference['speed_box'] === null)
		{
			$bool_speed_box =	$car_notice->speed_box ==  $preference['speed_box'];
		}
		else $bool_speed_box = TRUE;

		if (! $preference['car_type'] === null)
		{
			$bool_car_type = $car_notice->car_type ==  $preference['car_type'];
		}
		else $bool_car_type = TRUE;

		if (! $preference['age'] === null)
		{
			$age     = explode("-", $preference['age']);
			$bool_age 		=	$car_notice->age 	 > $age[0]&&
							    $car_notice->age 	 < $age[1];
		}
		else $bool_age = TRUE;

		if (! $preference['price'] === null)
		{
			$price   = explode("-", $preference['price']);
			$bool_price 	=	$car_notice->price   > $price[0]&&
							    $car_notice->price 	 < $price[1];
		}
		else $bool_price = TRUE;

		if (! $preference['mileage'] === null)
		{
			$mileage = explode("-", $preference['mileage']);
			$bool_mileage   =	$car_notice->mileage > $mileage[0]&&
							    $car_notice->mileage > $mileage[1];
		}
		else $bool_mileage = TRUE;

		return $bool_brand&&$bool_speed_box&&$bool_car_type&&$bool_age&&$bool_price&&$bool_mileage;
	}
	//=======================================
	//输入信息id，将其加入某一用户的推送列表
	//========================================
	function add_notice_list_interested($uid,$nid)
	{	
		$this->table_name = "prefix_user_relation";
		$this->primary_key = "uid";
		$this->db->select("notice_list_interested");
		$query = $this->db->get("prefix_user_relation");
		$array = $query->row_array();
		$notice_list_interested = $array['notice_list_interested'];

		$interested_array = json_decode($notice_list_interested,true);

		if (array_search($nid, $interested_array) == NULL)
			$interested_array[] = $nid;
		$notice_list_interested = json_encode($interested_array);

		$data = array
		(
				'uid' => $uid,
				'notice_list_interested' => $notice_list_interested
			);
		$this->add($data,"prefix_user_relation",'uid',$uid);
	}
	//===================================================
	//计算评论所在的层数
	//==============================================
	private function count_layer($p_nid)
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
	//==================================================
	//计算评论的根元素id
	//============================================
	private function count_root($p_nid,$layer)
	{
		if($layer == 1)
			$r_nid = $p_nid;
		else if($layer > 1)
		{
			for ($i=1; $i<$layer; $i++)
			{
				$this->db->select("p_nid");
				$query = $this->db->get_where("prefix_comment",array("nid"=>$p_nid));
				$r_nid = $query->row_array();
				$r_nid = $r_nid["p_nid"];
				$p_nid = $r_nid;
			}
		}
		return $r_nid;
	}

	//*******************************************************
	//API:getNoticeList & getNoticeDetail   -2-
	//****************************************************
	function get_notice_list($pageNumber,$numberPerPage,$pageType)
	{
		$this->noticeNumber  = ($pageNumber-1)*$numberPerPage;
		$this->numberPerPage = $numberPerPage;
		$uid = $this->input->head->uid;

		switch ($pageType) 
		{
			case "mainpage":
				$this->get_mainpage();
				break;
			case "discovery":
				$this->get_discovery($uid);
				break;
			case "timeline":
				$this->get_timeline($uid);
				break;
			case "friendPage":
				$this->get_friend_page($uid);
				break;
			case "collection":
				$this->get_collection($uid);
				break;
			default:
				echo "error";
				break;
		}
		//$this->counter_view();

		return $this->noticeList;
	}

	private function get_mainpage()
    {
    	$this->get_car_list();
    	foreach ($this->noticeList as &$value) 
    	{

    			$comment_list = $this->get_comment_list($value["nid"]);
    			$comment_list_3 = array_slice($comment_list,0,3);
    			$value["comment_list"] = $comment_list_3;
    		
    	}
    }
    //推送方法1
	// private function get_discovery($uid)
	// {
	// 	$preference = $this->get_preference($uid);
	// 	$this->get_prefer_car_list($preference);
	// }	
    //推送方法2
    private function get_discovery($uid)
	{
		$interested_list = $this->get_interested_list($uid);
		
		$this->get_notice_list_id($interested_list);


	}	

    private  function get_timeline($uid)
    {
    	$timeline_list = $this->get_timeline_list($uid);

    	foreach ($timeline_list as &$value) 
    	{
    		//if(! $value['timeline_type'] == 'my_comment')
    		//{
    			$comment_list = $this->get_comment_list($value["nid"]);
    			$value["comment_list"] = $comment_list;
    		//}
    	}

    	$this->noticeList = $timeline_list;
    	
    	//
    	//$this->get_sysInfo(); //include follow ,praise ,comment
    }

    private  function get_friend_page($uid)
    {
    	$friend_car_list = $this->get_friend_car_list($uid);
    
    	foreach ($friend_car_list as &$value) 
    	{
    		$comment_list = $this->get_comment_list($value["nid"]);
    		$value["comment_list"] = $comment_list;
    	}
    	$this->noticeList = $friend_car_list;
    	
    	//
    	//$this->get_sysInfo(); //include follow ,praise ,comment
    }

     private  function get_collection($uid)
    {
    	$collection_list = $this->get_collection_list($uid);

		
		$this->get_notice_list_id($collection_list);

    }

    //
    function get_interested_list($uid)
    {
    	$this->db->select('notice_list_interested');
    	$query = $this->db->get('prefix_user_relation');
    	$array = $query->row_array();
    	
    	return json_decode($array['notice_list_interested'],true);
    }

    function get_collection_list($uid)
    {
    	$this->db->select('notice_list_following');
    	$query = $this->db->get('prefix_user_relation');
    	$array = $query->row_array();
    	
    	return json_decode($array['notice_list_following'],true);
    }
    //根据输入的nid数组，返回相应的notice列表
    function get_notice_list_id($nid_array)
    {
    	$nid_str = "('".implode("','",$nid_array)."')";
    	$SQL = "SELECT `prefix_notice`.`nid`, `title`, `prefix_notice`.`uid`, `img_list`, `counter_view`, `counter_follow`, `counter_praise`, `notice_type`, `username`, `signature`, `avatar_url`, `price`, `save_money`, `location`, `brand`, `registration_time`, `speed_box`, `car_number`, `mileage`
				FROM (`prefix_notice`)
				JOIN `prefix_car_notice` ON `prefix_notice`.`nid` = `prefix_car_notice`.`nid`
				JOIN `prefix_user` ON `prefix_notice`.`uid` = `prefix_user`.`uid`
				WHERE `prefix_car_notice`.`nid` in ".$nid_str."
				ORDER BY `time` desc";
		$query = $this->db->query($SQL);
		$tmp = $query->result_array();
		$this->total_row = count($tmp);
	
    	$SQL = "SELECT `prefix_notice`.`nid`, `title`, `prefix_notice`.`uid`, `img_list`, `counter_view`, `counter_follow`, `counter_praise`, `notice_type`, `username`, `signature`, `avatar_url`, `price`, `save_money`, `location`, `brand`, `registration_time`, `speed_box`, `car_number`, `mileage`
				FROM (`prefix_notice`)
				JOIN `prefix_car_notice` ON `prefix_notice`.`nid` = `prefix_car_notice`.`nid`
				JOIN `prefix_user` ON `prefix_notice`.`uid` = `prefix_user`.`uid`
				WHERE `prefix_car_notice`.`nid` in ".$nid_str."
				ORDER BY `time` desc
				LIMIT ".$this->noticeNumber.",".$this->numberPerPage;
		$query = $this->db->query($SQL);
		$car_list = $query->result_array();
		$this->noticeList = $this->img_decode($car_list,'img_list');
    }



	// function get_total_car_row($pageType)
 //    {
 //   	    $this->db->select('count(nid) as total_row');
 //    	// ; $this->db->where($where)

	// 	$query = $this->db->get("prefix_car_notice");
				
	// 	return $query->row()->total_row;		
 //    }
    //时间线相关信息
    //取值：my_comment,my_publish,my_prise,my_follow,my_add_friend
    function get_timeline_list($uid)
    {
    	// //$this->db->select("prefix_user_timeline.uid,
		 		// 		   username,
     //  					   signature,
     //  					   avatar_url,
    	// 				   prefix_user_timeline.nid,"
    	// 				   .$this->table.".uid,
    	// 				   timeline_type,
    	// 				   is_sticky,
    	// 				   prefix_user_timeline.time,
    	// 				   title,
    	// 				   img_list,
    	// 				   counter_view,
		 		// 		   counter_follow,
		 		// 		   counter_praise,
		 		// 		   notice_type"
    	// 					);
  //   	$this->db->from("prefix_user_timeline");
  //   	$this->db->order_by("time", "desc"); 
    	 
  //   	//$this->db->join($this->table,"prefix_user_timeline.nid=".$this->table.".nid");
  //   	//$this->db->join("prefix_user","prefix_user_timeline.uid=prefix_user.uid");
  //   	$this->db->where("prefix_user_timeline.uid",$uid);
  //   	$this->db->limit($this->numberPerPage,$this->noticeNumber);
		// $query = $this->db->get(); 
		// $str = $this->db->last_query();
		
    	$SQL = "SELECT `prefix_user_timeline`.`uid`, `username`, `signature`, `avatar_url`, `prefix_user_timeline`.`nid`, `prefix_notice`.`uid`, `timeline_type`, `is_sticky`, `prefix_user_timeline`.`time`, `title`, `img_list`, `counter_view`, `counter_follow`, `counter_praise`, `notice_type`
				FROM (`prefix_user_timeline`)
				JOIN `prefix_notice` ON `prefix_user_timeline`.`nid`=`prefix_notice`.`nid`
				JOIN `prefix_user` ON `prefix_user_timeline`.`uid`=`prefix_user`.`uid`
				WHERE `prefix_user_timeline`.`uid` =  '".$uid."'
				ORDER BY `time` desc";
		$query = $this->db->query($SQL);
    	$tmp = $query->result_array();
    	$this->total_row = count($tmp);
		$SQL = "SELECT `prefix_user_timeline`.`uid`, `username`, `signature`, `avatar_url`, `prefix_user_timeline`.`nid`, `prefix_notice`.`uid`, `timeline_type`, `is_sticky`, `prefix_user_timeline`.`time`, `title`, `img_list`, `counter_view`, `counter_follow`, `counter_praise`, `notice_type`
				FROM (`prefix_user_timeline`)
				JOIN `prefix_notice` ON `prefix_user_timeline`.`nid`=`prefix_notice`.`nid`
				JOIN `prefix_user` ON `prefix_user_timeline`.`uid`=`prefix_user`.`uid`
				WHERE `prefix_user_timeline`.`uid` =  '".$uid."'
				ORDER BY `time` desc
				LIMIT ".$this->noticeNumber.",".$this->numberPerPage;
		$query = $this->db->query($SQL);
    	$timeline_list = $query->result_array();

    	foreach ($timeline_list as &$value) 
    	{
    		$img_list = json_decode($value["img_list"]);
    		$value["img_list"] = $img_list;
    	}

    	return $timeline_list;
    }

    //主页二手车信息
	private function get_car_list()
	{
		// $this->db->select($this->table.".nid,
		// 				   title,"
		//  				   .$this->table.".uid,
		//  				   img_list,
		//  				   counter_view,
		//  				   counter_follow,
		//  				   counter_praise,
		//  				   notice_type,
		//   				   username,
  //     					   signature,
  //     					   avatar_url,
  //     				   	   price   ,         
		// 				   save_money ,      
		//   				   location  ,  		
		// 				   brand    ,   								   		
		// 				   registration_time,
		// 				   speed_box    , 	
		// 				   car_number,    	
		// 				   mileage " );
		// $this->db->from($this->table);
		// $this->db->order_by("time", "desc"); 
		// $this->db->join("prefix_car_notice", $this->table.'.nid = prefix_car_notice.nid');
		// $this->db->join("prefix_user", $this->table.'.uid = prefix_user.uid');
		// $this->db->limit($this->numberPerPage,$this->noticeNumber);
		// $query = $this->db->get(); 
		// $str = $this->db->last_query();
		

		$SQL = "SELECT `prefix_notice`.`nid`, `title`, `prefix_notice`.`uid`, `img_list`, `counter_view`, `counter_follow`, `counter_praise`, `notice_type`, `username`, `signature`, `avatar_url`, `price`, `save_money`, `location`, `brand`, `registration_time`, `speed_box`, `car_number`, `mileage`
				FROM (`prefix_notice`)
				JOIN `prefix_car_notice` ON `prefix_notice`.`nid` = `prefix_car_notice`.`nid`
				JOIN `prefix_user` ON `prefix_notice`.`uid` = `prefix_user`.`uid`
				ORDER BY `time` desc
				LIMIT ".$this->noticeNumber.",".$this->numberPerPage;
		$query = $this->db->query($SQL);
		$car_list = $query->result_array();
		$this->noticeList = $this->img_decode($car_list,'img_list');
						
	}

	
	//推送二手车信息
	private function get_prefer_car_list($preference)
	{
		$this->db->select($this->table.".nid,
						   title,"
		 				   .$this->table.".uid,
		 				   img_list,
		 				   counter_view,
		 				   counter_follow,
		 				   counter_praise,
		 				   notice_type,
		 				   username,
      					   signature,
      					   avatar_url,
      					   price,
   
 						   save_money ,      
	   					   location  ,  
 						   brand    ,   	
 						   registration_time,
 						   speed_box    , 	
 						   car_number,    	
 						   mileage " );

		$this->db->from($this->table);
		$this->db->order_by("time", "desc"); 
		$this->db->join("prefix_car_notice", $this->table.'.nid = prefix_car_notice.nid');
		$this->db->join("prefix_user", $this->table.'.uid = prefix_user.uid');
		$this->db->where_in("brand",$preference['brand']);
		$this->db->where("price >",  $preference['price'][0] );
		$this->db->where("price <",  $preference['price'][1]);
		$this->db->where("mileage >",$preference['mileage'][0]);
		$this->db->where("mileage <",$preference['mileage'][1] );
		$this->db->limit($this->numberPerPage,$this->noticeNumber);
		$query = $this->db->get(); 
		// $str = $this->db->last_query();
	
		$prefer_car_list = $query->result_array();	
		$this->noticeList = $this->img_decode($prefer_car_list,'img_list');
					
	}
	//用户偏好
	function get_preference($uid)
	{
		$query = $this->db->get_where("prefix_user_preference",array("uid"=>$uid));
		$array = $query->row_array();

		$preference = array
		(
			'brand'   => explode(',', $array['brand']), 
			'price'   => explode('-', $array['price']),
			'mileage' => explode('-', $array['mileage'])	
			);
		return $preference;
	} 
	//好友二手车信息
	private function get_friend_car_list($uid)
	{
		$friend_list_initial = $this->get_friend_list($uid);

		$friend_list_initial = json_decode($friend_list_initial,true) ;
		$friend_list_initial = $this->array_to_str($friend_list_initial);
		// $this->db->select($this->table.".nid,
		// 				   title,"
		//  				   .$this->table.".uid,
		//  				   img_list,
		//  				   counter_view,
		//  				   counter_follow,
		//  				   counter_praise,
		//  				   notice_type,
		//  				   username,
  //     					   signature,
  //     					   avatar_url" );
		// $this->db->from($this->table);
		// $this->db->order_by("time", "desc"); 
		// $this->db->join("prefix_car_notice", $this->table.'.nid = prefix_car_notice.nid');
		// $this->db->join("prefix_user", $this->table.'.uid = prefix_user.uid');
		// $this->db->where_in($this->table.".uid",$friend_list_initial);
		// $this->db->limit($this->numberPerPage,$this->noticeNumber);
		// $query = $this->db->get(); 

		// $str = $this->db->last_query();
	

		$SQL = "SELECT `prefix_notice`.`nid`, `title`, `prefix_notice`.`uid`, `img_list`, `counter_view`, `counter_follow`, `counter_praise`, `notice_type`, `username`, `signature`, `avatar_url`
				FROM (`prefix_notice`)
				JOIN `prefix_car_notice` ON `prefix_notice`.`nid` = `prefix_car_notice`.`nid`
				JOIN `prefix_user` ON `prefix_notice`.`uid` = `prefix_user`.`uid`

				WHERE `prefix_notice`.`uid` IN ".$friend_list_initial."
				ORDER BY `time` desc";
		$query = $this->db->query($SQL);
		$tmp = $query->result_array();	
		$this->total_row = count($tmp);	
		$SQL = "SELECT `prefix_notice`.`nid`, `title`, `prefix_notice`.`uid`, `img_list`, `counter_view`, `counter_follow`, `counter_praise`, `notice_type`, `username`, `signature`, `avatar_url`
				FROM (`prefix_notice`)
				JOIN `prefix_car_notice` ON `prefix_notice`.`nid` = `prefix_car_notice`.`nid`
				JOIN `prefix_user` ON `prefix_notice`.`uid` = `prefix_user`.`uid`

				WHERE `prefix_notice`.`uid` IN ".$friend_list_initial."
				ORDER BY `time` desc
				LIMIT ".$this->noticeNumber.",".$this->numberPerPage;

		$query = $this->db->query($SQL);
		$friend_car_list = $query->result_array();

		$friend_car_list = $this->img_decode($friend_car_list,'img_list');

		return $friend_car_list;
				
	}

	function get_total_notice_num($uid){

		$this->db->select("nid");
		$this->db->where('notice_type','car_notice');
		$this->db->where('uid',$uid);

		
		$query =$this->db->get($this->table);		
		$noticeList = $query->result_array();
		var_dump($noticeList);
		return count($noticeList);
	}

	function get_friend_list($uid)
	{
		$this->db->select('friend_list_initial');
		$query = $this->db->get_where('prefix_user_relation',array('uid'=>$uid));
		$array = $query->row_array();
		$friend_list_initial = $array['friend_list_initial'];		
		
		return $friend_list_initial;
	}
	
	private function get_comment_list($nid)
	{
		$this->db->select("prefix_user_timeline.nid,
						   title,
						   content,
		 				   prefix_user_timeline.uid,
		 				   prefix_user_timeline.time,
		 				   counter_view,
		 				   counter_follow,
		 				   counter_praise,
		 				   notice_type,
		 				   username,
      					   signature,
      					   avatar_url,
      					   p_nid,
      					   r_nid
		 				   " );
		$this->db->from('prefix_user_timeline');
		$this->db->order_by("counter_praise", "desc");
		$this->db->order_by("time", "asc"); 
		
		$this->db->join($this->table, $this->table.'.nid = prefix_user_timeline.nid');
		$this->db->join("prefix_comment", 'prefix_user_timeline.nid = prefix_comment.nid');
		$this->db->join("prefix_user", 'prefix_user_timeline.uid = prefix_user.uid');
		//$this->db->limit($this->numberPerPage,$this->noticeNumber);
		$this->db->where("r_nid",$nid);
		$query = $this->db->get(); 
		$comment_list = $query->result_array();
		// if ($comment_list)
	
		// 	$comment_list = $this->img_decode($comment_list);
		
		return $comment_list;
	}

	// function counter_view()
	// {
	// 	//调用一次，浏览数+1
	// 	foreach ($this->noticeList as  &$value) 
	// 	{
	
	// 		++ $value["counter_view"] ;
	// 		$data = array(
	// 			"counter_view" => $value["counter_view"]) ;
	// 		$this->db->where("nid",$value["nid"]);
	// 		$this->db->update($this->table,$data);
	// 	}
	// }
		function get_notice_detail($nid)
	{
		// $this->db->select("nid,
		// 				   title,
		//  				   content,
		//  				   img_list,
		//  				   uid,
		//  				   coordinate,
		//  				   counter_view,
		//  				   counter_follow,
		//  				   counter_praise,
		//  				   notice_type" );
		// $query = $this->db->get_where($this->table,	
		// 						      array('nid'=> $nid));

		// $noticeArray = $query->row_array();
		// $str = $this->db->last_query();

		$SQL = "SELECT `nid`, `title`, `content`, `img_list`, `uid`, `time`,`coordinate`, 
						`counter_view`, `counter_follow`, `counter_praise`, `notice_type`
				FROM (`prefix_notice`)
				WHERE `nid` =  '".$nid."'";
		$query = $this->db->query($SQL);
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
		$noticeArray['relation_notice_list'] = 
											$this->get_relation_notice_list($noticeArray['brand'], 
																			$noticeArray['price']); 

		return $noticeArray;
	}
	private function get_relation_notice_list($brand, $price)
	{
		$price1 = $price - 15;
		$price2 = $price + 15;

		$SQL = "SELECT `prefix_notice`.`nid`, `title`, `prefix_notice`.`uid`, `img_list`, `counter_view`, `counter_follow`, `counter_praise`, `notice_type`, `username`, `signature`, `avatar_url`, `price`, `save_money`, `location`, `brand`, `registration_time`, `speed_box`, `car_number`, `mileage`
				FROM (`prefix_notice`)
				JOIN `prefix_car_notice` ON `prefix_notice`.`nid` = `prefix_car_notice`.`nid`
				JOIN `prefix_user` ON `prefix_notice`.`uid` = `prefix_user`.`uid`
				WHERE `prefix_car_notice`.`brand` = '".$brand."'
				AND `prefix_car_notice`.`price` <= ".$price2."
				AND `prefix_car_notice`.`price` >=".$price1."
				ORDER BY `time` desc";

		$query = $this->db->query($SQL);
		$car_list = $query->result_array();
		$relation_notice_list = $this->img_decode($car_list,'img_list');

		return $relation_notice_list;
	}

	private function update_comment_notice_detail($noticeArray)
	{
		$nid      = $noticeArray["nid"];
		$uid      = $noticeArray["uid"];
		$img_list = json_decode($noticeArray["img_list"]);

		$user_info = $this->get_user_info($uid);
		$comment_info  = $this->get_comment_info ($nid);
	 	$noticeArray["img_list"]   = $img_list;
		$noticeArray["uid"]        = $user_info["uid"];
		$noticeArray["username"]   = $user_info["username"];
		$noticeArray["signature"]  = $user_info["signature"];
		$noticeArray["avatar_url"] = $user_info["avatar_url"];
		$noticeArray["p_nid"]      = $comment_info["p_nid"];
		$noticeArray["layer"]      = $comment_info["layer"];
		$noticeArray["commentType"]      = $comment_info["commentType"];

		return $noticeArray;			
	}
	function update_car_notice_detail($noticeArray)
	{
		$nid = $noticeArray["nid"];

		$uid = $noticeArray["uid"];
		
		$img_list = json_decode($noticeArray["img_list"]);

		$user_info = $this->get_user_info($uid);
		$car_info  = $this->get_car_info ($nid);
		if (!$car_info["car_configuration"])
			$car_info["car_configuration"] = '["abs","esp","remote_key"]';
		$car_configuration = 
		json_decode($car_info["car_configuration"]);

		$noticeArray["img_list"]   = $img_list;
		$noticeArray["uid"]        = $user_info["uid"];
		$noticeArray["username"]   = $user_info["username"];
		$noticeArray["signature"]  = $user_info["signature"];
		$noticeArray["avatar_url"] = $user_info["avatar_url"];
		$noticeArray["price"]      = $car_info["price"];
		$noticeArray["market_price"]    = $car_info["market_price"];
		$noticeArray["save_money"]    = $car_info["save_money"];
		$noticeArray["mileage"]    = $car_info["mileage"];
		$noticeArray["brand"]      = $car_info["brand"];		
		$noticeArray["registration_time"]    = $car_info["registration_time"];
		$noticeArray["car_number"]    = $car_info["car_number"];
		$noticeArray["speed_box"]    = $car_info["speed_box"];
		$noticeArray["location"]    = $car_info["location"];		
		$noticeArray["valid_date"]    = $car_info["valid_date"];
		$noticeArray["insurance_date"]    = $car_info["insurance_date"];
		$noticeArray["commerce_insurance_date"]    = $car_info["commerce_insurance_date"];
		$noticeArray["exchange_time"]    = $car_info["exchange_time"];
		$noticeArray["car_configuration"]    = $car_configuration;
		return $noticeArray;			
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
		// $this->db->select("uid,username,signature,avatar_url");
		// $query = $this->db->get_where("prefix_user",
		// 							  array('uid' => $uid));


		// $user_info = $query->row_array();
		$SQL = "SELECT *
				FROM (`prefix_user`)
				WHERE `uid` =  '".$uid."'";
		$query = $this->db->query("$SQL");
		$user_info = $query->row_array();

	return $user_info;
	}

	private function get_car_info($nid)
	{
		// $query = $this->db->get_where("prefix_car_notice",
		// 							  array('nid' => $nid));
		// $car_info = $query->row_array();
		// $str = $this->db->last_query();
		

		$SQL = "SELECT *
				FROM (`prefix_car_notice`)
				WHERE `nid` =  '".$nid."'";
		$query = $this->db->query("$SQL");
		$car_info = $query->row_array();
	return $car_info;
	}

    //*******************************************************
	//API:searchNotice
	//****************************************************
	 //*******************************************************
	//API:searchNotice
	//****************************************************

	function search_notice_list($pageNumber,
								$numberPerPage,
								$location,
								$searchValue ,
								$filterValue,
								$sortValue)
	{
		$this->noticeNumber  = ($pageNumber-1)*$numberPerPage;
		$this->numberPerPage = $numberPerPage;
		$this->location      = $location;
		$this->SQL = '';
		$this->search_sql($searchValue);
		if (!($filterValue == '0') )
			$this->filter_sql($filterValue);

		$this->sort_sql($sortValue);

		$this->SQL .= " LIMIT ".$this->noticeNumber.",".$this->numberPerPage;
		$query = $this->db->query($this->SQL);
		$this->noticeList = $query->result_array();
		$this->noticeList = $this->img_decode($this->noticeList,'img_list');
		$this->noticeList = $this->img_decode($this->noticeList,'car_configuration');
		return $this->noticeList;
	}

	private function search_sql($searchValue)
	{
		$this->SQL .= "SELECT `prefix_car_notice`.`nid`, `title`, `prefix_notice`.`uid`, 
							  `img_list`, `counter_view`, `counter_follow`, `counter_praise`, 
							  `notice_type`, `username`, `signature`, `avatar_url`, `price`, 
							  `save_money`, `location`, `brand`,`age`, `registration_time`, 
							  `speed_box`, `car_number`, `mileage`, `car_configuration`
						FROM (`prefix_car_notice`)";
		$this->SQL .=  "JOIN `prefix_notice` ON `prefix_notice`.`nid` = `prefix_car_notice`.`nid`
						JOIN `prefix_user` ON `prefix_notice`.`uid` = `prefix_user`.`uid`";
		$this->SQL .= "WHERE (`title` LIKE '%".$searchValue."%'";
		$this->SQL .= "OR `brand`    LIKE '%".$searchValue."%'";
		$this->SQL .= "OR `content`  LIKE '%".$searchValue."%'";
		$this->SQL .= "OR `location` LIKE '%".$searchValue."%')";
		$this->SQL .= "AND `location`='".$this->location."' ";
	}

	private function filter_sql($filterValue)
	{

		if (property_exists ( $filterValue, 'price'))
		{
			$price = explode("-", $filterValue->price);
			$this->SQL .= "AND `price`<=".$price[1]." ";
			$this->SQL .= "AND `price`>=".$price[0]." ";
		}

		if (property_exists ( $filterValue, 'age'))
		{
			$age = explode("-", $filterValue->age);
			$this->SQL .= "AND `age`   <=  ".$age[1]." ";
			$this->SQL .= "AND `age`   >=  ".$age[0]." ";
		}

		if (property_exists ( $filterValue, 'mileage'))
		{
			$mileage = explode("-", $filterValue->mileage);
			$this->SQL .= "AND `mileage`   <=  ".$mileage[1]." ";
			$this->SQL .= "AND `mileage`   >=  ".$mileage[0]." ";
		}

		if (property_exists ( $filterValue, 'brand'))
		{
			$this->SQL .= "AND `brand`  =  '".$filterValue->brand."' ";
		}

		if (property_exists ( $filterValue, 'speedBox'))
		{
			$this->SQL .= "AND `speed_box`  =  '".$filterValue->speedBox."' ";
		}

		if (property_exists ( $filterValue, 'carType'))
		{
			$this->SQL .= "AND `car_type`  =  '".$filterValue->carType."' ";
		}
	}

	private function sort_sql($sortValue)
	{
		switch ($sortValue) 
		{
			case '0':
				$this->SQL .= "ORDER BY `time` desc";
				break;
			case 'price':
				$this->SQL .= "ORDER BY `price` desc";
				break;
			case 'age':
				$this->SQL .= "ORDER BY `age` desc";
				break;
			case 'mileage':
				$this->SQL .= "ORDER BY `mileage` desc";
				break;

		}
		
	}

	//*******************************************************
	//API:praiseNotice& followNotice
	//****************************************************
	//更新user_list_praise和counter_praise
	//use :json_encode
	function update_praise_list($uid,$nid)
	{
		// $this->db->select("user_list_praise,counter_praise");
		// $query = $this->db->get_where($this->table,
		// 	                          array('nid' => $nid));
		// 	$str = $this->db->last_query();
		
		$SQL = "SELECT `user_list_praise`, `counter_praise`
				FROM (`prefix_notice`)
				WHERE `nid` =  ".$nid;
		$query = $this->db->query($SQL);
		$noticeInfo = $query->row_array();
		$user_list_praise = $noticeInfo['user_list_praise'];
		$counter_praise   = $noticeInfo['counter_praise'];
		if($user_list_praise == NULL || $user_list_praise == 'null' )
			$user_list_praise = '[]';
		$user_list_praise = json_decode($user_list_praise,true);

		$key = array_search($uid, $user_list_praise);
		if($key === FALSE)
		{
			$user_list_praise[] = $uid;
			++$counter_praise;
			$is_praised = 1;
		}
		else
		{
			array_splice($user_list_praise,$key,1);
			--$counter_praise;
			$is_praised = 0;
		}
		$user_list_praise = json_encode($user_list_praise);

		$data = array(
			"user_list_praise" => $user_list_praise,
			"counter_praise"   => $counter_praise
			);
		$this->db->update($this->table,$data,array('nid' =>$nid));
		return $is_praised;
	}
	//更新user_list_follow和counter_follow
	//use :json_encode
	function update_follow_list($uid,$nid)
	{
		// $this->db->select("user_list_follow,counter_follow");
		// $query = $this->db->get_where($this->table,
		// 	                          array('nid' => $nid));
		$SQL = "SELECT `user_list_follow`, `counter_follow`
				FROM (`prefix_notice`)
				WHERE `nid` =  ".$nid;
		$query = $this->db->query($SQL);
		$noticeInfo = $query->row_array();
		$user_list_follow = $noticeInfo['user_list_follow'];
		$counter_follow   = $noticeInfo['counter_follow'];

		if($user_list_follow == NULL ||$user_list_follow == 'null' )
			$user_list_follow = '[]';
		$user_list_follow = json_decode($user_list_follow,true);

		$key = array_search($uid, $user_list_follow);
		if($key === FALSE)
		{
			$user_list_follow[] = $uid;
			++$counter_follow;
			$is_followd = 1;
		}
		else
		{
			array_splice($user_list_follow,$key,1);
			--$counter_follow;
			$is_followd = 0;
		}
		$user_list_follow = json_encode($user_list_follow);

		$data = array(
			"user_list_follow" => $user_list_follow,
			"counter_follow"   => $counter_follow
			);
		$this->db->update($this->table,$data,array('nid' =>$nid));
		return $is_followd;
	}

	// private function json_UTF8($json)
	// {
	// 	$json_array = $json;
	// 	foreach ( $json_array as $key_f => $value_f ) 
	// 	{  
	// 		foreach ($value_f as $key_s => $value_s) 
	// 		{
	// 			$json_array[$key_f][$key_s] = urlencode($value_s); 
	// 		}
        	
 //        }         
 //    $json = urldecode ( json_encode ($json_array) );
 //    return $json;   
	// }
	function get_total_row($pageType)
    {
    	$uid = $this->input->head->uid;

    	switch ($pageType) 
		{
			case "mainpage":
				$this->db->select('count(nid) as total_row');
 			   
		        $query = $this->db->get("prefix_car_notice");
				
		        $this->total_row = $query->row()->total_row;		
				
				break;
			case "discovery":
				$this->get_discovery($uid);
				break;
			case "timeline":
				$this->get_timeline($uid);
				break;
			case "friendPage":
				$this->get_friend_page($uid);
				break;
			case "collection":
				$this->get_collection($uid);
				break;
			case "search":
				$this->search_notice_list($uid);
			default:
				echo "error";
				break;
		}
       
        return $this->total_row;
        
    }

    function get_total_row_search($pageType,
								  $pageNumber,
								  $numberPerPage,
								  $location,
								  $searchType,
								  $searchValue)

    {

    	switch ($pageType) 
		{
			
			case "search":
				$this->search_notice_list(
										$pageNumber,
										$numberPerPage,
										$location,
										$searchType,
										$searchValue);
			break;

			default:
				echo "error";
				break;
		}
       
        return $this->total_row;
        
    }

    function array_to_str($array)
    {
    	$str = '("'.$array[0].'"';
    	foreach ($array as $key=>$value) 
    	{
    		if($key > 0)
    			$str .= ',"'.$value.'"';
    	}
    	$str .=')';
		
		return $str;
    }

	private function img_decode($array,$var_name)
	{

		foreach ($array as &$value) 
    	{
    		switch ($var_name)
			{
				case 'img_list':
					if ($value["img_list"] == '[""]')
    				{
    					$img_list = array("http://xdream.co/CI_API/upload_dir/225e97394f00e2bf3c42f34e665553c3.jpg");
    				}
    				else
    				{    		
    					$img_list = json_decode($value["img_list"]);  					
    				}
    				$value["img_list"] = $img_list;
    			break;

    			case 'car_configuration':
    				if ($value["car_configuration"] == '')
    				{
    					$car_configuration = array("abs","auto_conditioner","esp");
    				}
    				else
    				{    		
    					$car_configuration = json_decode($value["car_configuration"]);  					
    				}
    				$value["car_configuration"] = $car_configuration;
    			break;
					
			}
    		
    	}
    	return $array;
	}

	function delete_car_notice($nid)
	{
		$this->db->where('nid', $nid);
        $result = $this->db->delete($this->table);

        return $result;
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


delete_car_notice($nid)
	{
		$this->db->where('nid', $nid);
        $result = $this->db->delete($this->table);

        return $result;
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