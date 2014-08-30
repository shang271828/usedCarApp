<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Output extends CI_Output {

    function __construct()
    {
        parent::__construct();              
        $this->returnCode = 222;
    }

    function setReturnCode($code)
    {
    	$this->returnCode = $code;
    }

    function my_output($returnInfo)
    {
        echo "output info:"; 
        $returnInfo=json_encode($returnInfo); 
    	var_dump($returnInfo);
    }

    function __destruct()
    {
    	if(!$this->returnCode)
    	{
    		echo "error";
    	}
    }
}