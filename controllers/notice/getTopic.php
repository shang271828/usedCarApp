<?php
header("Content-Type: text/html; charset=utf-8");
class GetTopic extends CI_Controller
{
	function __construct()
	{
		parent ::__construct();
		$this->load->database();
		$this->load->helper("form");
		$this->load->model("notice_model");	
		$this->load->model("topic_model");		
	}

	function index()
	{
		//unite test
		$body = $this->input->body;
		if (property_exists ($body, 'pageNumber'))
			$this->pageNumber = $body->pageNumber;
		else					
			$this->pageNumber = '1';
		if (property_exists ($body, 'numberPerPage'))
			$this->numberPerPage  = $body->numberPerPage;
		else						
			$this->numberPerPage  = '3';



		$is_param_ok = $this->notice_param_check();
		
		if($is_param_ok)
		{

			$topic_list = $this->topic_model
							   ->get_topic_list($this->pageNumber,
										 	    $this->numberPerPage);

			//获得总信息条数
			//$total_row = $this->topic_model
			 			   	   //->get_total_row($this->pageNumber,
										 	  // $this->numberPerPage);
			 
			// 
			foreach ($topic_list as $key => &$value) 
			{
				$tmp = $this->notice_model
									->get_notice_list_nid($value['notice_list']);
				//编辑推荐车辆信息
				foreach ($tmp as $key => $notice) 
				{
					$tmp_array['nid']        = $notice['nid'];
					$tmp_array['title']        = $notice['title'];
					$tmp_array['reason']       = '推荐理由：性能卓越，外观酷炫';
					$tmp_array['brand']        = $notice['brand'];
					$tmp_array['price']        = $notice['price'];
					$tmp_array['image']        = $notice['img_list'][0];
					$tmp_array['car_location'] = $notice['car_location'];
					$notice_list[] = $tmp_array;
				}
				$value['notice_list'] = $notice_list;

			}

			if (! $topic_list)	
			{
				$this->output->set_body("result",1);
				$this->output->set_body("description",'不存在话题');
				$this->output->set_body("topic_list", $topic_list);
			}
			else
			{
				$this->output->set_body("result",0);
				$this->output->set_body("description",'获得话题信息');
				$this->output->set_body("topic_list", $topic_list);
			}		
		}
	}

	function view_test()
	{	
		$this->load->view('notice/get_topic_view');
	}

	// function verify_user()
	// {

	// 	if(!$this->is_time_ok())
	// 	{
	// 		; $this->output->set_user_code(1)
	// 		; exit()
	// 		;			
	// 	}

	// 	; $this->output->set_user_code(0)
	// 	; // through user verification
	// }

	// private function is_time_ok()
	// {
	// 	; $userTimeStamp = strtotime($this->input->userTime);	
	//  	; $nowTimeStamp  = strtotime($this->input->sysTime);

	// 	; $timeDiff   = $nowTimeStamp - $userTimeStamp;    
	// 	; $timeRange  = 300;								
	// 	; return ( ($timeDiff < $timeRange)
	// 			&& ($timeDiff > -$timeRange))
	// 	;
	// }

	function notice_param_check()
	{
		$is_param_ok = TRUE;
		$is_param_missing  = !($this->pageNumber&&$this->numberPerPage);

		do
		{
			if ($is_param_missing)
			{
				$is_param_ok = FALSE;
				$this->output->set_body("result",2);
				$this->output->set_body("description",PARAMETER_MISSING);
				break;
			}
			
		}while(FALSE);

	return $is_param_ok;
	}
}
//getNotice.php end
/*input :
{
 "head":{  
   "uid"          : "1",  
   "time"         : "2014-08-03 03:08:05", 
   "token"        : "9fd98454b511ce20120ecb593ed177e3"
  },
 "body":{  
  "pageNumber"    : "1",  
  "numberPerPage" : "8"
  }
}
*/