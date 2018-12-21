<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>文件上传</title>
</head>
<body>
<form method='POST' action='upload' enctype='multipart/form-data'>
    <input type = 'hidden' name='MAX_FILE_SIZE' value = '2000000' />
    <input type = 'file' name = 'image' />
    <input type = 'submit' value = '上传' />
</form>
<br/>
<br/>
</body>
</html>