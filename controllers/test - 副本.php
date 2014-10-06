<?php
//header("Content-Type:text/html;charset=utf-8");
class Test extends MY_Controller
{

	function __construct()
	{
		parent ::__construct();
		$this->load->database();
        $this->load->model("notice_model");
        $this->load->model("user_timeline_model");
        $this->load->library('JSON_lib');
	}

	function index()
    {
        $stooges = array('尚','Larry','Curly');
      $a = $this->json_lib->encode($stooges);
      var_dump($a);
       $b = $this->json_lib->decode($stooges);
    var_dump($b);
    }

    function array_serialize()
    {
        $stooges = array('Moe','Larry','Curly');
        $new = serialize($stooges);
        print_r($new);echo "<br />";
        print_r(unserialize($new));
    }

    function obj_serialize()
    {
        $obj = new StdClass;
        $obj->{"name"} = "尚";
        $obj->{"age"}  = "24";
        $new = serialize($obj);
        print_r($new);echo "<br />";
        print_r(unserialize($new));
        $this->obj_json($obj);
    }

    function obj_json($obj)
    {
        $json = json_encode($obj);
        print_r($json);
         print_r(json_decode($json));
    }
    function new_item()
    {
    	$this->str1 = "einstain";
    	var_dump($this->str1);
    	$this->change($this->str1) ; 

    }
}