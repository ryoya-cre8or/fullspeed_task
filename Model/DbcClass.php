<?php

Class DbcClass {
    private String $dsn;
    private String $user;
    private String $pass;
    protected String $table_name;

    public function __construct(String $dsn, String $user, String $pass, String $table_name){
        $this->dsn = $dsn;
        $this->user = $user;
        $this->pass = $pass;
        $this->table_name = $table_name;        
    }

    public function dbConnect() {
        try {
            $dbh = new PDO($this->dsn,$this->user,$this->pass,[
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
    public function dbGetAllData() {
        $dbh = $this->dbConnect();
        // SQLの準備
        $sql = "SELECT * FROM $this->table_name";
        // SQLの実行
        $stmt = $dbh->query($sql);
        // 結果を取得
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
        $dbh = null;
    }
    
    // IDからレコードを取得する
    function dbGetData($id) {
        if(empty($id)) {
            exit('元のページに戻ってください。');
        }
    
        $dbh = $this->dbConnect();
    
        // SQL準備
        $stmt = $dbh->prepare("SELECT * FROM $this->table_name WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        // SQL実行
        $stmt->execute();
        // 結果を取得
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
        $dbh = null;
    }

    public function dbCheckId($id) {
        $dbh = $this->dbConnect();
        // SQL準備
        $stmt = $dbh->prepare("SELECT * FROM $this->table_name WHERE id = :id LIMIT 1");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        // SQL実行
        $stmt->execute();
        // 結果を取得
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
        $dbh = null;
    }
    
    // あいまい検索
    public function dbSearchData($search) {
        $dbh = $this->dbConnect();
        $stmt = $dbh->prepare("SELECT * FROM $this->table_name WHERE title LIKE :search1 OR content LIKE :search2");
        $stmt->bindValue( ":search1", '%'. addcslashes($search, '\_%'). '%');
        $stmt->bindValue( ":search2", '%'. addcslashes($search, '\_%'). '%');
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
        $dbh = null;
    }
    
    // リストを新規作成する
    public function dbCreatePost($posts) {
        $sql = "INSERT INTO
                    $this->table_name(title, content, created_at ,updated_at)
                VALUES
                    (:title, :content, :created_at, :updated_at)";
        $dbh = $this->dbConnect();
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
        $dbh = null;
    }
    
    // リストを更新する
    public function dbPostUpdate($posts) {
        $sql = "UPDATE $this->table_name SET
                    title = :title, content = :content, updated_at = :updated_at
                WHERE
                    id = :id";
        $dbh = $this->dbConnect();
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
        $dbh = null;
    }
    
    // レコードを削除する
    function dbDelete($id) {
        if(empty($id)) {
            exit('元のページに戻ってください。');
        }
    
        if ($this->dbCheckId($id)) {
            $dbh = $this->dbConnect();
            // SQL準備
            $stmt = $dbh->prepare("DELETE FROM $this->table_name WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            // SQL実行
            $stmt->execute();
        } else {
            exit('存在しないIDです');
        }
        $dbh = null;
    }

}

?>