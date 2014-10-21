
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php //echo $header; ?>
<style>
  .img{
    width: 36em;
    height: 20em;
  }
</style>


</head>

<body>
<div id="body-wrapper">
  <!-- Wrapper for the radial gradient background -->
  <div id="sidebar">
    <?php echo $sidebar?>
  </div>
  <!-- End #sidebar -->

  <div id="main-content">
    <!-- Main Content Section with everything -->
    <noscript>
    <!-- Show a notification if the user has disabled javascript -->
    <div class="notification error png_bg">
      <div> Javascript is disabled or is not supported by your browser. Please <a href="http://browsehappy.com/" title="Upgrade to a better browser">upgrade</a> your browser or <a href="http://www.google.com/support/bin/answer.py?answer=23852" title="Enable Javascript in your browser">enable</a> Javascript to navigate the interface properly.
        Download From <a href="http://www.exet.tk">exet.tk</a></div>
    </div>
    </noscript>
    <!-- Page Head -->

    <!-- End .shortcut-buttons-set -->
    <div class="clear"></div>
    <!-- End .clear -->

<div class="content-box">
  <div class="content-box-header">
    <h3>信息查看</h3>
    <h3>
    <a onclick="edit_button(<?php echo $nid; ?>)">修改</a>
    </h3>
    <h3>
    <a onclick="delete_button()">删除</a>
    </h3>
  </div>
      <!-- End .content-box-header -->
  <div class="content-box-content">

    <strong>标题：</strong>
    <?php echo $title;?>

  <br/>
  <br/>

    <strong>描述：</strong>
    <p style="text-indent:3.5em;">
    <?php echo $content;?>
    </p>

  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <thead>
      <td align="center">
        <strong>
        车主姓名
        </strong>
      </td>
      <td align="center">
        <strong>
        性别
        </strong>
      </td>
      <td align="center">
        <strong>
        车主所在地
        </strong>
      </td>
      <td align="center">
        <strong>
        品牌
        </strong>
      </td>
      <td align="center">
        <strong>
        车款
        </strong>
      </td>
    </thead>
    <tbody>
    <tr>
      <td align="center">
        <?php echo $username;?>
      </td>
   
      <td align="center">
        <?php echo $location;?>
      </td>
      <td align="center">
        <?php echo $brand;?>
      </td>

    </tr>
    </tbody>
  </table>

  <br/>

  <p>
    <strong>价格：</strong>
    <?php echo nbs(3); ?>
    <?php echo $price;?> 万
  </p>

  <p>
    <strong>市场价：</strong>
    <?php echo $market_price;?> 万
  </p>

  <!--<div>
    <br/>
    <strong>图片：</strong>
        <?php $img_array = json_decode($img_list);?>
        <?php foreach ($img_array as $img):?>
          <div class="img" style="background:url('<?php echo base_url().'/upload_dir/'.$img; ?>')left no-repeat; ">
          </div>
        <?php endforeach;?>

  </div>-->

    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tbody>
      <tr>
        <td height="50" align="center">
          <span class="sp-y">行驶里程<br>

            <strong id="mileage_output">
              <?php echo $mileage;?> 万公里
            </strong>

          </span>
        </td>
        <td height="50" align="center">
          <span class="sp-y">上牌时间<br>

            <strong id="registration_time_output">
              <?php echo $registration_time;?>
            </strong>

          </span>
        </td>
        <td height="50" align="center">
          <span class="sp-y">车牌号<br>
            <strong id="car_number_output">
              <?php echo $car_number;?>
            </strong>
          </span>
        </td>
        <td height="50" align="center">变速箱<br>

          <strong id="speed_box_output">
            <?php echo $speed_box;?>
          </strong>

        </td>
      </tr>
      <tr>
      </tr>
      <tr>
        <td height="50" align="center"><span class="sp-y">年检有效期<br>
          <strong id="valid_date_output">
    <?php echo $valid_date;?>

          </strong></span></td>
        <td height="50" align="center"><span class="sp-y">交强险有效期<br>
          <strong id="insurance_date_output">
    <?php echo $insurance_date;?>

          </strong></span></td> 
        <td height="50" align="center"><span class="sp-y">商业险有效期<br>
          <strong id="commerce_insurance_date_output">
    <?php echo $commerce_insurance_date;?>

          </strong></span></td>
        <td height="50" align="center"><span class="sp-y">过户次数<br>
          <strong id="exchange_time_output">
    <?php echo $exchange_time;?>

          </strong>
          </span>
        </td>
      </tr>
      </tbody>
  </table>

  <br/>

  配置：
  <?php

  $confs = array(
                  'abs'              => 'ABS'
                 ,'auto_conditioner' => '自动空调'
                 ,'back_video'       => '倒车影像'
                 ,'cd'               => 'CD'
                 ,'chair_warm'       => '座椅加热'
                 ,'cruise'           => '定速巡航'
                 ,'esp'              => 'ESP'
                 ,'feather_chair'    => '真皮座椅'
                 ,'full_sky_window'  => '全景天窗'
                 ,'radar'            => '倒车雷达'
                 ,'remote_key'       => '遥控钥匙'
                 ,'sky_window'       => '天窗'
                 ,'xe_light'         => '氙气灯'
                 );

  $car_configuration = explode(',', $car_configuration);

  foreach ($confs as $key => $value)
  {
    $checked = in_array($key.'1.png', $car_configuration)?'checked="checked"':null;
    echo '<label><input id="'.$key.'" '.$checked.' name="parts[]" type="checkbox" value="a" />'.$value.' </label>';
  }

  ?>

  <p>
      实物尺寸
      iPhone：115×61mm
      iPhone3G:115.5×62.1mm
      iPhone3GS:115.5x62.1mm
      iPhone4：115.2x58.6mm
      iPhone4s：115.2x58.6mm
      iPhone5：123.8x58.6mm
      像素大小
      iPhone宽为320像素，高为480像素，
  </p>

    
  </div><!-- End .content-box-content -->
</div><!-- End .content-box -->

    <?php echo $footer; ?>

  </div>
  <!-- End #main-content -->
</div>
</body>
<!-- Download From www.exet.tk-->
</html>

<script type="text/javascript">

  $(document).ready(function(){
    $('.img').css('background-position', 'left')
  });

  function delete_button(){
    if(confirm('确定要删除吗？')){
        window.location.href="<?php echo base_url().'index.php/admin/delete_article/'.$id;?>"
        return true;
    }
    return false;
  }

  function edit_button(id){
    window.location.href="<?php echo base_url().'index.php/admin/update_article/'.$id;?>"
  }

</script>