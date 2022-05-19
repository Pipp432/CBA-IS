<?php

namespace controller;

use _core\controller;
use _core\helper\input;
use _core\helper\session;

class signInController extends controller {

    public function index() { 
        $this->view->setTitle("Sign In");
        $this->view->render("signIn/index");
    }

    public function signIn() {
        $this->requirePost();
        if(!$this->model->signIn(input::post('employee_id'), input::post('employee_password'))){
            echo "invalid";
        }
    }

    public function signOut() {
        session::destroy();
        header("location:/signin");
    }

    public function bubble() {
        $this->view->setTitle("Bubble Wrap");
        $this->view->render("signIn/bubble");
    }

}
