<?php

namespace model;

use _core\model;
use _core\helper\input;
use _core\helper\session;
use PDO;

class fileModel extends model {

    public function get_iv($iv_no) {

        $sql = $this->prepare("select
                                    ivs.iv_no,
                                    date_format(ivs.iv_datetime, '%d/%m/%Y') as iv_date,
                                    rgs.rg_iv_name,
                                    rgs.rg_iv_address,
                                    rgs.rg_iv_id_no,
                                    concat(courses.course_name, ' รุ่นที่ ', cast(rgs.batch_no as int)) as course_name,
                                    rgs.rg_total_price/rg_items.count as rg_item_price,
                                    rg_items.count,
                                    rgs.rg_total_price,
                                    rgs.rg_total_discount,
                                    round((rgs.rg_total_price - rgs.rg_total_discount)/107*100, 2) as iv_total_price_no_vat,
                                    round((rgs.rg_total_price - rgs.rg_total_discount)/107*7, 2) as iv_total_price_vat,
                                    case
                                        when rgs.rg_type = '1' then 0
                                        when rgs.rg_type = '2' then round((rgs.rg_total_price - rgs.rg_total_discount)/107*1.5, 2)
                                        when rgs.rg_type = '3' then round((rgs.rg_total_price - rgs.rg_total_discount)/107*1, 2)
                                    end as iv_total_price_wht,
                                    case
                                        when rgs.rg_type = '1' then (rgs.rg_total_price - rgs.rg_total_discount)
                                        when rgs.rg_type = '2' then round((rgs.rg_total_price - rgs.rg_total_discount)/107*105.5, 2)
                                        when rgs.rg_type = '3' then round((rgs.rg_total_price - rgs.rg_total_discount)/107*106, 2)
                                    end as iv_total_price,
                                    rgs.rg_type
                                from ivs
                                join rgs on rgs.rg_no = ivs.rg_no
                                join (select rg_items.rg_no, count(*) as count from rg_items group by rg_items.rg_no) as rg_items on rg_items.rg_no = rgs.rg_no
                                join courses on courses.course_no = rgs.course_no
                                where ivs.iv_no = ?");
        $sql->execute([$iv_no]);

        return  json_encode($sql->rowCount() > 0 ? $sql->fetchAll(PDO::FETCH_ASSOC) : [], JSON_UNESCAPED_UNICODE);

    }

    public function get_cn($cn_no) {

        $sql = $this->prepare("select
                                    pv4s.cn_no,
                                    date_format(pv4s.cn_datetime, '%d/%m/%Y') as cn_date,
                                    rgs.rg_iv_name,
                                    rgs.rg_iv_address,
                                    rgs.rg_iv_id_no,
                                    concat(courses.course_name, ' รุ่นที่ ', cast(rgs.batch_no as int)) as course_name,
                                    rgs.rg_total_price/rg_items.count as rg_item_price,
                                    rg_items.count,
                                    rgs.rg_total_price,
                                    rgs.rg_total_discount,
                                    round((rgs.rg_total_price - rgs.rg_total_discount)/107*100, 2) as iv_total_price_no_vat,
                                    round((rgs.rg_total_price - rgs.rg_total_discount)/107*7, 2) as iv_total_price_vat,
                                    case
                                        when rgs.rg_type = '1' then 0
                                        when rgs.rg_type = '2' then round((rgs.rg_total_price - rgs.rg_total_discount)/107*1.5, 2)
                                        when rgs.rg_type = '3' then round((rgs.rg_total_price - rgs.rg_total_discount)/107*1, 2)
                                    end as iv_total_price_wht,
                                    case
                                        when rgs.rg_type = '1' then (rgs.rg_total_price - rgs.rg_total_discount)
                                        when rgs.rg_type = '2' then round((rgs.rg_total_price - rgs.rg_total_discount)/107*105.5, 2)
                                        when rgs.rg_type = '3' then round((rgs.rg_total_price - rgs.rg_total_discount)/107*106, 2)
                                    end as iv_total_price,
                                    rgs.rg_type,
                                    pv4s.iv_no,
                                    date_format(ivs.iv_datetime, '%d/%m/%Y') as iv_date,
                                    pv4s.cn_detail
                                from pv4s
                                join ivs on ivs.iv_no = pv4s.iv_no
                                join rgs on rgs.rg_no = ivs.rg_no
                                join (select rg_items.rg_no, count(*) as count from rg_items group by rg_items.rg_no) as rg_items on rg_items.rg_no = rgs.rg_no
                                join courses on courses.course_no = rgs.course_no
                                where pv4s.cn_no = ?");
        $sql->execute([$cn_no]);

        return  json_encode($sql->rowCount() > 0 ? $sql->fetchAll(PDO::FETCH_ASSOC) : [], JSON_UNESCAPED_UNICODE);

    }

    public function get_rv($dv_no) {

        $sql = $this->prepare("select
                                    dvs.dv_no,
                                    payees.payee_name,
                                    payees.payee_address,
                                    payees.payee_id_no,
                                    accounts.account_name,
                                    dv_items.total_amount,
                                    employees.employee_name
                                from dvs
                                join accounts on accounts.account_no = dvs.dv_account_no
                                join (select dv_items.dv_no, sum(dv_items.dv_item_amount) as total_amount from dv_items group by dv_items.dv_no) dv_items on dv_items.dv_no = dvs.dv_no
                                join employees on employees.employee_no = dvs.dv_employee_no
                                join payees on payees.payee_no = dvs.dv_payee_no
                                where dvs.dv_no = ?");
        $sql->execute([$dv_no]);

        return  json_encode($sql->rowCount() > 0 ? $sql->fetchAll(PDO::FETCH_ASSOC) : [], JSON_UNESCAPED_UNICODE);

    }

    public function get_pv($pv_no) {

        $sql = $this->prepare("select
                                    pvs.pv_no,
                                    date_format(pvs.pv_datetime, '%d/%m/%Y') as pv_date,
                                    case
                                        when pvs.pv_type = 1 then 'บัญชีเงินรองจ่าย'
                                        when pvs.pv_type = 2 then payee_2.payee_name
                                        when pvs.pv_type = 3 then 'กรมสรรพากร'
                                        when pvs.pv_type = 4 then payee_4.payee_name
                                    end as payee_name,
                                    case
                                        when pvs.pv_type = 1 then '-'
                                        when pvs.pv_type = 2 then payee_2.payee_address
                                        when pvs.pv_type = 3 then '-'
                                        when pvs.pv_type = 4 then payee_4.payee_address
                                    end as payee_address,
                                    case
                                        when pvs.pv_type = 1 then '-'
                                        when pvs.pv_type = 2 then payee_2.payee_id_no
                                        when pvs.pv_type = 3 then '-'
                                        when pvs.pv_type = 4 then payee_4.payee_id_no
                                    end as payee_id_no,
                                    case
                                        when pvs.pv_type = 1 then '-'
                                        when pvs.pv_type = 2 then payee_2.payee_bank
                                        when pvs.pv_type = 3 then '-'
                                        when pvs.pv_type = 4 then payee_4.payee_bank
                                    end as payee_bank,
                                    case
                                        when pvs.pv_type = 1 then '-'
                                        when pvs.pv_type = 2 then payee_2.payee_bank_no
                                        when pvs.pv_type = 3 then '-'
                                        when pvs.pv_type = 4 then payee_4.payee_bank_no
                                    end as payee_bank_no,
                                    case
                                        when pvs.pv_type = 1 then 'เติมเงินรองจ่าย'
                                        when pvs.pv_type = 2 then accounts.account_name
                                        when pvs.pv_type = 3 then 'ชำระภาษีมูลค่าเพิ่ม'
                                        when pvs.pv_type = 4 then concat('ลดหนี้ใบกำกับภาษีเลขที่ ', pv4s.iv_no)
                                    end as detail,
                                    pvs.pv_total_amount
                                from pvs
                                left join dvs on dvs.pv_no = pvs.pv_no
                                left join accounts on accounts.account_no = dvs.dv_account_no
                                left join pv4s on pv4s.pv_no = pvs.pv_no
                                left join payees payee_2 on payee_2.payee_no = dvs.dv_payee_no
                                left join payees payee_4 on payee_4.payee_no = pv4s.pv_payee_no
                                where pvs.pv_no = ?");
        $sql->execute([$pv_no]);

        return  json_encode($sql->rowCount() > 0 ? $sql->fetchAll(PDO::FETCH_ASSOC) : [], JSON_UNESCAPED_UNICODE);

    }

    public function get_slip($slip_no) {

        $sql = $this->prepare("select slip_type, slip_data from slips where slip_no = ?");
        $sql->execute([$slip_no]);

        if ($sql->rowCount() > 0) {
            $data = $sql->fetchAll()[0];
    		header('Content-type: '.$data['slip_type']);
            echo base64_decode($data['slip_data']);
        } else {
            echo 'ไม่มีข้อมูล';
        }

    }

}