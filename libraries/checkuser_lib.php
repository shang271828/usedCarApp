<?php
class Checkuser_lib
{
	var $userName;
	var $userTime;
	var $token;
	var $password;
	var $userCode;
	function check($userInfo)
	{
		ini_set('date.timezone','Asia/Shanghai');
		$this->getInfo($userInfo);
		$this->userCode = 0;// no error

		do{
			if( ! $this->checkTime_Ok())
			{
				$this->userCode = 1;
				break;
			}
			if ( ! $this->checkName_Ok())
			{
				$this->userCode = 2;
				break;
			}
			if ( ! $this->checkToken_Ok())
			{	
				$this->userCode = 3;
				break;
			}
		}while(FALSE);	
		
	return 	$this->userCode;
	}

	function getInfo($userInfo)
	{
		//客户端传递过来的用户信息，保存在json字符串中。
		$this->userName = $userInfo->{"head"}->{"userName"};
		$this->userTime = $userInfo->{"head"}->{"userTime"};
		$this->token    = $userInfo->{"head"}->{"token"};			 		 	
	}

	private function checkTime_Ok()
	{
		$userTimeStamp = strtotime($this->userTime);	//生成时间戳，int
	 	$sTimeStamp = strtotime("now");
		$timeDiff   = $sTimeStamp - $userTimeStamp;    //服务器时间与客户端时间差值
		$timeError  = 300;								 //时间误差设置。
		$result = ($timeDiff < $timeError && $timeDiff > -$timeError);
		return $result;
	}

	private function checkName_Ok()
	{
		$dbInfo = $this->query_db($this->userName);
		$result = is_object($dbInfo);
		if($result)
		{		
			$this->password = $dbInfo->{"password"};
		}
		return $result;
	}

	private function checkToken_Ok()
	{
		$timeStr = substr($this->userTime,0,-6); 
		$str 	 = $this->userName.$timeStr.$this->password;
	    $result  = $this->compare_md5($str,$this->token); 
	    return 	$result;				
	}

	private function query_db($userName)
	{	
	 	$CI = & get_instance();	
		$CI->load->model("user_query_model");
		$result = $CI->user_query_model->query("prefix_user","username",$userName);				
 		return $result;					
	}

	private	function compare_md5($str,$token)
	{		
		$smd5   = md5($str);				
		$result = (substr_compare($smd5,$token,0) == 0);
		return $result;
	}
}