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
    public function addpoint() { 
        if(empty(uri::get(2))) {
            $this->requirePostition("hr");
            $this->view->setTitle("add_point");
            $this->view->render("hr/add_point", "navbar");
            //$this->view->render("mkt/gg", "navbar");

        } else if (uri::get(2)==='post_add_point') {
            $this->positionEcho('hr', $this->model->editPoint());
        }

}
