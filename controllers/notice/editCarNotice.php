<?php
class publishCarNotice extends MY_Controller
{

	function __construct()
	{
		parent ::__construct();
		$this->load->helper( array("url","form"));
		$this->load->model("notice_model");
		$this->load->model("user_timeline_model");
		$this->load->library("upload"); 		
	}	

	function index()
	{	
		$body = $this->input->body;

		$this->title    = $body->title;
		$this->content  = $body->content;
		$this->img_list = $body->img_list;
		$car_info       = $body->car_info;
		// $this->price              = $car_info->price      ;
		// $this->market_price       = $car_info->market_price      ;
		// $this->location    		 = $car_info->location;
		// $this->brand       		 = $car_info->brand      ;
		// $this->recency	   		 = $car_info->recency	   ;
		// $this->registration_time  = $car_info->registration_time;
		// $this->speed_box     	 = $car_info->speed_box    ;
		// $this->car_number    	 = $car_info->car_number    ;		
		// $this->valid_date           = $car_info->valid_date    ;
		// $this->insurance_date           = $car_info->insurance_date    ;
		// $this->commerce_insurance_date           = $car_info->commerce_insurance_date    ;
		// $this->exchange_time           = $car_info->exchange_time    ;
		// $this->car_configuration   = $car_info->car_configuration;
		

		$is_param_ok = $this->notice_param_check();
		if($is_param_ok)
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
			$this->output->set_body("result",0);
			$this->output->set_body("description",PUBLISH_CAR_NOTICE);
		}

	}

	function view_test()
	{	
		$this->load->view('notice/publish_car_notice_view');
	}
	
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
    "uid"  : "1", 
   "time"  : "2014-08-03 03:08:05", 
   "token" : "9fd98454b511ce20120ecb593ed177e3"
  },
 "body":{    
  "title"           : "my_title",
  "content"         : "my_content",
  "img_list"        : ["http://xdream.co/CI_API/upload_dir/1303bcfae0b6c8f859bcc2aafcb2ee23.jpg","http://xdream.co/CI_API/upload_dir/225e97394f00e2bf3c42f34e665553c3.jpg"],
  "car_info":{
"price":"50",
"market_price":"70",
"location":"杭州",
"brand":"Benz",
"recency":"80",
"registration_time":"",
"speed_box":"auto",
"car_number":"AX4039",
"mileage":"2",	
"valid_date":"",          
"insurance_date":"",         
"commerce_insurance_date":"",
"exchange_time":"1",
"car_configuration":""       
}
  }
}
*/