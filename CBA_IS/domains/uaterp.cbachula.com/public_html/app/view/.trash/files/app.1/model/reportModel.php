<?php

namespace model;

use _core\model;
use _core\helper\input;
use _core\helper\session;
use PDO;

class reportModel extends model {

    public function get_income_statement() {
        
        $sql = $this->prepare("select
                                    date_format(journals.date, '%Y%m'),
                                    journals.account_no,
                                    accounts.account_name,
                                    journals.project_no,
                                    sum(journals.debit) as debit,
                                    sum(journals.credit) as credit
                                from journals 
                                join accounts on accounts.account_no = journals.account_no
                                where journals.account_no like '4%' or journals.account_no like '5%'
                                group by date_format(journals.date, '%Y%m'), journals.account_no, journals.project_no");
        $sql->execute();

        return  json_encode($sql->rowCount() > 0 ? $sql->fetchAll(PDO::FETCH_ASSOC) : [], JSON_UNESCAPED_UNICODE);

    }

    public function get_vats() {

        $sql = $this->prepare("select 
                                    date_format(journals.date, '%c_%Y') as month, 
                                    journals.account_no, 
                                    accounts.account_name, 
                                    journals.project_no, 
                                    sum(journals.debit) as debit, 
                                    sum(journals.credit) as credit
                                from journals
                                join accounts on accounts.account_no = journals.account_no
                                where journals.account_no in ('6-01', '6-02', '2-03')
                                group by 
                                    date_format(journals.date, '%c_%Y'), 
                                    journals.account_no, 
                                    accounts.account_name, 
                                    journals.project_no");
        $sql->execute();

        return  json_encode($sql->rowCount() > 0 ? $sql->fetchAll(PDO::FETCH_ASSOC) : [], JSON_UNESCAPED_UNICODE);

    }

    public function get_revenues() {

        $sql = $this->prepare("select 
                                    rgs.course_no, 
                                    rgs.batch_no, 
                                    sum(rg_items.count) as count,
                                    sum(rgs.rg_total_price) as rg_total_price,
                                    sum(rgs.rg_total_discount) as rg_total_discount,
                                    sum(rgs.rg_total_price - rgs.rg_total_discount) as total_amount 
                                from rgs
                                join (select rg_items.rg_no, count(*) as count from rg_items group by rg_items.rg_no) rg_items on rg_items.rg_no = rgs.rg_no
                                where rgs.rg_status <> -1
                                group by rgs.course_no, rgs.batch_no");
        $sql->execute();

        return  json_encode($sql->rowCount() > 0 ? $sql->fetchAll(PDO::FETCH_ASSOC) : [], JSON_UNESCAPED_UNICODE);

    }

    public function get_expenses() {

        $sql = $this->prepare("select 
                                    dv_items.course_no, 
                                    dv_items.batch_no, 
                                    dvs.dv_account_no,
                                    sum(dv_items.dv_item_amount) as total_amount 
                                from dv_items
                                join dvs on dvs.dv_no = dv_items.dv_no
                                join accounts on accounts.account_no = dvs.dv_account_no
                                where dvs.dv_status <> -1
                                group by dv_items.course_no, dv_items.batch_no, dvs.dv_account_no");
        $sql->execute();

        return  json_encode($sql->rowCount() > 0 ? $sql->fetchAll(PDO::FETCH_ASSOC) : [], JSON_UNESCAPED_UNICODE);

    }

}