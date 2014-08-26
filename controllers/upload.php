<?php

class Upload extends CI_Controller {
 
 function __construct()
 {
  parent::__construct();
  $this->load->helper( array("form", "url") );
 }
 
 function index()
 { 
  $this->load->view( "upload_form_view", array("error" => " " ));//加载upload_form_view视图文件
 }

 function do_upload()
 {
  
  $this->load->library( "upload" );//加载系统类库upload

 //自定义文件名称
  $fileNameFirst = "pic0";
  $fileNameSecond = "pic1";

  //上传图片1
  $boolFirst=$this->upload->do_upload($fileNameFirst);
  $image_data["uploadDataFirst"] = $this->upload->data();
  $boolSecond=$this->upload->do_upload($fileNameSecond);
  $image_data["uploadDataSecond"] = $this->upload->data();
  if ( !$boolFirst || !$boolSecond)
  {
   $error = array("error" => $this->upload->display_errors());
   $this->load->view("upload_form_view", $error);
  } 
  else
  { 
   $this->load->view("upload_success_view", $image_data);
  }
  //压缩图片1
  echo $image_data["uploadDataFirst"]["file_name"];
  $imageName=$image_data["uploadDataFirst"]["file_name"];
  $imagePath=$image_data["uploadDataFirst"]["file_path"];
  $imagePathNew=substr_replace( $imagePath,"",-19,2 );

  echo $imagePathNew;
  $this->compress_image_png($imageName,$imagePath,$imagePathNew);
 } 

 function compress_image_png( $imageName,$imagePath,$imagePathNew )
 {
  $file = $imagePath.$imageName; 
  $percent=1.5;
  list($width, $height) = getimagesize( $file ); //获取原图尺寸 
  $newwidth = $width * $percent; //缩放尺寸 
  $newheight = $height * $percent; 
  $src_im = imagecreatefrompng($file); 
  $dst_im = imagecreatetruecolor( $newwidth, $newheight ); 
  imagecopyresized( $dst_im, $src_im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height ); 
  imagepng( $dst_im, $imagePathNew.$imageName ); //输出压缩后的图片 
  imagedestroy( $dst_im ); 
  imagedestroy( $src_im ); 
 }

 function compress_image_jpeg( $imageName,$imagePath,$imagePathNew )
 {
  $file = $imagePath.$imageName; 
  $percent=1.5;
  list($width, $height) = getimagesize( $file ); //获取原图尺寸 
  $newwidth = $width * $percent; //缩放尺寸 
  $newheight = $height * $percent; 
  $src_im = imagecreatefromjpeg($file); 
  $dst_im = imagecreatetruecolor( $newwidth, $newheight ); 
  imagecopyresized( $dst_im, $src_im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height ); 
  imagejpeg( $dst_im, $imagePathNew.$imageName ); //输出压缩后的图片 
  imagedestroy( $dst_im ); 
  imagedestroy( $src_im ); 
 }

 function compress_image_gif( $imageName,$imagePath,$imagePathNew )
 {
  $file = $imagePath.$imageName; 
  $percent=1.5;
  list($width, $height) = getimagesize( $file ); //获取原图尺寸 
  $newwidth = $width * $percent; //缩放尺寸 
  $newheight = $height * $percent; 
  $src_im = imagecreatefromgif($file); 
  $dst_im = imagecreatetruecolor( $newwidth, $newheight ); 
  imagecopyresized( $dst_im, $src_im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height ); 
  imagegif( $dst_im, $imagePathNew.$imageName ); //输出压缩后的图片 
  imagedestroy( $dst_im ); 
  imagedestroy( $src_im ); 
 }

}
?>




