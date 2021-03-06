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


          <table>
            <thead>
              <tr>
                <th>标题</th>
                <th>作者</th>
               
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

            <?php foreach($article_list as $article) :?>
              <tr>

                <td>
                  <a href="<?php echo site_url('cms/admin/display_single_article/'.$article['aid'])?>">
                  <?php echo $article['title'];?>
                  </a>
                </td>
                <td><?php echo $article['author'];?></td>
          
                <td>
                  <a href="<?php echo site_url('cms/admin/update_article/'.$article['aid'])?>" title="Update">
                    <img src="<?php echo base_url()?>assets/cms/images/icons/pencil.png" alt="Update" />
                  </a> 
                </td>
                <td>
                  <a href="<?php echo site_url('cms/admin/delete_article/'.$article['aid'])?>" title="Delete">
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
