<?php

require_once('dbc.php');

function boxData($id, $where) {
    $dbc = new Dbc('posts');
    switch ($where) {
        case "create":
            $title = "";
            $content = "";
            $dealingProcess = "create";
            $id = "";
            $formText = "投稿する";
            break;
        case "edit":
            if ($dbc->dbCheckId($id)) {
                $result = $dbc->dbGetData($id);
                $title = $result['title'];
                $content = $result['content'];
                $dealingProcess = "edit";
                $formText = "更新する";
            } else {
                exit('存在しないIDです');
            }
            break;
    }

    return array($title, $content, $dealingProcess, $id, $formText);
}

// バリデーション処理
function postValidate($posts) {
    if (empty($posts['todo_title'])) {
        exit('タイトルを入力してください');
    }
    
    if (mb_strlen($posts['todo_title']) > 255) {
        exit('タイトルは255文字以下にしてください');
    }
    
    if (empty($posts['contents'])) {
        exit('本文を入力してください');
    }
}

// XSS対策
function h($s) {
    return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
}

// 画面表示する最大ページ数,現在のページ,表示するページのデータを送る
function getDisplayInformation($list) {
    define('MAX', '5');

    $dataNumber = count($list);
    $maxPage = ceil($dataNumber / MAX);

    if(!isset($_GET['pageId'])) {
        $now = 1;
    } else {
        $now = $_GET['pageId'];
    }

    // 配列の何番目から画面に表示するかを示す番号
    $startNumber = ($now - 1) * MAX;

    // 画面に表示させるデータ
    $displayData = array_slice($list, $startNumber, MAX, true);

    return array($maxPage, $now, $displayData);
}

// ページング機能
function pagingFunction($now, $maxPage, $search) {
    if($now > 1) {
        echo '<a href="index.php?pageId='.($now - 1).'&searchTerm='.$search.'">前へ</a>'.' ';
    }

    for ($i = 1; $i <= $maxPage; $i++) {
        if ($i == $now) { // 現在表示中のページ数の場合はリンクを貼らない
            echo $now.' '; 
        } else {
            echo '<a href="index.php?pageId='.$i.'&searchTerm='.$search.'">'.$i.'</a>'.' ';
        }
    }

    if($now < $maxPage) {
        echo '<a href="index.php?pageId='.($now + 1).'&searchTerm='.$search.'">次へ</a>'.' ';
    }
}

// $backがNullの時ボタン表示
function backButton($back) {
    if (!isset($back)) {
        echo '<form><input type="button" value="戻る" onClick="history.back()"></form>';
    }
}

function confirmConditionalBranch ($nextAction, $posts) {
    $dbc = new Dbc('posts');
    if (!isset($nextAction)) {
        $message = "以下の内容を登録しますか？";
        $nextMessage = "登録する";
        $nextPage = "";
    } else {
        $message = "ToDoリストに以下の項目を追加できました！";
        $nextMessage = "ToDoリストを確認する";
        $nextPage = "index.php";
        $back ="noButton";
        switch ($posts['dealingProcess']) {
            case "create":
                $dbc->dbCreatePost($posts);
                break;
            case "edit":
                $dbc->dbPostUpdate($posts);
                break;
        }
    }

    return array($message, $nextMessage, $nextPage, $back);
}

?>