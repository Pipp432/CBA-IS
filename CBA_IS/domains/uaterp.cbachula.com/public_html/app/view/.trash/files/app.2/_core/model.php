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
            session::set('employee_detail', $sql->fetchAll()[0]);
            return true;
        }
        return false;
    }

    protected function assign_document_no($document_title, $document_column, $document_table) {
        if(!stristr($document_column.$document_table, 'delete') && !stristr($document_column.$document_table, 'update')) {
            $sql = $this->prepare("select ifnull(max(".$document_column."), 0) as max from ".$document_table." where ".$document_column." like '".$document_title."%'");
            $sql->execute();
            $max_no = $sql->fetchAll()[0]['max'];
            $running_no = '';
            if($max_no == '0') {
                $running_no = '0001';
            } else {
                $latest_running_no = (int) substr($max_no, 6) + 1;
                if(strlen($latest_running_no) == 4) {
                    $running_no = $latest_running_no;
                } else {
                    for ($x = 1; $x <= 4 - strlen($latest_running_no); $x++) $running_no .= '0';
                    $running_no .= $latest_running_no;
                }
            }
            return $document_title.$running_no;
        }
        return null;
    }

    public function get_dvs() {

        $sql = $this->prepare("select 
                                    dvs.dv_no, 
                                    dvs.dv_type,
                                    dvs.dv_datetime,
                                    dvs.dv_employee_no, 
                                    employees.employee_nickname, 
                                    payees.payee_no,
                                    payees.payee_name,
                                    payees.payee_bank,
                                    payees.payee_bank_no,
                                    payees.payee_bank_name,
                                    dvs.dv_account_no,
                                    accounts.account_name,
                                    dvs.dv_slip_no,
                                    dvs.pv_no,
                                    case
                                        when dvs.dv_status = -1 then 'ยกเลิกแล้ว'
                                        when dvs.dv_status = 0 then 'ยังไม่ได้จ่ายเงิน'
                                        when dvs.dv_status = 1 then 'จ่ายเงินแล้ว'
                                        when dvs.dv_status = 2 then 'รอยืนยันการจ่าย'
                                        when dvs.dv_status = 3 then 'รออนุมัติ'
                                        when dvs.dv_status = 4 then 'รอออกใบสำคัญจ่าย'
                                    end as dv_status,
                                    dv_items.dv_total_amount
                                from dvs
                                join employees on employees.employee_no = dvs.dv_employee_no
                                join payees on payees.payee_no = dvs.dv_payee_no
                                join accounts on accounts.account_no = dvs.dv_account_no
                                join (select 
                                            dv_items.dv_no, 
                                            sum(dv_items.dv_item_amount) as dv_total_amount 
                                        from dv_items 
                                        group by dv_items.dv_no) as dv_items on dv_items.dv_no = dvs.dv_no
                                where dvs.dv_employee_no = ? or ? in ('acc', 'fin', 'is')");
        $sql->execute([session::get('employee_no'), session::get('employee_detail')['employee_position']]);

        return  json_encode($sql->rowCount() > 0 ? $sql->fetchAll(PDO::FETCH_ASSOC) : [], JSON_UNESCAPED_UNICODE);

    }

    public function cancel_dv() {

        $dv_no = input::post('dv_no');

        $sql = $this->prepare("insert into edit_logs (file_no, table_name, column_name, old_value, new_value, employee_no, log_datetime) 
                                select ?, 'dvs', 'dv_status', dvs.dv_status, '-1', ?, current_timestamp from dvs where dvs.dv_no = ?");
        $sql->execute([$dv_no, session::get('employee_no'), $dv_no]);

        $sql = $this->prepare("update dvs set dv_status = -1 where dv_no = ?");
        $sql->execute([$dv_no]);

    }

    public function get_pvs() {

        $sql = $this->prepare("select 
                                    pvs.pv_no, 
                                    pvs.pv_type,
                                    payees.payee_no,
                                    payees.payee_name,
                                    payees.payee_bank,
                                    payees.payee_bank_no,
                                    payees.payee_bank_name,
                                    pvs.pv_datetime,
                                    pvs.pv_employee_no, 
                                    employees.employee_nickname, 
                                    pvs.pv_slip_no,
                                    case
                                        when pvs.pv_status = -1 then 'ยกเลิกแล้ว'
                                        when pvs.pv_status = 0 then 'ยังไม่ได้จ่ายเงิน'
                                        when pvs.pv_status = 1 then 'จ่ายเงินแล้ว'
                                        when pvs.pv_status = 2 then 'รอยืนยันการจ่าย'
                                    end as pv_status,
                                    pvs.pv_total_amount
                                from pvs
                                join employees on employees.employee_no = pvs.pv_employee_no
                                left join dvs on dvs.pv_no = pvs.pv_no and dvs.dv_type = 2
                                left join pv4s on pv4s.pv_no = pvs.pv_no
                                left join payees on payees.payee_no = ifnull(dvs.dv_payee_no, ifnull(pv4s.pv_payee_no, '00000'))");
        $sql->execute();

        return  json_encode($sql->rowCount() > 0 ? $sql->fetchAll(PDO::FETCH_ASSOC) : [], JSON_UNESCAPED_UNICODE);

    }

}