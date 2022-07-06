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

    public function po3() { 
        if(empty(uri::get(2))) {
            $this->err404();
        } else {
            if ($this->getPosition() == "ce" || $this->getPosition() == "cm" || $this->getPosition() == "smd" || $this->getPosition() == "scm" || $this->getPosition() == "is" || $this->getPosition() == "acc") {
                $this->view->setTitle("ใบสั่งซื้อ #".uri::get(2));
                $this->view->po = $this->model->getPo3(uri::get(2));
                $this->view->render("file/po3");
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

    public function iv_cr2() { 
        if(empty(uri::get(2))) {
            $this->err404();
        } else {
            if ($this->getPosition() == "acc" || $this->getPosition() == "fin" || $this->getPosition() == "is") {
                $this->view->setTitle("ใบกำกับภาษี/ใบเสร็จรับเงิน #".uri::get(2));
                $this->view->iv = $this->model->getIv(uri::get(2));
                $this->view->serial = uri::get(3);
                $this->view->render("file/iv_cr2");
            } else {
                $this->err404();
            }
        }
    }

    public function iv_cr_test() { 
        if(empty(uri::get(2))) {
            $this->err404();
        } else {
            if ($this->getPosition() == "acc" || $this->getPosition() == "fin" || $this->getPosition() == "is") {
                $this->view->setTitle("ใบกำกับภาษี/ใบเสร็จรับเงิน #".uri::get(2));
                $this->view->iv = $this->model->getIv(uri::get(2));
                $this->view->serial = uri::get(3);
                $this->view->render("file/iv_cr_test");
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

    public function pvd() { 
        if(empty(uri::get(2))) {
            $this->err404();
        } else {
            if ($this->getPosition() == "acc" || $this->getPosition() == "fin" || $this->getPosition() == "is") {
                $this->view->setTitle("ใบลดหนี้ Peyment Voucher-D #".uri::get(2));
                $this->view->cn = $this->model->getPVD(uri::get(2));
                $this->view->render("file/pvd");
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
            $this->positionEcho('acc', $this->model->getPV(Uri::get(3)));
            $this->view->render("file/pvc");

        }else {
          
                $this->view->setTitle("ใบสำคัญสั่งจ่าย #".uri::get(2));
                $this->view->pv = $this->model->getPv(uri::get(2));
                $this->view->render("file/pv");
             
        }
    }
    public function pvc() { 
        if(empty(uri::get(2))) {
            $this->err404();
        }else if(uri::get(2)=="pvc"){
            $this->positionEcho('acc', $this->model->getPVC(Uri::get(3)));
            $this->view->render("file/pvc");
            

        }else {
            if ($this->getPosition() == "acc" || $this->getPosition() == "fin" || $this->getPosition() == "is") {
                $this->view->setTitle("ใบสำคัญสั่งจ่าย #".uri::get(2));
                $this->view->pvc = $this->model->getPVC(uri::get(2));
                $this->view->render("file/pvc");
                echo "<script>console.log('Debug Objects: " . $this->model->getPVC(uri::get(2)) . "' );</script>";
            } else {
                $this->err404();
            }
        }
    }
    
    public function re_req() { 
        if(empty(uri::get(2))) {
            $this->err404();
        } else if(uri::get(2)==="get_re_req"){
            $this->positionEcho('file', $this->model->getReReq(Uri::get(3)));
            $this->view->render("file/re_req");
           
        }
        else {
            if ($this->getPosition() == "acc" || $this->getPosition() == "fin" || $this->getPosition() == "is") {
                $this->view->setTitle("ใบสำคัญสั่งจ่าย #".uri::get(2));
                $this->view->re_req = $this->model->getReReq(uri::get(2));
                $this->view->render("file/re_req");
            } else {
                $this->err404();
            }
        }
    }
    public function exc() { 
        if(empty(uri::get(2))) {
            $this->err404();
        } else if(uri::get(2)==="get_re_req"){
            $this->positionEcho('file', $this->model->getReReq(Uri::get(3)));
            $this->view->render("file/exc");
           
        }
        else {
            if ($this->getPosition() == "acc" || $this->getPosition() == "fin" || $this->getPosition() == "is") {
                $this->view->setTitle("ใบสำคัญสั่งจ่าย #".uri::get(2));
                $this->view->re_req = $this->model->getEXC(uri::get(2));
                $this->view->render("file/exc");
            } else {
                $this->err404();
            }
        }
    }
   
    public function pva() { 

        if(empty(uri::get(2))) {
            $this->err404();
        } else {
            $this->view->setTitle("ใบสั่งเติมเงินรองจ่าย #".uri::get(2));
            $this->view->pv = $this->model->getPva(Uri::get(2));
            $this->view->pvaChilds = $this->model->getPvaChild(Uri::get(2));
            $this->view->render("file/pva"); 
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
    
    public function RI() { 
        if(empty(uri::get(2))) {
            $this->err404();
        } else {
            if ($this->getPosition() == "ce" || $this->getPosition() == "cm" || $this->getPosition() == "smd" || $this->getPosition() == "scm" || $this->getPosition() == "is" || $this->getPosition() == "acc") {
                $this->view->setTitle("ใบคืนสินค้า #".uri::get(2));
                $this->view->ri = $this->model->getRI(uri::get(2));
                $this->view->render("file/ri");
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

    public function pve() { 
        if(empty(uri::get(2))) {
            $this->err404();
        } else {
            if ($this->getPosition() == "acc" || $this->getPosition() == "fin" || $this->getPosition() == "is") 
                {
                $this->view->setTitle("ใบสำคัญสั่งจ่าย".uri::get(2));
                //$this->view->cn = $this->model->getCn(uri::get(2));
                $this->view->render("file/pve");
            } else {
                $this->err404();
            }
        }
    }
}
 