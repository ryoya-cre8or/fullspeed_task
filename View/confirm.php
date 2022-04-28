<?php

require_once('../Controller/function.php');
require_once('../Controller/ValidationClass.php');
date_default_timezone_set('Asia/Tokyo');

// 強制ブラウザ対策
if (!isset($_POST['forcefulBrowsing'])) {
    exit('不適切な動作です');
}

// バリデーション処理
$validationFunction = new Validation($_POST);
$validationFunction->validate();

//遷移先の分岐→確認画面or登録完了画面
$confirmDataBox = confirmConditionalBranch($_POST['nextAction'], $_POST);

?>

<?php
$pageTitle = "Confirm Page";
require_once('head.php');
?>

<body>
    <h1>
        <?php echo $confirmDataBox["message"]; ?>
    </h1>
    <div class="listPage">
    <table>
        <tr>
            <th>タイトル：</th>
            <td><?php echo h($_POST['todo_title']) // XSSのエスケープ処理 ?></td>
        </tr>
        <tr>
            <th>内容：</th>
            <td><?php echo h($_POST['contents']) //XSSのエスケープ処理 ?></td>
        </tr>
    </table>
    </div>
    <form action="<?php echo $confirmDataBox["nextPage"] ?>" method="post">
        <input type="hidden" name="id" value="<?php echo (int)$_POST['id'] ?>">
        <input type="hidden" name="todo_title" value="<?php echo$_POST['todo_title'] ?>">
        <input type="hidden" name="contents" value="<?php echo $_POST['contents'] ?>">
        <input type="hidden" name="time" value="<?php echo date("Y/m/d H:i:s") ?>">
        <input type="hidden" name="dealingProcess" value="<?php echo $_POST['dealingProcess'] ?>">
        <input type="hidden" name="nextAction" value="register">
        <input type="hidden" name="forcefulBrowsing" value="no">
        <button type="submit" name="back"><?php echo $confirmDataBox["nextMessage"] ?></button>
    </form>
    <?php echo backButton($confirmDataBox["back"]) ?>
</body>
</html>