<?php

namespace _core;

use _core\helper\input;
use _core\helper\session;
use model;

abstract class controller {

    protected $model;
    protected $view;

    public function __construct() {
        $this->loadModel();
        $this->loadView();
    }

    private function loadModel() {
        $modelname = str_replace('Controller', 'Model', get_called_class());
        $modelname = str_replace('controller', 'model', $modelname);
        $this->model = new $modelname;
    }

    private function loadView() {
        $this->view = new view();
    }

    public function err404() {
        $this->view->setTitle('Page Not Found');
        $this->view->render('err404');
    }

    protected function requireSignIn() {
        if (is_null(session::get('employee_id')) || is_null(session::get('employee_password')) || is_null(session::get('employee_detail'))) {
            header("location:/signin");
        }
    }

    protected function requirePostition($position) {
        $this->requireSignIn();
        $getPosition = $this->getPosition();
        if ($position == "mkt") {
            if (!($getPosition == "ce" || $getPosition == "cm" || $getPosition == "smd" || $getPosition == "is")) {
                $this->err404();
            }
        } else if (!($getPosition == $position || $getPosition == "is")) {
            $this->err404();
        }
        // if (!($getPosition == "is" || $getPosition == "fin" || $getPosition == "acc" || $getPosition == "scm")) {
        //     $this->err404();
        //     exit();
        // }
        // if (!($getPosition == "is" || $getPosition == "acc")) {
        //     $this->err404();
        //     exit();
        // }
    }

    protected function getPosition() {
        return strtolower(json_decode(session::get('employee_detail'), true)['position']);
    }

    protected function requirePost() {
        if (is_null(input::post('post'))) {
            $this->err404();
            exit();
        }
    }

    protected function positionEcho($position, $echo) {
        $this->requirePostition($position);
        echo $echo;
    }

    public abstract function index();

}