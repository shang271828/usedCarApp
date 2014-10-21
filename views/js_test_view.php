<!DOCTYPE html>
<html>
<body>

  <?php echo form_open_multipart('notice/publishArticle');?>

	标题:<textarea name="title" cols="150" rows="1"></textarea>

	<br/>

	作者:<textarea name="author" cols="50" rows="1"></textarea>

	<br/>

  <script id="container" name="content" type="text/plain">
  
    请输入文本内容
  
  </script>

  
  <script type="text/javascript" src="<?php echo base_url()?>ueditor/ueditor.config.js"></script>
  
  <script type="text/javascript" src="<?php echo base_url()?>ueditor/ueditor.config.js"></script>
  
    <script type="text/javascript">
  
       var ue = UE.getEditor('container');
  
    </script>


	<input type="submit" value="submit" />

</form>


</body>
</html>