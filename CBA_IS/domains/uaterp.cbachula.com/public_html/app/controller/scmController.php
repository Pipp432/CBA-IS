<?php

namespace controller;

use _core\controller;
use _core\helper\input;
use _core\helper\session;
use _core\helper\uri;

class scmController extends controller {

    public function index() { 
        $this->requirePostition("scm");
        $this->err404();
    }

    
    
    public function confirm_shipping() {
        if(empty(uri::get(2))) {
            $this->requirePostition("scm");
            $this->view->setTitle("Confirm Shipping");
            $this->view->soxs = $this->model->getSoxsForCs();
            $this->view->render("scm/confirm_shipping", "navbar");
        } else if (uri::get(2)==='post_cs_items') {
            $this->positionEcho('scm', $this->model->addCs());
        }
    }

    public function confirm_pickup() {
        if(empty(uri::get(2))) {
            $this->requirePostition("scm");
            $this->view->setTitle("Confirm Pick Up");
            $this->view->soxs = $this->model->getSoxsForCp();
            $this->view->render("scm/confirm_pickup", "navbar");
        } else if (uri::get(2)==='post_cs_items') {
            $this->positionEcho('scm', $this->model->addCp());
        }
    }

    public function confirm_purchase_order() {
        if(empty(uri::get(2))) {
            $this->requirePostition("scm");
            $this->view->setTitle("Confirm Purchase Order");
            $this->view->pos = $this->model->getPosForCpo();
            $this->view->render("scm/confirm_purchase_order", "navbar");
        } else if (uri::get(2)==='post_cpo_items') {
            $this->positionEcho('scm', $this->model->addCpo());
        }
    }

    public function adjust_po() {
        if(empty(uri::get(2))) {
            $this->requirePostition("scm");
            $this->view->setTitle("Adjusted PO");
            $this->view->pos = $this->model->getPosForCpo();
            $this->view->render("scm/adjust_po", "navbar");
        } else if (uri::get(2)==='post_cpo_items') {
            $this->positionEcho('scm', $this->model->addadjCpo());
        }
    }

    public function receiving_report() {
        if(empty(uri::get(2))) {
            $this->requirePostition("scm");
            $this->view->setTitle("Receiving Report (RR)");
            $this->view->pos = $this->model->getPosForRr();
            $this->view->render("scm/receiving_report", "navbar");
        } else if (uri::get(2)==='post_rr_items') {
            $this->positionEcho('scm', $this->model->addRr());
        } 
    }
    
    public function counter_sales_out() {
        if(empty(uri::get(2))) {
            $this->requirePostition("scm");
            $this->view->setTitle("Counter Sales (Out)");
            $this->view->css = $this->model->getCsNotConfirmed();
            $this->view->render("scm/counter_sales_out", "navbar");
        } else if (uri::get(2)==='post_cs_items') {
            $this->positionEcho('scm', $this->model->addCounterSalesOut());
        }
    }
    
    public function counter_sales_in() {
        if(empty(uri::get(2))) {
            $this->requirePostition("scm");
            $this->view->setTitle("Counter Sales (In)");
            $this->view->css = $this->model->getCsConfirmed();
            $this->view->render("scm/counter_sales_in", "navbar");
        } else if (uri::get(2)==='post_cs_items') {
            $this->positionEcho('scm', $this->model->addCounterSalesIn());
        }
    }
    public function ird_download() {
		if(empty(uri::get(2))) {
            $this->err404();
        } else if (!empty(uri::get(2))) {
			header("Content-type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=".(uri::get(2))." address.xls");
			$data = $this->model->ird_download(uri::get(2));

			echo '<table style="width:100%">';

				echo '<tr>';
					echo '<th>?????????????????? SOX</th>';
					echo '<th>Customer_order_number (?????????????????????????????????????????????????????????)</th>';
					echo '<th>Consignee_name (??????????????????????????????)</th>';
					echo '<th>Address (?????????????????????)</th>';
					echo '<th>Postal_code (????????????????????????????????????)</th>';
					echo '<th>Phone_number (???????????????????????????????????????)</th>';
					echo '<th>Phone_number2 (???????????????????????????????????????2)</th>';
					echo '<th>COD (????????????????????????????????????)</th>';
					echo '<th>Item_type (????????????????????????????????????)</th>';
					echo '<th>Weight_kg (?????????????????????)</th>';
					echo '<th>Length (?????????)</th>';
					echo '<th>Width (???????????????)</th>';
					echo '<th>Height (?????????)</th>';
					echo '<th>Freight_insurance (?????????????????????????????????????????????????????????)</th>';
					echo '<th>Value_insurance (???????????????????????????????????????)</th>';
					echo '<th>Declared_value (????????????????????????????????????????????????????????????????????????????????????)</th>';
					echo '<th>Speed_service (??????????????????SPEED)</th>';
					echo '<th>Remark1 (????????????????????????1)</th>';
					echo '<th>Remark2 (????????????????????????2)</th>';
					echo '<th>Remark3 (????????????????????????3)</th>';
				echo '</tr>';

				foreach($data as $value) {
					echo '<tr>';
						echo '<td>'.$value['sox_no'].'</td>';
						echo '<td></td>';
						echo '<td>'.$value['customer_name'].' '.$value['customer_surname'].'</td>';
						echo '<td>'.substr($value['address'],0,-5).'</td>';
						echo '<td>'.substr($value['address'],-5).'</td>';
						echo '<td>'.$value['customer_tel'].'</td>';
						echo '<td></td>';
						echo '<td></td>';
						echo '<td></td>';
						echo '<td>'.$value['weight'].'</td>';
						echo '<td></td>';
						echo '<td></td>';
						echo '<td></td>';
						echo '<td></td>';
						echo '<td></td>';
						echo '<td></td>';
						echo '<td></td>';
						echo '<td></td>';
						echo '<td></td>';
						echo '<td></td>';
					echo '</tr>';
				}

			echo '</table>';
        }
        
    }
	
	public function sox_in_ird() {
        
        header("Content-type: application/vnd.ms-excel; charset=UTF-8");
        header("Content-Disposition: attachment; filename= SOX ".(uri::get(2)).".xls");
        $data = $this->model->getSOXinIRD(uri::get(2));
        echo "\xEF\xBB\xBF";
        echo '<table style="width:100%">';
        
            echo '<tr>';
                echo '<th>customer_name</th>';
                echo '<th>address</th>';
                echo '<th>zip_code</th>';
                echo '<th>customer_tel</th>';
                echo '<th>email</th>';
                echo '<th>sox_type</th>';
                echo '<th>sox_no</th>';
                echo '<th>sox_date</th>';
                echo '<th>company</th>';
                echo '<th>note</th>';
                echo '<th>invoice_no</th>';
                echo '<th>fin_form</th>';
                echo '<th>transportation_price</th>';
                echo '<th>so_no</th>';
                echo '<th>product_no</th>';
                echo '<th>product_name</th>';
                echo '<th>sales_no_vat</th>';
                echo '<th>sales_vat</th>';
                echo '<th>sales_price</th>';
                echo '<th>quantity</th>';
                echo '<th>total_sales_price</th>';
                echo '<th>box_size</th>';
            echo '</tr>';
            
            foreach($data as $value) {
                echo '<tr>';
				
					echo '<td>'.$value['customer_name'].'</td>';
                	echo '<td>'.$value['address'].'</td>';
					echo '<td>'.$value['zip_code'].'</td>';
					echo '<td>'.$value['customer_tel'].'</td>';
					echo '<td>'.$value['email'].'</td>';
					echo '<td>'.$value['sox_type'].'</td>';
					echo '<td>'.$value['sox_no'].'</td>';
					echo '<td>'.$value['sox_date'].'</td>';
					echo '<td>'.$value['company'].'</td>';
					echo '<td>'.$value['note'].'</td>';
					echo '<td>'.$value['invoice_no'].'</td>';
					echo '<td>'.$value['fin_form'].'</td>';
					echo '<td>'.$value['transportation_price'].'</td>';
					echo '<td>'.$value['so_no'].'</td>';
					echo '<td>'.$value['product_no'].'</td>';
					echo '<td>'.$value['product_name'].'</td>';
					echo '<td>'.$value['sales_no_vat'].'</td>';
					echo '<td>'.$value['sales_vat'].'</td>';
					echo '<td>'.$value['sales_price'].'</td>';
					echo '<td>'.$value['quantity'].'</td>';
					echo '<td>'.$value['total_sales_price'].'</td>';
					echo '<td>'.$value['box_size'].'</td>';	
				
                echo '</tr>';
            }
            
        echo '</table>';
        
    }
	
	
    public function check_stock() {
        if(empty(uri::get(2))) {
            $this->requirePostition("scm");
            $this->view->setTitle("Check Stock (IRD)");
            $this->view->Stocks = $this->model->getStockIRD();
            $this->view->render("scm/check_stock", "navbar");
        } 
    }
	
	public function view_stock_ird() {
        
        header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment; filename=stock IRD.xls");
        $data = $this->model->getStockIRDforDownload();
        
        echo '<table style="width:100%">';
        
            echo '<tr>';
                echo '<th>supplier_no</th>';
                echo '<th>supplier_name</th>';
                echo '<th>product_no</th>';
                echo '<th>product_name</th>';
                echo '<th>product_type</th>';
                echo '<th>stock_in</th>';
                echo '<th>stock_out</th>';
				echo '<th>stock_left</th>';
				echo '<th>rr_no</th>';
            echo '</tr>';
            
            foreach($data as $value) {
                echo '<tr>';
                    echo '<td>'.$value['supplier_no'].'</td>';
                    echo '<td>'.$value['supplier_name'].'</td>';
                    echo '<td>'.$value['product_no'].'</td>';
                    echo '<td>'.$value['product_name'].'</td>';
                    echo '<td>'.$value['product_type'].'</td>';
                    echo '<td>'.$value['stock_in'].'</td>';
                    echo '<td>'.$value['stock_out'].'</td>';
					echo '<td>'.$value['stock_left'].'</td>';
					echo '<td>'.$value['rr_no'].'</td>';
                echo '</tr>';
            }
            
        echo '</table>';
        
    }
	public function get_address(){
        header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment; filename=address.xls");
        $data = $this->model->getAddress();
        
        echo '<table style="width:100%">';
        
            echo '<tr>';
                echo '<th>sox_no</th>';
                // echo '<th>iv_no</th>';
                echo '<th>?????????/EMS</th>';
                echo '<th>????????????</th>';
                echo '<th>?????????????????????</th>';
                echo '<th>?????????</th>';
                // echo '<th>print</th>';
            echo '</tr>';
            
            foreach($data as $value) {
                echo '<tr>';
                    echo '<td>'.$value['sox_no'].'</td>';
                    // echo '<td>'.$value['invoice_no'].'</td>';
                    echo '<td>'.$value['note'].'</td>';
                    echo '<td>'.$value['customer_name'].'</td>';
                    echo '<td>'.$value['address'].'</td>';
                    echo '<td>'.$value['customer_tel'].'</td>';
                    
                echo '</tr>';
            }
            
        echo '</table>';
    }

	public function get_sox_no_ird() {
        
        header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment; filename=SOX no IRD.xls");
        $data = $this->model->getSOXnoIRD();
        
        echo '<table style="width:100%">';
        
            echo '<tr>';
                echo '<th>so_no</th>';
                echo '<th>sox_no</th>';
                echo '<th>box_size</th>';
                //echo '<th>iv_no</th>';
                echo '<th>Product No.</th>';
                echo '<th>Product Name.</th>';
                echo '<th>Quantity</th>';
            echo '</tr>';
            
            foreach($data as $value) {
                echo '<tr>';
                    echo '<td>'.$value['so_no'].'</td>';
                    echo '<td>'.$value['sox_no'].'</td>';
                    echo '<td>'.$value['box_size'].'</td>';
                    //echo '<td>'.$value['invoice_no'].'</td>';
                    echo '<td>'.$value['product_no'].'</td>';
                    echo '<td>'.$value['product_name'].'</td>';
                    echo '<td>'.$value['quantity'].'</td>';
                    
                echo '</tr>';
            }
            
        echo '</table>';
        
    }


    public function get_pickup() {
        
        header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment; filename=Pick Up.xls");
        $data = $this->model->getPickup();
        
        echo '<table style="width:100%">';
        
            echo '<tr>';
                echo '<th>sox_no</th>';
                echo '<th>iv_no</th>';
                echo '<th>??????????????????????????????????????????</th>';
                echo '<th>?????????????????????????????????????????????</th>';
                echo '<th>?????????????????? CE/SP</th>';
                echo '<th>?????????????????????????????????</th>';
                echo '<th>????????????????????????????????????????????????</th>';
                echo '<th>???????????????</th>';
            echo '</tr>';
            
            foreach($data as $value) {
                echo '<tr>';
                    echo '<td>'.$value['sox_no'].'</td>';
                    echo '<td>'.$value['invoice_no'].'</td>';
                    echo '<td>'.$value['date'].'</td>';
                    echo '<td>'.$value['time'].'</td>';
                    echo '<td>'.$value['receiver'].'</td>';
                    echo '<td>'.$value['tel'].'</td>';
                    echo '<td>'.$value['product_name'].'</td>';
                    echo '<td>'.$value['quantity'].'</td>';
                echo '</tr>';
            }
            
        echo '</table>';
        
    }

    public function dash_pickup() {
        if(empty(uri::get(2))) {
            $this->requirePostition("scm");
            $this->view->setTitle("Pick Up");
            $this->view->dashboards = $this->model->dashPickup();
            $this->view->render("scm/dash_pickup", "navbar");
        } else if (uri::get(2)==='get_getPickup') {
            $this->positionEcho('scm', $this->model->dashPickup());
        }
    }
    
    public function dashboard() {
        if(empty(uri::get(2))) {
            $this->requirePostition("scm");
            $this->view->setTitle("Dashboard");
            $this->view->dashboards = $this->model->getDashboard();
            $this->view->render("scm/dashboard", "navbar");
        } else if (uri::get(2)==='get_dashboard') {
            $this->positionEcho('scm', $this->model->getDashboard());
        }
    }
	
	public function dashboard2() {
        if(empty(uri::get(2))) {
            $this->requirePostition("scm");
            $this->view->setTitle("Dashboard IRD");
            $this->view->dashboards = $this->model->getDashboard2();
            $this->view->render("scm/dashboard2", "navbar");
        } else if (uri::get(2)==='get_dashboard2') {
            $this->positionEcho('scm', $this->model->getDashboard2());
        }
    }
	
	public function ird() {
        if(empty(uri::get(2))) {
            $this->requirePostition("scm");
            $this->view->setTitle("IRD");
            $this->view->srs = $this->model->getShippingRequest();
            $this->view->render("scm/ird", "navbar");
        } else if (uri::get(2)==='post_ird_items') {
            $this->positionEcho('scm', $this->model->addIRD());
        }
        else if (uri::get(2)==='get_status') {
            $this->positionEcho('scm', $this->model->get_status());
        }
        else if (uri::get(2)==='change_status') {
            $this->positionEcho('scm', $this->model->change_status());
        }
    }

	public function confirm_prepare() {
        if(empty(uri::get(2))) {
            $this->requirePostition("scm");
            $this->view->setTitle("??????????????????????????????????????????????????????");
            $this->view->render("scm/confirm_prepare", "navbar");
        } else if (uri::get(2)==='update_ird_items') {
            $this->positionEcho('scm', $this->model->updateIRD());
        }
        else if (uri::get(2)==='get_sox') {
            $this->positionEcho('scm', $this->model->download_sox());
        }
    }


	public function upload_ird() {
        if(empty(uri::get(2))) {
            $this->requirePostition("scm");
            $this->view->setTitle("Upload IRD");
			$this->view->irds = $this->model->getIrdForUpload();
            $this->view->render("scm/upload_ird", "navbar");
        } else if (uri::get(2)==='post_ird_file') {
            $this->positionEcho('scm', $this->model->uploadIrd());
        }
    }
	
	public function re_dashboard() {
        if(empty(uri::get(2))) {
            $this->requirePostition("acc");
            $this->view->setTitle("RE-Dashboard");
            $this->view->REreport = $this->model->getREreport();
            $this->view->render("scm/re_dashboard", "navbar");
        }
    }

    public function tracking_sheet() {
        //$this->err404();
        if(empty(uri::get(2))) {
            $this->view->setTitle("Tracking No. Update");
            $this->view->render("scm/tracking_sheet", "navbar");
        } else if (uri::get(2) == 'update_tracking_no') { 
           $this->positionEcho('scm', $this->model->updateTrackingNo());
        }
    }

    public function get_IRD_Load() {
        
        header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment; filename=SOX no IRD.xls");
        $data = $this->model->getIRDLoad();
        
        echo '<table style="width:100%">';
        
            echo '<tr>';
                echo '<th>ird_no</th>';
                echo '<th>sox_no</th>';
                echo '<th>so_no</th>';
                echo '<th>Product No.</th>';
                echo '<th>Product Name.</th>';
                echo '<th>Quantity</th>';
            echo '</tr>';
            
            foreach($data as $value) {
                echo '<tr>';
                    echo '<td>'.$value['ird_no'].'</td>';
                    echo '<td>'.$value['sox_no'].'</td>';
                    echo '<td>'.$value['so_no'].'</td>';
                    echo '<td>'.$value['product_no'].'</td>';
                    echo '<td>'.$value['product_name'].'</td>';
                    echo '<td>'.$value['quantity'].'</td>';
                    
                echo '</tr>';
            }
            
        echo '</table>';
        
    }
    
    public function exchange() {
        if(empty(uri::get(2))) {
            $this->requirePostition("scm");
            $this->view->setTitle("Exchange");
            $this->view->css = $this->model->getCsExchange();
            $this->view->render("scm/exchange", "navbar");
        } else if (uri::get(2)==='post_cs_items') {
            $this->positionEcho('scm', $this->model->addCsforExchange());
        }
    }

    public function get_product() {
        
        header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment; filename=Product.xls");
        $data = $this->model->getproduct();
        
        echo '<table style="width:100%">';
        
            echo '<tr>';
                echo '<th>Product No.</th>';
                echo '<th>Product Name.</th>';
                echo '<th>product_description</th>';
                echo '<th>product_line</th>';
                echo '<th>supplier_no</th>';
                echo '<th>brand</th>';
                echo '<th>category_no</th>';
                echo '<th>sub_category</th>';
                echo '<th>unit</th>';
                echo '<th>weight</th>';
                echo '<th>width</th>';
                echo '<th>length</th>';
                echo '<th>height</th>';

            echo '</tr>';
            
            foreach($data as $value) {
                echo '<tr>';
                    echo '<td>'.$value['product_no'].'</td>';
                    echo '<td>'.$value['product_name'].'</td>';
                    echo '<td>'.$value['product_description'].'</td>';
                    echo '<td>'.$value['product_line'].'</td>';
                    echo '<td>'.$value['supplier_no'].'</td>';
                    echo '<td>'.$value['brand'].'</td>';
                    echo '<td>'.$value['category_no'].'</td>';
                    echo '<td>'.$value['sub_category'].'</td>';
                    echo '<td>'.$value['unit'].'</td>';
                    echo '<td>'.$value['weight'].'</td>';
                    echo '<td>'.$value['width'].'</td>';
                    echo '<td>'.$value['length'].'</td>';
                    echo '<td>'.$value['height'].'</td>';
                     
                echo '</tr>';
            }
            
        echo '</table>';
        
    }

    public function check_stockcs() {
        if(empty(uri::get(2))) {
            $this->requirePostition("scm");
            $this->view->setTitle("Check Stock (CS)");
            $this->view->Stocks = $this->model->getStockCS();
            $this->view->render("scm/check_stockcs", "navbar");
        } 
    }

    public function Download_Report_Pickup() {
		header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment; filename=Report Pickup.xls");
        $data = $this->model->getDownloadReportPickup();

			echo '<table style="width:100%">';

				echo '<tr>';
					echo '<th>?????????????????? SOX</th>';
					echo '<th>?????????????????? Invoice</th>';
					echo '<th>Date</th>';
					echo '<th>Time</th>';
					echo '<th>REceiver</th>';
					echo '<th>Phone_number (???????????????????????????????????????)</th>';
					echo '<th>Product Name</th>';
					echo '<th>Quantity</th>';
					
				echo '</tr>';

				foreach($data as $value) {
					echo '<tr>';
						echo '<td>'.$value['sox_no'].'</td>';
                        echo '<td>'.$value['invoice_no'].'</td>';
                        echo '<td>'.$value['date'].'</td>';
                        echo '<td>'.$value['time'].'</td>';
                        echo '<td>'.$value['reciever'].'</td>';
                        echo '<td>'.$value['tel'].'</td>';
                        echo '<td>'.$value['product_name'].'</td>';
                        echo '<td>'.$value['quantity'].'</td>';
                        
					echo '</tr>';
                }
			echo '</table>';
        
        
    }
    public function get_dashboardcs(){
        header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment; filename=CS.xls");
        $data = $this->model->getdashboardcs();
        
        echo '<table style="width:100%">';
        
            echo '<tr>';
                echo '<th>CS</th>';       
                echo '<th>??????????????????????????????/EMS</th>';
                echo '<th>??????????????????</th>';
                echo '<th>????????????/???????????????</th>';
                echo '<th>???????????????</th>';
                echo '<th>?????????(???????????????)</th>';
                echo '<th>????????????(???????????????)</th>';
                echo '<th>?????????(???????????????)</th>';
            echo '</tr>';
            
            foreach($data as $value) {
                echo '<tr>';
                    echo '<td>'.$value['file_no'].'</td>';
                    echo '<td>'.$value['product_no'].'</td>';
                    echo '<td>'.$value['product_name'].'</td>';
                    echo '<td>'.$value['sales_price'].'</td>';
                    echo '<td>'.$value['unit'].'</td>';
                    echo '<td>'.$value['quantity_in'].'</td>';
                    echo '<td>'.$value['quantity_out'].'</td>';
                    echo '<td>'.$value['quantity_left'].'</td>';
                    
                echo '</tr>';
            }
            
        echo '</table>';
    }
}
