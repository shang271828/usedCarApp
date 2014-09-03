<?php
class Tradelist extends CI_Controller {

 	function __construct()
 	{
 		parent::__construct();
 	 	header("Content-type:text/html;charset=utf-8"); 	 	
 	}

    public function index()
    {
	$uin         = "2755922892";
	$appOAuthID  = "700204707";
	$appOAuthkey = "3NywjK7UyyerbWpD";
	$accessToken = "0d7dcbcece5c7655f30f83b6a7054385";
//相应api的uri
	$uriList = array('shopInfo'				=>"/shop/getShopInfo.xhtml", 
					 'userInfo'				=>"/user/getUserInfo.xhtml",
					 'paipaiShopSale'		=>"/v2/report/getPaipaiShopSale.xhtml", 
			 		 'sellerSearchItemList'	=>"/item/sellerSearchItemList.xhtml", 
			 		 'item'					=>"/item/getItem.xhtml", 
			 		 'commodityVisitTrade'	=>"/v2/report/getCommodityVisitTrade.xhtml"
			 	);
//api应用参数
	$paramList = array(
		'shopInfo' 				=> array('sellerUin'=>"2755922892"),
		'userInfo' 				=> array('userUin'  =>"2755922892"),
		'paipaiShopSale' 		=> array('sellerUin'=>"2755922892", 
										 'startDay' =>"20140728", 
							   			 'endDay'   =>"20140728",
		                             	 'pageIndex'=>"5", 
		                                 'pageNum'  =>"20"
		                                 ),
		'sellerSearchItemList'	=> array('sellerUin'=>"2755922892",
										 'pageIndex'=>"1",
										 'pageSize' =>"30"
										 ),
		'item' 					=> array('itemCode' =>'CC0B44A400000000040100003C39BA18'),
		'commodityVisitTrade' 	=> array('sellerUin'=>"2755922892", 
										 'startDay' =>"20140801", 
										 'endDay'   =>"20140806",
		                                 'pageIndex'=>"1", 
		                                 'pageNum'  =>"30"
		                                 )
					);

//set 初始参数 
	$this->load->library('PaiPaiOpenApiOauth');
	$this->PaiPaiOpenApiOauth->setApiPath($uri);
	$this->PaiPaiOpenApiOauth->setMethod("post");
	$this->PaiPaiOpenApiOauth->setCharset("utf-8");
	$this->PaiPaiOpenApiOauth->setDebugOn(true);
	$this->PaiPaiOpenApiOauth->setFormat("xml");
	
  
	$params = &$this->PaiPaiOpenApiOauth->getParams();
	foreach($paramList as $key => $value)
	{
		$params[$key] = $value;
	}
//load database
	$this->load->model('TradeList');
	$this->TradeList->function();
 	}
}
?> 