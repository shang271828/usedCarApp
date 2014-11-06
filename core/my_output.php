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


    function set_body($name, $value = 0)
    {
        
        $this->json_utf8($value);
        $this->body->$name = $value;
    }

    function json_utf8(&$array)
    {
        if(is_array($array))
        {
            foreach ( $array as &$value ) 
            {
                $this->json_utf8($value);
            }
        }
        else
        {
            $array = urlencode ( $array );
        }

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

            ; $description = THROUGH_VERIFICATION
            ;
        }while(false)
        ;

        if(IS_DEBUG)
        {
            ; $description = DEBUG_ENVIRONMENT
            ;
        }
        ; $this->head->returnCode  
            = $this->sysCode
                . $this->userCode
                . $this->APICode
        ; $this->head->returnDescription = urlencode($description)
        ; $this->head->sysTime = date('Y-m-d H:i:s')
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
        $this->userCode = 0; // no error
        $this->APICode  = 0; // no error


        ; $this->errorList = array()
        ; $this->errorList['user']
            = array(
                  USERINFO_CORRECT
                 ,TIME_OUT
                 ,ID_NONEXIST
                 ,PASSWORD_INCORRECT
                 )
        ;
    }

    function _my_output()
    {   
        ; $this->is_printed = true
        ; $this->set_return_head()
        ; $this->set_output(
                urldecode(json_encode($this->res))
            )
        ;


       // echo $returnInfo = json_encode($this->res);
        if(IS_DEBUG)
        {
            ; $this->set_content_type('text/html;charset=utf-8')
            ;            

        }
        else
        {
            ; $this->set_content_type('application/json;charset=utf-8')
            ;
        }
        //header("Content-Type:text/html;charset=utf-8");

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