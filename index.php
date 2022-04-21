<?php

require_once('function.php');
require_once('dbc.php');

$dbc = new Dbc('posts');

// レコードを削除
$id = (int)$_POST['id'];
if ($_POST['nextAction'] == "delete") {
    $dbc->dbDelete($id);
}

// あいまい検索の処理
if (!empty($_GET['searchTerm'])) {
    $search = $_GET['searchTerm'];
    $list = $dbc->dbSearchData($search);
} else {
    $list = $dbc->dbGetAllData();
}

$maxPage = getDisplayInformation($list)[0]; //最大ページ数
$now = getDisplayInformation($list)[1]; //現在のページ
$displayData = getDisplayInformation($list)[2]; //現在のページに表示するデータのまとまり

?>

<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Index Page</title>
</head>
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
            <?php foreach($displayData as $column): ?>
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
        <?php pagingFunction($now, $maxPage, $search); // リストの下に表示するページング機能 ?>
    </div>
</body>
</html>