<?php
class Loadshangdb extends CI_Controller {

 	function __construct()
 	{
 		parent::__construct();	
 		$this->load->model('shang_query_model'); 	
 	}
 	public function get_user()
 	{

		$json = '{"1":2}';
 		$obj = json_decode($json);
 		$uid=$obj->{"1"};
    	$res = $this->shang_query_model->shang_query($uid);
    	var_dump(json_encode($res));
    }
   	public function get_friend()
 	{
		$json = '{"1":2}';
 		$obj = json_decode($json);
 		$uid=$obj->{"1"};
    	$res = $this->shang_query_model->shang_query($uid);
    	var_dump(json_encode($res));
    }
}
?>