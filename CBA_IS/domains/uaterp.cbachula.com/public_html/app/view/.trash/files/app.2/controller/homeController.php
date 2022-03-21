<?php

namespace controller;

use _core\controller;
use _core\helper\input;
use _core\helper\session;
use _core\helper\uri;

class homeController extends controller {

    public function index() {
        // $this->require_signin();
        if(empty(uri::get(2))) {
            $this->view->set_title('หน้าแรก');
            $this->view->render(['navbar', 'home/index']);
        } else if(uri::get(2) == 'get_courses') {
            echo $this->model->get_courses();
        }
    }

    // public function disbursement_voucher() {
    //     $this->require_signin();
    //     if(empty(uri::get(2))) {
    //         $this->view->position = session::get('employee_detail')['employee_position'];
    //         $this->view->payees = $this->model->get_payees();
    //         $this->view->batches = $this->model->get_batches();
    //         $this->view->set_title('ใบเบิกเงิน - Disbursement Voucher (DV)');
    //         $this->view->render(['navbar', 'home/disbursement_voucher']);
    //     } else if(uri::get(2) == 'add_dv') {
    //         $this->require_post();
    //         echo $this->model->add_dv();
    //     }
    // }

    // public function disbursement_voucher_list() {
    //     $this->require_signin();
    //     if(empty(uri::get(2))) {
    //         $this->view->page = 'home';
    //         $this->view->set_title('รายการใบเบิกเงิน - List of Disbursement Voucher');
    //         $this->view->render(['navbar', 'home/disbursement_voucher_list']);
    //     } else if(uri::get(2) == 'get_dvs') {
    //         echo $this->model->get_dvs();
    //     } else if(uri::get(2) == 'cancel_dv') {
    //         $this->require_post();
    //         echo $this->model->cancel_dv();
    //     }
    // }

}