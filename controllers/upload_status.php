<?php

class Upload_status extends MY_Controller
{						
	function __construct()
	{
		parent :: __construct();
		$this->load->helper(  "url" );
        $this->load->helper(  "form" );
	 	$this->load->database();
	 	$this->load->library("upload");
	 	header("Content-type:text/html;charset=utf-8"); 
	 	//$this->load->model('user_query_model');
	 	$this->load->model('insert_model');	
    ini_set('date.timezone','Asia/Shanghai');
	}

	function index()
	{
		
		$this->load->view("upload_status_view");
		//var_dump($this->input->head);		
		//var_dump($image_data);
	}
	// function doUploadStatus()
	// {
	function do_publish()
	{	
      $returnInfo = $this->output->res;
			$returnInfo = json_encode($returnInfo);
      $returnInfo = json_decode($returnInfo,TRUE);

      $this->load->view("upload_status_success_view", $returnInfo);  
      
      $this->do_upload();      	  			            				           			      	
	}

	function do_upload()
    {   
        $this->load->library("upload_lib");  
        $imageData = $this->upload_lib->do_upload_one("pic0"); 
        var_dump($imageData);
        $table_name="prefix_user";
        $username=$this->input->userName;
        //$result = $this->user_query_model->query($table_name,"username",$username); 
        $base_url=base_url(); 
                var_dump($base_url); 

        $imageUrl=$this->output->body->{"imageUrl"};
        var_dump($imageUrl);    
        $img_list =array
        (
          "pic0"=>$base_url."upload\compressed_image"
        );
        var_dump($img_list);
        $imageList   = json_encode($img_list);
        
        var_dump($img_list);
        $notice_data["title"]      = $this->input->body->{"title"};
        $notice_data["content"]    = $this->input->body->{"content"};        
        $notice_data["img_list"]   = json_encode($img_list);
        //$notice_data["uid"]        = $result->{"uid"};  
        $notice_data["time"]       = $this->input->userTime;
        $notice_data["coordinate"] = $this->input->body->{"coordinate"};
        var_dump($notice_data);
        $table_name = "prefix_notice";
        echo $this->insert_model->insert($table_name,$notice_data);
    }

}

//code end