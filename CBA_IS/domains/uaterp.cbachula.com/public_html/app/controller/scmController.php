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
					echo '<th>เลขที่ SOX</th>';
					echo '<th>Customer_order_number (เลขออเดอร์ของลูกค้า)</th>';
					echo '<th>Consignee_name (ชื่อผู้รับ)</th>';
					echo '<th>Address (ทิ่อยู่)</th>';
					echo '<th>Postal_code (รหัสไปรษณีย์)</th>';
					echo '<th>Phone_number (เบอร์โทรศัพท์)</th>';
					echo '<th>Phone_number2 (เบอร์โทรศัพท์2)</th>';
					echo '<th>COD (ยอดเรียกเก็บ)</th>';
					echo '<th>Item_type (ประเภทสินค้า)</th>';
					echo '<th>Weight_kg (น้ำหนัก)</th>';
					echo '<th>Length (ยาว)</th>';
					echo '<th>Width (กว้าง)</th>';
					echo '<th>Height (สูง)</th>';
					echo '<th>Freight_insurance (คุ้มครองพัสดุตีกลับ)</th>';
					echo '<th>Value_insurance (คุ้มครองพัสดุ)</th>';
					echo '<th>Declared_value (มูลค่าสินค้าที่ระบุโดยลูกค้า)</th>';
					echo '<th>Speed_service (บริการSPEED)</th>';
					echo '<th>Remark1 (หมายเหตุ1)</th>';
					echo '<th>Remark2 (หมายเหตุ2)</th>';
					echo '<th>Remark3 (หมายเหตุ3)</th>';
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
	
	public function get_sox_no_ird() {
        
        header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment; filename=SOX no IRD.xls");
        $data = $this->model->getSOXnoIRD();
        
        echo '<table style="width:100%">';
        
            echo '<tr>';
                echo '<th>iv_no</th>';
                echo '<th>customer_name</th>';
                echo '<th>address</th>';
                echo '<th>zip_code</th>';
                echo '<th>customer_tel</th>';
                echo '<th>sox_type</th>';
                echo '<th>detail</th>';
            echo '</tr>';
            
            foreach($data as $value) {
                echo '<tr>';
                    echo '<td>'.$value['iv_no'].'</td>';
                    echo '<td>'.$value['customer_name'].'</td>';
                    echo '<td>'.$value['address'].'</td>';
                    echo '<td>'.$value['zip_code'].'</td>';
                    echo '<td>'.$value['customer_tel'].'</td>';
                    echo '<td>'.$value['sox_type'].'</td>';
                    echo '<td>'.$value['product_no'].'</td>';
                    echo '<td>'.$value['product_name'].'</td>';
                    echo '<td>'.$value['quantity'].'</td>';
                    
                echo '</tr>';
            }
            
        echo '</table>';
        
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
            $this->view->setTitle("ยืนยันการจัดเตรียม");
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



}
