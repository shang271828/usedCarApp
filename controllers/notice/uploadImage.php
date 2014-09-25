<?php 

class UploadImage extends MY_Controller 
{
    function __construct()
    {
        parent :: __construct();
        $this->load->helper("form");
        $this->load->helper("url");
        $this->load->database();
        $this->load->model("image_model");      
    }

    function index()
    {
        ;$body = $this->input->body
        ;$this->img_name = $body->file_name
        ;
        ;$this->img_store = $this->fetch_img_by_name($this->img_name)
        ;var_dump($this->img_store)
        ;
        ;$this->output->set_body("result",0)
        ;$this->output->set_body("description","notice published")
        ;
    }
    
    function view_test()
    {
        $this->load->view("notice/upload_image_view");        
    } 
   
    function fetch_img_by_name($img_name)
    {
        ;$base_url = 'http://xdream.co/CI_API/application/upload_dir/'
        ;var_dump($this->config)
        // ; $this->my_upload_path($this->config['upload_path'])
        ; $is_done = $this->do_upload($img_name)
        ;
        if ( ! $is_done)                                         
        {
            // ; $error = array("error" => $this->display_errors('',''))
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
                          ,'file_url'  => $base_url.$full_name
                          )
            ;
        // }
    }

    function do_upload($img_name)
    {
        ;$config['upload_path']   = './application/upload_dir'
        ;$config['allowed_types'] = 'gif|jpg|png'
        ;$config['max_size']      = '100'
        ;$config['max_width']     = '1024'
        ;$config['max_height']    = '768'

        ; $this->load->library('upload', $this->config) 
        ; $is_done = $this->upload->do_upload($img_name)
        ; return $is_done
        ;
    }

    function my_upload_path($path)
    {
        ; $this->config['upload_path'] = $path
        //; $this->initialize($this->config)
        ;
    }
}
    // private function define()
    // {
    // // ; $this->config 
    // //     = array(
    // //         'upload_path'    => './application/upload_dir'
    // //         ,'allowed_types' => 'gif|jpg|png'
    // //         ,'max_size'      => '100' //K 
    // //         ,'max_width'     => '1024'
    // //         ,'max_height'    => '768'            
    // //         )
    // // ;
    // // ;$config = $this->config
    // ;var_dump($config)
    // ;
    // }


    // function & fetch_img_accord_json($img_list_json)
    // {
    //     ; $img_list = json_decode($img_list_json)
    //     ; $res_list = array()
    //     ;
    //     foreach ($img_list as $img_name) 
    //     {
    //         ; $img = $this->fetch_img_by_name($img_name)
    //         ; 
    //         if($img)
    //         {
    //             ; array_push($res_list, $img)
    //             ;
    //         }
    //     }
    //     ; $this->img_store = $res_list
    //     ;
    // }


/* End of file upload_lib.php */

/*
{
 "head":{  
   "uid"          : "123456",  
   "time"         : "2014-08-03 03:08:05", 
   "token"        : "9fd98454b511ce20120ecb593ed177e3"
  },
 "body":{    
   "file_name"      : "pic0"
  }
}
*/    
