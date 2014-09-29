<html>
<head>
<title>Upload Form</title>
</head>
<body>


<?php echo form_open_multipart('user/resetPassword');?>

<textarea name="json_package" cols="100" rows="25"></textarea>

<br /><br />

<input type="submit" value="submit" />

</form>

</body>
</html>