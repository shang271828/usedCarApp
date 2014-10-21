<!DOCTYPE HTML>

<html lang="en-US">

<head>
    <meta charset="UTF-8">
    <title>ueditor demo</title>
</head>

<body>
    <base href="<?php  echo site_url();?>"/>
    <script id="container" name="content" type="text/plain">
        请输入文本内容
    </script>

    <script type="text/javascript" src="ueditor/ueditor.config.js"></script>
    <script type="text/javascript" src="ueditor/ueditor.all.js"></script>
      <script type="text/javascript">
         var ue = UE.getEditor('container');
     </script>

</body>

</html>