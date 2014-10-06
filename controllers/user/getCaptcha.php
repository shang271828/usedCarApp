

<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class GetCaptcha extends MY_Controller
{
						
	function __construct()
	{
		;parent :: __construct()
		;$this->load->database()
		;$this->load->helper("form")
		
		; $this->load->model('user_model')
		;
	}

	function index()
	{
		// work code

		$body = $this->input->body;
		; @$this->phone	 = $body->phone
		;

		; $is_param_ok = $this->captcha_param_check();
		if($is_param_ok)
		{
			// input ok!
			; $this->code  = rand(1000,9999)
			; $is_send = $this->send_sms($this->phone,array($this->code,'2'),"1")

			; $is_phone_exist 
				= $this->user_model->is_phone_exist($this->phone)
			;
			
			if($is_send == 0)
			{
				; $this->output->set_body('result', '2')
				; $this->output->set_body('description', SEND_ERROR)
				;		
			}
			else
			{
				if ($is_phone_exist)
				{			
					; $this->user_model->addCaptcha($this->phone,
											 		$this->code)
					;
				}
				else
				{
					; $this->user_model->updateCaptcha($this->phone,
											 			$this->code)	
					;					
				}
				; $this->output->set_body('result', '0')
				; $this->output->set_body('description', GET_CAPTCHA)
				;	
			}

				
		}
	}
	function view_test()
	{	
		$this->load->view('user/get_captcha_view');
	}

	
	private function captcha_param_check()
	{
		;$is_param_ok = true
		;
		do
		{
			; $is_param_missing = !($this->phone)
			; 
		
			if( $is_param_missing )
			{
				; $is_param_ok = false
				; $this->output->set_body('result', '1')
				; $this->output->set_body('description', PARAMETER_MISSING)
				; break
				; //function end
			}					

		}while(false)

		; return $is_param_ok
		;		
	}

	function send_sms($to,$datas,$tempId)
	{

		//主帐号,对应开官网发者主账号下的 ACCOUNT SID
		$accountSid= 'aaf98f89488d0aad0148ab8e790f0d1c';

		//主帐号令牌,对应官网开发者主账号下的 AUTH TOKEN
		$accountToken= 'fda1ca24f48c49daa959a0f5f095de53';
		
		//应用Id，在官网应用列表中点击应用，对应应用详情中的APP ID
		//在开发调试的时候，可以使用官网自动为您分配的测试Demo的APP ID
		$appId='8a48b551488d07a80148ab95f97d0d62';
		
		//请求地址
		//沙盒环境（用于应用开发调试）：sandboxapp.cloopen.com
		//生产环境（用户应用上线使用）：app.cloopen.com
		$serverIP='sandboxapp.cloopen.com';
		
		
		//请求端口，生产环境和沙盒环境一致
		$serverPort='8883';
		
		//REST版本号，在官网文档REST介绍中获得。
		$softVersion='2013-12-26';
		$params = array
		(
			"ServerIP" => $serverIP,
			"ServerPort" => $serverPort,
			"SoftVersion" => $softVersion
		);

		$this->load->library("Rest_lib",$params);
		$this->rest_lib->setAccount($accountSid,$accountToken);
     	$this->rest_lib->setAppId($appId);

     	//echo "Sending TemplateSMS to $to <br/>";
     	$result = $this->rest_lib->sendTemplateSMS($to,$datas,$tempId);

     	if($result == NULL ) {
     	   // echo "result error!";
     	    break;
     	}
     	if($result->statusCode!=0) {
     	   // echo "error code :" . $result->statusCode . "<br>";
     	   // echo "error msg :"  . $result->statusMsg  . "<br>";
     	   $is_send = 0;
     	    //TODO 添加错误处理逻辑
     	}else{
     	   // echo "Sendind TemplateSMS success!<br/>";
     	    //获取返回信息code
     	    $smsmessage = $result->TemplateSMS;
     	   // echo "dateCreated:"  .$smsmessage->dateCreated."<br/>";
     	   //  echo "smsMessageSid:".$smsmessage->smsMessageSid."<br/>";
     	    //TODO 添加成功处理逻辑
     	     $is_send = 1;    	     
     	}
     	return $is_send ;
	}
}
/*
{
 "head":{  
   "uid"          : "1",  
   "time"         : "2014-08-03 03:08:05", 
   "token"        : "9fd98454b511ce20120ecb593ed177e3"
  },
 "body":{  
  "phone"         : "13705185091"
  }
}
*/