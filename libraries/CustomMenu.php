<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 
header("Content-type: text/html; charset=utf-8");
class CustomMenu {
	public function createMenuFunc($access_token){
		$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=$access_token";
		$button='{
     	"button":[
     		{	
          "type":"click",
          "name":"热卖款式",
          "key":"V1001_TODAY_MUSIC"
      		},
      		{
           "type":"click",
           "name":"定制推荐",
           "key":"V1001_TODAY_SINGER"
      		},
      		{
           "name":"菜单",
           "sub_button":[
           {	
               "type":"view",
               "name":"汐然旗舰店",
               "url":"http://s.taobao.com/search?q=%CF%AB%C8%BB%C6%EC%BD%A2%B5%EA&rs=up&rsclick=1"
            },
            {
               "type":"view",
               "name":"子佩服饰",
               "url":"http://shop105247637.taobao.com/?spm=a230r.7195193.1997079397.2.XDUyUO"
            },
            {
               "type":"view",
               "name":"雪儿的衣柜",
               "url":"http://shop103561891.taobao.com/?spm=a230r.7195193.1997079397.2.GkFwXc"
            },
            {
               "type":"click",
               "name":"赞一下我们",
               "key":"V1001_GOOD"
            }]
      	 	}]
 		}';
		$result = https_request($url,$button);
		$json_obj=json_decode($result);
		echo var_dump($json_obj);
	}
	public function queryMenuFunc($access_token){
		$data=NULL;
		$url="https://api.weixin.qq.com/cgi-bin/menu/get?access_token=$access_token";
		$result = https_request($url,$data);
    return $result;
		//$json_str=json_decode($result,true);
  }
	public function deleteMenuFunc($access_token){
    $data=NULL;
    $url="https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=$access_token";
    $result = https_request($url,$data);
    $json_obj=json_decode($result);
    echo var_dump($json_obj);
  }
  
}