<?php

// $waterMark = new WaterMark;

// $waterMark->img_water_mark('./02140fv74k.jpg','1.png');
// var_dump(123);

class WaterMark
{
	function img_water_mark($srcImg, $waterImg, $savepath=null, $savename=null, $positon=5, $alpha=50)
	{
	    $temp = pathinfo($srcImg);
	    $name = $temp['basename'];
	    $path = $temp['dirname'];
	    $exte = $temp['extension'];
	    $savename = $savename ? $savename : $name;
	    $savepath = $savepath ? $savepath : $path;
	    $savefile = $savepath .'/'. $savename;
	    $srcinfo = @getimagesize($srcImg);
	    if (!$srcinfo) {
	        return -1; //原文件不存在
	    }
	    $waterinfo = @getimagesize($waterImg);
	    if (!$waterinfo) {
	        return -2; //水印图片不存在
	    }
	    $srcImgObj = $this->image_create_from_ext($srcImg);
	    if (!$srcImgObj) {
	        return -3; //原文件图像对象建立失败
	    }
	    $waterImgObj = $this->image_create_from_ext($waterImg);
	    if (!$waterImgObj) {
	        return -4; //水印文件图像对象建立失败
	    }
	    switch ($positon) {
	    //1顶部居左
	    case 1: $x=$y=0; break;
	    //2顶部居右
	    case 2: $x = $srcinfo[0]-$waterinfo[0]; $y = 0; break;
	    //3居中
	    case 3: $x = ($srcinfo[0]-$waterinfo[0])/2; $y = ($srcinfo[1]-$waterinfo[1])/2; break;
	    //4底部居左
	    case 4: $x = 0; $y = $srcinfo[1]-$waterinfo[1]; break;
	    //5底部居右
	    case 5: $x = $srcinfo[0]-$waterinfo[0]; $y = $srcinfo[1]-$waterinfo[1]; break;
	    default: $x=$y=0;
	    }
	    imagecopymerge($srcImgObj, $waterImgObj, $x, $y, 0, 0, $waterinfo[0], $waterinfo[1], $alpha);
	    switch ($srcinfo[2]) {
	    case 1: imagegif($srcImgObj, $savefile); break;
	    case 2: imagejpeg($srcImgObj, $savefile); break;
	    case 3: imagepng($srcImgObj, $savefile); break;
	    default: return -5; //保存失败
	    }
	    imagedestroy($srcImgObj);
	    imagedestroy($waterImgObj);
	    return $savefile;
	}
	function image_create_from_ext($imgfile)
	{
	    $info = getimagesize($imgfile);

	    $newwidth   = 660; 
		$newheight  = 440;
	    $im = null;
	    $dst_im = imagecreatetruecolor( $newwidth, $newheight );
	    switch ($info[2]) {
	    case 1: 
	    	$im=imagecreatefromgif($imgfile); 
	    	imagecopyresized( $dst_im, $im, 0, 0, 0, 0, $newwidth, $newheight, $info[0],$info[1] );
	    break;
	    case 2: 
	    	$im=imagecreatefromjpeg($imgfile); 
	    	imagecopyresized( $dst_im, $im, 0, 0, 0, 0, $newwidth, $newheight, $info[0],$info[1] );
	    break;
	    case 3: 
	    	$im=imagecreatefrompng($imgfile); 
	    	imagecopyresized( $dst_im, $im, 0, 0, 0, 0, $newwidth, $newheight, $info[0],$info[1] );
	    break;
	    }
	    return $dst_im;
	}

}