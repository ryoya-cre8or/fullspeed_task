<?php

class DisplayData {
    const MAX = 5;
    private int $maxPage;
    private int $now;
    private array $displayData;

    public function __construct(array $list) {
        $this->maxPage = ceil(count($list) / self::MAX);
        $this->now = $this->currentPage($_GET['pageId']);
        $this->displayData = array_slice($list, ($this->now - 1) * self::MAX, self::MAX);
    }

    private function currentPage(int $pageNumber = NULL) {
        if(!isset($pageNumber)) {
            $this->now = 1;
        } else {
            $this->now = $pageNumber;
        }
        return $this->now;
    }

    public function getDisplayData() {
        return array("maxPage" => $this->maxPage, "now" => $this->now, "displayData" => $this->displayData);
    }
}

?>