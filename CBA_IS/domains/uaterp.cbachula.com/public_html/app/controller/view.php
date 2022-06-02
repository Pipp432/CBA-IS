<?php

namespace _core;

class view {

    public $title;

    public function __construct() {
        $this->setTitle("CBA2020");
    }

    public function render($pathContent, $pathHeader="none") {
        if ($pathHeader != "none") {
            $this->pathHeader = 'app/view/'.$pathHeader.'.php';
        } else {
            $this->pathHeader = 'app/view-layout/null.php';
        }
        $this->pathContent = 'app/view/'.$pathContent.'.php';
        require 'app/view-layout/master.php';
    }

    public function setTitle($title) {
        $this->title = $title." - CBA 2021";
    }

}
