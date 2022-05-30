<?php

namespace controller;

use _core\controller;
use _core\helper\input;
use _core\helper\session;
use _core\helper\uri;
use PhpMyAdmin\Url;

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
    
	public function create_invoice() {
        if(empty(uri::get(2))) {
            $this->requirePostition("acc");
            $this->view->setTitle("Create Invoice (IV)");
            $this->view->soxs = $this->model->getSoxsForIv();
            $this->view->render("acc/create_invoice","navbar");
        } else if (uri::get(2)==='post_iv') {
            $this->positionEcho('fin', $this->view->iv_no = $this->model->addIV_noCR());
        } 
    }
	// -> Object operator
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
            if (!empty(Uri::get(3))||!empty(Uri::get(4))) {
                $this->positionEcho('acc', $this->model->getWsIv(Uri::get(3),Uri::get(4)));
            } else {
                $this->err404();
            }
        } else if (uri::get(2)==='post_pva') {
            $this->positionEcho('acc', $this->model->addPVA());
        } else if (uri::get(2)==='get_quotatiob') {
            $this->positionEcho('acc', $this->model->getQuotation());
        }else if (uri::get(2)==='post_pvb') {
            $this->positionEcho('acc', $this->model->addPVB());
        } else if (uri::get(2)==='post_pvc') {
            $this->positionEcho('acc', $this->model->addPVC());
        }else if (uri::get(2)==='get_ReReqs') {
            $this->positionEcho('acc', $this->model->getReReqsDetail());
        }else if (uri::get(2)==='get_PVC') {
            $this->positionEcho('acc', $this->model->getPVC());
        }else if (uri::get(2)==='due_date') {
            $this->positionEcho('acc', $this->model->postDueDate(Uri::get(3)));
        }else if(uri::get(2)==='confirm'){
            $this->positionEcho('acc', $this->model->postConfirm(Uri::get(3)));
        }else if(uri::get(2)==='get_IVPC_Files'){
            $this->positionEcho('acc', $this->model->getIVPCFiles(Uri::get(3),Uri::get(4)));
        } else if(uri::get(2)==='get_PVD'){
            $this->positionEcho('acc', $this->model->getPVDForPV()); 
        } else if(uri::get(2)==='update_PVD'){
            $this->positionEcho('acc', $this->model->updatePVDForPV()); 
        } else if(uri::get(2)==='post_PVD'){
            $this->positionEcho('acc', $this->model->postPVDForPV()); 
        } else if(uri::get(2)==='get_PVA'){
            $this->positionEcho('acc', $this->model->getPVAForPV());
        } else if(uri::get(2)==='post_PVA'){
            $this->positionEcho('acc', $this->model->postPVAForPV());
        }else if(uri::get(2)==='get_quotation'){
            $this->positionEcho('acc', $this->model->getQuotation(Uri::get(3)));
        } else if(uri::get(2)==='get_PVA_child'){
            $this->positionEcho('acc', $this->model->getPVAChild());
        } else if (uri::get(2)==='get_petty_cash_statement') {
            if (!empty(Uri::get(3))) {
                $this->positionEcho('acc', $this->model->getPettyCashStatement(Uri::get(3)));
            } else {
                $this->err404();
            }
        }
    }
    
    public function confirm_payment_voucher() {
        if(empty(uri::get(2))) {
            $this->requirePostition("acc");
            $this->view->setTitle("Confirm Payment Voucher");
            $this->view->render("acc/confirm_payment_voucher", "navbar");
        } else if (uri::get(2)==='get_rr_ci_pv') {
            $this->positionEcho('acc', $this->model->getRRCIPV());
        } else if (uri::get(2)==='get_pvd') {
            $this->positionEcho('acc', $this->model->getPVDConfirmPV()); 
        } else if (uri::get(2)==='get_pva') {
            $this->positionEcho('acc', $this->model->getPVAConfirmPV()); 
        }else if (uri::get(2)==='get_pvc') {
            $this->positionEcho('acc', $this->model->getPVCConfirmPV()); 
        } else if (uri::get(2)==='get_receipt') {
            $this->requirePostition("acc");
            if (!empty(Uri::get(3))) {
                $this->positionEcho('acc', $this->model->getReceiptData(Uri::get(3)));
            } else {
                $this->err404();
            }
        } else if (uri::get(2)==='get_pvaslip') {
            $this->requirePostition("acc");
            if (!empty(Uri::get(3))) {
                $this->positionEcho('acc', $this->model->getPVAReceiptData(Uri::get(3)));
            } else {
                $this->err404();
            }
        } else if (uri::get(2)==='get_pvcslip') {
            $this->requirePostition("acc");
            if (!empty(Uri::get(3))) {
                $this->positionEcho('acc', $this->model->getPVCReceiptData(Uri::get(3)));
            } else {
                $this->err404();
            }
        } else if (uri::get(2)==='get_pvdslip') {
            $this->requirePostition("acc");
            if (!empty(Uri::get(3))) {
                $this->positionEcho('acc', $this->model->getPVDReceiptData(Uri::get(3)));
            } else {
                $this->err404();
            }
        } else if (uri::get(2)==='post_cpv_items') {
            $this->positionEcho('acc', $this->model->confirmPV());
        } else if (uri::get(2)==='post_cpvc_items') {
            $this->positionEcho('acc', $this->model->confirmPVC());
        }
        else if (uri::get(2)==='post_cpvd_items') {
            $this->positionEcho('acc', $this->model->confirmPVD());
        } else if (uri::get(2)==='post_cpva_items') {
            $this->positionEcho('acc', $this->model->confirmPVA());
        }
    }
    
    
    public function credit_note() {
        if(empty(uri::get(2))) {
            $this->requirePostition("acc");
            $this->view->setTitle("Credit Note");
            $this->view->WSDs = $this->model->getWSD();
            $this->view->render("acc/credit_note", "navbar");
        } else if (uri::get(2)==='post_iv') {
            $this->positionEcho('acc', $this->model->getIvForCn());
        } else if (uri::get(2)==='post_cn') {
            $this->positionEcho('acc', $this->model->addCn());
        } else if (uri::get(2)==='update_PVD') {
            $this->positionEcho('acc', $this->model->updateWSDCreditNote());
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
	
	public function search_porrci() {
        if(empty(uri::get(2))) {
            $this->requirePostition("acc");
            $this->view->setTitle("Search PO/RR/CI");
            $this->view->render("acc/search_porrci", "navbar");
        } else if (uri::get(2)==='search') {
            $this->positionEcho('acc', $this->model->searchPoRrCi());
        }
    }
    
    public function dashboard() {
        if(empty(uri::get(2))) {
            $this->requirePostition("acc");
            $this->view->setTitle("Dashboard");
            $this->view->dashboardIv = $this->model->getDashboardIv();
            $this->view->dashboardPv = $this->model->getDashboardPv();
            $this->view->dashboardPva = $this->model->getDashboardPva();
            $this->view->dashboardPvb = $this->model->getDashboardPvb();
            $this->view->dashboardPvd = $this->model->getDashboardPvd();
            $this->view->dashboardPrePvd = $this->model->getDashboardPrePvd();
            $this->view->dashboardPo = $this->model->getDashboardPo();
			$this->view->dashboardCr = $this->model->getDashboardCr();
            $this->view->dashboardPvc = $this->model->getDashboardPvc();
            $this->view->dashboardPvc_confirm = $this->model->getDashboardPvc_confirm();
            $this->view->render("acc/dashboard", "navbar");
        } else if (uri::get(2)==='pv_slip') {
            if (!empty(Uri::get(3))) {
                $this->positionEcho('acc', $this->model->getSlipData(Uri::get(3)));
            } else {
                $this->err404();
            }
        } else if (uri::get(2)==='get_IVPC_Files_dashboard') {
            if (!empty(Uri::get(4))) {
                $this->positionEcho('acc', $this->model->getIVPCFilesDashboard(Uri::get(3),Uri::get(4)));
            } else {
                $this->err404();
            }
        } else if (uri::get(2)==='get_PVB_CR') {
            if (!empty(Uri::get(3))) {
                $this->positionEcho('acc', $this->model->getPVBCR(Uri::get(3)));
            } else {
                $this->err404();
            }
        }
        else if (uri::get(2)==='get_PVC_slip') {
            if (!empty(Uri::get(3))) {
                $this->positionEcho('acc', $this->model->getPVCRR(Uri::get(3)));
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
            $this->view->setTitle("Return Inventory (RI)");
            $this->view->suppliers = $this->model->getSuppliers();
            $this->view->RIproducts = $this->model->getRIProduct();
            $this->view->render("acc/return_inventory", "navbar");
        } else if (uri::get(2)==='post_rI_items') {
            $this->positionEcho('acc', $this->model->addRI());
        }
    }
    public function ri_dashboard() {
        if(empty(uri::get(2))) {
            $this->requirePostition("acc");
            $this->view->setTitle("RI-Dashboard");
            $this->view->REreport = $this->model->getRIreport();
            $this->view->render("acc/ri_dashboard", "navbar");
        }
    }
	
	//public function confirm_ird() {
	//	if(empty(uri::get(2))) {
    //        $this->requirePostition("acc");
    //        $this->view->setTitle("Confirm IRD");
    //        $this->view->irds = $this->model->getIRDforConfirm();
    //        $this->view->render("acc/confirm_ird", "navbar");
	//	} else if (uri::get(2)==='post_cird_items') {
    //        $this->positionEcho('acc', $this->model->confirmIRD());
    //    } else if (uri::get(2)==='ird_file') {
    //        $this->requirePostition("acc");
    //        if (!empty(Uri::get(3))) {
    //            $this->positionEcho('acc', $this->model->getIrdFile(Uri::get(3)));
    //        } else {
    //            $this->err404();
    //        }
    //    }
	//}


    public function confirm_iv() {
        if(empty(uri::get(2))) {
            $this->requirePostition("acc");
            $this->view->setTitle("Confirm IV");
            $this->view->invoices = $this->model->getConfirmIV();
            $this->view->render("acc/confirm_iv", "navbar");
        } 
        else if (uri::get(2)==='conivItems') {
            $this->positionEcho('acc', $this->model->confirmIV());
        }
    }

    public function print_iv() {
        if(empty(uri::get(2))) {
            $this->requirePostition("acc");
            $this->view->setTitle("Print IV");
            $this->view->invoicess = $this->model->getPrintIV();
            $this->view->render("acc/print_iv", "navbar");
        } 
        else if (uri::get(2)==='printivItems') {
            $this->positionEcho('acc', $this->model->confirmPrintIV());
        } 
    }
    


}