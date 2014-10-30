<?php
class publishCarNotice extends MY_Controller
{

	function __construct()
	{
		parent ::__construct();
		$this->load->helper( array("url","form"));
		$this->load->model("notice_model");
		$this->load->model("user_timeline_model");
		$this->load->model('user_relation_model');
		$this->load->model('message_model');
		$this->load->library("upload"); 		
	}	

	function index()
	{	
		$body = $this->input->body;
		if (property_exists ( $body, 'nid'))
			$this->nid  = $body->nid;
		else						
			$this->nid  = '0';
		$this->title       = $body->title;
		$this->content     = $body->content;
		$this->img_list    = $body->img_list;
		$car_info = $body->car_info;		

		$is_param_ok = $this->notice_param_check();
		if($is_param_ok)
		{
			if ($this->nid  =='0')
			{
				$nid = $this->notice_model
					 		->insert_normal_notice(  $this->title
													,$this->content
													,$this->img_list
													,"car_notice"
													);
			
				$this->notice_model
					 ->insert_car_notice($nid,
					 					 $car_info);	
					
				$this->user_timeline_model->insert($nid,"my_publish");
				/* 用户发布二手车信息推送功能 */
				$head = $this->input->head;
				$uid  = $head->uid;
				$this->send_car_message($nid, $uid);

				/* 功能模块结束 */
	
				$this->output->set_body("result",0);
				$this->output->set_body("description",PUBLISH_CAR_NOTICE);
			}
			else
			{
				$this->notice_model
					 ->update_normal_notice( $this->nid
					 						,$this->title
											,$this->content
											,$this->img_list										
											);
			
				$this->notice_model
					 ->update_car_notice($this->nid,
					 					 $car_info);	
				$this->output->set_body("result",0);
				$this->output->set_body("description",EDIT_CAR_NOTICE);
			}
		}

	}

	function view_test()
	{	
		$this->load->view('notice/publish_car_notice_view');
	}

	/* 用户发布二手车信息推送功能 */
	private function send_car_message($nid, $uid)
	{

		$friend_list = $this->user_relation_model
							    ->get_friend_list($uid);
							   
		$friend_list_initial = $friend_list->{"friend_list_initial"};
		$friend_info = json_decode(($friend_list_initial));

		$content = json_encode(
								array(
										$uid,
										$nid,
										$this->content
									)
								);		

		$this->message_model
			  ->insert_system_message($friend_info,
								$content 	   		,	
								$this->img_list     	   		 
							);
	}

	/* 功能模块结束 */

	function notice_param_check()
	{
		$is_param_ok = TRUE;
		
		$is_param_missing = ! ($this->title && $this->content && $this->img_list);
		
		do
		{
			if ($is_param_missing)
			{
				$is_param_ok = FALSE;
				$this->output->set_body("result",1);
				$this->output->set_body("description",PARAMETER_MISSING);
				break;
			}

			// if(!$this->img_list)
			// {
			// 	$is_param_ok = FALSE;
			// 	$this->output->set_body("result",2);
			// 	$this->output->set_body("description","image missing");
			// 	break;				
			// }
		}while(FALSE);
		return $is_param_ok;
	}
}
/*
{
    "head": {
        "uid": "1",
        "time": "2014-08-03 03:08:05",
        "token": "9fd98454b511ce20120ecb593ed177e3"
    },
    "body": {
        "nid": "2",
        "title": "my_title",
        "content": "my_content",
        "img_list": [
            "http://xdream.co/CI_API/upload_dir/1303bcfae0b6c8f859bcc2aafcb2ee23.jpg",
            "http://xdream.co/CI_API/upload_dir/225e97394f00e2bf3c42f34e665553c3.jpg"
        ],
        "car_info": {
            "price": "50",
            "market_price": "70",
            "location": "杭州",
            "brand": "Benz",
            "recency": "80",
            "registration_time": "",
            "speed_box": "auto",
            "car_number": "AX4039",
            "mileage": "2",
            "valid_date": "",
            "insurance_date": "",
            "commerce_insurance_date": "",
            "exchange_time": "1",
            "car_configuration": ["abs"],
            "sell_state":"2"
        }
    }
}
*/