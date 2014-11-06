<?php
class SetUserPreference extends MY_Controller
{
	var $userInfo;
	function __construct()
	{
		parent ::__construct();
		$this->load->helper( array("url","form"));
		$this->load->model("user_preference_model"); 		
	}	

	function index()
	{	
		$body = $this->input->body;
		$this->userInfo = new StdClass();
		if (property_exists ( $body, 'price'))
			$this->userInfo->price = $body->price;
		else						
			$this->userInfo->price  = '10-2000';
		if (property_exists ( $body, 'mileage'))
			$this->userInfo->mileage  = $body->mileage;
		else						
			$this->userInfo->mileage  = '0-100';
		if (property_exists ( $body, 'brand'))
			$this->userInfo->brand  = $body->brand;
		else						
			$this->userInfo->brand  = '全部';
		if (property_exists ( $body, 'age'))
			$this->userInfo->age  = $body->age;
		else						
			$this->userInfo->age  = '0-100';
		if (property_exists ( $body, 'car_type'))
			$this->userInfo->car_type  = $body->car_type;
		else						
			$this->userInfo->car_type  = '中型车';
		if (property_exists ( $body, 'speed_box'))
			$this->userInfo->speed_box  = $body->speed_box;
		else						
			$this->userInfo->speed_box  = 'auto';
		if (property_exists ( $body, 'avatar_url'))
			$this->userInfo->avatar_url  = $body->avatar_url;
		else						
			$this->userInfo->avatar_url  = 'http://115.29.208.39/CI_API/upload_dir/u_0001_u.jpg';
		if (property_exists ( $body, 'user_age'))
			$this->userInfo->user_age  = $body->user_age;
		else						
			$this->userInfo->user_age  = '未知';
		if (property_exists ( $body, 'user_car'))
			$this->userInfo->user_car  = $body->user_car;
		else						
			$this->userInfo->user_car  = '未设置';
		if (property_exists ( $body, 'location'))
			$this->userInfo->location  = $body->location;
		else						
			$this->userInfo->location  = '中国 浙江 杭州';
		if (property_exists ( $body, 'gender'))
			$this->userInfo->gender  = $body->gender;
		else						
			$this->userInfo->gender  = '未知';

		$is_param_ok = $this->user_param_check();
		if($is_param_ok)
		{

			 $this->user_preference_model
										->insert($this->userInfo);
			

			$this->output->set_body("result",0);
			$this->output->set_body("description",SET_PREFERENCE);
		}
	}

	function view_test()
	{	
		$this->load->view('user/set_user_preference_view');
	}
	

	function user_param_check()
	{
		$is_param_ok = TRUE;
		
		// $is_param_missing = ! ($this->price && $this->mileage && $this->brand);
		
		// do
		// {
		// 	if ($is_param_missing)
		// 	{
		// 		$is_param_ok = FALSE;
		// 		$this->output->set_body("result",1);
		// 		$this->output->set_body("description","parameter missing");
		// 		break;
		// 	}
		// }while(FALSE);
		return $is_param_ok;
	}
}
/*{
 "head": {  
    "uid"  : "1", 
   "time"  : "2014-08-03 03:08:05", 
   "token" : "9fd98454b511ce20120ecb593ed177e3"
  },
 "body":{    
"price"  : "50-80",
"mileage"  : "2-8",
"age" : "1-4",
"car_type":"",
"brand"  : "Benz",
"speed_box":"",
"avatar_url":"",
"user_age":"24",
"user_car":"奥迪A7",
"gender":"男"

  }
}

*/