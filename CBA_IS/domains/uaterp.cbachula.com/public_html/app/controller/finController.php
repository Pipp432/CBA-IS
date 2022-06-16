<?php

namespace controller;

use _core\controller;
use _core\helper\input;
use _core\helper\session;
use _core\helper\uri;

class finController extends controller {

    public function index() { 
        $this->requirePostition("fin");
        $this->err404();
    }

    public function cash_receipt() {
        if(empty(uri::get(2))) {
            $this->requirePostition("fin");
            $this->view->setTitle("Cash Receipt (CR)");
            $this->view->soxs = $this->model->getSoxsForCr();
            $this->view->render("fin/cash_receipt","navbar");
        } else if (uri::get(2)==='post_ivcr') {
            $this->positionEcho('fin', $this->view->cr_no = $this->model->addCr());
        } else if (uri::get(2)==='sox_slip') {
            $this->requirePostition("fin");
            if (!empty(Uri::get(3))) {
                $this->positionEcho('fin', $this->model->getSoxReceipt(Uri::get(3)));
            } else {
                $this->err404();
            }
        }
    }
	public function create_cash_receipt() {
        if(empty(uri::get(2))) {
            $this->requirePostition("fin");
            $this->view->setTitle("Cash Receipt with IV (CR)");
            $this->view->soxs = $this->model->getSoxsForCr_withIV();
            $this->view->render("fin/create_cash_receipt","navbar");
        } else if (uri::get(2)==='post_ivcr') {
            $this->positionEcho('fin', $this->view->cr_no = $this->model->addCr_withIV());
        } else if (uri::get(2)==='sox_slip') {
            $this->requirePostition("fin");
            if (!empty(Uri::get(3))) {
                $this->positionEcho('fin', $this->model->getSoxReceipt(Uri::get(3)));
            } else {
                $this->err404();
            }
        }
    }

public function show_ws_form() {
        if(empty(uri::get(2))) {
            $this->err404();
        } else {
            $this->model->showWsForm(uri::get(2));
        }
}

public function upload_slip_pvc(){
    if(empty(uri::get(2))) {
        $this->view->render("fin/upload_slip_pvc","navbar");
    }else if(uri::get(2)==='get_PVCs'){
        $this->positionEcho('fin', $this->model->getPVCs());

    }
    else if(uri::get(2)==='add_slip'){
        $this->positionEcho('fin', $this->model->addSlipToPVC(uri::get(3)));

    }
}
public function upload_iv_pvc(){
    if(empty(uri::get(2))) {
        $this->view->render("fin/upload_iv_pvc","navbar");
    }else if(uri::get(2)==='get_PVCs'){
        $this->positionEcho('fin', $this->model->getPVCsForIV());

    }
    else if(uri::get(2)==='add_iv'){
        $this->positionEcho('fin', $this->model->addIVToPVC(uri::get(3)));

    }
}

public function ws_list() {
$list = $this->model->getWsFormList();
foreach ($list as $value) {
    echo '<p><a href="/fin/show_ws_form/'.$value['form_no'].'">'.$value['form_no'].'</a></p>';
}
}
    
    public function demo() {
        if(empty(uri::get(2))) {
            $this->requirePostition("fin");
            $this->view->setTitle("Cash Receipt (CR)");
            $this->view->soxs = $this->model->getSoxsForCr();
            $this->view->render("fin/demo","navbar");
        } else if (uri::get(2)==='post_ivcr') {
            $this->positionEcho('fin', $this->model->addCr());
        } else if (uri::get(2)==='sox_slip') {
            $this->requirePostition("fin");
            if (!empty(Uri::get(3))) {
                $this->positionEcho('fin', $this->model->getSoxReceipt(Uri::get(3)));
            } else {
                $this->err404();
            }
        }
    }

    public function special_sales() {
        $this->requirePostition("fin");
        $this->view->setTitle("Special Sales");
        $this->view->render("fin/special_sales","navbar");
    }
    
    // public function transfer_report() {
    //     if(empty(uri::get(2))) {
    //         $this->requirePostition("fin");
    //         $this->view->setTitle("Transfer Report (TR)");
    //         $this->view->crnotrs = $this->model->getCrNoTr();
    //         $this->view->render("fin/transfer_report","navbar");
    //     } else if (uri::get(2)==='post_tr') {
    //         $this->positionEcho('fin', $this->model->addTr());
    //     } 
    // }
    
    public function dashboard() {
        if(empty(uri::get(2))) {
            $this->requirePostition("fin");
            $this->view->setTitle("Dashboard");
            $this->view->dashboards = $this->model->getDashboard();
            $this->view->render("fin/dashboard", "navbar");
        }
    }
    public function tr() {
        if(empty(uri::get(2))) {
            $this->requirePostition("fin");
            $this->view->setTitle("TR");
            $this->view->tr = $this->model->getTR();
			$this->view->tr_range = $this->model->get_TR_range();
			$this->view->tr_note = $this->model->get_TR_note();
			$this->view->tr_list = $this->model->get_TR_list();
            $this->view->render("fin/TR", "navbar");
        }else if (uri::get(2)==='post_tr') {
            $this->positionEcho('fin', $this->model->addTR());
        }
    }
	public function pv_check() {
        if(empty(uri::get(2))) {
            $this->requirePostition("fin");
            $this->view->setTitle("PV Check");
            $this->view->pv_list = $this->model->getPVCHECK();
            $this->view->render("fin/pv_check", "navbar");
        }else if (uri::get(2)==='pv') {
            $this->requirePostition("fin");
            if (!empty(Uri::get(3))) {
                $this->positionEcho('fin', $this->model->getPVSlip(Uri::get(3)));
            } else {
                $this->err404();
            }
        }
    }
    public function pvc(){
        if(empty(uri::get(2))) {
            $this->requirePostition("fin");
            $this->view->setTitle("Payment Voucher (PV)");
            $this->view->render("fin/pvc", "navbar");
        }else if(uri::get(2)==="get_PVCs"){
            $this->positionEcho('fin', $this->model->getPVCs());
        }else if(uri::get(2)==="get_PVC_Detail"){
            $this->positionEcho('fin', $this->model->getPVCDetail(uri::get(3)));
        }else if(uri::get(2)==="post_additional_data"){
            $this->positionEcho('fin', $this->model->postAdditionDetail(uri::get(3)));
        }
    }
    public function reimbursement_request(){
        if(empty(uri::get(2))) {
            $this->requirePostition("fin");
            $this->view->setTitle("Reimbursement Request");
            $this->view->render("fin/reimbursement_request", "navbar");
        }else if(uri::get(2)==="get_re_req"){
            $this->positionEcho('fin', $this->model->getReReq());
        }else if(uri::get(2)==="get_re_req_Detail"){
            $this->positionEcho('fin', $this->model->getReReqDetails(uri::get(3)));
        }else if(uri::get(2)==="post_additional_data"){
            $this->positionEcho('fin', $this->model->postAdditionReReqDetail());
        }
        else if(uri::get(2)==="cancel_exc"){
            $this->positionEcho('fin', $this->model->cancelEXC());
        }
    }
    public function upload()
    {
        if(empty(uri::get(2))) {
            //$this->requirePostition("fin");
            $this->view->getuploaddata = $this->model->GetUploadData(input::post('fileid'));
            $this->view->setTitle("Upload Test");
            $this->view->render("fin/upload");
        } else if (uri::get(2)==='sentfile') {
              if($this->model->insertfile(input::post('filename'),input::post('filedata'),input::post('filetype'),input::post('fileid'))){
                
                echo "valid";
              } else {
                echo "invalid";
              }
        } 
        
    }
    public function testuplode()
    {
        $this->view->setTitle("Uplode-Test");
        $this->view->render("fin/testupload");
    }
    public function viewfile()
    {
        $this->view->getuploaddata = $this->model->GetUploadData("11");
        $this->view->setTitle("view-Test");
        $this->view->render("fin/viewfile");
    }
     public function WithdrawalSlip()
    {

        if(empty(uri::get(2))) {
            $this->requirePostition("fin");
            $this->view->setTitle("Withdrawal Slip");
            $this->view->render("fin/WithdrawalSlip","navbar");
        } 
         else if (uri::get(2)==='createwstype1') {
            
                if($this->model->insertwstype1(input::post('emid'),input::post('type'),input::post('formid'),input::post('filename1'),input::post('filetype1'),input::post('filedata1'),input::post('filename2'),input::post('filetype2'),input::post('filedata2'),input::post('filename3'),input::post('filetype3'),input::post('filedata3'),input::post('filename4'),input::post('filetype4'),input::post('filedata4'),input::post('filename5'),input::post('filetype5'),input::post('filedata5')))
                {
                    echo"valid";
                }
                else echo"not success";
            
        
        }
        /*else if (uri::get(2)==='createwstype2') {
            
                if($this->model->insertwstype2(input::post('emid'),input::post('type'),input::post('formid'),input::post('filename1'),input::post('filetype1'),input::post('filedata1'),input::post('filename2'),input::post('filetype2'),input::post('filedata2'),input::post('filename3'),input::post('filetype3'),input::post('filedata3')))
                {
                    echo"valid";
                }
                else echo"not success";
            
        
        }*/
        else if (uri::get(2)==='createivtype3') {
        
        if($this->model->insertivtype3(input::post('filename'),input::post('filetype'),input::post('filedata'),input::post('ivno'))){
                echo "valid";
            } else {
                echo "invalid";
            }
        
       }
       else if (uri::get(2)==='createslip') {
            if($this->model->findwfno(input::post('wfno'))){
                if($this->model->insertslip(input::post('filename'),input::post('filetype'),input::post('filedata'),input::post('pvno'),input::post('wfno')))
                {
                    echo"valid";
                }
                else echo"not success";
            } else {
                echo "invalid";
            }
        
        }
        else if (uri::get(2)==='createreceipt') {
            
                if($this->model->insertreceipt(input::post('filename'),input::post('filetype'),input::post('filedata'),input::post('pvno')))
                {
                    echo"valid";
                }
                else echo"not success";
            
        
        }
        else if (uri::get(2)==='createslipForSup') {
            
                echo $this->model->insertslipForSup(input::post('filename'),input::post('filetype'),input::post('filedata'),input::post('filename2'),input::post('filetype2'),input::post('filedata2'),input::post('pvno'));

        
        }
       
    }
    public function WS()
    {
        if(empty(uri::get(2))) {
        
        $this->requirePostition("fin");
        $this->view->status2data = $this->model->GetStatus2Data(); //cause error 500
        $this->view->pvforreceipt = $this->model->GetPVforReceipt();
        $this->view->pvfortranfer = $this->model->GetPVforTranfer();
        $this->view->wstype3data = $this->model->GetWSType3();
        $this->view->pvas = $this->model->GetPVAforWS();
        $this->view->setTitle("Withdrawal Slip");
        $this->view->render("fin/WS","navbar");
        
        } else if (uri::get(2)==='get_ws_form') { 
            $this->requirePostition("fin");
            if (!empty(Uri::get(3))) {
                $this->positionEcho('fin', $this->model->getWsForm(Uri::get(3)));
            } else {
                $this->err404();
            }
        }
        else if (uri::get(2)==='get_ws_iv') {
            $this->requirePostition("fin");
            if (!empty(Uri::get(3))) {
                $this->positionEcho('fin', $this->model->getWsIv(Uri::get(3)));
            } else {
                $this->err404();
            }
        }
        else if (uri::get(2)==='get_ws_iv2') {
            $this->requirePostition("fin");
            if (!empty(Uri::get(3))) {
                $this->positionEcho('fin', $this->model->getWsIv2(Uri::get(3)));
            } else {
                $this->err404();
            }
        } else if (uri::get(2)==='PVA_slip') {
            $this->positionEcho('fin', $this->model->postSlipPVA());
        }
    }//ws

    public function validate_petty_cash_request() {
        if(empty(uri::get(2))) {
            $this->view->setTitle("confirm เบิกเงินรองจ่าย"); 
            $this->view->minor_requests = $this->model->getMinorRequestForFin();
            $this->view->render("fin/validate_petty_cash_request", "fin/pva_fin_hub"); 
        } else if(uri::get(2)==='get_re') {
            if (!empty(Uri::get(3))) {
                $this->positionEcho('fin', $this->model->getRe(Uri::get(3))); 
            } else {
                $this->err404();
            }
        } else if(uri::get(2)==='get_iv') {
            if (!empty(Uri::get(3))) {
                $this->positionEcho('fin', $this->model->getIv(Uri::get(3))); 
            } else {
                $this->err404();
            }
        } else if(uri::get(2) === 'confirm_request'){
            $this->positionEcho('fin', $this->model->confirmPettyCashRequest()); 
        } else if(uri::get(2) === 'reject_request'){
            $this->positionEcho('fin', $this->model->rejectPettyCashRequest()); 
        }
    }

    public function create_pva() {
        if(empty(uri::get(2))) {
            $this->view->setTitle("create PV-A"); 
            $this->view->render("fin/create_pva", "fin/pva_fin_hub"); 
        } else if(uri::get(2) === "get_pva") {
            $this->positionEcho('fin', $this->model->getPVAForCreation()); 
        } else if(uri::get(2) === "create_pva") {
            $this->positionEcho('fin', $this->model->bundlePVA()); 
        } else if(uri::get(2) === "get_fin_slip") {
            $this->positionEcho('fin', $this->model->getFinSlipPVA(uri::get(3))); 
        }
    }
    
    public function upslip_pvd() {
        if(empty(uri::get(2))) {
            $this->requirePostition("fin");
            $this->view->setTitle("Confirm PV-D");
            $this->view->pvds = $this->model->getPVD(); 
            $this->view->render("fin/upslip_pvd", "navbar"); 
        } 
        else if (uri::get(2)==='conpvdItems') {
            $this->positionEcho('fin', $this->model->confirmPVD()); 
        }
    }

    public function pvd_fin_hub() {     //nn
        if(empty(uri::get(2))) {
            $this->requirePostition("fin");
            $this->view->setTitle("PV-D");
            $this->view->render("fin/pvd_fin_hub"); 
        } 
    }

    public function pvd_status() {       //nn
        if(empty(uri::get(2))) {
            $this->requirePostition("fin");
            $this->view->pvds = $this->model->getStatusPvd();
            $this->view->prePvds = $this->model->getStatusPrePvd();
            $this->view->setTitle("ประวัติ PV-D");
            $this->view->render("fin/pvd_status","fin/pvd_fin_hub"); 
        } 
    }

    public function pva_fin_hub() {        
        if(empty(uri::get(2))) {
            $this->requirePostition("fin");
            $this->view->setTitle("PV-A");
            $this->view->render("fin/pva_fin_hub"); 
        } 
    }

    public function top_up_pva() { //since this page is copied from ws.php post request is done in /ws/...
        if(empty(uri::get(2))) {
            $this->requirePostition("fin");
            $this->view->pvas = $this->model->GetPVAforWS();
            $this->view->setTitle("โอนเข้า PV-A");
            $this->view->render("fin/top_up_pva","fin/pva_fin_hub"); 
        } 
    }

    public function pva_status() { //since this page is copied from ws.php post request is done in /ws/...
        if(empty(uri::get(2))) {
            $this->requirePostition("fin");
            $this->view->pvas = $this->model->getStatusPva();
            $this->view->prePvas = $this->model->getStatusPrePva();
            $this->view->setTitle("ประวัติ PV-A");
            $this->view->render("fin/pva_status","fin/pva_fin_hub"); 
        } 
    }
    public function pvc_status(){
        if(empty(uri::get(2))) {
            $this->requirePostition("fin");
            $this->view->pvcs = $this->model->getPVCStatus();
            $this->view->reReqs = $this->model->getReReqStatus();
            $this->view->render("fin/pvc_status","navbar"); 
    }
    }
    public function pvc_process(){
        if(empty(uri::get(2))) {
            $this->requirePostition("fin");
           
            $this->view->render("fin/pvc_process","navbar"); 
    }
    }

    public function bank_statement(){
        if(empty(uri::get(2))) {
            $this->requirePostition("fin");
           
            $this->view->render("fin/bank_statement","navbar"); 
        } else if(uri::get(2) == "post_bank_statement") {
            echo $this->model->postBankStatement();
        }
    }


    public function bank_reconsile() {
        
        header("Content-type: application/vnd.ms-excel; charset=UTF-8");
        header("Content-Disposition: attachment; filename=bank_reconsile.xls");
        $data = $this->model->getbankreconcile();
        echo "\xEF\xBB\xBF";
        echo '<table style="width:100%">';
        
            echo '<tr>';
                echo '<th>invoice_no</th>';
                echo '<th>sox_no</th>';
                echo '<th>file_no</th>';
                echo '<th>invoice_date</th>';
                echo '<th>total_sales_price</th>';
                echo '<th>payment_date</th>';
                echo '<th>payment_time</th>';
                echo '<th>payment_amount</th>';
                
            echo '</tr>';
            
            foreach($data as $value) {
                echo '<tr>';
                    echo '<td>'.$value['invoice_no'].'</td>';
                    echo '<td>'.$value['sox_no'].'</td>';
                    echo '<td>'.$value['file_no'].'</td>';
                    echo '<td>'.$value['invoice_date'].'</td>';
                    echo '<td>'.$value['total_sales_price'].'</td>';
                    echo '<td>'.$value['payment_date'].'</td>';
                    echo '<td>'.$value['payment_time'].'</td>';
                    echo '<td>'.$value['payment_amount'].'</td>';
                    
                echo '</tr>';
            }
            
        echo '</table>';
        
    }

    public function sales_report_fin() {
        
        header("Content-type: application/vnd.ms-excel; charset=UTF-8");
        header("Content-Disposition: attachment; filename=sales_report_fin.xls");
        $data = $this->model->getSalesReportforfin();
        echo "\xEF\xBB\xBF";
        echo '<table style="width:100%">';
        
            echo '<tr>';
                echo '<th>so_week</th>';
                echo '<th>product_line</th>';
                echo '<th>category_name</th>';
                echo '<th>total_no_vat</th>';
                echo '<th>total_sales</th>';
                echo '<th>margin</th>';
                
            echo '</tr>';
            
            foreach($data as $value) {
                echo '<tr>';
                    
                    echo '<td>'.$value['so_week'].'</td>';
                    echo '<td>'.$value['product_line'].'</td>';
                    echo '<td>'.$value['category_name'].'</td>';
                    echo '<td>'.$value['total_no_vat'].'</td>';
                    echo '<td>'.$value['total_sales'].'</td>';
                    echo '<td>'.$value['margin'].'</td>';
                    
                echo '</tr>';
            }
            
        echo '</table>';
        
    }
}
