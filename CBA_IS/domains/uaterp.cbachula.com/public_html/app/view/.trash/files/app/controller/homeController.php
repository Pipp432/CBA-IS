<?php

namespace controller;

use _core\controller;
use _core\helper\input;
use _core\helper\session;

class homeController extends controller {

    public function index() {
        $this->require_signin();
        $this->view->set_title('หน้าแรก');
        $this->view->position = json_decode(session::get('employee_detail'), true)['employee_position'];
        $this->view->render(['navbar', 'home/index']);
    }

    public function cash_disbursement() {
        $this->require_signin();
        $this->view->set_title('ใบเบิกเงิน - Cash Disbursement (CD)');
        $this->view->render(['navbar', 'home/cash_disbursement']);
    }

}