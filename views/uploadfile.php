<!DOCTYPE html>
<html>
<body>

<form action="upload_file.php" method="post"
enctype="multipart/form-data">
<label for="file">Filename:</label>
<input type="file" name="pic0" id="file" /> 
<br />
<input type="file" name="pic1" id="file" /> 
<br />
<input type="text" name="json_msg" value="{'pic_var_list':['pic0' , 'pic1']}" />
<br />
<input type="submit" name="submit" value="Submit" />
</form>

</body>
</html>