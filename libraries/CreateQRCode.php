<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 
class CreateQRCode {
	public function createTicketFunc($access_token){
		$url="https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$access_token";
		//临时二维码
		$data='{"expire_seconds": 1800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": 123}}}';
		//永久二维码
		//$data='{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": 123}}}';
		$result = https_request($url,$data);
		var_dump($result);
		$json_obj=json_decode($result);
		$ticket=$json_obj->{'ticket'};
		return $ticket;
	}
	public function getQRCodeFunc($ticket){
		$data=NULL;
		$url="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=$ticket";
		$imageInfo=downloadImage($url);
		$filename="qrcode.jpg";
		$local_file=fopen($filename,'w');
		if(false !== $local_file){
			if (false !== fwrite($local_file,$imageInfo["body"])){
				fclose($local_file);
			}
		}
		
	}
}