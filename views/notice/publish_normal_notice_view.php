<html>
<head>
<title>Upload Form</title>
</head>
<body>


<?php echo form_open_multipart('notice/publishNormalNotice');?>

<textarea name="json_package" cols="100" rows="25"></textarea>
<br/>
<input type="file" name="pic0" id="pic0" size="20" />
<br /><br />

<input type="submit" value="submit" />

</form>

</body>
</html>