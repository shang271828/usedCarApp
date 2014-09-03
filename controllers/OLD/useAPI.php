<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UseAPI extends CI_Controller {
	 function __construct()
    {
    	parent :: __construct();
    	$this->load->helper('downloadWeixinFile');
    	$this->load->helper('curl');
		$this->load->library('accesstoken');
		$this->load->library('custommenu');
		$this->load->library('createqrcode');
	}
	function token(){
		$app_id="wx6b4c379a9aac4d1b";
		$app_secret="31922138df20350e9dc51053ec3a0b6d";
		$access_token=$this->accesstoken->getAccess($app_id,$app_secret);	
		return $access_token;
	}
	function createMenu(){
		$access_token=$this->token();
		echo $access_token;
		$this->custommenu->createMenuFunc($access_token);
	}
	function queryMenu(){
		$access_token=$this->token();
		$json_button=$this->custommenu->queryMenuFunc($access_token);
		var_dump($json_button);	
		//echo $json_str;
	}
	function deleteMenu(){
		$access_token=$this->token();
		$this->custommenu->deleteMenuFunc($access_token);
	}
	function getQRCode(){
		$access_token=$this->token();
		$ticket=$this->createqrcode->createTicketFunc($access_token);
		$this->createqrcode->getQRCodeFunc($ticket);
	}
}