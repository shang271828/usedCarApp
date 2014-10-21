<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php echo $header; ?>
</head>
<body id="login">
<div id="login-wrapper" class="png_bg">
  <div id="login-top">
    <h1>管理登陆</h1>
    <!-- Logo (221px width) -->
    <a href="#">
<!--       <img id="logo" src="<?php echo base_url()?>assets/cms/images/logo.png" alt="Simpla Admin logo" />
 -->    </a> 
</div>
  <!-- End #logn-top -->
  <div id="login-content">
    <form action="<?php echo site_url('cms/login/check');?>" method="post">
      <div class="notification information png_bg">
        <div> 欢迎 </div>
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
<!--       <p id="remember-password">
        <input type="checkbox" />
        Remember me </p> -->
      <div class="clear"></div>
      <p>
        <input class="button" type="submit" value="登陆" />
      </p>
    </form>
  </div>
  <!-- End #login-content -->
</div>
<!-- End #login-wrapper -->
</body>
</html>
