<?php
class DropDown extends CI_Controller
{

	function __construct()
	{
		parent ::__construct();
		$this->load->database();
		$this->load->helper("form");
		$this->load->model("notice_model");
		$this->load->model("menu_model");
		
	}

	function index()
	{
		$body = $this->input->body;

		@$this->menu_type = $body->menu_type;
		
		$is_param_ok = $this->notice_param_check();
		
		if($is_param_ok)
		{
			if($this->menu_type == 'car')
			{
				$menu_list = $this->menu_model
									->get_brand();
	 				
				$this->output->set_body("result",0);
				$this->output->set_body("description",'获得车系信息');
				$this->output->set_body("menu_detail", $menu_list);
			}

			elseif ($this->menu_type == 'city') 
			{
				$city_list = $this->menu_model
									->get_province();
	 			
				$this->output->set_body("result",0);
				$this->output->set_body("description",'获得城市信息');
				$this->output->set_body("menu_detail", $city_list);
			}
			
		}
	}

	function view_test()
	{	
		$this->load->view('user/drop_down_view');
	}

	function notice_param_check()
	{
		$is_param_ok = TRUE;
		$is_param_missing  = ! ($this->menu_type);
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
//getNoticeDetail.php end
/*input :
{
 "head":{  
   "uid"          : "1",  
   "time"         : "2014-08-03 03:08:05", 
   "token"        : "9fd98454b511ce20120ecb593ed177e3"
  },
 "body":{  
  "menu_type"           : "car"
  }
}
*/