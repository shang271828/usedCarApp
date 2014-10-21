<?php
class test extends CI_Controller
{
	function __construct()
	{
		header("Content-type: text/html; charset=utf-8");
		parent :: __construct();
		; $this->load->helper('url')
		; $this->load->model('user_model')
		; $this->load->model('article_model')
		; $this->load->library('javascript')
		//; include("Snoopy.php")
		;
	}

	function index()
	{
		//$this->load->view('js_test_view');
		//构造字符串
		echo '这里是微信后台端口~~';

 		// $url = "http://www.baidu.com";
 		
 		// $snoopy = new Snoopy;
 		// $snoopy->fetch($url); //获取所有内容
 		// var_dump($snoopy->results) ; //显示结果
 		// //可选以下
 		// $snoopy->fetchtext($url)  ;//获取文本内容（去掉html代码）
 		// var_dump($snoopy->results) ;
 		// $snoopy->fetchlinks($url) ;//获取链接
 		// var_dump($snoopy->results) ;
 		// $snoopy->fetchform($url)  ;//获取表单
 		// var_dump($snoopy->results) ;

	}

	function insert()
	{
		$article = $this->article_model->get_article_detail('2');
		var_dump($article);
	}

}
