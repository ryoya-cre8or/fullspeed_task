<?php

require_once('function.php');
date_default_timezone_set('Asia/Tokyo');

?>

<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create Page</title>
</head>
<body>
<h1>
    Post New ToDo Page
</h1>
<form action="success.php" method="post">
    <input type="hidden" name="success" value="create">
    <input type="hidden" name="time" value="<?php echo date("Y/m/d H:i:s") ?>">
    <div style="margin: 10px">
        <label for="title">タイトル：</label>
        <input id="title" type="text" name="todo_title" maxlength="255" placeholder="例)掃除">
    </div>
    <div style="margin: 10px">
        <label for="content">内容：</label>
        <textarea id="content" name="contents" rows="8" cols="40" placeholder="例)トイレと風呂場を掃除する"></textarea>
    </div>
    <input type="submit" name="post" value="投稿する">
</form>
<form action="index.php">
    <button type="submit" name="back">戻る</button>
</form>
</body>
</html>