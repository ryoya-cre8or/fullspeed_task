<?php

require_once('function.php');
date_default_timezone_set('Asia/Tokyo');

$id = (int)$_POST['id'];
$result = getData($id);
$title = $result['title'];
$content = $result['content'];

?>

<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Page</title>
</head>
<body>
<h1>
    Edit Todo Page
</h1>
<form action="success.php" method="post">
    <input type="hidden" name="success" value="edit">
    <input type="hidden" name="id" value="<?php echo $id ?>">
    <input type="hidden" name="time" value="<?php echo date("Y/m/d H:i:s") ?>">
    <div style="margin: 10px">
        <label for="title">タイトル：</label>
        <input id="title" type="text" name="todo_title" maxlength="255" value="<?php echo h($title) ?>">
    </div>
    <div style="margin: 10px">
        <label for="content">内容：</label>
        <textarea id="content" name="contents" rows="8" cols="40"><?php echo h($content) ?></textarea>
    </div>
    <input type="submit" name="post" value="編集する">
</form>
<form action="index.php">
    <button type="submit" name="back">戻る</button>
</form>
</body>
</html>