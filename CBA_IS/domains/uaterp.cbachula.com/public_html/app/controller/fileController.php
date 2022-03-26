<?php

namespace controller;

use _core\controller;
use _core\helper\input;
use _core\helper\session;
use _core\helper\uri;

class fileController extends controller {

    public function index() { 
        $this->err404();
    }
    
    public function po() { 
        if(empty(uri::get(2))) {
            $this->err404();
        } else {
            if ($this->getPosition() == "ce" || $this->getPosition() == "cm" || $this->getPosition() == "smd" || $this->getPosition() == "scm" || $this->getPosition() == "is" || $this->getPosition() == "acc") {
                $this->view->setTitle("ใบสั่งซื้อ #".uri::get(2));
                $this->view->po = $this->model->getPo(uri::get(2));
                $this->view->render("file/po");
            } else {
                $this->err404();
            }
        }
    }
    
    public function po2() { 
        if(empty(uri::get(2))) {
            $this->err404();
        } else {
            if ($this->getPosition() == "ce" || $this->getPosition() == "cm" || $this->getPosition() == "smd" || $this->getPosition() == "scm" || $this->getPosition() == "is" || $this->getPosition() == "acc") {
                $this->view->setTitle("ใบสั่งซื้อ #".uri::get(2));
                $this->view->po = $this->model->getPo2(uri::get(2));
                $this->view->render("file/po2");
            } else {
                $this->err404();
            }
        }
    }
    
    public function special_po() { 
        if(empty(uri::get(2))) {
            $this->err404();
        } else {
            if ($this->getPosition() == "ce" || $this->getPosition() == "cm" || $this->getPosition() == "smd" || $this->getPosition() == "scm" || $this->getPosition() == "is") {
                $this->view->setTitle("ใบสั่งซื้อ #".uri::get(2));
                $this->view->po = $this->model->getPo(uri::get(2));
                $this->view->render("file/special_po");
            } else {
                $this->err404();
            }
        }
    }
    
    public function special_po2() { 
        if(empty(uri::get(2))) {
            $this->err404();
        } else {
            if ($this->getPosition() == "ce" || $this->getPosition() == "cm" || $this->getPosition() == "smd" || $this->getPosition() == "scm" || $this->getPosition() == "is") {
                $this->view->setTitle("ใบสั่งซื้อ #".uri::get(2));
                $this->view->po = $this->model->getPo(uri::get(2));
                $this->view->render("file/special_po2");
            } else {
                $this->err404();
            }
        }
    }    
    
    
    public function iv_cr() { 
        if(empty(uri::get(2))) {
            $this->err404();
        } else {
            if ($this->getPosition() == "acc" || $this->getPosition() == "fin" || $this->getPosition() == "is") {
                $this->view->setTitle("ใบกำกับภาษี/ใบเสร็จรับเงิน #".uri::get(2));
                $this->view->iv = $this->model->getIv(uri::get(2));
                $this->view->serial = uri::get(3);
                $this->view->render("file/iv_cr");
            } else {
                $this->err404();
            }
        }
    }
    public function iv() { 
        if(empty(uri::get(2))) {
            $this->err404();
        } else {
            if ($this->getPosition() == "acc" || $this->getPosition() == "fin" || $this->getPosition() == "is") {
                $this->view->setTitle("ใบกำกับภาษี #".uri::get(2));
                $this->view->iv = $this->model->getIv(uri::get(2));
                $this->view->serial = uri::get(3);
                $this->view->render("file/iv");
            } else {
                $this->err404();
            }
        }
    }
	public function tr() { 
        if(empty(uri::get(2))) {
            $this->err404();
        } else {
            if ($this->getPosition() == "acc" || $this->getPosition() == "fin" || $this->getPosition() == "is") {
                $this->view->setTitle("รายการโอนเงิน (TR) #".uri::get(2));
				$this->view->tr_price = $this->model->getTR(uri::get(2));
                $this->view->tr_range = $this->model->get_TR_range(uri::get(2));
                $this->view->tr_note = $this->model->get_TR_note(uri::get(2));
                $this->view->render("file/tr");
            } else {
                $this->err404();
            }
        }
    }
    
    public function cn() { 
        if(empty(uri::get(2))) {
            $this->err404();
        } else {
            if ($this->getPosition() == "acc" || $this->getPosition() == "fin" || $this->getPosition() == "is") {
                $this->view->setTitle("ใบลดหนี้/ใบกำกับภาษี #".uri::get(2));
                $this->view->cn = $this->model->getCn(uri::get(2));
                $this->view->render("file/cn");
            } else {
                $this->err404();
            }
        }
    }
    
    public function cr() { 
        if(empty(uri::get(2))) {
            $this->err404();
        } else {
            if ($this->getPosition() == "acc" || $this->getPosition() == "fin" || $this->getPosition() == "is") {
                $this->view->setTitle("ใบเสร็จรับเงิน #".uri::get(2));
                $this->view->cr = $this->model->getCr(uri::get(2));
                $this->view->render("file/cr");
            } else {
                $this->err404();
            }
        }
    }
    
    public function pv() { 
        if(empty(uri::get(2))) {
            $this->err404();
        }else if(uri::get(2)=="pvc"){
            $this->positionEcho('acc', $this->model->getPVC(Uri::get(3)));
            $this->view->render("file/pvc");

        }else {
            if ($this->getPosition() == "acc" || $this->getPosition() == "fin" || $this->getPosition() == "is") {
                $this->view->setTitle("ใบสำคัญสั่งจ่าย #".uri::get(2));
                $this->view->pv = $this->model->getPv(uri::get(2));
                $this->view->render("file/pv");
            } else {
                $this->err404();
            }
        }
    }
    public function pvc() { 
        if(empty(uri::get(2))) {
            $this->err404();
        } else if(uri::get(2)==="get_PVC"){
            $this->positionEcho('file', $this->model->getPVC(Uri::get(3)));
           
        }
        else {
            if ($this->getPosition() == "acc" || $this->getPosition() == "fin" || $this->getPosition() == "is") {
                $this->view->setTitle("ใบสำคัญสั่งจ่าย #".uri::get(2));
                $this->view->pvc = $this->model->getPvc(uri::get(3));
                $this->view->render("file/pvc");
            } else {
                $this->err404();
            }
        }
    }
    
    public function cs() { 
        if(empty(uri::get(2))) {
            $this->err404();
        } else if(uri::get(2) == 'scm' || uri::get(2) == 'mkt') {
            $this->view->setTitle("Counter Sales #".uri::get(3));
            $this->view->cs = $this->model->getCs(uri::get(3));
            $this->view->type = strtoupper(uri::get(2));
            $this->view->render("file/cs");
        } else {
            $this->err404();
        }
    }
    
    public function re() { 
        if(empty(uri::get(2))) {
            $this->err404();
        } else {
            if ($this->getPosition() == "ce" || $this->getPosition() == "cm" || $this->getPosition() == "smd" || $this->getPosition() == "scm" || $this->getPosition() == "is" || $this->getPosition() == "acc") {
                $this->view->setTitle("ใบคืนสินค้า #".uri::get(2));
                $this->view->re = $this->model->getRE(uri::get(2));
                $this->view->render("file/re");
            } else {
                $this->err404();
            }
        }
    }
	
	public function rr() { 
        if(empty(uri::get(2))) {
            $this->err404();
        } else {
            if ($this->getPosition() == "smd" || $this->getPosition() == "scm" || $this->getPosition() == "is" || $this->getPosition() == "acc") {
                $this->view->setTitle("ใบรับสินค้า #".uri::get(2));
                $this->view->rr = $this->model->getRR(uri::get(2));
                $this->view->render("file/rr");
            } else {
                $this->err404();
            }
        }
    }
	
	public function ird() { 
        if(empty(uri::get(2))) {
            $this->err404();
        } else {
            if ($this->getPosition() == "smd" || $this->getPosition() == "scm" || $this->getPosition() == "is" || $this->getPosition() == "acc") {
                $this->view->setTitle("ใบส่งสินค้า #".uri::get(2));
                $this->view->ird = $this->model->getIRD(uri::get(2));
                $this->view->render("file/ird");
            } else {
                $this->err404();
            }
        }
    }
}
 