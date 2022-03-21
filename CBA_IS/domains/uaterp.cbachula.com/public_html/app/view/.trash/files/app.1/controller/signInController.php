<?php

namespace controller;

use _core\controller;
use _core\helper\input;
use _core\helper\session;

class signInController extends controller {

    public function index() { 
        $this->view->set_title('เข้าสู่ระบบ');
        $this->view->render(['signIn/index']);
    }

    public function signin() {
        $this->require_post();
        echo $this->model->signin() ? 'valid' : 'invalid';
    }

    public function signout() {
        session::destroy();
        header('location:/signin');
    }

}
