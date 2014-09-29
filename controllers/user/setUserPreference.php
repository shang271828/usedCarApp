<?php
class SetUserPreference extends MY_Controller
{

	function __construct()
	{
		parent ::__construct();
		$this->load->helper( array("url","form"));
		$this->load->model("user_preference_model"); 		
	}	

	function index()
	{	
		$body = $this->input->body;
		$this->price   = $body->price;
		$this->mileage = $body->mileage;
		$this->brand   = $body->brand;
		
		$brand_str = $this->array_to_string($this->brand);

		$is_param_ok = $this->user_param_check();
		if($is_param_ok)
		{
			$this->user_preference_model
				->insert($this->price
						,$this->mileage
						,$brand_str						
						);

			$this->output->set_body("result",0);
			$this->output->set_body("description","set user preference");
		}
	}

	function view_test()
	{	
		$this->load->view('user/set_user_preference_view');
	}
	
	function array_to_string($array)
	{
		if (count($array) == 1)
			$str = $array[0];
		elseif(count($array) > 1)
		{
			foreach ($array as  $value) 
			{
				$str = $array[0].','.$value;
			}
		}
		return $str;
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
"price"  : "500000-800000",
"mileage"  : "20000-80000",
"brand"  : "Benz"
  }
}

*/