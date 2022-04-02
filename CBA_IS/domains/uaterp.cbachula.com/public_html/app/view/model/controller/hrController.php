<?php

namespace controller;

use _core\controller;
use _core\helper\input;
use _core\helper\session;

class hrController extends controller {

    public function index() { 
        $this->requirePostition("hr");
        $this->err404();
    }

}
