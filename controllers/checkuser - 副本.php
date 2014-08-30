<?php

class Checkuser extends CI_controller
{
	var $returnInfo=array
	(
		"head"=>array(),	
		"body"=>array()				
	);							

	function __construct()
	{
		parent :: __construct();
		ini_set('date.timezone','Asia/Shanghai');
		$this->load->database();
	}

	function check()
	{
		//客户端传递过来的用户信息，保存在json字符串中。暂时先自建。
		$uname    = "shangmeng";
		$utime    = "14-08-28 18:40:05";
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
		$userInfo = json_encode($userInfo);	            			            				           			      	
		//$obj        = json_decode($userInfo);
		//$uname      = $obj->{"head"}->{"uname"};
		//$utime      = $obj->{"head"}->{"utime"};
		//$token      = $obj->{"head"}->{"token"};	
		//生成时间戳，int 	
	 	$utimeStamp = strtotime($utime);	
	 	$stimeStamp = strtotime("now");	
	 	var_dump($userInfo);
	 	var_dump($utime);
		var_dump($utimeStamp);		
		var_dump($stimeStamp);
		$timeDiff   = $stimeStamp - $utimeStamp; //服务器时间与客户端时间差值
		$timeError  = 300;						 //时间误差设置。
		$result=$this->query_db($uname);		 //载入数据库
		var_dump($result);

		if ($timeDiff > $timeError)
		{
			echo "Connection time-out !";
			//$returnInfo["head"]["returnCode"]        = 001;
			//$returnInfo["head"]["returnDescription"] = "Connection time-out !";
			$returnInfo["head"] = array("returnCode" => 001,"returnDescription" => "Connection time-out !");
		}
		else if ( $timeDiff < -$timeError)
		{
			echo "Carsality error !";
			//$returnInfo["head"]["returnCode"]        = 005;
			//$returnInfo["head"]["returnDescription"] = "Carsality error !";
			$returnInfo["head"] = array("returnCode" => 004,"returnDescription" => "Carsality error !");
		}
		else if ( ! is_object($result))
		{
			$returnInfo["head"] = $result;
		}
		else
		{	
			$uname     = $result->{"uname"};
			$timeStr   = substr($utime,0,-6);				
			$upassword = $result->{"upassword"};
			$returnInfo["head"]=$this->compare_md5($uname,$timeStr,$upassword,$token);
		}
		var_dump($returnInfo);
		var_dump(json_encode($returnInfo));
		return $returnInfo;
	}

	function query_db($uname)
	{			
		$this->load->model("query_model");
		$result=$this->query_model->query("carapp_userinfo","uname",$uname);		
		if( ! $result)
		{	
			echo "username don't exist!";		
			$result = array("returnCode" => 002,"returnDescription" => "username don't exist!");
		}
 		return $result;					
	}

	function compare_md5($uname,$timeStr,$upassword,$token)
	{		
		$str=$uname.$timeStr.$upassword;
		var_dump($str);
		$smd5= md5($str);
		if(0 == substr_compare($smd5,$token,0))
		{
			echo "user information correct";
			$result = array("returnCode" => 000,"returnDescription" => "user information correct");
		}
		else 
		{
			echo "password error";
			$result = array("returnCode" => 003,"returnDescription" => "password error");
		}
	return $result;
	}
}

//code end