
    <div id="sidebar-wrapper">
      <!-- Sidebar with logo and menu -->
      <h1 id="sidebar-title"><a href="#">微信后台管理系统</a></h1>
      <!-- Logo (221px wide) -->
      <a href="<?php echo site_url().'/cms/admin/main'?>"><img id="logo" src="<?php echo base_url()?>assets/cms/images/logo.png" alt="Simpla Admin logo" /></a>
      <!-- Sidebar Profile links -->
      <div id="profile-links"> 
        你好, 
        <a href="#" title="Edit your profile">
          <?php echo $status?>
        </a>, 
         <a href="#"  title="">
          <?php echo $username?>
         </a>
        <br />
      <br />

        <!-- <a href="#" title="View the Site">View the Site</a> | <a href="#" title="Sign Out">Sign Out</a>  -->

      </div>
      <ul id="main-nav-">
        <!-- Accordion Menu -->
        <li> <a href="<?php echo site_url('cms/admin/display_articles')?>" class="nav-top-item ">
          <!-- Add the class "current" to current menu item -->
          信息管理 </a>
          <ul>
            <li><a href="<?php echo site_url('cms/admin/display_articles')?>">查看文章</a></li>
            <li><a href="<?php echo site_url('cms/admin/edit_article')?>">新增文章</a></li>
            <li><a href="<?php echo site_url('cms/admin/display_car_notice_list')?>">查看二手车信息</a></li>
            <li><a href="<?php echo site_url('cms/admin/edit_car_notice')?>">新增二手车信息</a></li>
            <li><a href="<?php echo site_url('cms/admin/edit_car_notice')?>">新增信息（来自url）</a></li>

          </ul>
        </li>

        <li> <a href="#" class="nav-top-item"> 用户管理 </a>
          <ul>
            <li><a href="<?php echo site_url('cms/admin/modify_passwd')?>" >修改密码</a></li>

            <?php if($level =='1') :?>
            <li><a href="<?php echo site_url('cms/admin/add_user')?>">添加用户</a></li>
            <li><a href="<?php echo site_url('cms/admin/user_list')?>">用户管理</a></li>
            <?php endif;?>

          </ul>
        </li>

        <li> <a href="#" class="nav-top-item"> 私信管理 </a>
          <ul>
            <li><a href="<?php echo site_url('cms/admin/send_message')?>" >发送私信</a></li>
            <li><a href="<?php echo site_url('cms/admin/get_message_list')?>" >查看私信列表</a></li>
          </ul>
        </li>

        <?php if($level =='1') :?>
        <li> <a href="#" class="nav-top-item"> 测试页 </a>
          <ul>
            <li><a href="<?php echo site_url('cms/admin/test_data')?>" >data测试</a></li>
          </ul>
        </li>
        <?php endif;?>

      </ul>
      <!-- End #main-nav -->

    </div>
