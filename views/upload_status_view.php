<html>
<head>
<title>Upload Form</title>
</head>
<body>

<?php //echo $error;?>
<?php echo form_open_multipart('upload_status/do_publish');?>

Title:
<br />
<input type="text" name="title" value="" size="100" />
<br />
Content:
<br />
<input type="text" name="content" value="" size="100" style="height: 100" />
<br /><br />
上传图片：
<br/>
<input type="file" name="pic0" id="pic0"  size="20"  />
<br/>

<input type="submit" value="submit" /> 

</form>

</body>
</html>