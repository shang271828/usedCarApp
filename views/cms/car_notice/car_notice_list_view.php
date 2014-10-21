<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php echo $header; ?>
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
      <!-- Start Content Box -->
      <div class="content-box-header">
        <h3>信息列表</h3>
        <ul class="content-box-tabs">
          <li><a href="#tab1" class="default-tab">Table</a></li>
          <!-- href must be unique and match the id of target div -->
        </ul>
        <div class="clear"></div>
      </div>
      <!-- End .content-box-header -->
      <div class="content-box-content">
        <div class="tab-content default-tab" id="tab1">

          <?php $base_url = base_url().'index.php/'; ?>
  
          <table width="60%" border="0" cellspacing="0" cellpadding="0" margin-left="0" margin-right="auto">
  <tr>
    <td>
      <label onclick = "price_popdown()">按价格查看</label>
    </br class="price_popdown">
      <a href="<?php echo $base_url; ?>admin/list_by_price_a" class="price_popdown">0-5万</a>
      </br class="price_popdown">
      <a href="<?php echo $base_url; ?>admin/list_by_price_b" class="price_popdown">5-10万</a>
      </br class="price_popdown">
      <a href="<?php echo $base_url; ?>admin/list_by_price_c" class="price_popdown">10-20万</a>
      </br class="price_popdown">
      <a href="<?php echo $base_url; ?>admin/list_by_price_d" class="price_popdown">20-50万</a>
      </br class="price_popdown">
      <a href="<?php echo $base_url; ?>admin/list_by_price_e" class="price_popdown">50万以上</a>
    </td>
    <td>
      <label onclick = "run_distance_popdown()">按里程查看</label>
      </br class="price_popdown">
      <a href="<?php echo $base_url; ?>admin/list_by_run_distance_a" class="run_distance_popdown">1-5万公里</a>
      </br class="price_popdown">
      <a href="<?php echo $base_url; ?>admin/list_by_run_distance_b" class="run_distance_popdown">5-8万公里</a>
      </br class="price_popdown">
      <a href="<?php echo $base_url; ?>admin/list_by_run_distance_c" class="run_distance_popdown">8-10万公里</a>
      </br class="price_popdown">
      <a href="<?php echo $base_url; ?>admin/list_by_run_distance_d" class="run_distance_popdown">10-20万公里</a>
    </td>
    <td>
      <label onclick = "region_popdown()">按地区查看</label>
      </br class="price_popdown">
      <a href="<?php echo $base_url; ?>admin/list_by_region_a" class="region_popdown">杭州本地</a>
      </br class="price_popdown">
      <a href="<?php echo $base_url; ?>admin/list_by_region_b" class="region_popdown">浙江省内</a>
      </br class="price_popdown">
      <a href="<?php echo $base_url; ?>admin/list_by_region_c" class="region_popdown">其它省份</a>
    </td>
    <td>
      <form action="<?php echo $base_url; ?>admin/list_by_search" method="post">
        <input type="text" name="search" value="杭州">
        <input type="submit" value="搜索"/>
      </form>
    </td>
  </tr>
</table>

<script type="text/javascript">

  function price_popdown(){
    $(".price_popdown").remove()
  };

  function run_distance_popdown(){
    $(".price_popdown").remove()
  };

  function region_popdown(){
    $(".price_popdown").remove()
  };

  function brand_popdown(){
    $(".price_popdown").remove()
  };

</script>

          <table>
            <thead>
              <tr>
                <th>标题</th>
                <th>姓名</th>
                <th>地区</th>
                <th>品牌</th>
                <th>车款</th>
                <th>上牌日期</th>
                <th>行驶里程</th>
                <th>价格</th>
                <th>编辑</th>
                <th>删除</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <td colspan="6">
                  <div class="clear"></div>
                </td>
              </tr>
            </tfoot>
            <tbody>

            <?php foreach($car_notice_list as $notice) :?>
              <tr>

                <td>
                  <a href="<?php echo site_url('cms/admin/display_car_notice_detail/'.$notice['nid'])?>">
                  <?php echo $notice['title'];?>
                  </a>
                </td>
                <td><?php echo $notice['username'];?></td>
                <td><?php echo $notice['location'];?></td>
                <td><?php echo $notice['brand'];?></td>
                <td><?php //echo $notice['version'];?></td>
                <td><?php echo $notice['registration_time'];?></td>
                <td><?php echo $notice['mileage'];?></td>
                <td><?php echo $notice['price'];?></td>
                <td>
                  <a href="<?php echo site_url('cms/admin/update_car_notice/'.$notice['nid'])?>" title="Delete">
                    <img src="<?php echo base_url()?>assets/cms/images/icons/pencil.png" alt="Delete" />
                  </a> 
                </td>
                <td>
                  <a href="<?php echo site_url('cms/admin/delete_car_notice/'.$notice['nid'])?>" title="Delete">
                    <img src="<?php echo base_url()?>assets/cms/images/icons/cross.png" alt="Delete" />
                  </a> 
                </td>
              </tr>
          <?php endforeach;?>
            </tbody>
          </table>
        </div>
        <!-- End #tab1 -->
      <?php echo $pagination; ?>
     
      </div>
      <!-- End .content-box-content -->
    </div>
    <!-- End .content-box -->
   
    <?php echo $footer; ?>

  </div>
  <!-- End #main-content -->
</div>
</body>
<!-- Download From www.exet.tk-->
</html>
