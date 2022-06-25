<?php

namespace controller;

use _core\controller;
use _core\helper\input;
use _core\helper\session;
use _core\helper\uri;

class mktController extends controller {

    public function index() { 
        $this->requirePostition("mkt");
        $this->err404();
    }
    
    public function demo() {
        if(empty(uri::get(2))) {
            $this->requirePostition("mkt");
            $this->view->setTitle("DEMO");
            $this->view->suppliers = $this->model->getSuppliers();
            $this->view->products = $this->model->getProducts();
            $this->view->render("mkt/demo", "navbar");
        }
    }
	
	public function t_price() {
        if(empty(uri::get(2))) {
            $this->view->setTitle("Calculate Transportation Price");
            $this->view->suppliers = $this->model->getSuppliers();
            $this->view->products = $this->model->getProductsForT();
            $this->view->render("mkt/t_price", "navbar");
        } 
    }
	public function calculateTransportation() {
        
        $this->requirePost();
        
        // $listSO = json_decode(input::post('list_so'), true);
        // $weight = $this->model->calculateWeight($listSO);
        // $isBangkok = $this->model->isBangkok($listSO[0]);
        
        // $sos = json_decode(input::post('sos'), true);
        
		$priceList = $this->model->calculate(input::post('sos'));
        $priceList = json_decode($priceList, true);
        // print_r($priceList);
        // echo $priceList['shippings'][0]['price'];
        // echo $priceList['shippings'][1]['price'];
		// $jprice=json_encode($priceList);
		if (isset($pricelist['error'])){
			echo 'error';
		} else{
		// 	//echo $jprice.'<br>';
			echo '<div class="form-check" style="margin-bottom:1rem;">';
			
			echo '<input class="form-check-input" type="radio" name="transportRadio" id="ThaiPost" value="'.$priceList['price'].'" checked>';
			echo '<lable>Courier : <b>Thailand Post (ถ้าขึ้นราคาเดียวเกิดจากสินค้าเกิน 2 kg ส่งแบบ EMS เท่านั้น!!!)</b> </lable> </br>';
			echo '<lable>Note : <b>'.$priceList['error'].'</b> </lable> </br>';
            echo 'ลงทะเบียน/EMS : <b>'.$priceList['shippings'][0]['price'].'</b> บาท / <b>'.$priceList['shippings'][1]['price'].'</b> บาท</lable> </br>';
			// echo '<lable>ลงทะเบียน/EMS(ถ้าสั่งของจำนวนเยอะ) : <b>'.$priceList['shippings'][0]['price'].'</b> บาท</lable> </br>';
			// echo '<lable>EMS : <b>'.$priceList['shippings'][1]['price'].'</b> บาท</lable><br>';
			
		// 	echo '<input class="form-check-input" type="radio" name="transportRadio" id="ThaiPost(EMS)" value="'.$priceList['price'].'" checked>';
		// 	echo '<lable>Courier : <b>Kerry</b> </lable> </br>';
		// 	echo '<lable>Note : <b>'.$priceList['error'].'</b> </lable> </br>';
		// 	echo '<lable>กรุงเทพและปริมณฑล : <b>'.$priceList['kerry_price'].'</b> บาท</lable> </br>';
		// 	echo '<lable>อื่นๆ : <b>'.$priceList['kerry_oprice'].'</b> บาท</lable><br>';
			
	
			
		// 	/*echo '</div><br>ทุกคนอย่าสนสิ่งนี้ สำหรับ IS ดู อย่าแพนิคเมื่อเห็นสิ่งนี้ ปล่อยมันขึ้นได้เลยค่า <br>'.$priceList['response'].'<br> ';*/
		}
		
    }

    public function sales_order() {
        if(empty(uri::get(2))) {
            
            $this->view->setTitle("Sales Order (SO)");
            $this->view->suppliers = $this->model->getSuppliers();
            $this->view->products = $this->model->getProducts();
            $this->view->render("mkt/sales_order", "navbar");
        } else if (uri::get(2)==='get_customer_name') {
            $this->positionEcho('mkt', $this->model->getCustomerName());
        } else if (uri::get(2)==='post_so_items') {
            $this->positionEcho('mkt', $this->model->addSo());
        }
    }
    public function os_sales_order() {
        if(empty(uri::get(2))) {
            $this->view->setTitle("Sales Order (SO)");
            $this->view->suppliers = $this->model->getSuppliers();
            $this->view->products = $this->model->osGetProducts();
            $this->view->render("mkt/sales_order", "navbar");
        } else if (uri::get(2)==='get_customer_name') {
           echo $this->model->getCustomerName();
        } else if (uri::get(2)==='post_so_items') {
           echo $this->model->addSo();
        }
    }
	
    
    public function commart() {
        if(empty(uri::get(2))) {
            $this->requirePostition("mkt");
            $this->view->setTitle("CBA x COMMART");
            $this->view->products = $this->model->getCommartProduct();
            $this->view->render("mkt/commart", "navbar");
        } else if (uri::get(2)==='post_items') {
            $this->positionEcho('mkt', $this->model->addCommart());
        }
    }

    public function purchase_order() {
        if(empty(uri::get(2))) {
            $this->requirePostition("mkt");
            $this->view->setTitle("Purchase Order (PO)");
            $this->view->suppliers = $this->model->getSupplierList();
            $this->view->sos = $this->model->getOrderInstallSo();
            $this->view->products = $this->model->getStockProduct();
            $this->view->render("mkt/purchase_order", "navbar");
        } else if (uri::get(2)==='post_po_items') {
            $this->positionEcho('mkt', $this->model->addPo());
        } else if (uri::get(2)==='get_install_fortulip') {
            $this->positionEcho('mkt', $this->model->getOrderInstallSo());}
    }

    public function confirm_install() {
        if(empty(uri::get(2))) {
            $this->requirePostition("mkt");
            $this->view->setTitle("Confirm Install (CI)");
            $this->view->pos = $this->model->getInstallPo();
            $this->view->render("mkt/confirm_install", "navbar");
        } else if (uri::get(2)==='get_po_install_ci') {
            $this->positionEcho('mkt', $this->model->getInstallPo());
        } else if (uri::get(2)==='post_ci_items') {
            $this->positionEcho('mkt', $this->model->addCi());
        }  
    }
    
    public function request_counter_sales() {
        if(empty(uri::get(2))) {
            $this->requirePostition("mkt");
            $this->view->setTitle("Request Counter Sales (RCS)");
            $this->view->locations = $this->model->getLocation();
            $this->view->products = $this->model->getProducts();
            $this->view->render("mkt/request_counter_sales", "navbar");
        } else if (uri::get(2)==='post_cs_items') {
            $this->positionEcho('mkt', $this->model->addCs());
        }
    }
    
    public function xiaomi_report() {
        if(empty(uri::get(2))) {
            $this->requirePostition("mkt");
            $this->view->setTitle("Xiaomi Report");
            $this->view->report = $this->model->getXiaomiReport();
            $this->view->render("mkt/xiaomi_report", "navbar");
        } else if (uri::get(2)==='post_xr_items') {
            $this->positionEcho('mkt', $this->model->addXr());
        } else if (uri::get(2)==='download') {
            
            header("Content-type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=xiaomi_report.xls");
            $data = $this->model->getXiaomiReportDownload();
            
            echo '<table style="width:100%">';
            
                echo '<tr>';
                    echo '<th>product_no</th>';
                    echo '<th>product_description</th>';
                    echo '<th>quantity</th>';
                    echo '<th>purchase</th>';
                echo '</tr>';
                
                foreach($data as $value) {
                    echo '<tr>';
                        echo '<td>'.$value['product_no'].'</td>';
                        echo '<td>'.$value['product_description'].'</td>';
                        echo '<td>'.$value['quantity'].'</td>';
                        echo '<td>'.$value['purchase'].'</td>';
                    echo '</tr>';
                }
                
            echo '</table>';
            
        }
    }
    
    public function dashboard() {
        if(empty(uri::get(2))) {
            // $this->requirePostition("mkt");
            $this->view->setTitle("Dashboard");
            $this->view->sos = $this->model->getDashboradSo();
            $this->view->pos = $this->model->getDashboradPo();
            $this->view->css = $this->model->getDashboradCs();
            $this->view->render("mkt/dashboard", "navbar");
        } else if (uri::get(2)==='get_dashboard') {
            $this->positionEcho('mkt', $this->model->getDashborad());
        }
    }
    
    public function sales_report() {
        
        header("Content-type: application/vnd.ms-excel; charset=UTF-8");
        header("Content-Disposition: attachment; filename=sales_report.xls");
        $data = $this->model->getSalesReport();
        echo "\xEF\xBB\xBF";
        echo '<table style="width:100%">';
        
            echo '<tr>';
                echo '<th>so_no</th>';
                echo '<th>so_week</th>';
                echo '<th>so_date</th>';
                echo '<th>so_time</th>';
                echo '<th>product_line</th>';
                echo '<th>product_no</th>';
                echo '<th>product_name</th>';
                echo '<th>category_name</th>';
                echo '<th>sub_category</th>';
                echo '<th>supplier_name</th>';
                echo '<th>quantity</th>';
                echo '<th>total_no_vat</th>';
                echo '<th>total_sales</th>';
                echo '<th>total_point</th>';
                echo '<th>total_commission</th>';
                echo '<th>margin</th>';
                echo '<th>sp</th>';
                echo '<th>ce</th>';
            echo '</tr>';
            
            foreach($data as $value) {
                echo '<tr>';
                    echo '<td>'.$value['so_no'].'</td>';
                    echo '<td>'.$value['so_week'].'</td>';
                    echo '<td>'.$value['so_date'].'</td>';
                    echo '<td>'.$value['so_time'].'</td>';
                    echo '<td>'.$value['product_line'].'</td>';
                    echo '<td>'.$value['product_no'].'</td>';
                    echo '<td>'.$value['product_name'].'</td>';
                    echo '<td>'.$value['category_name'].'</td>';
                    echo '<td>'.$value['sub_category'].'</td>';
                    echo '<td>'.$value['supplier_name'].'</td>';
                    echo '<td>'.$value['quantity'].'</td>';
                    echo '<td>'.$value['total_no_vat'].'</td>';
                    echo '<td>'.$value['total_sales'].'</td>';
                    echo '<td>'.$value['total_point'].'</td>';
                    echo '<td>'.$value['total_commission'].'</td>';
                    echo '<td>'.$value['margin'].'</td>';
                    echo '<td>'.$value['sp'].'</td>';
                    echo '<td>'.$value['ce'].'</td>';
                echo '</tr>';
            }
            
        echo '</table>';
        
    }
    
    public function point_report() {
        
        header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment; filename=point_report.xls");
        $data = $this->model->getPointReport();
        
        echo '<table style="width:100%">';
        
            echo '<tr>';
                echo '<th>date</th>';
                echo '<th>sp</th>';
                echo '<th>product_line</th>';
                echo '<th>ce</th>';
                echo '<th>point</th>';
                echo '<th>remark</th>';
                echo '<th>note</th>';
				echo '<th>type</th>';
				echo '<th>total_sales_no_vat</th>';
				echo '<th>total_sales_vat</th>';
                echo '<th>total_sales_price</th>';
            echo '</tr>';
            
            foreach($data as $value) {
                echo '<tr>';
                    echo '<td>'.$value['date'].'</td>';
                    echo '<td>'.$value['sp'].'</td>';
                    echo '<td>'.$value['product_line'].'</td>';
                    echo '<td>'.$value['ce'].'</td>';
                    echo '<td>'.$value['point'].'</td>';
                    echo '<td>'.$value['remark'].'</td>';
                    echo '<td>'.$value['note'].'</td>';
					echo '<td>'.$value['type'].'</td>';
					echo '<td>'.$value['total_sales_no_vat'].'</td>';
					echo '<td>'.$value['total_sales_vat'].'</td>';
                    echo '<td>'.$value['total_sales_price'].'</td>';
                echo '</tr>';
            }
            
        echo '</table>';
        
    }
    
    
    
    // public function championLeague_report() {
        
    //     header("Content-type: application/vnd.ms-excel");
    //     header("Content-Disposition: attachment; filename=championLeague_report.xls");
    //     $data = $this->model->getChampionLeagueReport();
        
    //     echo '<table style="width:100%">';
        
    //         echo '<tr>';
    //             echo '<th>so_no</th>';
    //             echo '<th>so_date</th>';
    //             echo '<th>so_time</th>';
    //             echo '<th>team_name</th>';
    //             echo '<th>sp</th>';
    //             echo '<th>team_ce_name</th>';
    //             echo '<th>total_sales_price</th>';
    //         echo '</tr>';
            
    //         foreach($data as $value) {
    //             echo '<tr>';
    //                 echo '<td>'.$value['so_no'].'</td>';
    //                 echo '<td>'.$value['so_date'].'</td>';
    //                 echo '<td>'.$value['so_time'].'</td>';
    //                 echo '<td>'.$value['team_name'].'</td>';
    //                 echo '<td>'.$value['sp'].'</td>';
    //                 echo '<td>'.$value['team_ce_name'].'</td>';
    //                 echo '<td>'.$value['total_sales_price'].'</td>';
    //             echo '</tr>';
    //         }
            
    //     echo '</table>';
        
    // }
    
    // public function finalLeague_report() {
        
    //     header("Content-type: application/vnd.ms-excel");
    //     header("Content-Disposition: attachment; filename=finalLeague_report.xls");
    //     $data = $this->model->getFinalLeagueReport();
        
    //     echo '<table style="width:100%">';
        
    //         echo '<tr>';
    //             echo '<th>sp</th>';
    //             echo '<th>ce_id</th>';
    //             echo '<th>rank</th>';
    //             echo '<th>so_no</th>';
    //             echo '<th>total_sales_price</th>';
    //             echo '<th>remark</th>';
    //         echo '</tr>';
            
    //         foreach($data as $value) {
    //             echo '<tr>';
    //                 echo '<td>'.$value['sp'].'</td>';
    //                 echo '<td>'.$value['ce_id'].'</td>';
    //                 echo '<td>'.$value['rank'].'</td>';
    //                 echo '<td>'.$value['so_no'].'</td>';
    //                 echo '<td>'.$value['total_sales_price'].'</td>';
    //                 echo '<td>'.$value['remark'].'</td>';
    //             echo '</tr>';
    //         }
            
    //     echo '</table>';
        
    // }
    
    public function infected_wars_report() {
        
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=infected_wars_report.xls");
        $data = $this->model->getTournament();
        
        echo '<table style="width:100%">';
        
            echo '<tr>';
                echo '<th>product_line</th>';
                echo '<th>team_name</th>';
                echo '<th>team_ce</th>';
                echo '<th>team_sales</th>';
            echo '</tr>';
            
            foreach($data as $value) {
                echo '<tr>';
                    echo '<td>'.$value['product_line'].'</td>';
                    echo '<td>'.$value['team_name'].'</td>';
                    echo '<td>'.$value['team_ce'].'</td>';
                    echo '<td>'.$value['team_sales'].'</td>';
                echo '</tr>';
            }
            
        echo '</table>';
        
    }
    
    public function get_supplier_list() {
        $this->positionEcho('mkt', $this->model->getSupplierList());
    }
	
	public function sales_and_margin(){	
		$this->requirePostition("mkt");
        $this->view->setTitle("sales_and_margin"); 
		$this->view->top10_so_data = $this->model->get_top10_so();
		$this->view->top10_margin_data = $this->model->get_top10_margin();
		$this->view->fa_sales_total = $this->model->get_fa_sales_total();
		$this->view->fa_margin_total = $this->model->get_fa_margin_total();
		$this->view->fa_sales_weeks = $this->model->get_fa_sales_weeks();
		$this->view->fa_margin_weeks = $this->model->get_fa_margin_weeks();
		$this->view->fa_sales_cat = $this->model->get_fa_sales_cat();
		$this->view->fa_margin_cat = $this->model->get_fa_margin_cat();
		$this->view->fa_sales_cat_all = $this->model->get_fa_sales_cat_all();
		$this->view->fa_margin_cat_all = $this->model->get_fa_margin_cat_all();
		$this->view->stack_data = $this->model->get_stack();
		$this->view->cat_for_stack = $this->model->get_cat_for_stack();
		$this->view->render("mkt/sales_and_margin", "navbar");
	}

    public function os_sales_and_margin(){	
        $this->view->setTitle("sales_and_margin"); 

        if(uri::get(2) != 's') {
		    $this->view->top10_so_data = $this->model->os_get_top10_so(uri::get(2));
		    $this->view->top10_margin_data = $this->model->os_get_top10_margin(uri::get(2));
		    $this->view->fa_sales_total = $this->model->os_get_fa_sales_total(uri::get(2));
		    $this->view->fa_margin_total = $this->model->os_get_fa_margin_total(uri::get(2));
		    $this->view->fa_sales_weeks = $this->model->os_get_fa_sales_weeks(uri::get(2));
		    $this->view->fa_margin_weeks = $this->model->os_get_fa_margin_weeks(uri::get(2));
		    $this->view->fa_sales_cat = $this->model->os_get_fa_sales_cat(uri::get(2));
		    $this->view->fa_margin_cat = $this->model->os_get_fa_margin_cat(uri::get(2));
		    $this->view->fa_sales_cat_all = $this->model->os_get_fa_sales_cat_all(uri::get(2));
		    $this->view->fa_margin_cat_all = $this->model->os_get_fa_margin_cat_all(uri::get(2));
		    $this->view->stack_data = $this->model->os_get_stack(uri::get(2));
		    $this->view->cat_for_stack = $this->model->os_get_cat_for_stack(uri::get(2));
            $this->view->render("mkt/sales_and_margin","mkt/os_sales_and_marginHub");
        } else $this->view->render( "mkt/os_sales_and_marginHub");
		
	}
    
	public function sp_tracking(){
        $this->view->setTitle("sp_tracking"); 
		$this->requirePostition("mkt");
		$this->view->sp_data = $this->model->get_sp_data();
		$this->view->top10_sp_data = $this->model->get_top10_sp();
//		$this->view->act_data = $this->model->get_act_sp();
		$this->view->render("mkt/sp_tracking", "navbar");
	}
	
	public function actualsales_margin(){
		$this->requireSignIn();
        $this->view->setTitle("actualsales_margin");
		$this->view->forecast_vs_actual = $this->model->get_accumsales_forecast_diff();
		$this->view->fa_sales_total = $this->model->get_fa_sales_total_all();
		$this->view->fa_margin_total = $this->model->get_fa_margin_total_all();
		$this->view->render("mkt/actualsales_margin", "navbar");
	}
	
	public function sp_response() {
        $this->view->setTitle("sp_response"); 
		$this->view->sp_contributing_data = $this->model->get_sp_contributing();
		$this->view->sp_engagement_data = $this->model->get_sp_engagement();
		$this->view->sp_data = $this->model->get_sp_data_all();
		$this->view->render("mkt/sp_response","navbar");
	}
    public function pvc(){
        if (empty(uri::get(2))) {
            $this->view->setTitle("Payment Voucher C (PV-C)"); 
            $this->view->render("mkt/pvc","navbar");
        }else if (uri::get(2)==='post_PVC'){
             $this->positionEcho('mkt', $this->model->addPVC());
        }else if(uri::get(2)==='post_quotation'){
            $this->positionEcho('mkt', $this->model->uploadImgForPVC());
        }
        
    }
    public function reimbursement_request(){
        if (empty(uri::get(2))) {
            $this->view->setTitle("Reimbursement Request"); 
            $this->view->render("mkt/reimbursement_request","navbar");
        }else if (uri::get(2)==='post_reimbursement_Request'){
            $this->positionEcho('mkt', $this->model->addReReqDetails());
        }else if(uri::get(2)==='post_quotation'){
            $this->positionEcho('mkt', $this->model->uploadImgForReReq());
        }
        
    }
    // public function post_quotation(){
    //     if(empty(uri::get(2))) {
    //         $this->view->setTitle("Upload quotation");
    //         $this->view->render("mkt/pvc", "navbar");
    //     } else if (uri::get(2)==='post_quotation_file') {
    //         $this->positionEcho('mkt', $this->model->uploadQuotation());
    //     }
    // }
    
    public function petty_cash_request() {
        if(empty(uri::get(2))) {
            $this->view->setTitle("Payment Voucher A (PV-A)"); 
            $this->view->render("mkt/petty_cash_request", "navbar");
        } else if (uri::get(2)==='post_pva') {
            $this->positionEcho('mkt', $this->model->addRequestPettyMoney());
        }
    }
    
    
    public function Confirm_Po() {
        if(empty(uri::get(2))) {
            $this->requirePostition("mkt");
            $this->view->setTitle("Confirm_Po");
            $this->view->pos = $this->model->getStockPo();
            $this->view->render("mkt/Confirm_Po", "navbar");
        } else if (uri::get(2)==='get_po_install_ci') {
            $this->positionEcho('mkt', $this->model->getStockPo());
        } else if (uri::get(2)==='post_ci_items') {
            $this->positionEcho('mkt', $this->model->confirmPO());
        }  
    }

    public function pre_pvd() {
        if(empty(uri::get(2))) {
            $this->view->setTitle("Payment Voucher D (PV-D)");
            $this->view->render("mkt/pre_pvd","navbar");
        } else if (uri::get(2)==='post_requestwsd') {
            $this->positionEcho('mkt', $this->model->requestWSD());
        }
    }

    public function Cancel_Sox() {
        if(empty(uri::get(2))) {
            $this->requirePostition("mkt");
            $this->view->setTitle("Cancel Sox");     
            $this->view->soxs = $this->model->getsoxs();   
            $this->view->onlysos = $this->model->getso();        
            $this->view->render("mkt/Cancel_Sox", "navbar");
        } else if (uri::get(2)==='get_SOX') {
            $this->positionEcho('mkt', $this->model->getSOX());
        } else if (uri::get(2)==='Change_CancelSOX') {
            $this->positionEcho('mkt', $this->model->ChangeCancelSOX());
        } else if (uri::get(2)==='Change_CancelSO') {
            $this->positionEcho('mkt', $this->model->ChangeCancelSO());
        }
    }

    public function Cancel_SO() {
        if(empty(uri::get(2))) {
            $this->requirePostition("mkt");
            $this->view->setTitle("Cancel SO");   
            $this->view->render("mkt/Cancel_SO", "navbar");
        } else if (uri::get(2)==='get_SOX') {
            $this->positionEcho('mkt', $this->model->getSOX());
        } else if (uri::get(2)==='Change_Cancel') {
            $this->positionEcho('mkt', $this->model->ChangeCancel());
        }
    }


}

