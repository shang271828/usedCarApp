<!DOCTYPE html>
<html>
	<head>
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<title>拍拍店铺自检</title>
	<!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
	<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
	<script type="text/javascript">
        contextPath="";
    </script>
	
	<link rel="stylesheet" href="./styles/api.css"/>

</head>	<body>
		<!-- navigation area -->
		<!--S toolbar-->
<div class="toolbar">
    <div class="main_con">
        <div class="left">
           	欢迎使用拍拍店铺自检工具！
        </div>
        <div class="right">
            <a href="http://www.paipai.com/" target="_blank">拍拍首页</a><span class="sep">|</span>
            <a href="http://fuwu.paipai.com/" target="_blank">卖家服务市场</a><span class="sep">|</span>
        </div>
    </div>
</div>
<!--E toolbar-->

<!--S shop-->
<div class="container">
	<div class="title">
		店铺基本状况
	</div>
<p>您好，您的店铺注册于：
<?php
require_once './config.php';
require_once ROOTDIR.'src/helpers/helper.php';
require_once ROOTDIR.'src/helpers/invokeAPI.php';
require_once ROOTDIR.'src/helpers/apiTools.php';

error_reporting(E_NOTICE);

$shopInfo = shopInspec();
$saleInfo = saleInspec();
echo $shopInfo['regTime'];
echo '</p>';

echo '<p>基本信息：</p>';

echo '<pre>';
print_r($saleInfo);
echo '<p class="eval">好评数：';
echo $shopInfo['goodEval'];
echo '</p>';
echo '<p class="eval">中评数：';
echo $shopInfo['normalEval'];
echo '</p>';echo '<p class="eval">差评数：';
echo $shopInfo['badEval'];
echo '</p>';

echo '</p>';echo '<p class="eval">在售商品数：';
echo $shopInfo['itemCountOnSale'];
echo '</p>';

echo '</p>';echo '<p class="eval">货品符合度：';
echo $shopInfo['goodDescriptionMatch'];
echo '</p>';
echo '</p>';echo '<p class="eval">服务质量：';
echo $shopInfo['attitudeOfService'];
echo '</p>';
echo '</p>';echo '<p class="eval">物流速度：';
echo $shopInfo['speedOfDelivery'];
echo '</p>';
echo '</pre>';

?>

</div>
<!--E shop-->
		<!-- copyright area -->				    
	    <div class="footer">
    <div class="main_con">
        <p>
            <a href="http://www.paipai.com/sitemap.shtml?ptag=20316.38.1" target="_blank">网站地图</a> |
            <a href="http://help.paipai.com/user_agreement.shtml?ptag=20316.38.1" target="_blank">用户协议</a>
        </p>
        <p>Copyright &copy; 1998-2014 杭州傲娇服饰有限公司 xdream.co 版权所有</p>
        <p>浙江省通管局 增值电信业务经营许可证B2-20130209</p>
    </div>
</div>

	    <!-- copyright area -->		

	<!-- Load JS  -->
	<script src="/resources/js/blog.js"></script>
	
	</body>

</html>
