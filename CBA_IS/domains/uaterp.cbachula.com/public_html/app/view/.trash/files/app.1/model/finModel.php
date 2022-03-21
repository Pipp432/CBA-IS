<?php

namespace model;

use _core\model;
use _core\helper\input;
use _core\helper\session;
use PDO;

class finModel extends model {
    
    public function get_rgs() {

        $sql = $this->prepare("select 
                                    rgs.rg_no,
                                    rgs.rg_datetime, 
                                    concat(customers.customer_first_name, ' ', customers.customer_last_name) as customer_name,
                                    customers.customer_tel,
                                    case
                                        when rgs.rg_type = '1' then 'บุคคลธรรมดา'
                                        when rgs.rg_type = '2' then 'นิติบุคคล (เอกชน)'
                                        when rgs.rg_type = '3' then 'นิติบุคคล (รัฐบาล)'
                                    end as rg_type,
                                    concat(courses.course_name, ' รุ่นที่ ', convert(rgs.batch_no, unsigned)) as course_name,
                                    (rgs.rg_total_price - rgs.rg_total_discount) as sub_total_price,
                                    case
                                        when rgs.rg_type = '1' then (rgs.rg_total_price - rgs.rg_total_discount)
                                        when rgs.rg_type = '2' then round((rgs.rg_total_price - rgs.rg_total_discount)/107*105.5, 2)
                                        when rgs.rg_type = '3' then round((rgs.rg_total_price - rgs.rg_total_discount)/107*106, 2)
                                    end as total_price,
                                    if(rgs.rg_status = '0', timestampdiff(hour, rgs.rg_confirmed_datetime, current_timestamp), '-') as time_diff,
                                    case
                                        when rgs.rg_status = '-1' then 'ยกเลิกแล้ว'
                                        when rgs.rg_status = '0' then 'ค้างชำระ'
                                        when rgs.rg_status = '1' then 'ชำระเงินแล้ว'
                                        when rgs.rg_status = '2' then 'ยังไม่ได้ยืนยันการสมัคร'
                                        when rgs.rg_status = '3' then 'การสมัครมีปัญหา'
                                    end as rg_status,
                                    rgs.rg_slip_no
                                from rgs
                                join customers on customers.customer_no = rgs.customer_no
                                join courses on courses.course_no = rgs.course_no
                                where rgs.rg_confirmed_datetime is not null");
        $sql->execute();

        return  json_encode($sql->rowCount() > 0 ? $sql->fetchAll(PDO::FETCH_ASSOC) : [], JSON_UNESCAPED_UNICODE);

    }

    public function add_iv() {

        $rg = json_decode(input::post('rg'), true);
        $rg = json_decode($rg, true); 

        $rg_no = $rg['rg_no'];

        $project_no = substr($rg_no, 0, 1);
        $iv_no = $this->assign_document_no($project_no.'IV-'.substr(date("Y") + 543, 2), 'iv_no', 'ivs');

        $sub_total_sales_price = $rg['sub_total_price'];
        $total_sales_no_vat = (double) $sub_total_sales_price / 107 * 100;
        $total_sales_vat = (double) $sub_total_sales_price / 107 * 7;
        $total_sales_wht = (double) round($total_sales_no_vat, 2) + round($total_sales_vat, 2) - round($rg['total_price'], 2);

        $sql = $this->prepare("insert into ivs (iv_no, iv_datetime, rg_no, iv_status) values (?, current_timestamp, ?, ?)");
        $sql->execute([$iv_no, $rg_no, $rg['rg_type'] == 'บุคคลธรรมดา' ? '1' : '2']);

        // Dr. เงินฝากธนาคาร
        $sql = $this->prepare("insert into journals (file_no, sequence, account_no, project_no, date, debit, credit) values (?, ?, ?, ?, current_date, ?, ?)");
        $sql->execute([$iv_no, 1, '1-01', '0', $rg['total_price'], 0]);

        // Dr. ภาษีหัก ณ ที่จ่าย
        if ($total_sales_wht > 0) {
            $sql = $this->prepare("insert into journals (file_no, sequence, account_no, project_no, date, debit, credit) values (?, ?, ?, ?, current_date, ?, ?)");
            $sql->execute([$iv_no, 2, '6-03', $project_no, $total_sales_wht, 0]);
        }

        // Cr. รายได้ค่าบริการ
        $sql = $this->prepare("insert into journals (file_no, sequence, account_no, project_no, date, debit, credit) values (?, ?, ?, ?, current_date, ?, ?)");
        $sql->execute([$iv_no, 3, '4-01', $project_no, 0, $total_sales_no_vat]);

        // Cr. ภาษีขาย
        $sql = $this->prepare("insert into journals (file_no, sequence, account_no, project_no, date, debit, credit) values (?, ?, ?, ?, current_date, ?, ?)");
        $sql->execute([$iv_no, 4, '6-02', $project_no, 0, $total_sales_vat]);

        $sql = $this->prepare("insert into edit_logs (file_no, table_name, column_name, old_value, new_value, employee_no, log_datetime) 
                                select ?, 'rgs', 'rg_status', rgs.rg_status, '1', ?, current_timestamp from rgs where rgs.rg_no = ?");
        $sql->execute([$rg_no, session::get('employee_no'), $rg_no]);

        $sql = $this->prepare("update rgs set rgs.rg_status = 1 where rgs.rg_no = ?");
        $sql->execute([$rg_no]);

    }

    public function report_rg() {

        $rg_no = input::post('rg_no');

        $sql = $this->prepare("insert into edit_logs (file_no, table_name, column_name, old_value, new_value, employee_no, log_datetime) 
                                select ?, 'rgs', 'rg_status', rgs.rg_status, '3', ?, current_timestamp from rgs where rgs.rg_no = ?");
        $sql->execute([$rg_no, session::get('employee_no'), $rg_no]);

        $sql = $this->prepare("update rgs set rg_status = 3 where rg_no = ?");
        $sql->execute([$rg_no]);

    }

    public function cancel_rg() {
        
        $rg_no = input::post('rg_no');

        $sql = $this->prepare("insert into edit_logs (file_no, table_name, column_name, old_value, new_value, employee_no, log_datetime) 
                                select ?, 'rgs', 'rg_status', rgs.rg_status, '-1', ?, current_timestamp from rgs where rgs.rg_no = ?");
        $sql->execute([$rg_no, session::get('employee_no'), $rg_no]);

        $sql = $this->prepare("update rgs set rg_status = -1 where rg_no = ?");
        $sql->execute([$rg_no]);

    }

    public function get_amount() {

        $sql = $this->prepare("select 
                                    substring(journals.file_no, 1, 1) as project_no, 
                                    sum(journals.debit) - sum(journals.credit) as amount
                                from journals
                                where journals.account_no = '1-01'
                                group by substring(journals.file_no, 1, 1)");
        $sql->execute();

        return  json_encode($sql->rowCount() > 0 ? $sql->fetchAll(PDO::FETCH_ASSOC) : [], JSON_UNESCAPED_UNICODE);

    }

    public function get_trs() {

        $sql = $this->prepare("select trs.*, employees.employee_nickname from trs join employees on employees.employee_no = trs.tr_employee_no");
        $sql->execute();

        return  json_encode($sql->rowCount() > 0 ? $sql->fetchAll(PDO::FETCH_ASSOC) : [], JSON_UNESCAPED_UNICODE);

    }

    public function add_tr() {

        $tr_no = $this->assign_document_no('0TR-'.substr(date("Y") + 543, 2), 'tr_no', 'trs');
        $tr_amount_1 = (double) input::post('tr_amount_1');
        $tr_amount_2 = (double) input::post('tr_amount_2');

        $sql = $this->prepare("insert into trs (tr_no, tr_datetime, tr_employee_no, amount_1, amount_2) values (?, current_timestamp, ?, ?, ?)");
        $sql->execute([$tr_no, session::get('employee_no'), $tr_amount_1, $tr_amount_2]);

        if ($tr_amount_1 > 0) {
                
            // Dr. เงินฝากออมทรัพย์
            $sql = $this->prepare("insert into journals (file_no, sequence, account_no, project_no, date, debit, credit) values (?, ?, ?, ?, current_date, ?, ?)");
            $sql->execute(['1'.substr($tr_no, 1), 1, '1-02', '1', $tr_amount_1, 0]);
            
            // Cr. เงินฝากธนาคาร
            $sql = $this->prepare("insert into journals (file_no, sequence, account_no, project_no, date, debit, credit) values (?, ?, ?, ?, current_date, ?, ?)");
            $sql->execute(['1'.substr($tr_no, 1), 3, '1-01', '0', 0, $tr_amount_1]);

        }

        if ($tr_amount_2 > 0) {
                
            // Dr. เงินฝากออมทรัพย์
            $sql = $this->prepare("insert into journals (file_no, sequence, account_no, project_no, date, debit, credit) values (?, ?, ?, ?, current_date, ?, ?)");
            $sql->execute(['2'.substr($tr_no, 1), 1, '1-02', '1', $tr_amount_2, 0]);
            
            // Cr. เงินฝากธนาคาร
            $sql = $this->prepare("insert into journals (file_no, sequence, account_no, project_no, date, debit, credit) values (?, ?, ?, ?, current_date, ?, ?)");
            $sql->execute(['2'.substr($tr_no, 1), 3, '1-01', '0', 0, $tr_amount_2]);

        }

    }

    public function confirm_dv() {

        $dv_no = input::post('dv_no');

        $sql = $this->prepare("insert into edit_logs (file_no, table_name, column_name, old_value, new_value, employee_no, log_datetime) 
                                select ?, 'dvs', 'dv_status', dvs.dv_status, '4', ?, current_timestamp from dvs where dvs.dv_no = ?");
        $sql->execute([$dv_no, session::get('employee_no'), $dv_no]);

        $sql = $this->prepare("update dvs set dv_status = 4 where dv_no = ?");
        $sql->execute([$dv_no]);

    }

    public function add_slip_dv() {

        $slip_no = $this->assign_document_no('SLP-'.substr(date("Y") + 543, 2), 'slip_no', 'slips');

        $dv_slip_data = base64_encode(file_get_contents($_FILES['dv_slip']['tmp_name']));
        $dv_slip_type = $_FILES['dv_slip']['type'];

        $sql = $this->prepare('insert into slips (slip_no, slip_type, slip_data, employee_no) values (?, ?, ?, ?)');
        $sql->execute([$slip_no, $dv_slip_type, $dv_slip_data, session::get('employee_no')]);

        if($sql->errorInfo()[0] == '00000') {

            $to_paid_dvs = json_decode(input::post('to_paid_dvs'), true); 
            $to_paid_dvs = json_decode($to_paid_dvs, true); 

            foreach($to_paid_dvs as $value) {

                $sql = $this->prepare("insert into edit_logs (file_no, table_name, column_name, old_value, new_value, employee_no, log_datetime) 
                                        values (?, 'dvs', 'dv_slip_no', 'null', ?, ?, current_timestamp)");
                $sql->execute([$value['dv_no'], $slip_no, session::get('employee_no')]);

                $sql = $this->prepare("insert into edit_logs (file_no, table_name, column_name, old_value, new_value, employee_no, log_datetime) 
                                        values (?, 'dvs', 'dv_status', '0', '2', ?, current_timestamp)");
                $sql->execute([$value['dv_no'], session::get('employee_no')]);

                $sql = $this->prepare("update dvs set dv_slip_no = ?, dv_status = 2 where dv_no = ?");
                $sql->execute([$slip_no, $value['dv_no']]);

            }

        } else {

            return 'error';

        }

    }

    public function add_slip_pv() {

        $slip_no = $this->assign_document_no('SLP-'.substr(date("Y") + 543, 2), 'slip_no', 'slips');

        $pv_slip_data = base64_encode(file_get_contents($_FILES['pv_slip']['tmp_name']));
        $pv_slip_type = $_FILES['pv_slip']['type'];

        $sql = $this->prepare('insert into slips (slip_no, slip_type, slip_data, employee_no) values (?, ?, ?, ?)');
        $sql->execute([$slip_no, $pv_slip_type, $pv_slip_data, session::get('employee_no')]);

        if($sql->errorInfo()[0] == '00000') {

            $to_paid_pvs = json_decode(input::post('to_paid_pvs'), true); 
            $to_paid_pvs = json_decode($to_paid_pvs, true); 

            foreach($to_paid_pvs as $value) {

                $sql = $this->prepare("insert into edit_logs (file_no, table_name, column_name, old_value, new_value, employee_no, log_datetime) 
                                        values (?, 'pvs', 'pv_slip_no', 'null', ?, ?, current_timestamp)");
                $sql->execute([$value['pv_no'], $slip_no, session::get('employee_no')]);

                $sql = $this->prepare("insert into edit_logs (file_no, table_name, column_name, old_value, new_value, employee_no, log_datetime) 
                                        values (?, 'pvs', 'pv_status', '0', '2', ?, current_timestamp)");
                $sql->execute([$value['pv_no'], session::get('employee_no')]);

                $sql = $this->prepare("update pvs set pv_slip_no = ?, pv_status = 2 where pv_no = ?");
                $sql->execute([$slip_no, $value['pv_no']]);

            }

        } else {

            return 'error';

        }

    }

}