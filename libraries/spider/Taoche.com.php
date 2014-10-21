<?php
class Taoche
{
	var $url   = 'http://www.taoche.com/';


	function __construct()
	{
		$this->logo = './logo.png';
		$this->waterMark = new WaterMark;
	}

	function setHtml(&$html)
	{
		$this->html = &$html;
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
		$this->getRegion();
		$this->getDescription();
		$this->getBaseInfo();
		// $this->getAccessories();
		$this->getPhone();
		$this->saveImg();
	}

	function getUrlList()
	{
		$tmp = $this->getInnerContentList( $this->html
									,"<div class='carpic'>"
									,"'  VIPWT=");
		
		$this->url_list = $tmp;

	}

	function getTitle()
	{
		$list = $this->getInnerContentList( $this->html
									,'<div class="lixdianh_left">'
									,'</div>');
		$tmp = $this->getInnerContentList($list[0]
									,'<h2>'
									,'</h2>');
		$this->title = strip_tags($tmp[0]);
	}

	function getSeller()
	{
		$list = $this->getInnerContentList( $this->html
									,'<div class="lixdianh_rit"'
									,'</div>');
		$tmp = $this->getInnerContentList($list[0]
									,'<strong>'
									,'</strong>');
		$this->seller = strip_tags($tmp[0]);									
	}

	function getPrice()
	{
		$list = $this->getInnerContentList( $this->html
									,'<div class="lixdianh_left">'
									,'</div>');
		$tmp = $this->getInnerContentList($list[0]
									,'￥'
									,'万');
		$this->price = strip_tags($tmp[0]);
	}

	function getNewPrice()
	{
		list($tmp) = $this->getInnerContentList($this->html
									,'<i>新车花费：</i>'
									,'万');
		$this->market_price = strip_tags($tmp);
	}

	function getRegion()
	{
		$list = $this->getInnerContentList( $this->html
									,'<li><strong>地址：</strong>'
									,'[<a href=');
		$this->region = strip_tags($list[0]);
	}
	function getBaseInfo()
	{

		list($tmp) = $this->getInnerContentList( $this->html
									,'车源基本信息'
									,'亮点');

		$colomn = $this->getInnerContentList($tmp
									,'<span>'
									,'</span>');	
		
		$tmp = str_replace($colomn, '|', $tmp);
		$tmp = strip_tags($tmp);
		$tmp = substr($this->trimAll($tmp),1);
		// $this->baseInfo = $tmp;
		$baseInfo = explode('|', $tmp);
	
		//TODO  修改格式。
		$this->baseInfo = $baseInfo;
	}

	// function getAccessories()
	// {
	// 	list($tmp) = $this->getInnerContentList( $this->html
	// 								,'<div id="divLiangdian" class="figbox car_con sxian clearfix">'
	// 								,'</div>');
	// 	list($tmp) = $this->getInnerContentList($tmp
	// 								,'<div class="tc14-cytspz">'
	// 								,'</div>');	
	// 	$this->accessories = strip_tags($tmp);
	// }

	function getDescription()
	{
		list($tmp) = $this->getInnerContentList( $this->html
									,'<meta name="description" content='
									,' />');
		$tmp = strip_tags($tmp);
		// list($tmp) = $this->getInnerContentList($tmp
		// 							,'<div class="tc14-cytspz">'
		// 							,'</div>');
		$tmp = $this->trimAll($tmp);
		$this->description = $tmp;	

	}

	function getPhone()
	{
		list($tmp) = $this->getInnerContentList( $this->html
									,'style="background-image: url(\''
									,'\')">');
		$phoneUrl = $tmp;
		$dirName = $this->getNameFromUrl($this->url);
		$this->newDir($dirName);

		$fullPath = './'.$dirName.'/gettel.jpg';
		$file_name = $this->getNameFromUrl($phoneUrl);
		$img = file_get_contents($phoneUrl);
		file_put_contents($fullPath, $img);
		$taochePhone = new TaochePhone($fullPath);

		$this->phone = $taochePhone->getPhone();
	}

	function saveImg()
	{
		$this->getImgList();
		$dirName = str_replace('.','',$this->getNameFromUrl($this->url)) ;
		$dirName = str_replace('?','',$this->getNameFromUrl($this->url)) ;
		$dirName = str_replace('&','',$this->getNameFromUrl($this->url)) ;
		$path = './upload_dir/';
		foreach ($this->imgUrlList as $key =>$imgUrl) 
		{
			$file_name = $this->getNameFromUrl($imgUrl);
			$file_name = str_replace('?','',$this->getNameFromUrl($imgUrl)) ;
			$file_name = str_replace('&','',$this->getNameFromUrl($imgUrl)) ;
			$file_name = $dirName.$key.$file_name;
			$img = file_get_contents($imgUrl);
			$fullPath = $path.$file_name;
			
			file_put_contents($fullPath, $img);
			$this->addWaterMark($fullPath);
			$this->imgPathList[] = $file_name;
		}
	}

	function addWaterMark($file_name)
	{
		$this->waterMark->img_water_mark( $srcImg = $file_name
										, $waterImg = $this->logo
										, $savepath=null
										, $savename=null
										, $positon=5
										, $alpha=50)
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
		$list = $this->getInnerContentList( $this->html
									,'<p class="carpicbox">'
									,'</p>');

		foreach ($list as $key => $value) 
		{
			preg_match("<img.+src=\'?(.+\.(jpg|gif|bmp|bnp|png))\'?.+>",$value,$match); 
			$this->imgUrlList[] = $match[1];
		}
	}

	function getInnerContentList($content,$tag_head,$tag_tail)
	{
		$res = array();
		$token = '|_|';
		$content = str_replace( array($tag_head
									,$tag_tail), 
								array($token.$tag_head
									,$tag_tail.$token),
								$content);

		$list = explode($token, $content);
		//var_dump($list);
		foreach ($list as $item) {

			$tmp = strstr($item,$tag_head);
			if(!$tmp){
				continue;
			}
			$tmp = str_replace(array($tag_head,$tag_tail,"\n","<a href='"),
								 '',
								 $tmp);

			$res[] = trim($tmp);
		}
		return $res;

	}

	function trimAll($str)
	{
    	return str_replace(array(" ","　","\t","\n","\r","’","'")
					    	,''
					    	,	$str); 
	}
}

//strip_tags() 函数剥去 HTML、XML 以及 PHP 的标签
//trim() 函数从字符串的两端删除空白字符和其他预定义字符。