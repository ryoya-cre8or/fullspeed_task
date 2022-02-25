<?php

// データベース接続
function dbConnect() {
    $dsn = 'mysql:host=localhost;dbname=todo_app;charset=utf8';
    $user = 'post_user';
    $pass = 'post_user';
    
    try {
        $dbh = new PDO($dsn,$user,$pass,[
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    } catch(PDOException $e) {
        echo '接続失敗'. $e->getMessage();
        exit();
    };

    return $dbh;
}

// データを取得する
function getAllData() {
    $dbh = dbConnect();
    // SQLの準備
    $sql = 'SELECT * FROM posts';
    // SQLの実行
    $stmt = $dbh->query($sql);
    // 結果を取得
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
    $dbh = null;
}

// IDからレコードを取得する
function getData($id) {
    if(empty($id)) {
        exit('元のページに戻ってください。');
    }

    $dbh = dbConnect();

    // SQL準備
    $stmt = $dbh->prepare('SELECT * FROM posts WHERE id = :id');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    // SQL実行
    $stmt->execute();
    // 結果を取得
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result;
}

// リストを新規作成する
function createPost($posts) {
    $sql = 'INSERT INTO
                posts(title, content, created_at ,updated_at)
            VALUES
                (:title, :content, :created_at, :updated_at)';

    $dbh = dbConnect();
    $dbh->beginTransaction();
    try {
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':title',$posts['todo_title'], PDO::PARAM_STR);
        $stmt->bindValue(':content',$posts['contents'], PDO::PARAM_STR);
        $stmt->bindValue(':created_at',$posts['time'], PDO::PARAM_STR);
        $stmt->bindValue(':updated_at',$posts['time'], PDO::PARAM_STR);
        $stmt->execute();
        $dbh->commit();
    } catch (PDOException $e) {
        $dbh->rollBack();
        exit($e);
    }
}

// リストを更新する
function postUpdate($posts) {
    $sql = 'UPDATE posts SET
                title = :title, content = :content, updated_at = :updated_at
            WHERE
                id = :id';

    $dbh = dbConnect();
    $dbh->beginTransaction();
    try {
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':title',$posts['todo_title'], PDO::PARAM_STR);
        $stmt->bindValue(':content',$posts['contents'], PDO::PARAM_STR);
        $stmt->bindValue(':updated_at',$posts['time'], PDO::PARAM_STR);
        $stmt->bindValue(':id',$posts['id'], PDO::PARAM_INT);
        $stmt->execute();
        $dbh->commit();
    } catch (PDOException $e) {
        $dbh->rollBack();
        exit($e);
    }
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

// レコードを削除する
function delete($id) {
    if(empty($id)) {
        exit('元のページに戻ってください。');
    }

    $dbh = dbConnect();

    // SQL準備
    $stmt = $dbh->prepare('DELETE FROM posts WHERE id = :id');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    // SQL実行
    $stmt->execute();
}

// 画面表示する最大ページ数,現在のページ,表示するページのデータを送る
function getDisplayInformation($searchData) {
    define('MAX', '5');

    $postsData = $searchData;
    $dataNumber = count($postsData);
    $maxPage = ceil($dataNumber / MAX);

    if(!isset($_GET['pageId'])) {
        $now = 1;
    } else {
        $now = $_GET['pageId'];
    }

    // 配列の何番目から画面に表示するかを示す番号
    $startNumber = ($now - 1) * MAX;

    // 画面に表示させるデータ
    $displayData = array_slice($postsData, $startNumber, MAX, true);

    return array($maxPage, $now, $displayData);
}

// ページング機能
function pagingFunction($now, $maxPage) {
    if($now > 1) {
        echo '<a href="index.php?pageId='.($now - 1).'")>前へ</a>'.' ';
    }

    for ($i = 1; $i <= $maxPage; $i++) {
        if ($i == $now) { // 現在表示中のページ数の場合はリンクを貼らない
            echo $now.' '; 
        } else {
            echo '<a href="index.php?pageId='.$i.'")>'.$i.'</a>'.' ';
        }
    }

    if($now < $maxPage) {
        echo '<a href="index.php?pageId='.($now + 1).'")>次へ</a>'.' ';
    }
}

// あいまい検索
function searchData($search) {
    $dbh = dbConnect();

    $stmt = $dbh->prepare("SELECT * FROM posts WHERE title LIKE :search1 OR content LIKE :search2");
    $stmt->bindValue( ":search1", '%'. addcslashes($search, '\_%'). '%');
    $stmt->bindValue( ":search2", '%'. addcslashes($search, '\_%'). '%');
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
   
    return $result;
    $dbh = null;
}

?>