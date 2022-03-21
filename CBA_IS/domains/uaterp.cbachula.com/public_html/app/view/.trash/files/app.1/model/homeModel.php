<?php

namespace model;

use _core\model;
use _core\helper\input;
use _core\helper\session;
use PDO;

class homeModel extends model {

    public function get_payees() {

        $sql = $this->prepare("select * from payees");
        $sql->execute();

        return  json_encode($sql->rowCount() > 0 ? $sql->fetchAll(PDO::FETCH_ASSOC) : [], JSON_UNESCAPED_UNICODE);

    }

    public function get_batches() {

        $sql = $this->prepare("select 
                                    batches.course_no, 
                                    courses.course_name, 
                                    batches.batch_no
                                from batches
                                join courses on courses.course_no = batches.course_no
                                where batches.batch_status > -1");
        $sql->execute();

        return  json_encode($sql->rowCount() > 0 ? $sql->fetchAll(PDO::FETCH_ASSOC) : [], JSON_UNESCAPED_UNICODE);

    }


    public function add_dv() {
        
        $dv_employee_no = session::get('employee_no');

        $dv_type = input::post('dv_type');
        $dv_no = $this->assign_document_no(input::post('dv_project_no').'DV-'.substr(date("Y") + 543, 2), 'dv_no', 'dvs');

        $payee_no = input::post('i_am_payee_check') == 'true' ? $dv_employee_no : input::post('payee_chosen');

        if($payee_no == 'add') { $payee_no = $this->add_payee(); }

        $dv_status = $dv_type == '1' ? 0 : 3;
        
        $sql = $this->prepare('insert into dvs (dv_no, dv_datetime, dv_employee_no, dv_account_no, dv_payee_no, dv_type, dv_slip_no, pv_no, dv_status)
                                values (?, current_timestamp, ?, ?, ?, ?, null, null, ?)');
        $sql->execute([$dv_no, $dv_employee_no, input::post('dv_account_no'), $payee_no, $dv_type, $dv_status]);

        if($sql->errorInfo()[0] == '00000') {

            $dv_items = json_decode(input::post('dv_items'), true);
            $dv_items = json_decode($dv_items, true); 

            foreach ($dv_items as $value) {
                $sql = $this->prepare('insert into dv_items (dv_no, course_no, batch_no, dv_item_amount) values (?, ?, ?, ?)');
                $sql->execute([$dv_no, $value['course_no'], $value['batch_no'], $value['dv_item_amount']]);
            }

            return $dv_no;

        } else {

            return 'error';

        }

    }

    private function assign_payee_no() {

        $document_title = substr(date("Y") + 543, 2);

        $sql = $this->prepare("select ifnull(max(payee_no), 0) as max from payees where payee_no like '".$document_title."%'");
        $sql->execute();

        $max_no = $sql->fetchAll()[0]['max'];
        $running_no = '';
        if($max_no == '0') {
            $running_no = '001';
        } else {
            $latest_running_no = (int) substr($max_no, 2) + 1;
            if(strlen($latest_running_no) == 3) {
                $running_no = $latest_running_no;
            } else {
                for ($x = 1; $x <= 3 - strlen($latest_running_no); $x++) $running_no .= '0';
                $running_no .= $latest_running_no;
            }
        }
        return $document_title.$running_no;

    }

    public function add_payee() {

        $payee_no = $this->assign_payee_no();

        $sql = $this->prepare('insert into payees (payee_no, payee_name, payee_address, payee_id_no, payee_bank, payee_bank_no, payee_bank_name) values (?, ?, ?, ?, ?, ?, ?)');
        $sql->execute([$payee_no, input::post('payee_name'), input::post('payee_address'), input::post('payee_id_no'), input::post('payee_bank'), input::post('payee_bank_no'), input::post('payee_bank_name')]);

        return $sql->errorInfo()[0] == '00000' ? $payee_no : 'error';

    }

    public function add_slip() {

        $slip_no = $this->assign_document_no('SLP', 'slip_no', 'slips');

        $dv_slip_data = base64_encode(file_get_contents($_FILES['dv_slip']['tmp_name']));
        $dv_slip_type = $_FILES['dv_slip']['type'];

        $sql = $this->prepare('insert into slips (slip_no, slip_type, slip_data) values (?, ?, ?)');
        $sql->execute([$slip_no, $dv_slip_type, $dv_slip_data]);

        return $sql->errorInfo()[0] == '00000' ? $slip_no : 'error';

    }

}