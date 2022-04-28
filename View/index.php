<?php

require_once('../Controller/function.php');
require_once('../Controller/PagingClass.php');
require_once('../Controller/DisplayDataClass.php');
require_once('../Model/DbcClass.php');

$dbc = new DbcClass('mysql:host=localhost;dbname=todo_app;charset=utf8', 'post_user', 'post_user', 'posts');

// レコードを削除
$id = (int)$_POST['id'];
if ($_POST['nextAction'] == "delete") {
    $dbc->dbDelete($id);
}

// あいまい検索の処理
if (!empty($_GET['searchTerm'])) {
    $list = $dbc->dbSearchData($_GET['searchTerm']);
} else {
    $list = $dbc->dbGetAllData();
}

$displayData = new DisplayData($list);
$paging = new Paging($displayData->getDisplayData()["now"], $displayData->getDisplayData()["maxPage"], $_GET['searchTerm']);

?>

<?php
$pageTitle = "Index Page";
require_once('head.php');
?>

<body>
    <h1>
        ToDo List Page
    </h1>
    <div class="searchBox">
        <form action="" method="get">
            <div style="margin: 10px">
                <input type="text" name="searchTerm" maxlength="255" placeholder="キーワード入力">
            </div>
                <input type="submit" value="検索">
        </form>
    </div>
    <div class="listPage">
        <form action="edit.php" method="post">
            <button type="submit" style="padding: 10px;font-size: 16px;margin-bottom: 10px" name="where" value="create">New Todo</button>
        </form>
        <table border="1">
            <colgroup span="4"></colgroup>
            <tr>
                <th>ID</th>
                <th>タイトル</th>
                <th>内容</th>
                <th>作成日時</th>
                <th>更新日時</th>
                <th>編集</th>
                <th>削除</th>
            </tr>
            <?php foreach($displayData->getDisplayData()["displayData"] as $column): ?>
            <tr>
                <td><?php echo $column['ID'] ?></td>
                <td><?php echo h($column['title']) // XSSのエスケープ処理 ?></td>
                <td><?php echo h($column['content']) //XSSのエスケープ処理 ?></td>
                <td><?php echo $column['created_at'] ?></td>
                <td><?php echo $column['updated_at'] ?></td>
                <td>
                    <form action="edit.php" method="post">
                        <input type="hidden" name="id" value="<?php echo $column['ID']; ?>">
                        <button type="submit" style="padding: 10px;font-size: 16px;" name="where" value="edit">編集する</button>
                    </form>
                </td>
                <td>
                    <form action="" method="post">
                        <input type="hidden" name="nextAction" value="delete">
                        <button type="submit" style="padding: 10px;font-size: 16px;" name="id" value="<?php echo $column['ID']; ?>">削除する</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <div class="pageNumbers">
        <?php echo $paging->getPagingCode(); // リストの下に表示するページング機能 ?>
    </div>
</body>
</html>