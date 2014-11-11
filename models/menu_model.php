<?php
class Menu_model extends CI_Model
{
	function __construct()
	{
		parent ::__construct();
		$this->load->database();
	}
	function get_letter()
	{
		$SQL = "SELECT  distinct(letter)
				FROM (`brand_info`)";
	}
	function get_brand()
	{

		$SQL = "SELECT  distinct(brand)
				FROM (`brand_info`)";
		$query = $this->db->query($SQL)	;
		$brand_list = $query->result_array();
	
		foreach ($brand_list as $value) 
		{
			$brand = $value['brand'];
			$brand_menu_second  = '';
			$brand_layer = $this->get_brand_layer($brand);
			
			foreach ($brand_layer as $value) 
			{
				$brand_menu_second[$value] = $this->get_version($brand,
																$value);
			}
	        $brand_menu[$brand] = $brand_menu_second;
		}
		return $brand_menu;
		//$this->brand = 
		//$menu_list = array('' => ,
							 //);
		//return $commentList;
	}

	private function get_brand_layer($brand)
	{
		$SQL = "SELECT  distinct(brand_layer)
				FROM (`brand_info`)
				WHERE brand ='".$brand."'";
			$query = $this->db->query($SQL)	;
		$tmp = $query->result_array();
		foreach ($tmp as  $value) 
		{
			$brand_layer[] = $value['brand_layer'];
		}
		return $brand_layer;
	}

	private function get_version($brand,$brand_layer)
	{
		$SQL = "SELECT  distinct(version)
				FROM (`brand_info`)
				WHERE brand ='".$brand."'
				AND brand_layer = '".$brand_layer."'";
		$query = $this->db->query($SQL)	;
		$tmp = $query->result_array();
		foreach ($tmp as  $value) 
		{
			$version[] = $value['version'];
		}
		return $version;
	}

	function get_province()
	{
		
		$SQL = "SELECT  ProvinceName,ProvinceID
				FROM (`S_Province`)";
		$query = $this->db->query($SQL)	;
		$province_list = $query->result_array();
	
		foreach ($province_list as $value) 
		{
			$pid = $value['ProvinceID'];
			$province = $value['ProvinceName'];
			$menu_second  = '';
			$city = $this->get_city($pid);
			
			foreach ($city as $key=>$value) 
			{
				$menu_second[$value] = $this->get_district($key);
			}
	        $menu[$province] = $menu_second;
		}
		return $menu;
		//$this->brand = 
		//$menu_list = array('' => ,
							 //);
		//return $commentList;
	}

	private function get_city($pid)
	{
		$SQL = "SELECT  CityName,CityID
				FROM (`S_City`)
				WHERE ProvinceID ='".$pid."'";
		$query = $this->db->query($SQL)	;
		$tmp = $query->result_array();
		foreach ($tmp as  $value) 
		{
			$city[$value['CityID']] = $value['CityName'];
		}
		return $city;
	}

	private function get_district($cid)
	{
		$SQL = "SELECT  DistrictName
				FROM (`S_District`)
				WHERE CityID ='".$cid."'";
		$query = $this->db->query($SQL)	;
		$tmp = $query->result_array();
		$distinct ='';
		foreach ($tmp as  $value) 
		{
			$distinct[] = $value['DistrictName'];
		}


		return $distinct;

	}

}

/*
CREATE TABLE IF NOT EXISTS `prefix_comment` (
  `cid` int(30) NOT NULL AUTO_INCREMENT,
  `title` tinytext,
  `content` text,
  `img_list` varchar(512) DEFAULT NULL,
  `parent_type` varchar(512) DEFAULT NULL,
  `pid` int(30) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  `coordinate` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
*/