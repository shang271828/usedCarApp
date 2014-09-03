<?php
class Test extends MY_Controller
{
	function __construct()
	{
		parent ::__construct();
		$this->load->database();
	}

	function index()
	{
		$str_json = '{"head":{"returnCode":222},"body":{"result":1,"description":"notice checked","notice_list":[{"nid":"28","title":"qqq","content":"www","img_list":"eee","coordinate":null,"sortStr":"20"},{"nid":"29","title":"aaa","content":"sss","img_list":"ddd","coordinate":null,"sortStr":"10"},{"nid":"30","title":"aaa","content":"bbb","img_list":"ccc","coordinate":null,"sortStr":"10"},{"nid":"31","title":"aaa","content":"bbb","img_list":"ccc","coordinate":null,"sortStr":"10"}]}}';
		$json_obj = json_decode($str_json);
		var_dump($json_obj->body->notice_list);
	}

}