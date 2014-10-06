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


    function set_body($name, $value_1)
    {
        if (is_array($value_1))
        {
            foreach ( $value_1 as $key_2 => &$value_2 ) 
            {  
                if(is_array($value_2))
                {
                    foreach ($value_2 as $key_3 => &$value_3) 
                    {
                        if(is_array($value_3))
                        {
                            foreach ($value_3 as $key_4 => &$value_4) 
                            {
                        
                                ;$value_3[$key_4] = urlencode ( $value_4)
                                ;  
                            }
                        }
                        else
                        {
                            ;$value_2[$key_3] = urlencode ( $value_3 )
                            ;  
                        }
                    }
                
                }
                else
                {
                    ;$value_1[$key_2] = urlencode ( $value_2 )
                    ;  
                }
                
            } 
        ; $this->body->$name = $value_1;
        ; 

        }
        else
        {
        ; $this->body->$name = urlencode($value_1)
        ;
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
            ; $this->set_content_type('application/json')
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