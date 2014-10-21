<?php
class Hx2Car
{
	var $url   = 'http://zj.hx2car.com/';


	function __construct()
	{
		$this->logo = './logo.png';
		$this->waterMark = new WaterMark;
	}

	function setHtml(&$html)
	{
		$tmp = &$html;
		$tmp = str_replace(array('_华夏二手车网','华夏二手车网'),'',$tmp);
		$this->html = $tmp;
	}

	function setUrl($url)
	{
		$this->url = $url;
	}

	function setLogo($logo)
	{
		$this->logo = $logo;
	}

	function getInfo()
	{
		$this->getTitle();
		$this->getSeller();
		$this->getPrice();
		$this->getNewPrice();
		$this->getBaseInfo();
		$this->getDescription();
		$this->getRegion();
		//$this->getAccessories();
		$this->getPhone();
		$this->saveImg();
	}

	function getUrlList()
	{
		list($tmp) = $this->getInnerContentList( $this->html
									,'<div class="cary-infor">'
									,'VIPWT=');
		
		$this->url_list = strip_tags($tmp);
	}


	function getTitle()
	{
		list($tmp) = $this->getInnerContentList( $this->html
									,'<title>'
									,'</title>');
		
		$this->title = strip_tags($tmp);
	}

	function getRegion()
	{
		list($tmp) = $this->getInnerContentList( $this->html
									,'<strong>联系地址：</strong><b>'
									,'<a href=');
		$this->region = strip_tags($tmp);
		
	}

	function getSeller()
	{
		list($tmp) = $this->getInnerContentList( $this->html
									,'联系人：'
									,'联系地址：');

		$this->seller = strip_tags($tmp);									
	}

	function getPrice()
	{
		list($tmp) = $this->getInnerContentList($this->html
									,'￥'
									,'万');
		$this->price = strip_tags($tmp);
	}

	function getNewPrice()
	{
		list($tmp) = $this->getInnerContentList($this->html
									,'<i>新车花费：</i>'
									,'万');
		$this->price = strip_tags($tmp);
	}


	function getBaseInfo()
	{

		list($tmp) = $this->getInnerContentList( $this->html
									,'<div class="li1" id="li1">'
									,'(扫描二维码，手机浏览)');


		$this->baseInfo = $this->trimAll($tmp);
	}

	function getAccessories()
	{
		list($tmp) = $this->getInnerContentList( $this->html
									,'<div id="divLiangdian" class="figbox car_con sxian clearfix">'
									,'</div>');
		list($tmp) = $this->getInnerContentList($tmp
									,'<div class="tc14-cytspz">'
									,'</div>');	
		$this->accessories = strip_tags($tmp);
	}

	function getDescription()
	{
		list($tmp) = $this->getInnerContentList( $this->html
									,'车辆描述：'
									,'</div>');
		$tmp = strip_tags($tmp);

		$tmp = $this->trimAll($tmp);
		$this->description = $tmp;	
		;
	}

	function getPhone()
	{
		list($tmp) = $this->getInnerContentList( $this->html
									,'<p class="cartel ">'
									,'预约看车');
		$tmp = $this->trimAll($tmp);
		$this->phone = $tmp;
	}

	function saveImg()
	{
		$this->getImgList();	
        $dirName = $this->getNameFromUrl($this->url);	
		$path = './upload_dir/';
		foreach ($this->imgUrlList as $key => $imgUrl) 
		{
			$file_name = $this->getNameFromUrl($imgUrl);
	
			$img = file_get_contents($imgUrl);
			$fullPath = $path. $dirName.$key.$file_name;
			file_put_contents($fullPath, $img);
			$this->addWaterMark($fullPath);
			$this->imgPathList[] = $dirName.$key.$file_name;

		}
               
	}

	function addWaterMark($file_name)
	{
		$this->waterMark->img_water_mark( $srcImg = $file_name
										, $waterImg = $this->logo
										, $savepath=null
										, $savename=null
										, $positon=5
										, $alpha=95)
		;
	}
	//创建目录
	function newDir($dirName)
	{
		if(is_dir($dirName)||is_file($dirName))
		{
			return ;
		}
		mkdir($dirName);

	}

	function getNameFromUrl($url)
	{
		$file_name = strrchr($url, '/');
		return substr($file_name, 1);		
	}

	function getImgList()
	{
		$list = $this->getInnerContentList( $this->html
									,'<p class="detail_pic">'
									,'</p>');

		foreach ($list as $key => $value) 
		{
			$this->imgUrlList[] 
				= $this->getInnerContent($value
										,'data-original="'
										,'" src=');
		}

	}
	//
	function getInnerContent($content,$tag_head,$tag_tail)
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
								 $item);
			$res = trim($tmp);
			break;
		}

		return $res;
	}
	//在head和tail里添加token，并拆分为数组
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
								 $item);
			$res[] = trim($tmp);
		}
		return $res;

	}
	//去除html元素和特殊字符
	function trimAll($str)
	{
		
		$str = strip_tags($str);
    	return str_replace(array(" ","　","\t","\n","\r",' ','&nbsp;')
					    	,''
					    	,$str); 
	}
}
