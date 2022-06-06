<?php

namespace controller;

use _core\controller;
use _core\helper\input;
use _core\helper\session;
use _core\helper\uri;

class hrController extends controller {

    public function index() { 
        $this->requirePostition("hr");
        $this->err404();
    }

    public function add_point() {
        if(empty(uri::get(2))) {
            $this->requirePostition("hr");
            $this->view->setTitle("Add Learning Point");
            $this->view->render("hr/add_point", "navbar"); 
        } else if (uri::get(2) === 'post_point') {
            $this->positionEcho('hr', $this->model->postPoint());
        } else if (uri::get(2) === 'post_edit_point') {
            $this->positionEcho('hr', $this->model->editPoint());
        }

        
    }



}
