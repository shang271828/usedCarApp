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
        ; $this->define()
        ;
    }

    function set_user_code($code)
    {
        ; $this->userCode = $code
        ; $this->set_return_head()
        ;
    }


    function set_body($name, $value)
    {
        ; $this->body->$name = $value
        ;
    }



    
    private function set_return_head()
    {
        $description = '';
        do
        {
            if($this->sysCode)
            {
                ; $description
                    = $this->errorList['sys'][$this->sysCode]
                ; break
                ;
            }
            if($this->userCode)
            {
                ; $description 
                    = $this->errorList['user'][$this->userCode]
                ; break
                ;
            }
            if($this->APICode)
            {
                ; $description 
                    = $this->errorList['API'][$this->APICode]
                ; break
                ;
            }

            ; $description = 'through verification'
            ;
        }while(false)
        ;

        if(IS_DEBUG)
        {
            ; $description = 'debug environment'
            ;
        }
        ; $this->head->returnCode  
            = $this->sysCode
                . $this->userCode
                . $this->APICode
        ; $this->head->returnDescription = $description
        ;        
    }

    private function define()
    {
        $this->is_printed = false;            
        
        $this->res  = new stdClass; 
        $this->head = new stdClass; 
        $this->body = new stdClass;

        $this->res->head = $this->head;
        $this->res->body = $this->body;

        //default set
        $this->sysCode  = 0; // no error
        $this->userCode = 1; // Connection time-out!
        $this->APICode  = 0; // no error


        ; $this->errorList = array()
        ; $this->errorList['user']
            = array(
                  "UserInfo correct!"
                 ,"Error: Connection time-out!"
                 ,"Error: user id don't exist!!"
                 ,"Error: password incorrect!"
                 )
        ;
    }

    function _my_output()
    {   
        ; $this->is_printed = true
        ; $this->set_return_head()
        ; $this->set_output(
                json_encode($this->res)
            )
        ;


       // echo $returnInfo = json_encode($this->res);
        if(IS_DEBUG)
        {
            ; $this->set_content_type('text/html')
            ;            

        }
        else
        {
            ; $this->set_content_type('application/json')
            ;
        }

    }
    function __destruct()
    {
        if(!$this->is_printed)
        {
            $this->_my_output();
        }
        $this->_display();
    }
}