<?php

require_once('function.php');
$posts = $_POST;

postValidate($posts);

if ($posts['success'] == "create") {
    createPost($posts);
} elseif ($posts['success'] == "edit") {
    postUpdate($posts);
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Success Page</title>
</head>
<body>
    <h1>
        ToDoリストに追加できました！
    </h1>
    <form action="index.php">
        <button type="submit" name="back">ToDoリストを確認する</button>
    </form>
</body>
</html>