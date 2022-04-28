<?php

class Validation {
    private String $todo_title;
    private String $contents;

    public function __construct($posts) {
        $this->todo_title = $posts['todo_title'];
        $this->contents = $posts['contents'];
    }

    public function validate() {
        $this->checkInput($this->todo_title, "タイトル");
        $this->checkTitleLength();
        $this->checkInput($this->contents, "本文");
    }

    private function checkInput(String $content, String $arg) {
        if (empty($content)) {
            exit($arg."を入力してください");
        }
    }

    private function checkTitleLength() {
        if (mb_strlen($this->todo_title) > 255) {
            exit("タイトルは255文字以下にしてください");
        }
    }
}

?>