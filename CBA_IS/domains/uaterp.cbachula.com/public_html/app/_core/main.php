<?php

namespace _core;

use controller;
use _core\helper\uri;
use _core\helper\session;

class main {

    public function __construct() {

        session::start();
        uri::setUri();

        $controller = is_null(uri::get(0)) ? "home" : uri::get(0);
        $action = is_null(uri::get(1)) ? "index" : uri::get(1);

        if (method_exists($this, $controller)) {
            $con = $this->{$controller}();
            if (method_exists($con, $action)) {
                $con->{$action}();
            } else {
                $con->err404();
            }
        } else {
            $this->home()->err404();
        }

    }

    public function home() {
        return new controller\homeController();
    }

    public function signin() {
        return new controller\signInController();
    }
    
    public function is() {
        return new controller\isController();
    }

    public function acc() {
        return new controller\accController();
    }

    public function fin() {
        return new controller\finController();
    }

    public function scm() {
        return new controller\scmController();
    }

    public function mkt() {
        return new controller\mktController();
    }

    public function hr() {
        return new controller\hrController();
    }
    
    public function file() {
        return new controller\fileController();
    }
    public function os() {
        return new controller\osController();
    }
    
}