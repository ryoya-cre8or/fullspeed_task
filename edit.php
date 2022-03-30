<?php

require_once('function.php');
require_once('db-function.php');

$id = (int)$_POST['id']; //編集するレコードのID
$where = $_POST['where']; //追加or編集の分岐を伝えるキー

$placeholderTitle = boxData($id, $where)[0]; //プレースホルダーのタイトル
$placeholderContent = boxData($id, $where)[1]; //プレースホルダーの内容
$title = boxData($id, $where)[2]; //編集するレコードのタイトル
$content = boxData($id, $where)[3]; //編集するレコードの内容
$dealingProcess = boxData($id, $where)[4]; //編集先を伝えるキー
$id = boxData($id, $where)[5]; //編集するレコードのID
$formText = boxData($id, $where)[6]; //フォームに表示する文字

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
<form action="confirm.php" method="post">
    <input type="hidden" name="dealingProcess" value="<?php echo $dealingProcess ?>">
    <input type="hidden" name="id" value="<?php echo $id ?>">
    <input type="hidden" name="forcefulBrowsing" value="no">
    <div style="margin: 10px">
        <label for="title">タイトル：</label>
        <input id="title" type="text" name="todo_title" maxlength="255" placeholder="<?php echo $placeholderTitle ?>" value="<?php echo h($title) ?>" >
    </div>
    <div style="margin: 10px">
        <label for="content">内容：</label>
        <textarea id="content" name="contents" rows="8" cols="40" placeholder="<?php echo $placeholderContent ?>"><?php echo h($content) ?></textarea>
    </div>
    <input type="submit" name="post" value="<?php echo $formText ?>">
</form>
<form action="index.php">
    <button type="submit" name="back">戻る</button>
</form>
</body>
</html>