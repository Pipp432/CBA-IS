<?php

namespace controller;

use _core\controller;
use _core\helper\input;
use _core\helper\session;
use _core\helper\uri;

class osController extends controller {

    public function index() { 
      
        $this->err404();
    }
    
    public function sales_order() {
        if(empty(uri::get(2))) {
           
            $this->view->setTitle("Sales Order (SO)");
            // $this->view->suppliers = $this->model->getSuppliers();
            // $this->view->products = $this->model->getProducts();
            $this->view->render("os/sales_order", "navbar");
        } else if (uri::get(2)==='get_customer_name') {
            $this->positionEcho('mkt', $this->model->getCustomerName());
        } else if (uri::get(2)==='post_so_items') {
            $this->positionEcho('mkt', $this->model->addSo());
        }
        
    }

}

