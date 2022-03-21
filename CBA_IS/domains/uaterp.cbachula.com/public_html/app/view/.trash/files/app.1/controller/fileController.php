<?php

namespace controller;

use _core\controller;
use _core\helper\input;
use _core\helper\session;
use _core\helper\uri;

class fileController extends controller {

    public function index() {
        $this->err404();
    }

    public function iv() {
        if(empty(uri::get(2))) {
            $this->err404();
        } else {
            $this->view->set_title("ใบกำกับภาษี #".uri::get(2));
            $this->view->iv = $this->model->get_iv(uri::get(2));
            $this->view->render(['file/iv']);
        }
    }

    public function cn() {
        if(empty(uri::get(2))) {
            $this->err404();
        } else {
            $this->view->set_title("ใบลดหนี้ #".uri::get(2));
            $this->view->cn = $this->model->get_cn(uri::get(2));
            $this->view->render(['file/cn']);
        }
    }

    public function rv() {
        if(empty(uri::get(2))) {
            $this->err404();
        } else {
            $this->view->set_title("ใบสำคัญรับเงิน #".uri::get(2));
            $this->view->rv = $this->model->get_rv(uri::get(2));
            $this->view->render(['file/rv']);
        }
    }

    public function pv() {
        if(empty(uri::get(2))) {
            $this->err404();
        } else {
            $this->view->set_title("ใบสำคัญจ่าย #".uri::get(2));
            $this->view->pv = $this->model->get_pv(uri::get(2));
            $this->view->render(['file/pv']);
        }
    }

    public function slip() {
        if(empty(uri::get(2))) {
            $this->err404();
        } else {
            $this->model->get_slip(uri::get(2));
        }
    }

}