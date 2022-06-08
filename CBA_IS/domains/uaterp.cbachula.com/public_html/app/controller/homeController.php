<?php

namespace controller;

use _core\controller;
use _core\helper\input;
use _core\helper\session;
use _core\helper\uri;

class homeController extends controller {

    public function index() {
        $this->requireSignIn();
        $this->view->setTitle("Home");
        $this->view->getPosition = $this->getPosition();
		$this->view->sales = $this->model->getCompanySales();
        $this->view->render("home/index", "navbar");
    }
    
    public function get_password() {
        if(empty(uri::get(2))) {
            $this->view->setTitle("Get Username and Password");
            $this->view->render("home/get_password");
        } else if (uri::get(2)==='get') {
            echo $this->model->getUsernamePassword();
        }
        
    }
    
    public function add_customer() {
        if(empty(uri::get(2))) {
            $this->view->setTitle("Add Customer");
            $this->view->render("home/add_customer", "navbar");
        } else if (uri::get(2)==='post_customer') {
            echo $this->model->addCustomer();
        }
        else if (uri::get(2)==='get_all') {
            echo $this->model->getAll();
        }
    }
    
    public function employeeDetail() {
        $this->requireSignIn();
        echo session::get('employee_detail');
    }
	
	public function sales_and_margin(){	
		$this->requireSignIn();
		$this->view->setTitle("Product Line Sales and Margin");
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
		$this->view->render("home/sales_and_margin", "navbar");
	}

}