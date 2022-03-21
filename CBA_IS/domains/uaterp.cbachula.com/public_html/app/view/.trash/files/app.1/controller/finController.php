<?php

namespace controller;

use _core\controller;
use _core\helper\input;
use _core\helper\session;
use _core\helper\uri;

class finController extends controller {

    public function index() {
        $this->err404();
    }
    
    public function registration_list() {
        $this->require_position('fin');
        if(empty(uri::get(2))) {
            $this->view->page = 'fin';
            $this->view->set_title('รายการการสมัคร - List of Registration');
            $this->view->render(['navbar', 'home/registration_list']);
        } else if(uri::get(2) == 'get_rgs') {
            echo $this->model->get_rgs();
        } else if(uri::get(2) == 'add_iv') {
            $this->require_post();
            echo $this->model->add_iv();
        } else if(uri::get(2) == 'report_rg') {
            $this->require_post();
            echo $this->model->report_rg();
        } else if(uri::get(2) == 'cancel_rg') {
            $this->require_post();
            echo $this->model->cancel_rg();
        }
    }

    public function transfer_report() {
        $this->require_position('fin');
        if(empty(uri::get(2))) {
            $this->view->set_title('รายงานการโอนเงิน - Transfer Report');
            $this->view->amount = $this->model->get_amount();
            $this->view->trs = $this->model->get_trs();
            $this->view->render(['navbar', 'fin/transfer_report']);
        } else if (uri::get(2) == 'add_tr') {
            $this->require_post();
            echo $this->model->add_tr();
        }
    }

    public function disbursement_voucher_list() {
        $this->require_position('fin');
        if(empty(uri::get(2))) {
            $this->view->page = 'fin';
            $this->view->employee_no = session::get('employee_no');
            $this->view->set_title('รายการใบเบิกเงิน - List of Disbursement Voucher');
            $this->view->render(['navbar', 'home/disbursement_voucher_list']);
        } else if(uri::get(2) == 'get_dvs') {
            echo $this->model->get_dvs();
        } else if(uri::get(2) == 'confirm_dv') {
            $this->require_post();
            echo $this->model->confirm_dv();
        } else if(uri::get(2) == 'add_slip') {
            $this->require_post();
            echo $this->model->add_slip_dv();
        } else if(uri::get(2) == 'cancel_dv') {
            $this->require_post();
            echo $this->model->cancel_dv();
        }
    }

    public function payment_voucher_list() {
        $this->require_position('fin');
        if(empty(uri::get(2))) {
            $this->view->page = 'fin';
            $this->view->set_title('รายการใบสำคัญจ่าย - List of Payment Voucher');
            $this->view->render(['navbar', 'home/payment_voucher_list']);
        } else if(uri::get(2) == 'get_pvs') {
            echo $this->model->get_pvs();
        } else if(uri::get(2) == 'add_slip') {
            $this->require_post();
            echo $this->model->add_slip_pv();
        }
    }

}