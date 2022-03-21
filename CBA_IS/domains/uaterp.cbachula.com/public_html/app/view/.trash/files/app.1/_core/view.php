<?php

namespace _core;
use _core\helper\session;

class view {

    public $title;

    public function __construct() {
        $this->set_title('');
    }

    public function render($paths) {
        $this->contents = $paths;
        $this->employee_name = session::get('employee_no').' '.session::get('employee_detail')['employee_nickname'];
        require 'app/view/master.php';
    }

    public function set_title($title) {
        $this->title = $title.' | Biz Cube Chulalongkorn';
    }

}
