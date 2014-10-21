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
      <div class="notification information png_bg">
        <div> 登陆确定 </div>
      </div>
      <p>
        <?php echo $msg?>

        <a href="<?php echo site_url().'/cms/admin/main'?>">
        <?php echo $jump_alt?>
        </a>
      </p>
      <div class="clear"></div>
<!--       <p id="remember-password">
        <input type="checkbox" />
        Remember me </p> -->
      <div class="clear"></div>
      <p>
      </p>
  </div>
  <!-- End #login-content -->
</div>
<!-- End #login-wrapper -->
</body>
</html>
