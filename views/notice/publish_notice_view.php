<html>
<head>
<title>Upload Form</title>
</head>
<body>


<?php echo form_open_multipart('notice/publishNotice');?>

<input type="text" name="json_package"  size="20" />
<br/>
<input type="file" name="pic0" id="pic0" size="20" />
<br /><br />

<input type="submit" value="submit" />

</form>

</body>
</html>