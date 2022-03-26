<?php

namespace model;

use _core\model;
use _core\helper\session;
use _core\helper\input;
use _core\helper\uri;
use _core\helper\thaiNum;
use PDO;

class isModel extends model {
	
	// Edit PO Module
    public function getPo() {
        $sql = $this->prepare("select * from PO where po_no = ?");
        $sql->execute([input::postAngular('po_no')]);
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return json_encode([]);
    }
    
    // Edit PO Module
    public function editPo() {
        
        // insert EditPOHistory
        $sql = $this->prepare("insert into EditPOHistory (employee_id, po_no, date, time, purchase_no_vat, purchase_vat, purchase_price)
                                values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?)");
        $sql->execute([
            json_decode(session::get('employee_detail'), true)['employee_id'],
            input::post('po_no'),
            (double) input::post('p_no_vat'),
            (double) input::post('p_vat'),
            (double) input::post('p_price')
        ]);
        
        if(input::post('received') == 0) {
            
            $sql = $this->prepare("update PO set total_purchase_no_vat = ?, total_purchase_vat = ?, total_purchase_price = ? where po_no = ?");
            $sql->execute([
                (double) input::post('p_no_vat_edited'),
                (double) input::post('p_vat_edited'),
                (double) input::post('p_price_edited'),
                input::post('po_no')
            ]);
            
        } else if(input::post('received') == 1 && input::post('product_type') == 'Install') {
            
            // update CI & PO
            $sql = $this->prepare("update PO inner join CI on CI.po_no = PO.po_no 
                                    set PO.total_purchase_no_vat = ?, PO.total_purchase_vat = ?, PO.total_purchase_price = ?, CI.confirm_subtotal = ?, CI.confirm_vat = ?, CI.confirm_total = ? 
                                    where PO.po_no = ?");
            $sql->execute([
                (double) input::post('p_no_vat_edited'),
                (double) input::post('p_vat_edited'),
                (double) input::post('p_price_edited'),
                (double) input::post('p_no_vat_edited'),
                (double) input::post('p_vat_edited'),
                (double) input::post('p_price_edited'),
                input::post('po_no')
            ]);
            
            // update Dr ซื้อ
            $sql = $this->prepare("update AccountDetail
                                    join CI on CI.ci_no = AccountDetail.file_no
                                    join PO on PO.po_no = CI.po_no
                                    set AccountDetail.debit = ?
                                    where AccountDetail.sequence = '5' and PO.po_no = ?");
            $sql->execute([(double) input::post('p_no_vat_edited'), input::post('po_no')]);
            
            // update Cr เจ้าหนี้การค้ารอ Tax Invoice - Supplier XXX
            $sql = $this->prepare("update AccountDetail
                                    join CI on CI.ci_no = AccountDetail.file_no
                                    join PO on PO.po_no = CI.po_no
                                    set AccountDetail.credit = ?
                                    where AccountDetail.sequence = '6' and PO.po_no = ?");
            $sql->execute([(double) input::post('p_no_vat_edited'), input::post('po_no')]);
            
        } else if(input::post('received') == 1 && input::post('product_type') != 'Install') {
            
            // update RR & PO
            $sql = $this->prepare("update PO inner join RR on RR.po_no = PO.po_no 
                                    set PO.total_purchase_no_vat = ?, PO.total_purchase_vat = ?, PO.total_purchase_price = ?, RR.total_purchase_no_vat = ?, RR.total_purchase_vat = ?, RR.total_purchase_price = ? 
                                    where PO.po_no = ?");
            $sql->execute([
                (double) input::post('p_no_vat_edited'),
                (double) input::post('p_vat_edited'),
                (double) input::post('p_price_edited'),
                (double) input::post('p_no_vat_edited'),
                (double) input::post('p_vat_edited'),
                (double) input::post('p_price_edited'),
                input::post('po_no')
            ]);
            
            // update Dr ซื้อ
            $sql = $this->prepare("update AccountDetail
                                    join RR on RR.rr_no = AccountDetail.file_no
                                    join PO on PO.po_no = RR.po_no
                                    set AccountDetail.debit = ?
                                    where AccountDetail.sequence = '1' and PO.po_no = ?");
            $sql->execute([(double) input::post('p_no_vat_edited'), input::post('po_no')]);
            
            // update Cr เจ้าหนี้การค้ารอ Tax Invoice - Supplier XXX
            $sql = $this->prepare("update AccountDetail
                                    join RR on RR.rr_no = AccountDetail.file_no
                                    join PO on PO.po_no = RR.po_no
                                    set AccountDetail.credit = ?
                                    where AccountDetail.sequence = '2' and PO.po_no = ?");
            $sql->execute([(double) input::post('p_no_vat_edited'), input::post('po_no')]);
            
        }
        
        echo input::post('po_no');
        
    }
    
    public function getInvoice() {
        $sql = $this->prepare("select * from Invoice");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return json_encode([]);
    }
    
	public function endproject() {
        $sql = $this->prepare( "insert into hee (employee_id, reason, problem, solution,what, found)
                                values (?, ?,?,?,?,?)" );
		$sql->execute( [
		  json_decode( session::get( 'employee_detail' ), true )[ 'employee_id' ],
		  input::post( 'reason' ),
		  input::post( 'problem' ),
		  input::post( 'solution' ),
			input::post( 'what' ),
			input::post( 'found' )
		] );
        return json_encode([]);
    }
	
    public function getScoreBoard() {
        $sql = $this->prepare("select *, CURRENT_TIMESTAMP from Teams_2, (select sum(Teams_2.team_score) as score_s from Teams_2 where team_group = 1) s, (select sum(Teams_2.team_score) as score_z from Teams_2 where team_group = 2) z order by team_group desc, team_score desc, team_id");
        $sql->execute();
        return $sql->fetchAll();
    }
    
	public function getTier1() {
        $sql = $this->prepare(" select *, CURRENT_TIMESTAMP from Teams where team_tier ='1' order by team_score DESC");
        $sql->execute();
        return $sql->fetchAll();
    }
    
	public function getTier2() {
        $sql = $this->prepare(" select *, CURRENT_TIMESTAMP from Teams where team_tier ='2' order by team_score DESC");
        $sql->execute();
        return $sql->fetchAll();
    }
    
	public function getTier3() {
        $sql = $this->prepare("select *, CURRENT_TIMESTAMP from Teams where team_tier ='3' order by team_score DESC");
        $sql->execute();
        return $sql->fetchAll();
    }
    
	public function getTier4() {
        $sql = $this->prepare(" select *, CURRENT_TIMESTAMP from Teams where team_tier ='4' order by team_score DESC");
        $sql->execute();
        return $sql->fetchAll();
    }
    
    public function getInfiniteConqueror() {
        $sql = $this->prepare("select
                                	concat(infiniteConqueror.employee_id, ' ', Employee.employee_nickname_thai) as employee_name,
                                    View_SPRank.point,
                                    ifnull(sales.sum, 0) as sales
                                from infiniteConqueror 
                                left join (select SO.employee_id, sum(SO.total_sales_price - SO.discountso) as sum from SO where SO.cancelled = 0 and SO.so_date > '2020-08-02' group by SO.employee_id) sales on sales.employee_id = infiniteConqueror.employee_id
                                left join View_SPRank on View_SPRank.employee_id = infiniteConqueror.employee_id
                                join Employee on Employee.employee_id = infiniteConqueror.employee_id
                                order by ifnull(sales.sum, 0) desc");
        $sql->execute();
        return $sql->fetchAll();                 
    }

    
    public function newTarget(){
        $sql = $this->prepare("select line, target  from Target");
        $sql->execute();
        //if ($sql->rowCount() > 0) {
          //  return $sql->fetchAll();
        //}
		//return [];
        return $sql->fetchAll();
        
    }
    
    public function getSalesGM_SMD(){
        $sql = $this->prepare("SELECT sum(SOPrinting.sales_no_vat*SOPrinting.quantity) as sales FROM SOPrinting 
                                INNER JOIN SO ON SO.so_no = SOPrinting.so_no
                                LEFT JOIN Product on Product.product_no = SOPrinting.product_no 
                                left join ProductCategory on ProductCategory.category_no = Product.category_no and ProductCategory.product_line = Product.product_line 
                                INNER JOIN SOXPrinting on SOXPrinting.so_no = SO.so_no
                                JOIN SOX ON SOX.sox_no = SOXPrinting.sox_no
                                WHERE SOX.cancelled = 0 AND SO.employee_id in ('GMSMD','GM001') AND
                                ((SOX.slip_uploaded = '1' AND SO.so_date < '2020-08-01') OR (SO.so_date >= '2020-08-01'))");
        $sql->execute();        
        return $sql->fetchAll()[0]['sales'];
    }
    
    public function yesterdaySalesGM_SMD(){
        $sql = $this->prepare("SELECT sum(SOPrinting.sales_no_vat*SOPrinting.quantity) as sales FROM SOPrinting 
                                INNER JOIN SO ON SO.so_no = SOPrinting.so_no
                                LEFT JOIN Product on Product.product_no = SOPrinting.product_no 
                                left join ProductCategory on ProductCategory.category_no = Product.category_no and ProductCategory.product_line = Product.product_line 
                                INNER JOIN SOXPrinting on SOXPrinting.so_no = SO.so_no
                                JOIN SOX ON SOX.sox_no = SOXPrinting.sox_no
                                WHERE SOX.cancelled = 0 AND SO.so_date BETWEEN '2020-06-16' AND CURRENT_DATE - 1 AND SO.employee_id in ('GMSMD','GM001') AND
                                ((SOX.slip_uploaded = '1' AND SO.so_date < '2020-08-01') OR (SO.so_date >= '2020-08-01'))");
        $sql->execute();
        return $sql->fetchAll()[0]['sales'];
    }
    
    public function getSalesCBA(){
        $sql = $this->prepare("SELECT sum(SOPrinting.sales_no_vat*SOPrinting.quantity) as sales FROM SOPrinting 
                                INNER JOIN SO ON SO.so_no = SOPrinting.so_no
                                LEFT JOIN Product on Product.product_no = SOPrinting.product_no 
                                left join ProductCategory on ProductCategory.category_no = Product.category_no and ProductCategory.product_line = Product.product_line 
                                INNER JOIN SOXPrinting on SOXPrinting.so_no = SO.so_no
                                JOIN SOX ON SOX.sox_no = SOXPrinting.sox_no
                                WHERE SOX.cancelled = 0 AND ((SOX.slip_uploaded = '1' AND SO.so_date < '2020-08-01') OR (SO.so_date >= '2020-08-01'))");
        $sql->execute();
        
        return $sql->fetchAll()[0]['sales'];
        
    }
    
    public function getSales(){
        $sql = $this->prepare("select
 substring(SOPrinting.product_no, 1, 1) as line,
    sum(SOPrinting.sales_no_vat * SOPrinting.quantity) as sales
from SOPrinting
join SO on SO.so_no = SOPrinting.so_no
left join SOX on SOX.sox_no = SO.so_no
left join SOXPrinting on SOXPrinting.sox_no = SOX.sox_no
where SO.cancelled = 0 and ((SOX.slip_uploaded = '1' and SO.so_date < '2020-08-01') or SO.so_date >= '2020-08-01' or SOX.sox_no is null)
group by substring(SOPrinting.product_no, 1, 1)
order by substring(SOPrinting.product_no, 1, 1)");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            // return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
			return $sql->fetchAll();
        }
        // return json_encode([]);
		return [];
        //return $sql->fetchAll();
        //return [];
    }
    
    public function yesterdaySales(){
        $sql = $this->prepare("select
 substring(SOPrinting.product_no, 1, 1) as line,
    sum(SOPrinting.sales_no_vat * SOPrinting.quantity) as sales
from SOPrinting
join SO on SO.so_no = SOPrinting.so_no
left join SOX on SOX.sox_no = SO.so_no
left join SOXPrinting on SOXPrinting.sox_no = SOX.sox_no
where SO.cancelled = 0 and ((SOX.slip_uploaded = '1' and SO.so_date < '2020-08-01') or SO.so_date >= '2020-08-01' or SOX.sox_no is null)
AND SO.so_date BETWEEN '2020-06-16' AND CURRENT_DATE - 1
group by substring(SOPrinting.product_no, 1, 1)
order by substring(SOPrinting.product_no, 1, 1)");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            // return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
			return $sql->fetchAll();
        }
        // return json_encode([]);
		return [];
        //return $sql->fetchAll();
    }
    
    public function yesterdaySalesCBA(){
        $sql = $this->prepare("SELECT sum(SOPrinting.sales_no_vat*SOPrinting.quantity) as sales FROM SOPrinting 
                                INNER JOIN SO ON SO.so_no = SOPrinting.so_no
                                LEFT JOIN Product on Product.product_no = SOPrinting.product_no 
                                left join ProductCategory on ProductCategory.category_no = Product.category_no and ProductCategory.product_line = Product.product_line 
                                INNER JOIN SOXPrinting on SOXPrinting.so_no = SO.so_no
                                JOIN SOX ON SOX.sox_no = SOXPrinting.sox_no
                                WHERE SOX.cancelled = 0 AND ((SOX.slip_uploaded = '1' AND SO.so_date < '2020-08-01') OR (SO.so_date >= '2020-08-01')) AND SO.so_date BETWEEN '2020-06-16' AND CURRENT_DATE - 1");
        //  AND SOX.slip_uploaded='1'
        $sql->execute();
        return $sql->fetchAll()[0]['sales'];
    }
    
    public function csSales(){
        $sql = $this->prepare("SELECT substring(InvoicePrinting.product_no,1,1) as line, sum(InvoicePrinting.sales_no_vat*InvoicePrinting.quantity) as sales FROM `InvoicePrinting` 
                                inner join Invoice on Invoice.invoice_no = InvoicePrinting.invoice_no
                                LEFT JOIN Product on Product.product_no = InvoicePrinting.product_no 
                                WHERE InvoicePrinting.invoice_no LIKE '%ic%' AND InvoicePrinting.cancelled = '0'
                                group by SUBSTRING(Product.product_no, 1, 1) 
                                ORDER BY Substring(Product.product_no,1,1) ASC");
       $sql->execute();
       if ($sql->rowCount() > 0) {
            // return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
			return $sql->fetchAll();
        }
        // return json_encode([]);
		return [];
        //return $sql->fetchAll();
    }
    
    public function csYesterdaySales(){
        $sql = $this->prepare("SELECT substring(InvoicePrinting.product_no,1,1) as line, sum(InvoicePrinting.sales_no_vat*InvoicePrinting.quantity) as sales FROM `InvoicePrinting` 
                                inner join Invoice on Invoice.invoice_no = InvoicePrinting.invoice_no
                                LEFT JOIN Product on Product.product_no = InvoicePrinting.product_no 
                                WHERE InvoicePrinting.invoice_no LIKE '%ic%' AND InvoicePrinting.cancelled = '0' AND Invoice.invoice_date BETWEEN '2020-06-16' AND CURRENT_DATE - 1
                                group by SUBSTRING(Product.product_no, 1, 1) 
                                ORDER BY Substring(Product.product_no,1,1) ASC");
       $sql->execute();
       if ($sql->rowCount() > 0) {
            // return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
			return $sql->fetchAll();
        }
        // return json_encode([]);
		return [];
        //return $sql->fetchAll();
           
    }
    
    
}