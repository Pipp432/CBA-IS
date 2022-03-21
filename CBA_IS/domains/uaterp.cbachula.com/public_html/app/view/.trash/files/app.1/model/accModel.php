<?php

namespace model;

use _core\model;
use _core\helper\input;
use _core\helper\session;
use PDO;

class accModel extends model {

    public function get_ivs() {

        $sql = $this->prepare("select 
                                    ivs.iv_no, 
                                    'iv' as iv_type,
                                    date_format(ivs.iv_datetime, '%Y/%m/%d') as iv_date, 
                                    rgs.rg_no,
                                    rgs.rg_iv_name, 
                                    case
                                        when rgs.rg_type = '1' then 'บุคคลธรรมดา'
                                        when rgs.rg_type = '2' then 'นิติบุคคล (เอกชน)'
                                        when rgs.rg_type = '3' then 'นิติบุคคล (รัฐบาล)'
                                    end as rg_type,
                                    case
                                        when ivs.iv_status = '-1' then 'ลดหนี้แล้ว'
                                        when ivs.iv_status = '0' then 'อยู่ในขั้นตอนลดหนี้'
                                        when ivs.iv_status = '1' then '-'
                                        when ivs.iv_status = '2' then 'ยังไม่ได้ส่งใบ 50 ทวิ'
                                    end as iv_status
                                from ivs
                                join rgs on rgs.rg_no = ivs.rg_no
                                union
                                select 
                                    pv4s.cn_no, 
                                    'cn',
                                    date_format(pv4s.cn_datetime, '%Y/%m/%d'), 
                                    '-',
                                    rgs.rg_iv_name, 
                                    case
                                        when rgs.rg_type = '1' then 'บุคคลธรรมดา'
                                        when rgs.rg_type = '2' then 'นิติบุคคล (เอกชน)'
                                        when rgs.rg_type = '3' then 'นิติบุคคล (รัฐบาล)'
                                    end as rg_type,
                                    '-'
                                from pv4s
                                join ivs on ivs.iv_no = pv4s.iv_no
                                join rgs on rgs.rg_no = ivs.rg_no
                                where pv4s.cn_no is not null");
        $sql->execute();

        return  json_encode($sql->rowCount() > 0 ? $sql->fetchAll(PDO::FETCH_ASSOC) : [], JSON_UNESCAPED_UNICODE);

    }

    public function get_tax_invoice_detail($iv_no) {

        $sql = $this->prepare("select ivs.iv_no, rgs.rg_iv_name, rgs.rg_iv_address, rgs.rg_iv_id_no from ivs
                                join rgs on rgs.rg_no = ivs.rg_no
                                where ivs.iv_no = ?");
        $sql->execute([$iv_no]);

        return  json_encode($sql->rowCount() > 0 ? $sql->fetchAll(PDO::FETCH_ASSOC) : [], JSON_UNESCAPED_UNICODE);

    }

    public function edit_iv() {

        $iv = json_decode(input::post('iv'), true);
        $iv = json_decode($iv, true); 

        $iv_no = $iv['iv_no'];
        $rg_iv_name_old = $iv['rg_iv_name'];
        $rg_iv_address_old = $iv['rg_iv_address'];
        $rg_iv_id_no_old = $iv['rg_iv_id_no'];

        $rg_iv_name_new = input::post('rg_iv_name');
        $rg_iv_address_new = input::post('rg_iv_address');
        $rg_iv_id_no_new = input::post('rg_iv_id_no');

        if ($rg_iv_name_old != $rg_iv_name_new) {
            $sql = $this->prepare("insert into edit_logs (file_no, table_name, column_name, old_value, new_value, employee_no, log_datetime) 
                                    values (?, 'rgs', 'rg_iv_name', ?, ?, ?, current_timestamp)");
            $sql->execute([$iv_no, $rg_iv_name_old, $rg_iv_name_new, session::get('employee_no')]);
        }

        if ($rg_iv_address_old != $rg_iv_address_new) {
            $sql = $this->prepare("insert into edit_logs (file_no, table_name, column_name, old_value, new_value, employee_no, log_datetime) 
                                    values (?, 'rgs', 'rg_iv_address', ?, ?, ?, current_timestamp)");
            $sql->execute([$iv_no, $rg_iv_address_old, $rg_iv_address_new, session::get('employee_no')]);
        }

        if ($rg_iv_id_no_old != $rg_iv_id_no_new) {
            $sql = $this->prepare("insert into edit_logs (file_no, table_name, column_name, old_value, new_value, employee_no, log_datetime) 
                                    values (?, 'rgs', 'rg_iv_id_no', ?, ?, ?, current_timestamp)");
            $sql->execute([$iv_no, $rg_iv_id_no_old, $rg_iv_id_no_new, session::get('employee_no')]);
        }
        
        $sql = $this->prepare("update rgs join ivs on ivs.rg_no = rgs.rg_no set rgs.rg_iv_name = ?, rgs.rg_iv_address = ?, rgs.rg_iv_id_no = ? where ivs.iv_no = ?");
        $sql->execute([$rg_iv_name_new, $rg_iv_address_new, $rg_iv_id_no_new, $iv_no]);

        return $iv_no;

    }

    public function add_wc() {

        $iv_no = input::post('iv_no');

        $sql = $this->prepare("insert into edit_logs (file_no, table_name, column_name, old_value, new_value, employee_no, log_datetime) 
                                select ?, 'ivs', 'iv_status', ivs.iv_status, '1', ?, current_timestamp from ivs where ivs.iv_no = ?");
        $sql->execute([$iv_no, session::get('employee_no'), $iv_no]);

        $sql = $this->prepare("update ivs set iv_status = 1 where iv_no = ?");
        $sql->execute([$iv_no]);

        return $iv_no;

    }
    
    public function confirm_paid_dv() {

        $dv_no = input::post('dv_no');

        $sql = $this->prepare("insert into edit_logs (file_no, table_name, column_name, old_value, new_value, employee_no, log_datetime) 
                                select ?, 'dvs', 'dv_status', dvs.dv_status, '1', ?, current_timestamp from dvs where dvs.dv_no = ?");
        $sql->execute([$dv_no, session::get('employee_no'), $dv_no]);

        $sql = $this->prepare("update dvs set dv_status = 1 where dv_no = ?");
        $sql->execute([$dv_no]);

        $total_amount = (double) input::post('total_amount');
        $vat = (double) input::post('dv_proof_vat');

        $project_no = substr($dv_no, 0, 1);
        $dv_proof_date = input::post('dv_proof_date');
        
        // Dr. ค่าใช้จ่าย
        $sql = $this->prepare("insert into journals (file_no, sequence, account_no, project_no, date, debit, credit) values (?, ?, ?, ?, ?, ?, ?)");
        $sql->execute([$dv_no, 1, input::post('account_no'), $project_no, $dv_proof_date, $total_amount - $vat, 0]);

        //Dr. ภาษีซื้อ
        if($vat != 0) {
            $sql = $this->prepare("insert into journals (file_no, sequence, account_no, project_no, date, debit, credit) values (?, ?, ?, ?, ?, ?, ?)");
            $sql->execute([$dv_no, 2, '6-01', $project_no, $dv_proof_date, $vat, 0]);
        }

        //Cr. เงินรองจ่าย
        $sql = $this->prepare("insert into journals (file_no, sequence, account_no, project_no, date, debit, credit) values (?, ?, ?, ?, ?, ?, ?)");
        $sql->execute([$dv_no, 3, '1-03', '0', $dv_proof_date, 0, $total_amount]);

    }

    public function add_pv_type_1() {

        $sql = $this->prepare("select 
                                    substring(dvs.dv_no, 1, 1) as project_no, 
                                    sum(dv_items.dv_item_amount) as total_amount 
                                from dvs
                                join dv_items on dv_items.dv_no = dvs.dv_no
                                where dvs.dv_type = '1' and dvs.pv_no is null
                                group by substring(dvs.dv_no, 1, 1)");
        $sql->execute();
        $dvs = $sql->fetchAll();

        foreach($dvs as $value) {
                
            $pv_no = $this->assign_document_no($value['project_no'].'PV-'.substr(date("Y") + 543, 2), 'pv_no', 'pvs');

            $sql = $this->prepare('insert into pvs (pv_no, pv_datetime, pv_employee_no, pv_type, pv_total_amount, pv_slip_no, pv_status)
                                    values (?, current_timestamp, ?, 1, ?, null, 0)');
            $sql->execute([$pv_no, session::get('employee_no'), (double) $value['total_amount']]);

            $sql = $this->prepare("update dvs set dvs.pv_no = ?, dvs.dv_status = 0 where dvs.dv_no like '".$value['project_no']."DA%' and dvs.pv_no is null");
            $sql->execute([$pv_no]);

        }

    }

    public function add_pv_type_2() {
        
        $dv_no = input::post('dv_no');

        $pv_no = $this->assign_document_no(substr($dv_no, 0, 1).'PV-'.substr(date("Y") + 543, 2), 'pv_no', 'pvs');

        $sql = $this->prepare("insert into edit_logs (file_no, table_name, column_name, old_value, new_value, employee_no, log_datetime) 
                                select ?, 'dvs', 'dv_status', dvs.dv_status, '0', ?, current_timestamp from dvs where dvs.dv_no = ?");
        $sql->execute([$dv_no, session::get('employee_no'), $dv_no]);
        
        $sql = $this->prepare('insert into pvs (pv_no, pv_datetime, pv_employee_no, pv_type, pv_total_amount, pv_slip_no, pv_status)
                                values (?, current_timestamp, ?, 2, ?, null, 0)');
        $sql->execute([$pv_no, session::get('employee_no'), (double) input::post('total_amount')]);

        $sql = $this->prepare('update dvs set dvs.pv_no = ?, dvs.dv_status = 0 where dvs.dv_no = ?');
        $sql->execute([$pv_no, $dv_no]);

    }

    public function add_pv_type_3() {

        $project_no = input::post('project_no');
        $input_tax = (double) input::post('input_tax');
        $sales_tax = (double) input::post('sales_tax');

        $pv_no = $this->assign_document_no($project_no.'PV-'.substr(date("Y") + 543, 2), 'pv_no', 'pvs');

        $sql = $this->prepare("insert into pv3s (pv_no, input_tax, sales_tax)
                                values (?, ?, ?)");
        $sql->execute([$pv_no, $input_tax, $sales_tax]);

        $sql = $this->prepare('insert into pvs (pv_no, pv_datetime, pv_employee_no, pv_type, pv_total_amount, pv_slip_no, pv_status)
                                values (?, current_timestamp, ?, 3, ?, null, 0)');
        $sql->execute([$pv_no, session::get('employee_no'), $input_tax + $sales_tax]);

    }

    public function add_pv_type_4() {

        $iv_no = input::post('iv_no');

        $sql = $this->prepare("insert into edit_logs (file_no, table_name, column_name, old_value, new_value, employee_no, log_datetime) 
                                select ivs.iv_no, 'ivs', 'iv_status', ivs.iv_status, '0', ?, current_timestamp from ivs where ivs.iv_no = ?");
        $sql->execute([session::get('employee_no'), $iv_no]);

        $sql = $this->prepare("insert into edit_logs (file_no, table_name, column_name, old_value, new_value, employee_no, log_datetime) 
                                select rgs.rg_no, 'rgs', 'rg_status', rgs.rg_status, '-1', ?, current_timestamp from rgs join ivs on ivs.rg_no = rgs.rg_no where ivs.iv_no = ?");
        $sql->execute([session::get('employee_no'), $iv_no]);

        $sql = $this->prepare("update ivs join rgs on rgs.rg_no = ivs.rg_no set ivs.iv_status = 0, rgs.rg_status = -1 where ivs.iv_no = ?");
        $sql->execute([$iv_no]);

        $pv_no = $this->assign_document_no(substr($iv_no, 0, 1).'PV-'.substr(date("Y") + 543, 2), 'pv_no', 'pvs');

        $sql = $this->prepare("insert into pv4s (pv_no, iv_no, pv_payee_no, cn_no, cn_datetime, cn_detail)
                                values (?, ?, '64000', null, null, ?)");
        $sql->execute([$pv_no, $iv_no, input::post('cn_detail')]);

        $sql = $this->prepare("insert into pvs (pv_no, pv_datetime, pv_employee_no, pv_type, pv_total_amount, pv_slip_no, pv_status)
                                select ?, current_timestamp, ?, 4, journals.debit, null, 0 from journals where journals.file_no = ? and journals.account_no = '1-01'");
        $sql->execute([$pv_no, session::get('employee_no'), $iv_no]);

    }

    public function confirm_paid_pv() {

        $pv_no = input::post('pv_no');

        $sql = $this->prepare("insert into edit_logs (file_no, table_name, column_name, old_value, new_value, employee_no, log_datetime) 
                                select ?, 'pvs', 'pv_status', pvs.pv_status, '1', ?, current_timestamp from pvs where pvs.pv_no = ?");
        $sql->execute([$pv_no, session::get('employee_no'), $pv_no]);

        $sql = $this->prepare("update pvs set pv_status = 1 where pv_no = ?");
        $sql->execute([$pv_no]);

        $pv_type = input::post('pv_type');

        if ($pv_type == '1') {
            $this->confirm_paid_pv_type_1($pv_no);
        } else if ($pv_type == '2') {
            $this->confirm_paid_pv_type_2($pv_no);
        } else if ($pv_type == '3') {
            $this->confirm_paid_pv_type_3($pv_no);
        } else if ($pv_type == '4') {
            $this->confirm_paid_pv_type_4($pv_no);
        }

    }

    private function confirm_paid_pv_type_1($pv_no) {

        $total_amount = input::post('total_amount');
        
        // Dr. เงินรองจ่าย
        $sql = $this->prepare("insert into journals (file_no, sequence, account_no, project_no, date, debit, credit) values (?, ?, ?, ?, current_timestamp, ?, ?)");
        $sql->execute([$pv_no, 1, '1-03', '0', $total_amount, 0]);

        // Cr. เงินฝากออมทรัพย์ โครงการ X
        $sql = $this->prepare("insert into journals (file_no, sequence, account_no, project_no, date, debit, credit) values (?, ?, ?, ?, current_timestamp, ?, ?)");
        $sql->execute([$pv_no, 2, '1-02', substr($pv_no, 0, 1), 0, $total_amount]);

    }

    private function confirm_paid_pv_type_2($pv_no) {

        $total_amount = (double) input::post('total_amount');
        $vat = (double) input::post('dv_proof_vat');

        $project_no = substr($pv_no, 0, 1);
        $dv_proof_date = input::post('dv_proof_date');
        
        // Dr. ค่าใช้จ่าย
        $sql = $this->prepare("insert into journals (file_no, sequence, account_no, project_no, date, debit, credit) values (?, ?, ?, ?, ?, ?, ?)");
        $sql->execute([$pv_no, 1, input::post('account_no'), $project_no, $dv_proof_date, $total_amount - $vat, 0]);

        // Dr. ภาษีซื้อ
        if($vat != 0) {
            $sql = $this->prepare("insert into journals (file_no, sequence, account_no, project_no, date, debit, credit) values (?, ?, ?, ?, ?, ?, ?)");
            $sql->execute([$pv_no, 2, '6-01', $project_no, $dv_proof_date, $vat, 0]);
        }

        // Cr. เงินฝากออมทรัพย์ โครงการ X
        $sql = $this->prepare("insert into journals (file_no, sequence, account_no, project_no, date, debit, credit) values (?, ?, ?, ?, ?, ?, ?)");
        $sql->execute([$pv_no, 3, '1-02', $project_no, $dv_proof_date, 0, $total_amount]);

    }

    private function confirm_paid_pv_type_3($pv_no) {

        $total_amount = (double) input::post('total_amount');

        $project_no = substr($pv_no, 0, 1);
        
        // Dr. ภาษีขาย - โครงการพิเศษ X
        $sql = $this->prepare("insert into journals (file_no, sequence, account_no, project_no, date, debit, credit) 
                                select ?, ?, ?, ?, current_timestamp, pv3s.sales_tax, 0 from pv3s where pv3s = ?");
        $sql->execute([$pv_no, 1, '6-01', $project_no, $pv_no]);

        // Cr. ภาษีซื้อ - โครงการพิเศษ X
        $sql = $this->prepare("insert into journals (file_no, sequence, account_no, project_no, date, debit, credit) 
                select ?, ?, ?, ?, current_timestamp, 0, pv3s.input_tax from pv3s where pv3s = ?");
        $sql->execute([$pv_no, 1, '6-02', $project_no, $pv_no]);

        // Cr. ภาษีมูลค่าเพิ่มค้างจ่าย - โครงการพิเศษ X
        $sql = $this->prepare("insert into journals (file_no, sequence, account_no, project_no, date, debit, credit) values (?, ?, ?, ?, current_timestamp, ?, ?)");
        $sql->execute([$pv_no, 3, '2-03', $project_no, 0, $total_amount]);

    }

    private function confirm_paid_pv_type_4($pv_no) {

        $cn_no = $this->assign_document_no(substr($pv_no, 0, 1).'CN-'.substr(date("Y") + 543, 2), 'cn_no', 'pv4s');

        $sql = $this->prepare("update pv4s set pv4s.cn_no = ? where pv4s.pv_no = ?");
        $sql->execute([$cn_no, $pv_no]);

        $sql = $this->prepare("insert into edit_logs (file_no, table_name, column_name, old_value, new_value, employee_no, log_datetime) 
                                values (?, 'pv4s', 'cn_no', '', ?, ?, current_timestamp)");
        $sql->execute([$cn_no, $cn_no, session::get('employee_no')]);
        
        // Dr. รายได้ค่าบริการ
        // Dr. ภาษีขาย
        // Cr. เงินฝากออมทรัพย์ โครงการพิเศษ X
        // Cr. ภาษีหัก ณ ที่จ่าย
        $sql = $this->prepare("insert into journals (file_no, sequence, account_no, project_no, date, debit, credit)
                                select 
                                    pv4s.cn_no, 
                                    if(journals.sequence > 2, journals.sequence - 2, journals.sequence + 2), 
                                    journals.account_no, 
                                    ?, 
                                    current_timestamp, 
                                    journals.credit, 
                                    journals.debit
                                from journals 
                                join pv4s on pv4s.iv_no = journals.file_no 
                                where pv4s.pv_no = ?");
        $sql->execute([substr($pv_no, 0, 1), $pv_no]);

    }

    public function cancel_pv() {

        $pv_no = input::post('pv_no');

        $sql = $this->prepare("insert into edit_logs (file_no, table_name, column_name, old_value, new_value, employee_no, log_datetime) 
                                select ?, 'pvs', 'pv_status', pvs.pv_status, '-1', ?, current_timestamp from pvs where pvs.pv_no = ?");
        $sql->execute([$pv_no, session::get('employee_no'), $pv_no]);

        $sql = $this->prepare("update pvs set pv_status = -1 where pv_no = ?");
        $sql->execute([$pv_no]);

    }

}