<?php
header("Content-Type: text/html; charset=utf-8");
class HotSearch extends CI_Controller
{
	function __construct()
	{
		parent ::__construct();
		$this->load->database();
		$this->load->helper("form");		
	}

	function index()
	{
		//unite test
		$body = $this->input->body;
		if (property_exists ($body, 'pageNumber'))
			$this->pageNumber = $body->pageNumber;
		else					
			$this->pageNumber = '1';
		if (property_exists ($body, 'number'))
			$this->number  = $body->number;
		else						
			$this->number  = 'a';



		$is_param_ok = true ;
		
		if($is_param_ok)
		{

			$search_value = array("宝马","商务车","奔驰","二手","兰博基尼","跑车","进口");
			if($this->number !='a')
			{
			for($i=0;$i<$this->number;$i++)
				$hot_search[] = $search_value[$i];
			}
			else
			{
				$hot_search =$search_value;
			}
			$this->output->set_body("result",0);
			$this->output->set_body("description",'获得热门关键字');
			$this->output->set_body("hot_search", $hot_search);
	
		}
	}

	function view_test()
	{	
		$this->load->view('notice/get_topic_view');
	}


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
  "number"    : "3"
  }
}
*/