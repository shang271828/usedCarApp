
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php echo $header; ?>
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
    <h3>私信查看</h3>
    <h3>
    <a onclick="edit_button(<?php echo $mid; ?>)">修改</a>
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
  <strong>发送者：</strong>
    <?php echo $destination_list;?>
  <br/>
  <br/>
  <br/>

    <strong>内容：</strong>
    <p style="text-indent:3.5em;">
    <?php echo $content;?>
    </p>

    
  </div><!-- End .content-box-content -->
</div><!-- End .content-box -->

    <?php echo $footer; ?>

  </div>
  <!-- End #main-content -->
</div>
</body>

</html>

<script type="text/javascript">

  $(document).ready(function(){
    $('.img').css('background-position', 'left')
  });

  function delete_button(){
    if(confirm('确定要删除吗？')){
        window.location.href="<?php echo base_url().'index.php/cms/admin/delete_article/'.$mid;?>"
        return true;
    }
    return false;
  }

  function edit_button(mid){
    window.location.href="<?php echo base_url().'index.php/cms/admin/update_article/'.$mid;?>"
  }

</script>