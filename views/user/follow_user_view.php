<html>
<head>
<title>Upload Form</title>
</head>
<body>


<?php echo form_open_multipart('user/followUser');?>

<input type="text" name="json_package"  size="200" />
<br/>

<input type="submit" value="submit" />

</form>

</body>
</html>