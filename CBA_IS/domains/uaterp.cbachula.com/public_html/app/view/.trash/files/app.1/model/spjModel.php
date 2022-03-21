<?php

namespace model;

use _core\model;
use _core\helper\input;
use _core\helper\session;
use PDO;

class spjModel extends model {

    public function get_rgs() {

        $sql = $this->prepare("select 
                                    rgs.rg_no,
                                    rgs.rg_datetime, 
                                    concat(customers.customer_first_name, ' ', customers.customer_last_name) as customer_name,
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
                                    end as rg_status
                                from rgs
                                join customers on customers.customer_no = rgs.customer_no
                                join courses on courses.course_no = rgs.course_no");
        $sql->execute();

        return  json_encode($sql->rowCount() > 0 ? $sql->fetchAll(PDO::FETCH_ASSOC) : [], JSON_UNESCAPED_UNICODE);

    }

    public function confirm_rg() {

        $rg_no = input::post('rg_no');

        $sql = $this->prepare("insert into edit_logs (file_no, table_name, column_name, old_value, new_value, employee_no, log_datetime) 
                                select ?, 'rgs', 'rg_status', rgs.rg_status, '0', ?, current_timestamp from rgs where rgs.rg_no = ?");
        $sql->execute([$rg_no, session::get('employee_no'), $rg_no]);

        $sql = $this->prepare("update rgs set rg_status = 0 where rg_no = ?");
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

}