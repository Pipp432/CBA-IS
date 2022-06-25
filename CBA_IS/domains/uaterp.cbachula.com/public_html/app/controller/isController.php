<?php

namespace controller;

use _core\controller;
use _core\helper\input;
use _core\helper\session;
use _core\helper\uri;

class isController extends controller {

    public function index() { 
        $this->err404();
    }
    
    public function edit_purchase_order() { 
        if(empty(uri::get(2))) {
            $this->requirePostition("is");
            $this->view->setTitle("Edit Purchase Order");
            $this->view->render("is/edit_purchase_order", "navbar");
            //$this->view->render("mkt/gg", "navbar");
        } else if (uri::get(2)==='get_po') {
            $this->positionEcho('is', $this->model->getPo());
        } else if (uri::get(2)==='post_edit_po') {
            $this->positionEcho('is', $this->model->editPo());
        }
    }
    
    public function demo() { 
        if(empty(uri::get(2))) {
            $this->requirePostition("is");
            $this->view->setTitle("demo");
            $this->view->ivs = $this->model->getInvoice();
            $this->view->render("is/demo", "navbar");
        }
    }
    // public function thelastday() { 
    //     $this->requireSignIn();
    //     $this->view->setTitle("The Last Day");
    //     $this->view->getPosition = $this->getPosition();
    //     $this->view->render("is/thelastday");
    // }
	
	public function postEndProject() { 
        $this->requirePost();
		$this->model->endproject();
    }
	
    public function scoreboard() { 
        if(empty(uri::get(2))) {
            $this->requirePostition("is");
            echo json_encode($this->model->getScoreBoard(), JSON_UNESCAPED_UNICODE);
        }
    }
    
    public function tier1(){
        if(empty(uri::get(2))) {
            $this->requirePostition("is");
            echo json_encode($this->model->getTier1(), JSON_UNESCAPED_UNICODE);
        }
    }
    
    public function tier2(){
        if(empty(uri::get(2))) {
            $this->requirePostition("is");
            echo json_encode($this->model->getTier2(), JSON_UNESCAPED_UNICODE);
        }
    }
    
    public function tier3(){
        if(empty(uri::get(2))) {
            $this->requirePostition("is");
            echo json_encode($this->model->getTier3(), JSON_UNESCAPED_UNICODE);
        }
    }
    
    public function tier4(){
        if(empty(uri::get(2))) {
            $this->requirePostition("is");
            echo json_encode($this->model->getTier4(), JSON_UNESCAPED_UNICODE);
        }
    }
    
    public function forecast() { 
        if(empty(uri::get(2))) {
            $this->requirePostition("is");
            $this->view->target = $this->model->newTarget();
            $this->view->actual = $this->model->getSales();
            $this->view->actual_cba = $this->model->getSalesCBA();
            $this->view->yesterday = $this->model->yesterdaySales();
            $this->view->yesterdayCBA = $this->model->yesterdaySalesCBA();
            $this->view->yesterdayGM_SMD = $this->model->yesterdaySalesGM_SMD();
            $this->view->actualGM_SMD = $this->model->getSalesGM_SMD();
            $this->view->actualCS = $this->model->csSales();
            $this->view->yesterdayCS = $this->model->csYesterdaySales();
            $this->view->setTitle("Forecast");
            $this->view->render("is/forecast");
        }
    }
    
    public function infinite_conqueror() { 
        if(empty(uri::get(2))) {
            $sps = $this->model->getInfiniteConqueror();
            echo '<style>table, th, td {
                  border: 1px solid black;
                }</style>';
            echo '<table>';
            echo '<tr>';
                echo '<th>SP</th>';
                echo '<th>Point</th>';
                echo '<th>Sales</th>';
            echo '</tr>';
            foreach($sps as $sp) {
                echo '<tr>';
                    echo '<td>'.$sp['employee_name'].'</td>';
                    echo '<td style="text-align:right;">'.$sp['point'].'</td>';
                    echo '<td style="text-align:right;">'.$sp['sales'].'</td>';
                echo '</tr>';
            }
            echo '</table>';
        }
    }
    public function sales_order() {
        if(empty(uri::get(2))) {
           
            $this->view->setTitle("Sales Order (SO)");
            // $this->view->suppliers = $this->model->getSuppliers();
            // $this->view->products = $this->model->getProducts();
            $this->view->render("is/sales_order", "navbar");
        } else if (uri::get(2)==='get_customer_name') {
            $this->positionEcho('mkt', $this->model->getCustomerName());
        } else if (uri::get(2)==='post_so_items') {
            $this->positionEcho('mkt', $this->model->addSo());
        }
    }

    public function split_rr() { 
        if(empty(uri::get(2))) {
            $this->requirePostition("is");
            $this->view->setTitle("split RR");
            $this->view->IRDRRstock = $this->model->getIRDRRstock();
            $this->view->render("is/split_rr", "navbar");
        } else if (uri::get(2) === 'split') {
            echo $this->model->splitRR();
        }
    }
}
 