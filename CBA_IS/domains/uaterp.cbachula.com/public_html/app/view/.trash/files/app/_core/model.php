<?php

namespace _core;

use _core\helper\database;
use _core\helper\input;
use _core\helper\session;
use PDO;

class model extends database {
    
    public function __construct() {
        parent::__construct();
    }

    public function signin() {
        $sql = $this->prepare("select * from employees where employee_no = ? and employee_password = ?");
        $sql->execute([
            input::post('employee_no'), 
            hash('sha512', input::post('employee_password'))
        ]);
        if ($sql->rowCount() > 0) {
            session::set('employee_no', strtoupper(input::post('employee_no')));
            session::set('employee_password', hash('sha512', input::post('employee_password')));
            session::set('employee_detail', json_encode($sql->fetchAll(PDO::FETCH_ASSOC)[0], JSON_UNESCAPED_UNICODE));
            return true;
        }
        return false;
    }

}
