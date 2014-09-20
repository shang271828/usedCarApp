<?php
class GetMessage extends MY_Controller
{
	function __construct()
	{
		parent ::__construct();
		$this->load->database();
		$this->load->model("message_model");
		$this->load->model("user_alert_model");
	}

	function index()
	{
		//unite test
		$body = new stdClass;
		$body->pageNumber    = "2";
		$body->numberPerPage = "4"; 
		

		//work space
		$this->pageNumber    = $body->pageNumber;
		$this->numberPerPage = $body->numberPerPage;
		

		
		$message_list  = $this->message_model
							  ->get_message($this->pageNumber,
											$this->numberPerPage
										    );
		var_dump($message_list);
						
		if ($this->is_fetched == 1)
		{
			$message_all = $this->message_model->get_unread_message();
		}

		foreach ($message_all as $value)
		{
			if($value["is_fetched"] == 0)
			$this->unread_list = substr_replace($this->unread_list,
												',"'.$value["mid"].'"',
												-1,
												0);
		}
		var_dump($this->unread_list);
		$this->user_alert_model->update($this->unread_list);
	}
}