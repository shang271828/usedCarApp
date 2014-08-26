<?php
/**
 * Created by JetBrains PhpStorm.
 * 说明：post 和 get方法都可以使用
 * api 入口文件
 * User: denniszhu
 * Date: 12-8-13
 * Time: 下午4:02
 * To change this template use File | Settings | File Templates.
 */
	 
function helper_init(&$api,$uri) {
		    $api->setApiPath($uri); 
    		$api->setMethod("post");//post
    		$api->setCharset("utf-8");
    		$api->setDebugOn(true);
			$api->setFormat("xml");
}

function set_params(&$api,$paramList=array()) {
	    $params = &$api->getParams();
	
		foreach($paramList as $key => $value)
		{
			$params[$key] = $value;
		}
}

function table_Info($table) {
		$link = mysql_connect(SQLSERVER, SQLUSERNAME, SQLPASSWORD);
		
		if (mysqli_connect_errno()) {
			echo 'Error: Could not connect to database. Please try again later.';
		}
	
		$fields = mysql_list_fields(DBNAME, $table, $link);
		$columns = mysql_num_fields($fields);

		for ($i = 0; $i < $columns; $i++) {
    		echo mysql_field_name($fields, $i) . "\n";
    		echo mysql_field_type($fields, $i);
    		echo "<p></p>";
		}
}

?>
