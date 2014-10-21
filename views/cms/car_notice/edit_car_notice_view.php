<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<?php echo $header; ?>

<style>
  .img{
    width: 100%;
    height: 20em;
    float:left;
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

    <div class="content-box column-left">
      <!-- Start Content Box -->
      <div class="content-box-header">
        <h3>新增信息</h3>
        <div class="clear"></div>
      </div><!-- End .content-box-header -->

      <div class="content-box-content">

  <?php $attributes = array('id' => 'editor_submit'); echo form_open($car_notice_action, $attributes); ?>

    <input type="hidden" name="nid" value="<?php echo isset($nid)?$nid:NULL?>"/>
      标题<?php echo nbs(10);?>
    <input id="title_input" type="text" name="title" value="<?php echo isset($title)?$title:NULL; ?>"/>
    <br/>

        车主姓名
        <?php echo nbs(2);?>
        <input id="username_input" size="4" type="text" name="username" value="<?php echo isset($username)?$username:NULL; ?>"/>
 

        <br/>
        车主所在地
         <input id="province_input" name="province" type="text" size="3" /> 省
         <input id="city_input" name="city" type="text" size="3" /> 市
         <input id="district_input" name="district" type="text" size="3" /> 区

        品牌
        <?php echo nbs(1);?>
        <input id="brand_input" size="4" type="text" name="brand" value="<?php echo isset($brand)?$brand:NULL; ?>"/>
        <?php echo nbs(4);?>


   <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tbody>
        <tr>
        <td height="50" align="center">
          <span class="sp-y">行驶里程<br/>
          <input type="text"  size="2" id="mileage_input"
          name="mileage" value="<?php echo isset($mileage)?$mileage:20; ?>"/>
          万公里
          </span>
        </td>
        <td height="50" align="center">
          <span class="sp-y">上牌时间<br>
          <input type="text"  name="registration_time" size="6" id="registration_time_input"
           value="<?php echo isset($registration_time)?$registration_time:date('Y-m-d'); ?>"/>  
          </span>
        </td>
        <td height="50" align="center">
          <span class="sp-y">车牌号<br>
          <input type="text" id="car_number_input" name="car_number" size="8"
          value="<?php echo isset($car_number)?$car_number:NULL; ?>"/>
          </span>
        </td>
        <td height="50" align="center">变速箱<br>
          <select id="speed_box_input" name="speed_box" width="10em">
          <?php
            $speed_box_conf = array(
                              '自动',
                              '手动',
                              '手自一体',
                              '无级',
                              '带档位',
                              '双离合',
                              'AMT',
                              '序列'
                              );

            foreach ($speed_box_conf as $value)
            {
              echo '<option ';
              $selected = isset($speed_box)?$speed_box:'手自一体';
              if ($value == $selected)
              {
                echo 'selected="selected"';
              }
              echo '>';
              echo $value;
              echo '</option>';
            }
            ?>
          </select>
      </td>
      </tr>
      <tr>
      </tr>
      <tr>
        <td height="50" align="center">
          年检有效期<br>
          <input type="text" id="valid_date_input" name="valid_date" size="6"
          value="<?php echo isset($valid_date)?$valid_date:date('Y-m-d'); ?>"/>
        </td>
        <td height="50" align="center">
          交强险有效期<br>
          <input type="text" id="insurance_date_input" name="insurance_date" size="6"
          value="<?php echo isset($insurance_date)?$insurance_date:date('Y-m-d'); ?>"/>
        </td> 
        <td height="50" align="center">
          商业险有效期<br>
        <input type="text" id="commerce_insurance_date_input" name="commerce_insurance_date" size="6"
        value="<?php echo isset($commerce_insurance_date)?$commerce_insurance_date:date('Y-m-d'); ?>"/>
        </td>
        <td height="50" align="center">
          过户次数<br>

          <?php 
          $list = array(0,1,2,3,4,5);
          $exchange_time = isset($exchange_time)?$exchange_time:0;
          $extends = 'id="exchange_time_input"';
          echo form_dropdown('exchange_time',$list,$exchange_time, $extends);
          ?>

        </td>
      </tr>
    </tbody>
  </table>

  </br>

        价格:
        <?php echo nbs(4);?>
        <input type="text" id="price_input" name="price" size="4"
        value="<?php echo isset($price)?$price:NULL; ?>"/>
        万
        <?php echo nbs(4);?>
        市场价:
        <?php echo nbs(2);?>
         <input type="text" id="market_price_input" size="4"
         name="market_price" value="<?php echo isset($market_price)?$market_price:NULL; ?>"/>
         万
    <br/>
    <br/>
        描述：
        <br/>
        <?php echo nbs(10);?>
         <textarea type="text" id="content_input" name="content" value="<?php echo isset($content)?$content:NULL; ?>" cols="50" rows="10"></textarea>


        <!-- <textarea id="content_input" name="content" style="resize: none; width: 25em;height: 16em;">
          <?php echo isset($content)?$content:NULL; ?>
        </textarea> -->

        图片：
        <hr />
          <div class="divMain" style="height:500">
            <button id="upload">添加图片</button>
            <div class="content"></div>
            <div id="image_view">
            <?php if (isset($img_list))
            {
              $img_list = explode(',', $img_list);
              foreach ($img_list as $img)
              {
                echo '<div class="img" style="background:url(';
                echo base_url().'upload_dir/'.$img;
                echo ')left no-repeat;"><input type="button" onclick="delete_photo(this)" value="删除"></div>';
              }
            }
            ?>
            </div>
        </div>

      <input type="hidden" id="image_input" name="img_list"/>

      <?php 
      if (isset($configuration))
      {
        $car_configuration = explode(',', $configuration);
      }
      else
      {
        $car_configuration = array();
      }
      ?>
      <div class="clear"></div>
      <p>配置：</p>
        <hr/>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tbody>
            <tr>
            <td style="height:4em;width:25%">
                <img style="height:4em;width:4em;" src="<?php echo base_url().'assets/'; ?>configuration/abs1.png">
            </td>
             <td style="height:4em;width:25%">
                <img style="height:4em;width:4em;" src="<?php echo base_url().'assets/'; ?>configuration/auto_conditioner1.png">
            </td>
             <td style="height:4em;width:25%">
                <img style="height:4em;width:4em;" src="<?php echo base_url().'assets/'; ?>configuration/back_video1.png">
            </td>
             <td style="height:4em;width:25%">
                <img style="height:4em;width:4em;" src="<?php echo base_url().'assets/'; ?>configuration/cd1.png">
            </td>
          </tr>
          <tr>
            <td style="height:1em;">
              ABS
                <input type="checkbox" name="car_configuration[]" onchange="set_configuration()" value="abs"
                <?php if (in_array('abs', $car_configuration)) echo 'checked="checked"'; ?> />
            </td>
            <td style="height:1em;">
              自动空调
                <input type="checkbox" name="car_configuration[]" onchange="set_configuration()" value="auto_conditioner"
                <?php if (in_array('auto_conditioner', $car_configuration)) echo 'checked="checked"'; ?> />
            </td>
            <td style="height:1em;">
              倒车影像
                <input type="checkbox" name="car_configuration[]" onchange="set_configuration()" value="back_video"
                <?php if (in_array('back_video', $car_configuration)) echo 'checked="checked"'; ?> />
            </td>
            <td style="height:1em;">
              CD
                <input type="checkbox" name="car_configuration[]" onchange="set_configuration()" value="cd"
                <?php if (in_array('cd', $car_configuration)) echo 'checked="checked"'; ?> />
            </td>
          </tr>
          <tr>
            <td style="height:4em;width:25%">
                <img style="height:4em;width:4em;" src="<?php echo base_url().'assets/'; ?>configuration/chair_warm1.png">
            </td>
             <td style="height:4em;width:25%">
                <img style="height:4em;width:4em;" src="<?php echo base_url().'assets/'; ?>configuration/auto_cruise1.png">
            </td>
             <td style="height:4em;width:25%">
                <img style="height:4em;width:4em;" src="<?php echo base_url().'assets/'; ?>configuration/esp1.png">
            </td>
             <td style="height:4em;width:25%">
                <img style="height:4em;width:4em;" src="<?php echo base_url().'assets/'; ?>configuration/feather_chair1.png">
            </td>
          </tr>
          <tr>
            <td style="height:1em;">
              座椅加热
                <input type="checkbox" name="car_configuration[]" onchange="set_configuration()" value="chair_warm"
                <?php if (in_array('chair_warm', $car_configuration)) echo 'checked="checked"'; ?> />
            </td>
            <td style="height:1em;">
              自动巡航
                <input type="checkbox" name="car_configuration[]" onchange="set_configuration()" value="auto_cruise"
                <?php if (in_array('auto_cruise', $car_configuration)) echo 'checked="checked"'; ?> />
            </td>
            <td style="height:1em;">
              ESP
                <input type="checkbox" name="car_configuration[]" onchange="set_configuration()" value="esp"
                <?php if (in_array('esp', $car_configuration)) echo 'checked="checked"'; ?> />
            </td>
            <td style="height:1em;">
              真皮座椅
                <input type="checkbox" name="car_configuration[]" onchange="set_configuration()" value="feather_chair"
                <?php if (in_array('feather_chair', $car_configuration)) echo 'checked="checked"'; ?> />
            </td>
          </tr>
          <tr>
            <td style="height:4em;width:25%">
                <img style="height:4em;width:4em;" src="<?php echo base_url().'assets/'; ?>configuration/full_sky_window1.png">
            </td>
             <td style="height:4em;width:25%">
                <img style="height:4em;width:4em;" src="<?php echo base_url().'assets/'; ?>configuration/radar1.png">
            </td>
             <td style="height:4em;width:25%">
                <img style="height:4em;width:4em;" src="<?php echo base_url().'assets/'; ?>configuration/remote_key1.png">
            </td>
             <td style="height:4em;width:25%">
                <img style="height:4em;width:4em;" src="<?php echo base_url().'assets/'; ?>configuration/sky_window1.png">
            </td>
          </tr>
          <tr>
            <td style="height:1em;">
              全景天窗
                <input type="checkbox" name="car_configuration[]" onchange="set_configuration()" value="full_sky_window"
                <?php if (in_array('full_sky_window', $car_configuration)) echo 'checked="checked"'; ?> />
            </td>
            <td style="height:1em;">
              倒车雷达
                <input type="checkbox" name="car_configuration[]" onchange="set_configuration()" value="radar"
                <?php if (in_array('radar', $car_configuration)) echo 'checked="checked"'; ?> />
            </td>
            <td style="height:1em;">
              遥控钥匙
                <input type="checkbox" name="car_configuration[]" onchange="set_configuration()" value="remote_key"
                <?php if (in_array('remote_key', $car_configuration)) echo 'checked="checked"'; ?> />
            </td>
            <td style="height:1em;">
              天窗
                <input type="checkbox" name="car_configuration[]" onchange="set_configuration()" value="sky_window"
                <?php if (in_array('sky_window', $car_configuration)) echo 'checked="checked"'; ?> />
            </td>
          </tr>
          <tr>
            <td style="height:4em;width:25%">
                <img style="height:4em;width:4em;" src="<?php echo base_url().'assets/'; ?>configuration/xe_light1.png">
            </td>
          </tr>
          <tr>
            <td style="height:1em;">
              氙气灯
                <input type="checkbox" name="car_configuration[]" onchange="set_configuration()" value="xe_light"
                <?php if (in_array('xe_light', $car_configuration)) echo 'checked="checked"'; ?> />
            </td>
          </tr>
        </tbody>
      </table> 

      <hr/>

  <input type="submit" value="提交">

  </form>

    </div><!-- End .content-box-content -->
    <?php echo nbs(5); ?>
    <input type="submit" onclick="preview()" value="预览">
  </div>    <!-- End .content-box -->

<div class="content-box column-right"><!--Begin the preview section-->
      <div class="content-box-header">
        <h3>信息预览</h3>
      </div>
      <!-- End .content-box-header -->
      <div class="content-box-content">
    <strong>标题：</strong>
      <p id="title_output" style="text-indent:3.5em;"></p>

    <strong>描述：</strong>
      <p id="content_output" style="text-indent:3.5em;"></p>

    <br/>

    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <thead>
      <td align="center">
        <strong>
        车主姓名
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
      
    </thead>
    <tbody>
    <tr>
      <td align="center" id="name_output">
      </td>
      <td align="center" id="gender_output">
      </td>
      <td align="center" id="region_output">
      </td>
      <td align="center" id="brand_output">
      </td>
      <td align="center" id="version_output">
      </td>
    </tr>
    </tbody>
  </table>

  </br>

  <strong>价格：</strong>
    <p id="price_output" style="text-indent:3.5em;"></p>

  <strong>市场价：</strong>
    <p id="market_price_output" style="text-indent:3.5em;"></p>

  <strong>图片：</strong>
  
  </br>

  <div id="image_preview">
  </div>

  </br>

  <div class="field">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tbody><tr>
        <td height="50" align="center">
          <span class="sp-y">行驶里程<br>
            <strong id="mileage_output">
              100
            </strong>
          </span>
        </td>
        <td height="50" align="center">
          <span class="sp-y">上牌时间<br>
            <strong id="registration_time_output">

            </strong>
          </span>
        </td>
        <td height="50" align="center">
          <span class="sp-y">车牌号<br>
            <strong id="car_number_output">

            </strong>
          </span>
        </td>
        <td height="50" align="center">变速箱<br>

          <strong id="speed_box_output">

        </strong></td>
      </tr>
      <tr>
      </tr>
      <tr>
        <td height="50" align="center"><span class="sp-y">年检有效期<br>
          <strong id="valid_date_output">

          </strong></span></td>
        <td height="50" align="center"><span class="sp-y">交强险有效期<br>
          <strong id="insurance_date_output">

          </strong></span></td> 
        <td height="50" align="center"><span class="sp-y">商业险有效期<br>
          <strong id="commerce_insurance_date_output">

          </strong></span></td>
        <td height="50" align="center"><span class="sp-y">过户次数<br>
          <strong id="exchange_time_output">

          </strong>
          </span>
        </td>
      </tr>
    </tbody>
  </table>

  </div>

  <div class="field">
    配置：
    <label>ABS        <input id="abs"              name="parts[]" type="checkbox" value="a" /></label>
    <label>自动空调   <input id="auto_conditioner" name="parts[]" type="checkbox" value="a" /></label>
    <label>倒车影像   <input id="back_video"       name="parts[]" type="checkbox" value="a" /></label>
    <label>CD         <input id="cd"               name="parts[]" type="checkbox" value="a" /></label>
    <label>座椅加热   <input id="chair_warm"       name="parts[]" type="checkbox" value="a" /></label>
    <label>自动巡航   <input id="auto_cruise"      name="parts[]" type="checkbox" value="a" /></label>
    <label>ESP        <input id="esp"              name="parts[]" type="checkbox" value="a" /></label>
    <label>真皮座椅   <input id="feather_chair"    name="parts[]" type="checkbox" value="a" /></label>
    <label>全景天窗   <input id="full_sky_window"  name="parts[]" type="checkbox" value="a" /></label>
    <label>倒车雷达   <input id="radar"            name="parts[]" type="checkbox" value="a" /></label>
    <label>遥控钥匙   <input id="remote_key"       name="parts[]" type="checkbox" value="a" /></label>
    <label>天窗       <input id="sky_window"       name="parts[]" type="checkbox" value="a" /></label>
    <label>氙气灯     <input id="xe_light"         name="parts[]" type="checkbox" value="a" /></label>
  </div>

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
</div>    <!-- End .content-box -->

<div class="clear"></div>
 

  <?php echo $footer; ?>
    <!-- End #footer -->
  </div> <!-- End #main-content -->
</div> <!--End #body-warpper-->
</body>
<!-- Download From www.exet.tk-->
</html>

<script type="text/javascript">

function preview(){  //jquery获取输入信息并生成预览
  var s='';
  var conf=''; 

  $('input[name="car_configuration[]"]:checked').each(function(){ 
    s = $(this).val();
    $('#'+s).attr("checked", true)
  }); //配置复选框预览

  content = $('#content_input').val()
  $('#content_output').html(content)

  title = $('#title_input').val()
  $('#title_output').html(title)

  name = $('#name_input').val()
  $('#name_output').html(name)

  gender = $('#gender_input').val()
  $('#gender_output').html(gender)

  province = $('#province_input').val()
  city = $('#city_input').val()
  district = $('#district_input').val()

  region = province+'  '+city+'  '+district

  $('#region_output').html(region)

  brand = $('#brand_input').val()
  $('#brand_output').html(brand)

  version = $('#version_input').val()
  $('#version_output').html(version)

  price = $('#price_input').val()
  market_price = $('#market_price_input').val()
  price = price + ' 万'
  $('#price_output').html(price)
  market_price = market_price + ' 万'
  $('#market_price_output').html(market_price)

  img = $('#image_view').html()
  $('#image_preview').html(img)
  $('#image_preview .img').html('')

  mileage = $('#mileage_input').val()
  $('#mileage_output').html(mileage)

  registration_time = $('#registration_time_input').val()
  $('#registration_time_output').html(registration_time)

  car_number = $('#car_number_input').val()
  $('#car_number_output').html(car_number)

  speed_box = $('#speed_box_input').val()
  $('#speed_box_output').html(speed_box)

  valid_date = $('#valid_date_input').val()
  $('#valid_date_output').html(valid_date)

  insurance_date = $('#insurance_date_input').val()
  $('#insurance_date_output').html(insurance_date)

  commerce_insurance_date = $('#commerce_insurance_date_input').val()
  $('#commerce_insurance_date_output').html(commerce_insurance_date)

  exchange_time = $('#exchange_time_input').val()
  $('#exchange_time_output').html(exchange_time)

}

function delete_photo() //删除图片+
    {
      e = window.event
      tag = e.srcElement
      $(tag).parent('.img').remove()
      $("#image_input").val($("#image_view").html());
    }

</script>

<script type="text/javascript">

       $(document).ready(function(){ //上传图片
          $("#image_input").val($("#image_view").html());

          var button = $('#upload'), interval;
          var fileType = "all",fileNum = "one"; 
          new AjaxUpload(button,{
              action: '<?php echo $action?>',
              name: '<?php echo $file_name?>',
              onSubmit : function(file, ext){
                  if(fileType == "pic")
                  {
                      if (ext && /^(jpg|png|jpeg|gif)$/.test(ext)){
                          this.setData({
                              'info': '文件类型为图片'
                          });
                      } else {
                          $('<li></li>').appendTo('.files').text('非图片类型文件，请重传');
                          return false;               
                      }
                  }
                  button.text('文件上传中');
                  if(fileNum == 'one')
                      this.disable();
                  interval = window.setInterval(function(){
                      var text = button.text();
                      if (text.length < 14){
                          button.text(text + '.');                    
                      } else {
                          button.text('文件上传中');             
                      }
                  }, 200);
              },
              onComplete: function(file, response){//上传成功的函数；response代表服务器返回的数据
              //清楚按钮的状态
              button.text('文件上传');
                    window.clearInterval(interval);
                    this.enable();
              //修改下方div的显示文字
            if(response){
              $(".content").text("上传成功");
              $img= '<div class="img" style="background:url('+response+')left no-repeat;">'
              $img= $img+'<input type="button" onclick="delete_photo(this)" value="删除"/>'
              $img= $img+'</div>'
              $("#image_view").append($img);
              $("#image_input").val($("#image_view").html());
            }
            else
            {
              $(".content").text("上传失败");
            }
              }
          });
      });
  </script>