<?php

require_once('../Controller/function.php');

$id = (int)$_POST['id']; //編集するレコードのID
$where = $_POST['where']; //追加or編集の分岐を伝えるキー

?>

<?php
$pageTitle = "Edit Page";
require_once('head.php');
?>

<body>
<h1>
    Edit Todo Page
</h1>
<form action="confirm.php" method="post">
    <input type="hidden" name="dealingProcess" value="<?php echo boxData($id, $where)["where"] ?>">
    <input type="hidden" name="id" value="<?php echo $id ?>">
    <input type="hidden" name="forcefulBrowsing" value="no">
    <div style="margin: 10px">
        <label for="title">タイトル：</label>
        <input id="title" type="text" name="todo_title" placeholder="例)掃除" value="<?php echo h(boxData($id, $where)["title"]) ?>" >
    </div>
    <div style="margin: 10px">
        <label for="content">内容：</label>
        <textarea id="content" name="contents" rows="8" cols="40" placeholder="例)トイレと風呂場を掃除する"><?php echo h(boxData($id, $where)["content"]) ?></textarea>
    </div>
    <input type="submit" name="post" value="<?php echo boxData($id, $where)["textForm"] ?>">
</form>
<form action="index.php">
    <button type="submit" name="back">戻る</button>
</form>
</body>
</html>