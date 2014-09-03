<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Output extends CI_Output 
{
    var $res;
    var $head;
    var $body;
    var $is_printed;
    function __construct()
    {
        parent::__construct();
        $this->is_printed = false;            
        
        $this->res  = new stdClass; 
        $this->head = new stdClass; 
        $this->body = new stdClass;

        $this->res->head = $this->head;
        $this->res->body = $this->body;

        //default set
        $this->head->returnCode = 222;     
    }

    function setReturnCode($code)
    {
        $this->head->returnCode = "0"."$code"."0";
        $this->setReturnDescription();
    }

    private function setReturnDescription()
    {
        switch ($this->head->returnCode) 
        {
            case "000":
                $returnDescription = "UserInfo correct!";
                break;
            case "010":
                $returnDescription = "Error: Connection time-out!";
                break;
            case "020":
                $returnDescription = "Error: username don't exist!!";
                break;
            case "030":
                $returnDescription = "Error: password incorrect!";
                break;             
            default:
                $returnDescription = "unknown problem";
                break;
        }
        $this->head->returnDescription = $returnDescription;        
    }

    function set_body($name, $value)
    {
        ; $this->body->$name = $value
        ;
    }


    function my_output()
    {   
        $this->is_printed = true;
        // $this->set_content_type('application/json');

        echo $returnInfo = json_encode($this->res);
        // echo "output info:"; 
        // var_dump($returnInfo);
    }

    function __destruct()
    {
        if(!$this->is_printed)
        {
            $this->my_output();
        }
    }
}

