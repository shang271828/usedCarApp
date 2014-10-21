
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
 
   <div class="clear"></div>

    <div class="content-box column-left">
      <!-- Start Content Box -->
      <div class="content-box-header">
        <h3>发送私信</h3>
        <div class="clear"></div>
      </div><!-- End .content-box-header -->

      <div class="content-box-content">

  <?php $attributes = array('id' => 'editor_submit'); echo form_open($action, $attributes); ?>

    
    <input type="hidden" name="mid" value="<?php echo isset($mid)?$mid:NULL?>"/>

      收件人名称。多位收件人用，隔开。
      <?php echo nbs(10);?>
      <input id="destination_list_input" size="50" type="text" name="destination_list" value='[""]'/>
      <br/>

        内容

        <?php echo nbs(9);?>
        <input id="title_input" size="50" type="text" name="title" value="<?php echo isset($title)?$title:NULL; ?>"/>
        <br/>



  <input type="submit" value="提交">

  </form>
    

    <?php echo $footer; ?>   

  </div>
  <!-- End #main-content -->
</div>

</body>
<!-- Download From www.exet.tk-->
</html>
