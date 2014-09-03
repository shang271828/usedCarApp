<?php
class MY_Input extends CI_Input
{		

	
	function __construct()
	{
		parent :: __construct();
		ini_set('date.timezone','Asia/Shanghai');
	//	
	}

	function createJson()
	{
		//$CI =& get_instance();
		//$CI->load->library("upload_lib");
		//客户端传递过来的用户信息，保存在json字符串中。暂时先自建。
		$userName    = "shangmeng";
		$userTime    = date("Y-m-d H:i:s");
		$userTimeStr = substr($userTime,0,-6);
		$password    = "123";
		$token       = md5($userName.$userTimeStr.$password);
		$title       = $this->post("title");
		$content     = $this->post("content");		
		$userInfo = array
		(
			"head" => array 
			(
				"userName"  => $userName,
			    "userTime"  => $userTime,
			    "token"     => $token
			),
			"body" => array
			(
				"title"         => $title,
				"content"       => $content,
				"img_var_list"  => "pic0",
				"coordinate"    => '{"longitude":"E118", "latitude":"N32"}'
			)
		);
		$userInfo=json_encode($userInfo);//json字符串
	return $userInfo; 
	}

	function my_input($postJson)
	{
		// echo "input info :";
	 	// var_dump($userInfo);		
		/////*****/////

		//var_dump($status);
		// //union test
		$json_package = json_decode($postJson);
		$this->json_package = & $json_package;

		$this->head = & $json_package->head;
		$this->body = & $json_package->body;
		$this->userName = & $json_package->head->userName;
		$this->userTime = & $json_package->head->userTime;
		$this->token = & $json_package->head->token;

	}
	private function sys_input()
	{
		$this->sysTime = date('Y-m-d H:i:s');
	}
}
// end