<?php
//获取车系品牌详细信息
class News18a
{

	function __construct()
	{
		
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

	

	function getBrand()
	{
		$tmp = $this->getInnerContentList( $this->html
									,'http://auto.news18a.com/logo'
									,'" style=');

		foreach ($tmp as $value) 
		{
			$tmp_2 = explode('"', $value);
			$logo_url[] = 'http://auto.news18a.com/logo'.$tmp_2[0];
			$brand[]    = $tmp_2[2];
		}
		
		// var_dump($brand);
		// var_dump($logo_url);
		$this->logo_url = $logo_url;
		$this->brand = $brand;

	}

	function getBrandUrlList()
	{
		$this->getBrand();
		$token = '|=|';
		foreach ($this->brand as $key => $value) 
		{

			if ($key > 0)
			{
				// $this->brand_layer[] = $this->brand[$key - 1];
				// $this->brand_url_list[]    = $this->logo_url[$key - 1];
				$tmp = $this->getInnerContentList( $this->html
											,$this->logo_url[$key-1]
											,$this->logo_url[$key]);

				$tmp_2 = explode('<h1>', $tmp[0]);  
						
						unset($tmp_2[0]);	
							
				foreach ($tmp_2 as $value_2) 
				{
					$tmp_3 = explode('</h1>', $value_2);
					// $brand_layer[] = $this->brand[$key-1].$token.$tmp_3[0];
					foreach ($tmp_3 as  $value_3) 
					{

						$tmp_4 = explode('</a>&nbsp;&nbsp;', $value_3);						
						array_pop($tmp_4);
						foreach ($tmp_4 as $key_4 => $value_4) 
						{

							$url_tmp = $this->getInnerContentList( $value_4
											,'<a href="'
											,'" title="');
							
							$this->brand_url_list[] = $url_tmp[0];
							
								$this->brand_layer[] = 
												$this->brand[$key-1].
												$token.
												$tmp_3[0].
												$token.
												strip_tags($value_4);
						}						
					}							       	
				}
			}							     
		}
	}

	function getBrandInfo()
	{
		$this->get_url();
		$this->get_title();
		$this->get_price();
		$this->saveImg();
		//$this->get_speedbox();

	}

	function get_url()
	{
		list($this->data) = $this->getInnerContentList( $this->html
									,'<!--车型列表开始-->'
									,'<!--车型列表结束-->');
		$tmp = $this->getInnerContentList( $this->data
									,'<td><a href="http://auto.news18a.com/model'
									,'" title="');
		
		foreach ($tmp as $key => $value) 
		{
			if($key%2 == 0)
			{
				$this->url_num[] = $value;
				$this->info_url[] = 'http://auto.news18a.com/model'.$value;
			}
		}
		

	}

	function get_title()
	{

		foreach ($this->url_num as $key => $value) 
		{
			list($tmp) = $this->getInnerContentList( $this->data
												,$value.'" title="'
												,'" target="_self">');
			$this->title[] = $tmp;
		}
	}

	function get_price()
	{		
		
		$tmp = $this->getInnerContentList( $this->data
									,'target="_self"><b style="color:#FF0000;">'
									,'</b></a></td>');
		$this->price = $tmp;

	}

	function getImgList()
	{
		list($this->img_data) = $this->getInnerContentList( $this->html
														,'<!--车型图片开始-->'
														,'<!--车型图片结束-->');
		$this->imgUrlList = $this->getInnerContentList( $this->img_data
									,'src="'
									,'" alt="');
		

	}


	function saveImg()
	{
		$this->getImgList();
		$dirName = $this->getNameFromUrl($this->url) ;
		
		$path = './upload_dir/';
		foreach ($this->imgUrlList as $key =>$imgUrl) 
		{
			$file_name = $this->getNameFromUrl($imgUrl);

			$file_name = $dirName.$key.$file_name;
			$img = file_get_contents($imgUrl);
			$fullPath = $path.$file_name;
			
			file_put_contents($fullPath, $img);
			$this->imgPathList[] = $file_name;
		}
	}


	function getNameFromUrl($url)
	{
		$file_name = strrchr($url, '/');
		return substr($file_name, 1);		
	}

	// 

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
    	return str_replace(array(" ","　","\t","\n","\r","’","'","&nbsp;")
					    	,''
					    	,	$str); 
	}
}

//strip_tags() 函数剥去 HTML、XML 以及 PHP 的标签
//trim() 函数从字符串的两端删除空白字符和其他预定义字符。