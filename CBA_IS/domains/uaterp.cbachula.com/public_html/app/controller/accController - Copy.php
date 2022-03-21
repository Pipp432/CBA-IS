<?php

namespace controller;

use _core\controller;
use _core\helper\input;
use _core\helper\session;
use _core\helper\uri;

class accController extends controller {

    public function index() { 
        $this->requirePostition("acc");
        $this->err404();
    }
    
    public function invoice() {
        if(empty(uri::get(2))) {
            $this->requirePostition("acc");
            $this->view->setTitle("Invoice (IV)");
            $this->view->ivs = $this->model->getDashboardIv2();
            $this->view->render("acc/invoice", "navbar");
        }  
    }
    
    public function invoice_receipt_confirm() {
        if(empty(uri::get(2))) {
            $this->requirePostition("acc");
            $this->view->setTitle("Invoice Receipt Confirm (IVRC)");
            $this->view->rrcis = $this->model->getRRCINOIV();
            $this->view->render("acc/invoice_receipt_confirm", "navbar");
        } else if (uri::get(2)==='post_ivrc') {
            $this->positionEcho('acc', $this->model->addIvrc());
        }    
    }
    
    public function payment_voucher() {
        if(empty(uri::get(2))) {
            $this->requirePostition("acc");
            $this->view->setTitle("Payment Voucher (PV)");
            $this->view->render("acc/payment_voucher", "navbar");
        } else if (uri::get(2)==='get_rr_ci_no_pv') {
            $this->positionEcho('acc', $this->model->getRRCINOPV());
        } else if (uri::get(2)==='get_invoice') {
            $this->requirePostition("acc");
            if (!empty(Uri::get(3))) {
                $this->positionEcho('acc', $this->model->getRRCIInvoice(Uri::get(3)));
            } else {
                $this->err404();
            }
        } else if (uri::get(2)==='get_ws') {
            $this->positionEcho('acc', $this->model->getWS());
        } else if (uri::get(2)==='get_ws_form') {
            $this->requirePostition("acc");
            if (!empty(Uri::get(3))) {
                $this->positionEcho('acc', $this->model->getWsForm(Uri::get(3)));
            } else {
                $this->err404();
            }
        } else if (uri::get(2)==='get_ws_iv') {
            $this->requirePostition("acc");
            if (!empty(Uri::get(3))) {
                $this->positionEcho('acc', $this->model->getWsIv(Uri::get(3)));
            } else {
                $this->err404();
            }
        } else if (uri::get(2)==='post_pva') {
            $this->positionEcho('acc', $this->model->addPVA());
        } else if (uri::get(2)==='post_pvb') {
            $this->positionEcho('acc', $this->model->addPVB());
        } else if (uri::get(2)==='post_pvc') {
            $this->positionEcho('acc', $this->model->addPVC());
        } else if (uri::get(2)==='post_pvd') {
            
        }     
    }
    
    public function confirm_payment_voucher() {
        if(empty(uri::get(2))) {
            $this->requirePostition("acc");
            $this->view->setTitle("Confirm Payment Voucher");
            $this->view->render("acc/confirm_payment_voucher", "navbar");
        } else if (uri::get(2)==='get_rr_ci_pv') {
            $this->positionEcho('acc', $this->model->getRRCIPV());
        } else if (uri::get(2)==='get_receipt') {
            $this->requirePostition("acc");
            if (!empty(Uri::get(3))) {
                $this->positionEcho('acc', $this->model->getReceiptData(Uri::get(3)));
            } else {
                $this->err404();
            }
        } else if (uri::get(2)==='post_cpv_items') {
            $this->positionEcho('acc', $this->model->confirmPV());
        }   
    }
    
    public function credit_note() {
        if(empty(uri::get(2))) {
            $this->requirePostition("acc");
            $this->view->setTitle("Credit Note");
            $this->view->render("acc/credit_note", "navbar");
        } else if (uri::get(2)==='post_iv') {
            $this->positionEcho('acc', $this->model->getIvForCn());
        } else if (uri::get(2)==='post_cn') {
            $this->positionEcho('acc', $this->model->addCn());
        } 
    }
    
    public function general_journal() {
        if(empty(uri::get(2))) {
            $this->requirePostition("acc");
            $this->view->setTitle("General Journal");
            $this->view->accountDetails = $this->model->getAccountDetail();
            $this->view->render("acc/general_journal", "navbar");
        } else if (uri::get(2)==='get_account_detail') {
            $this->positionEcho('acc', $this->model->getAccountDetail());
        }
    }
    
    public function po_calculator() {
        if(empty(uri::get(2))) {
            $this->requirePostition("acc");
            $this->view->setTitle("PO Calculator");
            $this->view->render("acc/po_calculator", "navbar");
        } else if (uri::get(2)==='calculate_po') {
            $this->positionEcho('acc', $this->model->calculatePo());
        }
    }
    
    public function dashboard() {
        if(empty(uri::get(2))) {
            $this->requirePostition("acc");
            $this->view->setTitle("Dashboard");
            $this->view->dashboardIv = $this->model->getDashboardIv();
            $this->view->dashboardPv = $this->model->getDashboardPv();
            $this->view->dashboardPo = $this->model->getDashboardPo();
			$this->view->dashboardCr = $this->model->getDashboardCr();
            $this->view->render("acc/dashboard", "navbar");
        } else if (uri::get(2)==='pv_slip') {
            if (!empty(Uri::get(3))) {
                $this->positionEcho('acc', $this->model->getSlipData(Uri::get(3)));
            } else {
                $this->err404();
            }
        }
    }
    
    public function dashboard_acc() {
        if(empty(uri::get(2))) {
            $this->requirePostition("fin");
            $this->view->setTitle("Dashboard");
            $this->view->dashboardIv = $this->model->getDashboardIv();
            $this->view->dashboardPv = $this->model->getDashboardPv();
            $this->view->dashboardPo = $this->model->getDashboardPo();
			$this->view->dashboardCr = $this->model->getDashboardCr();
            $this->view->render("acc/dashboard", "navbar");
        } else if (uri::get(2)==='pv_slip') {
            if (!empty(Uri::get(3))) {
                $this->positionEcho('acc', $this->model->getSlipData(Uri::get(3)));
            } else {
                $this->err404();
            }
        }
    }
    
    // public function dashboard2() {
    //     if(empty(uri::get(2))) {
    //         $this->requirePostition("acc");
    //         $this->view->setTitle("Dashboard");
    //         $this->view->dashboards = $this->model->getDashboardPo();
    //         $this->view->render("scm/dashboard", "navbar");
    //     } else if (uri::get(2)==='get_dashboard') {
    //         $this->positionEcho('acc', $this->model->getDashboardPo());
    //     }
    // }

 public function invoice_cs() {
        if(empty(uri::get(2))) {
            $this->requirePostition("acc");
            $this->view->setTitle("Invoice for CS (IC)");
            $this->view->CSs = $this->model->getCSforIV();
            $this->view->render("acc/invoice_cs","navbar");
        } else if (uri::get(2)==='post_iv') {
            $this->positionEcho('acc', $this->model->addIV());
        } 
    }
    
    public function return_inventory() {
        if(empty(uri::get(2))) {
            $this->requirePostition("acc");
            $this->view->setTitle("Return Inventory (RE)");
            $this->view->suppliers = $this->model->getSuppliers();
            $this->view->REproducts = $this->model->getREProduct();
            $this->view->render("acc/return_inventory", "navbar");
        } else if (uri::get(2)==='post_re_items') {
            $this->positionEcho('acc', $this->model->addRE());
        }
    }
    public function re_dashboard() {
        if(empty(uri::get(2))) {
            $this->requirePostition("acc");
            $this->view->setTitle("RE-Dashboard");
            $this->view->REreport = $this->model->getREreport();
            $this->view->render("acc/re_dashboard", "navbar");
        }
    }
}