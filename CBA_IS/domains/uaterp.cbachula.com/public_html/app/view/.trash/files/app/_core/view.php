<?php

namespace _core;

class view {

    public $title;

    public function __construct() {
        $this->set_title('');
    }

    public function render($paths) {
        $this->contents = $paths;
        require 'app/view_layout/master.php';
    }

    public function set_title($title) {
        $this->title = $title.' | Biz Cube Chulalongkorn';
    }

}
