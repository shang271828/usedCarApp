<?php
class Che168
{
	var $url   = 'http://www.che168.com/';


	function __construct()
	{
		$this->logo = './logo.png';
		$this->waterMark = new WaterMark;
	}

	function setHtml(&$html)
	{
		$this->html = iconv('GB2312', 'UTF-8', $html);
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
		$this->getBaseInfo();
		$this->getDescription();
		$this->getRegion();
		// $this->getAccessories();
		$this->getPhone();
		$this->saveImg();
	}

	function getUrlList()
	{
		$tmp = $this->getInnerContentList( $this->html
									,'<div class="title"><a href="'
									,'" title=');
		
		foreach ($tmp as $key => &$value) 
		{
			$value='http://www.che168.com'.$value;
		}
		$this->url_list = $tmp;
		var_dump($this->url_list);

	}

	function getTitle()
	{
		list($tmp) = $this->getInnerContentList( $this->html
									,'<div class="car-info">'
									,'</h2>');

		$this->title = $this->trimAll($tmp);
	}

	function getSeller()
	{
		$tmp = $this->getInnerContent( $this->html
									,'<div class="merchant-name">'
									,'</a>');

		$this->seller = $this->trimAll($tmp);									
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
		$this->market_price = strip_tags($tmp);
	}

	function getBaseInfo()
	{
		
		list($tmp_f) = $this->getInnerContentList( $this->html
									,'<div class="history">'
									,'<a id="m_config"');

		list($tmp_s) = $this->getInnerContentList( $this->html
									,'<div class="configuration">'
									,'<div class="clear"></div>');

		$tmp = $tmp_f.$tmp_s ;
		
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
									,'<meta name="description" content="'
									,'" />');
		$tmp = strip_tags($tmp);

		$tmp = $this->trimAll($tmp);
		$this->description = $tmp;	
		;
	}

	function getPhone()
	{
		list($tmp) = $this->getInnerContentList( $this->html
									,'<div class="num">'
									,'</div>');
		$tmp = $this->trimAll($tmp);
		$this->phone = $tmp;
	}

	function getRegion()
	{
		list($tmp) = $this->getInnerContentList( $this->html
									,'<span class="com-name-tit">'
									,'&nbsp;&nbsp;<a target="_blank"');
		$this->region = strip_tags($tmp);
		
	}

	function saveImg()
	{
		$this->getImgList();	
        $dirName = str_replace('.', '',$this->getNameFromUrl($this->url) );
        $dirName = str_replace('#', '',$this->getNameFromUrl($this->url) );
		$path = './upload_dir/';

		$snoopy = new Snoopy;
		$snoopy->referer = $this->url;
		foreach ($this->imgUrlList as $key => $imgUrl) 
		{
			$snoopy->fetch($imgUrl);
			$img = $snoopy->results;

			$file_name = $this->getNameFromUrl($imgUrl);
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
		$tmp  = $this->getInnerContent( $this->html
									,'<!--车主说明-->'
									,'<!--保障车-->');
		$list = $this->getInnerContentList( $tmp
									,'<div class="pic-box">'
									,'</div>');

		foreach ($list as $key => $value) 
		{
			$this->imgUrlList[] 
				= $this->getInnerContent($value
										,'src2="'
										,'" width="');
		}

	}

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

	function trimAll($str)
	{
		$str = str_replace('</li>', '：', $str);
		$str = strip_tags($str);
    	return str_replace(array(" ","　","\t","\n","\r",'&nbsp;')
					    	,''
					    	,	$str); 
	}
}

