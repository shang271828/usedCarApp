<?php
class MY_Input extends CI_Input
{
	function __construct()
	{
		parent :: __construct();
	}
	function my_input()
	{
		//客户端传递过来的用户信息，保存在json字符串中。暂时先自建。
		$userName    = "shangmeng";
		$userTime    = "14-08-30 20:10:05";
		$userTimeStr = substr($userTime,0,-6);
		$password    = "123456";
		$token       = md5($userName.$userTimeStr.$password);
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
				"imageUrl"  => "http://xdream.co/upload/compressed_image/Newton.png",
			    "imageName" => "Newton.png"
			)
		);
		$userInfo=json_encode($userInfo);//json字符串
		// echo "input info :";
	 	// var_dump($userInfo);
		

		/////*****/////
		$json_msg = $this->get('json_msg');
		//union test
		$json_msg = $userInfo;

		$json_package = json_decode($json_msg);

		$this->json_package = & $json_package;
		$this->head = & $json_package->head;
		$this->body = & $json_package->body;
		$this->userName = & $json_package->head->userName;
		$this->userTime = & $json_package->head->userTime;
		$this->token    = & $json_package->head->token;	
		// var_dump($json_package);	
		// var_dump($this->userName);
		// var_dump($this->userTime);
		// var_dump($this->token);
	}
}
// end