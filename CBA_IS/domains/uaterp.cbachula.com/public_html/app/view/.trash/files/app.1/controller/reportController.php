<?php

namespace controller;

use _core\controller;
use _core\helper\input;
use _core\helper\session;
use _core\helper\uri;

class reportController extends controller {

    public function index() {
        $this->err404();
    }

    public function income_statement() {
        $this->require_position('acc');
        if(empty(uri::get(2))) {
            $this->view->set_title('งบกำไรขาดทุน - Income Statement');
            $this->view->logs = $this->model->get_income_statement();
            $this->view->render(['navbar', 'report/income_statement']);
        } else {
            $this->err404();
        }
    }

    public function vat_report() {
        $this->require_position('acc');
        if(empty(uri::get(2))) {
            $this->view->set_title('รายงานภาษีมูลค่าเพิ่ม - VAT Report');
            $this->view->vats = $this->model->get_vats();
            $this->view->render(['navbar', 'report/vat_report']);
        }
    }

    public function income_statement2() {
        $this->require_signin();
        if(empty(uri::get(2))) {
            $this->view->set_title('งบกำไรขาดทุน - Income Statement');
            $this->view->revenues = $this->model->get_revenues();
            $this->view->expenses = $this->model->get_expenses();
            $this->view->render(['navbar', 'report/income_statement']);
        }
    }

}