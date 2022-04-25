<?php

class Paging {
    private String $pagingCode = "";


    // ページング機能
    public function __construct($now, $maxPage, $search) {
        if($now > 1) {
            $this->pagingCode .= '<a href="index.php?pageId='.($now - 1).'&searchTerm='.$search.'">前へ</a>'.' ';
        }

        for ($i = 1; $i <= $maxPage; $i++) {
            if ($i == $now) { // 現在表示中のページ数の場合はリンクを貼らない
                $this->pagingCode .= $now.' '; 
            } else {
                $this->pagingCode .= '<a href="index.php?pageId='.$i.'&searchTerm='.$search.'">'.$i.'</a>'.' ';
            }
        }

        if($now < $maxPage) {
            $this->pagingCode .= '<a href="index.php?pageId='.($now + 1).'&searchTerm='.$search.'">次へ</a>'.' ';
        }
    }

    public function getPagingCode() {
        return $this->pagingCode;
    }
}

?>