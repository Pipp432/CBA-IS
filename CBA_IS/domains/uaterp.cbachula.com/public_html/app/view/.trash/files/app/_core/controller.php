<?php

namespace _core;

use _core\helper\input;
use _core\helper\session;
use model;

abstract class controller {

    protected $model;
    protected $view;

    public function __construct() {
        $this->load_model();
        $this->load_view();
    }

    private function load_model() {
        $modelname = str_replace('Controller', 'Model', get_called_class());
        $modelname = str_replace('controller', 'model', $modelname);
        $this->model = new $modelname;
    }

    private function load_view() {
        $this->view = new view();
    }

    public function err404() {
        $this->view->set_title('ไม่พบหน้าที่ต้องการ');
        $this->view->render('err404');
    }

    protected function require_signin() {
        if (is_null(session::get('employee_no')) || is_null(session::get('employee_password')) || is_null(session::get('employee_detail'))) {
            header("location:/signin");
        }
    }

    protected function require_position($position) {
        $this->require_signin();
        $get_position = json_decode(session::get('employee_detail'), true)['position'];
        if (!($get_position == $position || $get_position == 'is')) {
            $this->err404();
        }
    }

    protected function require_post() {
        if (is_null(input::post('post'))) {
            $this->err404();
            exit();
        }
    }

    public abstract function index();

}