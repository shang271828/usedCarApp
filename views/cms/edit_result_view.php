
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
    <!-- <div class="content-box"> -->
      <!-- Start Content Box -->
      <!-- <div class="content-box-header"> -->
      
        <h3>
          <?php
            if ($result)
            {
              if ($result == 'delete')
              {
                echo '删除成功！';
              }
              else
              {

              echo '提交成功！';
              echo '<a href="';
              switch ($type) 
              {
                case 'article':
                  echo base_url().'index.php/cms/admin/display_single_article/'.$result;
                  break;
                case 'car_notice':
                  echo base_url().'index.php/cms/admin/display_car_notice_detail/'.$result;
                  break;
                case 'message':
                  echo base_url().'index.php/cms/admin/display_single_message/'.$result;
                  break; 

                default:
                  # code...
                  break;
              }
             
              echo '">';
              echo '查看';
              echo '</a>';
              }
            }
            else
            {
              echo '提交不成功，好像出错了哦！';
            }
          ?>
        </h3>
        <div class="clear"></div>

    <?php echo $footer; ?>   

  </div>
  <!-- End #main-content -->
</div>
</body>
<!-- Download From www.exet.tk-->
</html>
