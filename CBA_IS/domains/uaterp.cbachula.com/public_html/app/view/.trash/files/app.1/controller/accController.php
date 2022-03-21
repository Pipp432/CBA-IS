<?php

namespace controller;

use _core\controller;
use _core\helper\input;
use _core\helper\session;
use _core\helper\uri;

class accController extends controller {

    public function index() {
        $this->err404();
    }

    public function tax_invoice_list() {
        $this->require_position('acc');
        if(empty(uri::get(2))) {
            $this->view->set_title('รายการใบกำกับภาษี - List of Tax Invoice');
            $this->view->render(['navbar', 'acc/tax_invoice_list']);
        } else if(uri::get(2) == 'get_ivs') {
            echo $this->model->get_ivs();
        } else if(uri::get(2) == 'edit_tax_invoice' && !empty(uri::get(3))) {
            if (uri::get(3) == 'edit_iv') {
                $this->require_post();
                echo $this->model->edit_iv();
            } else {
                $this->view->set_title('แก้ไขใบกำกับภาษี - Edit Tax Invoice');
                $this->view->tax_invoice_detail = $this->model->get_tax_invoice_detail(uri::get(3));
                $this->view->render(['navbar', 'acc/edit_tax_invoice']);
            }
        } else if(uri::get(2) == 'add_wc') {
            $this->require_post();
            echo $this->model->add_wc();
        } else if(uri::get(2) == 'add_pv_type_4') {
            $this->require_post();
            echo $this->model->add_pv_type_4();
        }
    }

    public function disbursement_voucher_list() {
        $this->require_position('acc');
        if(empty(uri::get(2))) {
            $this->view->page = 'acc';
            $this->view->employee_no = session::get('employee_no');
            $this->view->dvs = $this->model->get_dvs();
            $this->view->set_title('รายการใบเบิกเงิน - List of Disbursement Voucher');
            $this->view->render(['navbar', 'home/disbursement_voucher_list']);
        } else if(uri::get(2) == 'confirm_paid_dv') {
            $this->require_post();
            echo $this->model->confirm_paid_dv();
        } else if(uri::get(2) == 'add_pv_type_1') {
            $this->require_post();
            echo $this->model->add_pv_type_1();
        } else if(uri::get(2) == 'add_pv_type_2') {
            $this->require_post();
            echo $this->model->add_pv_type_2();
        }
    }

    public function payment_voucher_list() {
        $this->require_position('acc');
        if(empty(uri::get(2))) {
            $this->view->page = 'acc';
            $this->view->set_title('รายการใบสำคัญจ่าย - List of Payment Voucher');
            $this->view->render(['navbar', 'home/payment_voucher_list']);
        } else if(uri::get(2) == 'get_pvs') {
            echo $this->model->get_pvs();
        } else if(uri::get(2) == 'confirm_paid_pv') {
            $this->require_post();
            echo $this->model->confirm_paid_pv();
        } else if(uri::get(2) == 'edit_pv') {
            $this->require_post();
            echo $this->model->edit_pv();
        } else if(uri::get(2) == 'cancel_pv') {
            $this->require_post();
            echo $this->model->cancel_pv();
        }
    }

}