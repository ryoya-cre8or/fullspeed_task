<?php

require_once('../Model/DbcClass.php');

function boxData($id, $where) {
    $dbc = new DbcClass('mysql:host=localhost;dbname=todo_app;charset=utf8', 'post_user', 'post_user', 'posts');
    switch ($where) {
        case "create":
            $displayBox = array("title" => "", "content" => "", "where" => $where, "textForm" => "投稿する");
            break;
        case "edit":
            if ($dbc->dbCheckId($id)) {
                $displayBox = array("title" => $dbc->dbGetData($id)['title'], "content" => $dbc->dbGetData($id)['content'], "where" => $where, "textForm" => "更新する");
            } else {
                exit('存在しないIDです');
            }
            break;
    }
    return $displayBox;
}

// XSS対策
function h($s) {
    return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
}

// $backがNullの時ボタン表示
function backButton($back) {
    if (!isset($back)) {
        echo '<form><input type="button" value="戻る" onClick="history.back()"></form>';
    }
}

function confirmConditionalBranch ($nextAction, $posts) {
    $dbc = new DbcClass('mysql:host=localhost;dbname=todo_app;charset=utf8', 'post_user', 'post_user', 'posts');
    if (!isset($nextAction)) {
        $confirmDataBox = array ("message" => "以下の内容を登録しますか？", "nextMessage" => "登録する", "nextPage" => "", "back" => NULL);
    } else {
        $confirmDataBox = array ("message" => "ToDoリストに以下の項目を追加できました！", "nextMessage" => "ToDoリストを確認する", "nextPage" => "index.php", "back" => "noButton");
        switch ($posts['dealingProcess']) {
            case "create":
                $dbc->dbCreatePost($posts);
                break;
            case "edit":
                $dbc->dbPostUpdate($posts);
                break;
        }
    }

    return $confirmDataBox;
}

?>