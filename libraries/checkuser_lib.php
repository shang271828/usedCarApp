<?php
class Checkuser_lib
{
	function check($userInfo)
	{
		ini_set('date.timezone','Asia/Shanghai');
		
		//客户端传递过来的用户信息，保存在json字符串中。			            			            				           			      	
		$obj        = json_decode($userInfo);
		$uname      = $obj->{"head"}->{"uname"};
		$utime      = $obj->{"head"}->{"utime"};
		$token      = $obj->{"head"}->{"token"};			 	
	 	$utimeStamp = strtotime($utime);	//生成时间戳，int
	 	$stimeStamp = strtotime("now");
	 	
	 	echo "client time:";
		var_dump($utimeStamp);	
		echo "server time:"	;
		var_dump($stimeStamp);
		$timeDiff   = $stimeStamp - $utimeStamp; //服务器时间与客户端时间差值
		$timeError  = 300;						 //时间误差设置。
		$result     = $this->query_db($uname);		 //载入数据库
		echo "datebase userInfo:";
		var_dump($result);

		if ($timeDiff > $timeError)
		{
			$returnInfo["head"] 
					= array("returnCode"  		=> "001",
							"returnDescription" => "error: Connection time-out !"
							);
		}
		else if ( $timeDiff < -$timeError)
		{
			$returnInfo["head"] = array("returnCode" => "004","returnDescription" => "error: Carsality error !");
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
		return $returnInfo;
	}

	private function query_db($uname)
	{	
	 	$CI =& get_instance();	
		$CI->load->model("query_model");
		$result=$CI->query_model->query("carapp_userinfo","uname",$uname);		
		if( ! $result)
		{	
			//echo "username don't exist!";		
			$result = array("returnCode" => "002","returnDescription" => "error: username don't exist!");
		}
 		return $result;					
	}

	private	function compare_md5($uname,$timeStr,$upassword,$token)
	{		
		$str=$uname.$timeStr.$upassword;
		var_dump($str);
		$smd5= md5($str);
		var_dump($smd5) ;
		if(0 == substr_compare($smd5,$token,0))
		{
			//echo "user information correct";
			$result = array("returnCode" => "000","returnDescription" => "user information correct!");
		}
		else 
		{
			//echo "password error";
			$result = array("returnCode" => "003","returnDescription" => "error: password error");
		}
	return $result;
	}
}