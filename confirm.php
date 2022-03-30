<?php

require_once('function.php');
require_once('db-function.php');
date_default_timezone_set('Asia/Tokyo');

$posts = $_POST;

// 強制ブラウザ対策
if (!isset($posts['forcefulBrowsing'])) {
    exit('不適切な動作です');
}

postValidate($posts);

$todo_title = $posts['todo_title']; //画面に表示するタイトル
$contents = $posts['contents']; //画面に表示する内容
$dealingProcess = $posts['dealingProcess']; //分岐先→追加or編集
$nextAction = $posts['nextAction']; //次の遷移先指定→登録or確認
$id = (int)$_POST['id']; //編集時に受け取るID

$materials = confirmConditionalBranch($nextAction, $posts);

$message = $materials[0]; //見出し
$nextMessage = $materials[1]; //次のページボタンに表示する文字
$nextPage = $materials[2]; //次のページのURL
$back = $materials[3]; //戻るボタンの表示有無

?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Confirm Page</title>
</head>
<body>
    <h1>
        <?php echo $message; ?>
    </h1>
    <div class="listPage">
    <table>
        <tr>
            <th>タイトル：</th>
            <td><?php echo h($todo_title) // XSSのエスケープ処理 ?></td>
        </tr>
        <tr>
            <th>内容：</th>
            <td><?php echo h($contents) //XSSのエスケープ処理 ?></td>
        </tr>
    </table>
    </div>
    <form action="<?php echo $nextPage ?>" method="post">
        <input type="hidden" name="id" value="<?php echo $id ?>">
        <input type="hidden" name="todo_title" value="<?php echo $todo_title ?>">
        <input type="hidden" name="contents" value="<?php echo $contents ?>">
        <input type="hidden" name="time" value="<?php echo date("Y/m/d H:i:s") ?>">
        <input type="hidden" name="dealingProcess" value="<?php echo $dealingProcess ?>">
        <input type="hidden" name="nextAction" value="register">
        <input type="hidden" name="forcefulBrowsing" value="no">
        <button type="submit" name="back"><?php echo $nextMessage ?></button>
    </form>
    <?php echo backButton($back) ?>
</body>
</html>