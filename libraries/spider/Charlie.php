<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');  
include 'Snoopy.class.php';
include 'WaterMark.php';
include 'Taoche.com.php';
include 'TaochePhone.php';
include 'Che168.com.php';
include 'Hx2car.com.php';
include 'news18a.php';
/****************
* http://www.taoche.com/
****************/

/*
图片的真实 链接 为

*/                   
                           
class Charlie
{
	var $url   = 'http://xdream.co/index.php';
	var $snoopy;
	var $waterMark;
	var $logo;
	var $imgUrlList;
	var $imgPathList;

	function __construct()
	{
		$this->snoopy = new Snoopy;
		$this->waterMark = new WaterMark;
		$this->logo = './logo.png';
	}

	function fetch($url)
	{
		$this->url = $url;
		$this->snoopy->fetch($url);

	}




	function getHx2carInfo()
	{
		$this->web = new Hx2car;
		$this->setWeb();
		$this->web->getInfo();				
	}

	function getTaocheInfo()
	{
		$this->web = new Taoche;
		$this->setWeb();
		$this->web->getInfo();				
	}

	function getChe168Info()
	{
		$this->web = new Che168;
		$this->setWeb();
		$this->web->getInfo();				
	}

	function getTaocheUrl()
	{
		$this->web = new Taoche;
		$this->setWeb();
		
		$this->web->getUrlList();	
	}

	function getChe168Url()
	{
		$this->web = new Che168;
		$this->setWeb();
		
		$this->web->getUrlList();	
	}

	function getNews18aUrl()
	{
		$this->web = new News18a;
		$this->setWeb();
		
		$this->web->getBrandUrlList();	
	}

	function getNews18Info()
	{
		$this->web = new News18a;
		$this->setWeb();
		
		$this->web->getBrandInfo();	
	}



	function saveImg()
	{
		$this->getImgList();
		$dirName = $this->getNameFromUrl($this->url);
		$this->newDir($dirName);

		$path = './'.$dirName.'/';
		foreach ($this->imgUrlList as $imgUrl) 
		{
			$file_name = $this->getNameFromUrl($imgUrl);
			$img = file_get_contents($imgUrl);
			$fullPath = $path.$file_name;
			file_put_contents($fullPath, $img);
			$this->addWaterMark($fullPath);
			$this->imgPathList[] = $fullPath;
		}
	}

	function addWaterMark($file_name)
	{
		$this->waterMark->img_water_mark( $srcImg = $file_name
										, $waterImg = $this->logo
										, $savepath=null
										, $savename=null
										, $positon=5
										, $alpha=100)
		;
	}

	// function getListUrl()
	// {

		
	// }

	// function newDir($dirName)
	// {
	// 	if(is_dir($dirName)||is_file($dirName))
	// 	{
	// 		return ;
	// 	}

	// 	mkdir($dirName);

	// }

	function getNameFromUrl($url)
	{
		$file_name = strrchr($url, '/');
		return substr($file_name, 1);		
	}


	function getInnerContentList($content,$tag_head,$tag_tail)
	{
		$res = array();
		$token = '|_|';
		$content = str_replace( array($tag_head
										,$tag_tail), 
								array($token.$tag_head
									, $tag_tail.$token),
								$content);

		$list = explode($token, $content);
		foreach ($list as $item) {

			$tmp = strstr($item,$tag_head);
			if(!$tmp){
				continue;
			}
			$tmp = str_replace(array($tag_head,$tag_tail,"\n"),
								 '',
								 $tmp);

			$res[] = trim($tmp);
		}
		return $res;

	}

	function trimAll($str)
	{
    	return str_replace(array(" ","　","\t","\n","\r")
					    	,''
					    	,	$str); 
	}

	function setWeb()
	{
		$this->web->setLogo($this->logo);
		$this->web->setUrl($this->url);
		$this->web->setHtml($this->snoopy->results);
	}
}

