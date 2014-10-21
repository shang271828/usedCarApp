
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


    <div class="clear"></div>

   
<div id="login-wrapper" class="png_bg">

  <!-- End #logn-top -->
  <div id="">
    <form action="<?php echo site_url('cms/user/add_user');?>" method="post">
      <div class="notification information png_bg">
        <div> 用户添加 </div>
      </div>
      <p>
        <label>用户名</label>
        <input class="text-input" name="username" type="text" />
      </p>
      <div class="clear"></div>
      <p>
        <label>密码</label>
        <input class="text-input" name="password" type="password" />
      </p>
      <div class="clear"></div>

      <p>
        <label>岗位</label>
        <select class="text-input" name='level'>
          <option value='1'>管理员</option>
          <option value='2'>编辑</option>
          <option value='3'>注册用户</option>
        </select>
      </p>
      <div class="clear"></div>
<!--       <p id="remember-password">
        <input type="checkbox" />
        Remember me </p> -->
      <div class="clear"></div>
      <p>
        <input class="button" type="submit" value="确定" />
      </p>
    </form>
  </div>
  <!-- End #login-content -->
</div>    
 

    <!-- End Notifications -->
  <?php echo $footer; ?>
    <!-- End #footer -->
  </div>
  <!-- End #main-content -->
</div>
</body>
<!-- Download From www.exet.tk-->
</html>
