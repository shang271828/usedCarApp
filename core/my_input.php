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
		$uname    = "shangmeng";
		$utime    = "14-08-29 14:28:05";
		$utimeStr = substr($utime,0,-6);
		$password = "123456";
		$token    = md5($uname.$utimeStr.$password);
		$userInfo = array
		(
			"head" => array 
			(
				"uname"  => $uname,
			    "utime"  => $utime,
			    "token"  => $token
			),
			"body" => array
			(
				"imageUrl"   => "http://xdream.co/upload/compressed_image/Newton.png",
			    "imageName"  => "Newton.png"
			)
		);
		$userInfo=json_encode($userInfo);//json字符串
		echo "input info:";
	 	var_dump($userInfo);
		return $userInfo;
	}
}
// end