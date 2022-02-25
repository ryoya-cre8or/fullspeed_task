<?php

require_once('function.php');

// レコードを削除
$id = (int)$_POST['id'];
if ($_POST['loading'] == "delete") {
    delete($id);
}

// あいまい検索の処理
if (!empty($_POST['search'])) {
    $search = $_POST['search'];
    $list = searchData($search);
} else {
    $list = getAllData();
}

// 最大ページ数,現在のページの場所,表示するデータ
$pageData = getDisplayInformation($list);
$maxPage = $pageData[0];
$now = $pageData[1];
$displayData = $pageData[2];

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
        <form action="" method="post">
            <div style="margin: 10px">
                <input type="text" name="search" maxlength="255" placeholder="キーワード入力">
            </div>
                <input type="submit" value="検索">
        </form>
    </div>
    <div class="listPage">
        <form action="create.php">
            <button type="submit" style="padding: 10px;font-size: 16px;margin-bottom: 10px">New Todo</button>
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
                        <button type="submit" style="padding: 10px;font-size: 16px;" name="id" value="<?php echo $column['ID']; ?>">編集する</button>
                    </form>
                </td>
                <td>
                    <form action="" method="post">
                        <input type="hidden" name="loading" value="delete">
                        <button type="submit" style="padding: 10px;font-size: 16px;" name="id" value="<?php echo $column['ID']; ?>">削除する</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <div class="pageNumbers">
        <?php pagingFunction($now, $maxPage); // リストの下に表示するページング機能 ?>
    </div>
</body>
</html>