<?php

class Test extends MY_Controller
{
	function __construct()
	{
		parent ::__construct();
		$this->load->database();
        $this->load->model("notice_model");
        $this->load->model("user_timeline_model");

	}

	function index()
    {
        $this->notice_model->test('1','4');
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