<?php
/**
 * Created by JetBrains PhpStorm.
 * 说明：post 和 get方法都可以使用
 * sdk 入口文件
 * User: denniszhu
 * Date: 12-8-13
 * Time: 下午4:02
 * To change this template use File | Settings | File Templates.
 */
require_once ROOTDIR.'src/helpers/invokeAPI.php';
	
function shopInspec() {
	$Day = date('Y-m-d H:i:s');

	$link = mysql_connect(SQLSERVER, SQLUSERNAME, SQLPASSWORD);

	mysql_select_db(DBNAME, $link);
	mysql_set_charset('utf8', $link);

	$sql = "select * from xt_shopInfo where insertTime < '".$Day."';";
	$result = mysql_query($sql, $link);

	$currentInfo = mysql_fetch_assoc($result);
	$lastModify  = $currentInfo['insertTime'];
	$tl = strtotime($lastModify);
	$tn = strtotime($Day);
	$interval = $tn-$tl;
	
	if ($interval > 120) {
		$option = 'shopInfo';
		$method = 'addRecord';
		invokeAPI($option, $method);
	}

	return $currentInfo;
}

function saleInspec() {
	$Day = date('Y-m-d H:i:s');

	$link = mysql_connect(SQLSERVER, SQLUSERNAME, SQLPASSWORD);

	mysql_select_db(DBNAME, $link);
	mysql_set_charset('utf8', $link);

	$sql = "select * from xt_paipaiShopSale where insertTime < '".$Day."';";
	$result = mysql_query($sql, $link);

	$currentInfo = mysql_fetch_assoc($result);
	$lastModify  = $currentInfo['insertTime'];
	$tl = strtotime($lastModify);
	$tn = strtotime($Day);
	$interval = $tn-$tl;
	
	if ($interval > 120) {
		$option = 'shopInfo';
		$method = 'addRecord';
		invokeAPI($option, $method);
	}

	return $currentInfo;
}

?>
