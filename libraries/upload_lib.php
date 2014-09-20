<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 
class Upload_lib
{   
    //var $image_data;
    function do_upload_one($fileName)
    {
        $CI =& get_instance();
        $CI->load->library("upload");//加载系统类库upload
        $bool            = $CI->upload->do_upload($fileName); //上传图片        
        $image_data_all  = $CI->upload->data();               //保存图片信息                            
        if ( ! $bool)                                         //判断文件是否上传成功
        {
            $error = array("error" => $CI->upload->display_errors());
        } 
        else
        { 
            $image_data_old["imageName"] = $image_data_all["file_name"];
            $image_data_old["imagePath"] = $image_data_all["file_path"];
            $image_data_old["imageType"] = $image_data_all["image_type"];                                    
            $image_data                  = $this->compress_image( $image_data_old ); //压缩图片     
        return $image_data_all;
        }  
    } 

    function compress_image($image_data_old, $width ,$height)
    {
        $imageType            = $image_data_old["imageType"];
        $imageName            = $image_data_old["imageName"];
        $imagePathOld         = $image_data_old["imagePath"];  
        $file                 = $imagePathOld.$imageName; 
        $imagePath            = substr_replace( $imagePathOld,"",-19,2 );  //压缩图片存放路径
        $percent              = 0.7;                                    //缩放尺寸
        list($width, $height) = getimagesize( $file );                  
        $newwidth             = $width * $percent;  
        $newheight            = $height * $percent; 
        $dst_im               = imagecreatetruecolor( $newwidth, $newheight ); 
        switch ($imageType) 
        {
        case "png":
            $src_im           = imagecreatefrompng($file);
            imagecopyresized( $dst_im, $src_im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height ); 
            imagepng( $dst_im, $imagePath.$imageName ); //输出压缩后的图片 
        break;
        case "jpeg":
            $src_im           = imagecreatefromjpeg($file);
            imagecopyresized( $dst_im, $src_im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height ); 
            imagejpeg( $dst_im, $imagePath.$imageName ); //输出压缩后的图片 
        break;
        case "gif":
            $src_im           = imagecreatefromgif($file);
            imagecopyresized( $dst_im, $src_im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height ); 
            imagegif( $dst_im, $imagePath.$imageName ); //输出压缩后的图片 
        break;
        default:
            echo "unknown picture format";
        }    
        imagedestroy( $dst_im ); 
        imagedestroy( $src_im ); 
        $image_data  = array
        (
            "image_name"       => $imageName, 
            "type"             => $imageType,
            "image_url"        => $imagePath.$imageName,
            "uid"              => "",
            "image_url_origin" => $imagePathOld.$imageName
        );
        return $image_data;
    }




    //  function do_upload_two($fileNameFirst,$fileNameSecond)
    // {
    //     $CI =& get_instance();
    //     $CI->load->library( "upload" );//加载系统类库upload
    //     $boolFirst  = $CI->upload->do_upload($fileNameFirst); //上传图片1        
    //     $imageDatAll["uploadDataFirst"]  = $CI->upload->data();//保存图片1信息       
    //     $boolSecond = $CI->upload->do_upload($fileNameSecond);//上传图片2               
    //     $imageDataAll["uploadDataSecond"] = $CI->upload->data();//保存图片2信息
        
    //     //判断文件是否上传成功
    //     if ( ! $boolFirst || ! $boolSecond)
    //     {
    //         $error = array("error" => $CI->upload->display_errors());
    //         $CI->load->view("upload_form_view", $error);
    //     } 
    //     else
    //     {   
    //         foreach ($imageDataAll as $key => $value) 
    //         {
    //             $imageData[$key]["imageName"] = $imageDataAll[$key]["file_name"];
    //             $imageData[$key]["imagePath"] = $imageDataAll[$key]["file_path"];
    //             $imageData[$key]["imageType"] = $imageDataAll[$key]["image_type"]; 
    //             $compress_image_data[""]=$this->compress_image( $image_data[$key] ); //压缩图片
    //         }
    //     $CI->load->view("upload_success_view", $image_data);                                    
    //     return $image_data;
    //     }  
    // } 
}
/* End of file upload_lib.php */