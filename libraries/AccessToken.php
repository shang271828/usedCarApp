<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 
class AccessToken {
	public function getAccess($app_id,$app_secret){
		$url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$app_id&secret=$app_secret";
		$data = NULL;
		$result = https_request($url);
		$json_obj=json_decode($result, $data);
		$access_token=$json_obj->{"access_token"};
		return $access_token;
	}

}