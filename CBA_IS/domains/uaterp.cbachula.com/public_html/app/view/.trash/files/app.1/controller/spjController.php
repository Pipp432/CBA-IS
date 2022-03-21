<?php

namespace controller;

use _core\controller;
use _core\helper\input;
use _core\helper\session;
use _core\helper\uri;

class spjController extends controller {

    public function index() {
        $this->err404();
    }

    public function registration_list() {
        $this->require_position('spj');
        if(empty(uri::get(2))) {
            $this->view->page = 'spj';
            $this->view->set_title('รายการการสมัคร - List of Registration');
            $this->view->render(['navbar', 'home/registration_list']);
        } else if(uri::get(2) == 'get_rgs') {
            echo $this->model->get_rgs();
        } else if(uri::get(2) == 'confirm_rg') {
            $this->require_post();
            echo $this->model->confirm_rg();
        } else if(uri::get(2) == 'cancel_rg') {
            $this->require_post();
            echo $this->model->cancel_rg();
        }
    }

    public function edit_registration() {
        $this->require_position('spj');
        if(empty(uri::get(2))) {
            $this->err404();
        } else {
            $this->view->page = 'spj';
            $this->view->set_title('แก้ไขการสมัคร - Edit Registration');
            $this->view->render(['navbar', 'spj/edit_registration']);
        }
    }

    public function disbursement_voucher_list() {
        $this->require_position('spj');
        if(empty(uri::get(2))) {
            $this->view->page = 'spj';
            $this->view->dvs = $this->model->get_dvs();
            $this->view->set_title('รายการใบเบิกเงิน - List of Disbursement Voucher');
            $this->view->render(['navbar', 'home/disbursement_voucher_list']);
        } else if(uri::get(2) == 'confirm_paid_dv') {
            $this->require_post();
            echo $this->model->confirm_paid_dv();
        }
    }

}