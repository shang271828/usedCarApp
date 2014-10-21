<?php
class Fetch extends CI_Controller
{
	function __construct()
	{
		parent ::__construct();
		$this->load->library('spider/charlie');
		$this->load->model('wx_article_model');
		
		$this->define();
		$this->init();
	}
	//$url = "http://www.taoche.com/buycar/b-Dealer14090411927.html"; 
	//$url = 'http://zj.hx2car.com/details/138113573';
	//$url = 'http://www.che168.com/dealer/102967/3512169.html#pvareaid=100519';
   
    function get_url_single()
    {
    	$url = $_POST['url'];
		$this->data['result'] = $this->get_url($url);
		$this->load->view('cms/edit_result_view',$this->data);
    }

	function get_url_list()
	{
		$url = $_POST['url'];
		//$url = 'http://hangzhou.taoche.com/all/?kwcity=3001&page=2';
		$url_parts = parse_url($url);		
		$base_url  = $url_parts['host'];
		$urlStartNumber = $_POST['urlStartNumber'];

		
		$charlie = new Charlie;
		
		$charlie->fetch($url);
		switch ($base_url) 
		{
			case 'hangzhou.taoche.com':
				$charlie->getTaocheUrl();
				break;

			case 'zj.hx2car.com':
				
				break;

			case 'www.che168.com':
				$charlie->getChe168Url();
				break;
			
			default:

				break;
		}
		
		$this->web = $charlie->web;

		foreach ($this->web->url_list as $key=>$value) 
		{
			if ($_POST['urlNumber'] == '全部')
				$urlNumber = 100000;
			else
				$urlNumber = $urlStartNumber + $_POST['urlNumber']-1;
			if($key >= $urlStartNumber && $key <= $urlNumber)
			{
				
				if($base_url == 'www.taoche.com'||$base_url == 'www.che168.com')
				{
					$result[] = $this->get_url($value);
				}
			}
		}
		$this->data['result'] = $result;
		$this->load->view('spider/url_list_result_view',$this->data);
	}

	function get_url($url)
	{
		//$url = 'http://www.che168.com/dealer/102967/3512169.html#pvareaid=100519';
		$url_parts = parse_url($url);
		
		$base_url  = $url_parts['host'];		
		$charlie = new Charlie;
		$charlie->fetch($url);

		switch ($base_url) 
		{
			case 'www.taoche.com':
				$charlie->getTaocheInfo();
				$this->web = $charlie->web;
				$this->generate_taoche_data();
				break;

			case 'zj.hx2car.com':
				$charlie->getHx2carInfo();
				$this->web = $charlie->web;
				$this->generate_hx2car_data();
				break;

			case 'www.che168.com':
				$charlie->getChe168Info();
				$this->web = $charlie->web;
				
				$this->generate_che168_data();
				break;
			
			default:

				break;
		}
		//var_dump($this->web->baseInfo);		
		$result = $this->wx_article_model->insert($this->car_data);
		return $result;
		
	}

	
	private function generate_taoche_data()
	{

		$baseInfo = $this->web->baseInfo;
		

		$this->get_img_str();	
		
		$this->car_data = array
					(
					 'title'            => $this->check_title($this->web->title),
					 'name'             => $this->web->seller,
					 'tel'              => $this->web->phone,
					 'region'           => $this->check_region($this->web->region),					 
					 'price'       		=> $this->web->price,
					 'market_price'     => $this->web->market_price,
					 'description'      => $this->web->description,
					 'speedbox'    		=> $baseInfo[4],
					 'brand'       		=> $baseInfo[9],
					 'version'          => $baseInfo[10],
					 'run_distance'		=> str_replace('万公里', '',$baseInfo[1]),
					 'register_date'    =>  $this->check_date($baseInfo[0]),
					 'valid_date'       =>  $this->check_date($baseInfo[12]),
					 'images'           => $this->images,
					 'configuration'    => $this->set_config()
					);
		
	}

	private function generate_hx2car_data()
	{

		$baseInfo = $this->cut_str($this->web->baseInfo);
		
		$this->get_img_str();	

		$this->car_data = array
					(
					 'title'            => $this->check_title($this->web->title),
					 'name'             => $this->web->seller,
					 'tel'              => $this->web->phone,
					 'region'           => $this->check_region($this->web->region),
					 'price'       		=> $this->web->price,
					 'market_price'     => $this->web->market_price,
					 'description'      => $this->web->description,
					 'speedbox'    		=> '自动',
					 'brand'       		=> '奔驰',
					 'version'          => $baseInfo[1],
					 'run_distance'		=> $baseInfo[2],
					 'register_date'    => $this->check_date($baseInfo[8]),
					 'valid_date'       => $this->check_date($baseInfo[9]),
					 'images'           => $this->images,
					 'configuration'    => $this->set_config()
					);			
	}

	private function generate_che168_data()
	{

		$baseInfo = $this->select_str($this->web->baseInfo);
		
		
		$this->get_img_str();	

		$this->car_data = array
					(
					 'title'           => $this->check_title($this->web->title),
					 'name'            => $this->web->seller,
					 'tel'             => $this->web->phone,
					 'region'          => $this->check_region($this->web->region),
					 'price'       		=> $this->web->price,
					 'description'      => $this->web->description,
					 'speedbox'    		=> $baseInfo[9],
					 'brand'       		=> '奔驰',
					 'version'          => $baseInfo[10],
					 'run_distance'		=> '2',
					 'register_date'    =>  '2012-3',
		
					 'valid_date'       =>  $baseInfo[0],
					 'images'           => $this->images,
					 'exchtime'         => $baseInfo[3],
					 'configuration'    => $this->set_config()
					);
		
		
	}


	function check_region($str)
	{
		$region = '浙江 杭州 ';

		$region_array = array('西湖'=>'0',
							  '拱墅'=>'1',
							  '江干'=>'2',
							  '下城'=>'3',
							  '上城'=>'4',
							  '滨江'=>'5',
							  '萧山'=>'6',
							  '余杭'=>'7');
		foreach ($region_array as  $key=>$value) 
		{
			if(strchr($str,$key))
				$region .= $key;
		}
		
		if ($region == '浙江 杭州 ')
			$region .= array_rand($region_array);
		
		return $region;
		
	}

	function check_title($str)
	{

		if(strlen($str) > 180)
			$str = substr($str,0,180);


		return $str;

	}
	//xx年xx月 转化为 date格式
	function check_date($str)
	{
		$bool = (!strchr($str,'年')) || (!strchr($str,'月'));
		if(!$bool)
		{
			$tmp_1 = explode('年', $str);
			$date[] = $tmp_1[0];
			$tmp_2 = explode('月', $tmp_1[1]);
			$date[] = $tmp_2[0];
			$date_str = $date[0].'-'.$date[1];
		}
		else if(strchr($str,'-'))
			$date_str = $str;

		else
			$date_str = '2012-09';
		return $date_str;
		
	}

	function set_config()
	{
		$configuration = array(
			'abs'				=> '0'		,
			'auto_conditioner'	=> '1'		,
			'back_video'		=> '2'	,
			'cd'				=> '3'		,
			'chair_warm'		=> '4'	,
			'auto_cruise'		=> '5'		,
			'esp'				=> '6'	,
			'feather_chair'		=> '7'		,
			'full_sky_window'	=> '8'			,
			'radar'				=> '9'	,
			'remote_key'		=> '10'		,
			'xe_light'          => '11'
			) ;

		$return_array = array_rand($configuration,7);
		
		$str = implode(',', $return_array);

		return $str;
	}

	private function select_str($str)
	{
		$str = str_replace("次（需购车时验证）"
					    	,''
					    	,$str); 
		$array = explode('：',$str);
		foreach ($array as  $key => $value) 
		{
			if($key % 2 == 1)
				$return_array[] = $value;
		}
		return $return_array;
	}
	private function cut_str($str)
	{
		$str = str_replace(array("编号：","车型","里程","排放","万公里",'燃油','用途',
								 '能否过户','能否按揭','保险情况','年审情况')
					    	,''
					    	,$str); 
		$array = explode('：', $str);
		
		return $array;
	}

    private  function get_img_str()
    {
            $this->images = implode(',',$this->web->imgPathList) ;
           // $this->images = str_replace('.', '',$this->images );
    }

	private function get_img_html()
	{
		$this->images = '';
		foreach ($this->web->imgPathList as  $img) 
		{
			$path = site_url('libraries/spider');
			$img = str_replace('.', '',$img );
			$this->images.= '<div class="img" style="background:url('
				       		.$path.$img.
				       		')center no-repeat;"></div>';
		}

	}

	function car_notice_url_single()
	{
		$this->data['action'] = 'spider/fetch/get_url_single';
		$this->load->view('spider/fetch_view',$this->data);
	}

	function car_notice_url_list()
	{
		$this->data['action'] = 'spider/fetch/get_url_list';
		$this->load->view('spider/fetch_list_view',$this->data);
	}

	private function define()
	{
		; $this->position 
			= array('1'=>'管理员'
					,'2'=>'编辑'
					,'3'=>'注册用户'	
					)

		; $this->numPerPage = 20
		;
	}
	private function init()
	{
		; $this->uid 
			= $this->input->cookie('uid')
		; $this->level 
			= $this->input->cookie('level')
		; $this->status 
			= $this->position[$this->level]
		; $this->username
			= $this->input->cookie('username')
		;		
		; $this->data = array( 'uid'=>$this->uid
						, 'level'=>$this->level
						, 'status'=>$this->status
						, 'username'=>$this->username
						, 'position'=>$this->position
						)		
		; $sidebar = $this->load->view('cms/sidebar_view', $this->data, true)
		; $this->data['sidebar'] = $sidebar
		; $header = $this->load->view('cms/header_view', $this->data, true)
		; $this->data['header'] = $header
		; $footer = '<div id="footer">
    					<small>
					    © Copyright 2014 换车吧
					</small> 
					</div>'

  		;
  		; $this->data['footer'] = $footer;
  		;

		if(!$this->uid)
		{
			header("location:".site_url('cms/login'));
		}
	}
}