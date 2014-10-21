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

  <?php $attributes = array('id' => 'editor_submit'); echo form_open($article_action, $attributes); ?>

    
    <input type="hidden" name="aid" value="<?php echo isset($aid)?$aid:NULL?>"/>

      标题<?php echo nbs(10);?>
      <input id="title_input" size="50" type="text" name="title" value="<?php echo isset($title)?$title:NULL; ?>"/>
      <br/>

        作者
        <?php echo nbs(9);?>
        <input id="author_input" size="8" type="text" name="author" value="<?php echo isset($author)?$author:NULL; ?>"/>
        <br/>
        <?php echo nbs(10);?>

        <script id="content_input" name="content" type="text/plain">
            请输入文本内容
        </script>
    
        <script type="text/javascript" src="<?php echo base_url()?>ueditor/ueditor.config.js"></script>
    
        <script type="text/javascript" src="<?php echo base_url()?>ueditor/ueditor.all.js"></script>
          
        <script type="text/javascript">
            var ue = UE.getEditor('content_input');
        </script>

  <input type="submit" value="提交">

  </form>


  <?php echo $footer; ?>
    <!-- End #footer -->
  </div> <!-- End #main-content -->
</div> <!--End #body-warpper-->
</body>
<!-- Download From www.exet.tk-->
</html>


