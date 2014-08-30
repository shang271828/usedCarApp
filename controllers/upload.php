<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

class Upload extends CI_Controller 
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper( array("form", "url") );//加载upload_form_view表单视图文件
        $this->load->database(); 
        $this->load->library( "upload" );            //加载系统类库upload
        $this->load->model("insert_model");           
    }
    
    function index()
    { 
        $this->load->view( "upload_form_one_view", array("error" => " " ));
    }
    //上传并压缩图片
    function do_upload()
    {       
        $this->load->library("upload_lib");        //自定义类库upload_lib
        $fileNameFirst  = "pic0";
        //$fileNameSecond = "pic1";
        $image_data=$this->upload_lib->do_upload_one($fileNameFirst);  
        var_dump($image_data);
        $table_name = "carapp_imageinfo";
        echo $this->insert_model->insert($table_name,$image_data);

    }
}

/* End of file upload.php */




