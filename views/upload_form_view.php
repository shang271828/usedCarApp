<html>
<head>
<title>Upload Form</title>
</head>
<body>

<?php echo $error;?>

<?php echo form_open_multipart('upload/do_upload');?>

<input type="file" name="pic0" id="pic0" size="20" />
<br/>
<input type="file" name="pic1" id="pic0" size="20" />
<br/>
<input type="text" name="json_msg" value="{'pic_var_list':['pic0' , 'pic1'],}" />
<br /><br />

<input type="submit" value="upload" />

</form>

</body>
</html>