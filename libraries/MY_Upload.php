<?php if (!defined('APPPATH')) exit('No direct script access allowed'); 

class MY_Upload extends CI_Upload {
    function __construct()
    {
        ; parent :: __construct()
        ; $this->define()
        ;
    }

    function my_upload_path($path)
    {
        ; $this->config['upload_path'] = $path
        ; $this->initialize($this->config)
        ;
    }
    
    function set_upload_dir($path)
    {
        ; $this->config['upload_path'] = $path
        ; $this->initialize($this->config)
        ;
    }

  
    function & fetch_img_accord_json($img_list_json)
    {
        ; $img_list =  json_decode($img_list_json)
        ; $res_list = array()
        ;
        foreach ($img_list as $img_name) 
        {
            ; $img = $this->fetch_img_by_name($img_name)
            ; 
            if($img)
            {
                ; array_push($res_list, $img)
                ;
            }
        }
        ; $this->img_store = $res_list
        ; return $this->img_store
        ;


    }

    function fetch_img_by_name($img_name)
    {
        ; $this->my_upload_path($this->config['upload_path'])
        ; $is_done   = $this->do_upload($img_name);    
        ; 
        if ( ! $is_done)                                         
        {
            ; $this->error = array("error" => $this->display_errors('',''));
            ; return false
            ;
        } 
        else 
        {
            ; $file_info = $this->data()
            ; $full_name = $file_info['raw_name'].$file_info['file_ext']
            ; $full_path = $file_info['full_path']
            ; $file_size = $file_info['file_size']
            ; return array('full_name' => $full_name
                          ,'full_path' => $full_path
                          ,'file_size' => $file_size
                          ,'file_url'  => base_url().'upload_dir/'.$full_name
                          )
            ;
        }
    }

   private function define()
   {
        ; $this->config 
            = array(
                 'upload_path'   => realpath('./upload_dir')
                ,'allowed_types' => 'gif|jpg|png'
                ,'max_size'      => '100' //K 
                ,'max_width'     => '1024'
                ,'max_height'    => '768'
                ,'encrypt_name'  => true
                )
        ;
   }
}
/* End of file upload_lib.php */