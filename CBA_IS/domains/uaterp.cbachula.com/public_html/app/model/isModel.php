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
            
            // update Dr ????????????
            $sql = $this->prepare("update AccountDetail
                                    join CI on CI.ci_no = AccountDetail.file_no
                                    join PO on PO.po_no = CI.po_no
                                    set AccountDetail.debit = ?
                                    where AccountDetail.sequence = '5' and PO.po_no = ?");
            $sql->execute([(double) input::post('p_no_vat_edited'), input::post('po_no')]);
            
            // update Cr ???????????????????????????????????????????????? Tax Invoice - Supplier XXX
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
            
            // update Dr ????????????
            $sql = $this->prepare("update AccountDetail
                                    join RR on RR.rr_no = AccountDetail.file_no
                                    join PO on PO.po_no = RR.po_no
                                    set AccountDetail.debit = ?
                                    where AccountDetail.sequence = '1' and PO.po_no = ?");
            $sql->execute([(double) input::post('p_no_vat_edited'), input::post('po_no')]);
            
            // update Cr ???????????????????????????????????????????????? Tax Invoice - Supplier XXX
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



    //split RR module
    public function getIRDRRstock(){
        $sql = $this->prepare(" SELECT
                                    View_StockIRD.product_no,
                                    View_StockIRD.stock_in,
                                    View_StockIRD.stock_out,
                                    View_StockIRD.stock_left,
                                    rr_in.*
                                FROM
                                    `View_StockIRD`
                                JOIN(
                                    SELECT
                                        product_no,
                                        SUM(RRPrinting.quantity) AS rr_q,
                                        GROUP_CONCAT(rr_no) AS rr_csv,
                                        GROUP_CONCAT(
                                            CAST(
                                                RRPrinting.quantity AS VARCHAR(10)
                                            )
                                        ) AS rr_csv_q
                                    FROM
                                        `RRPrinting`
                                    GROUP BY
                                        RRPrinting.product_no
                                ) AS rr_in
                                ON
                                    View_StockIRD.product_no = rr_in.product_no;");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
		return [];
    }

    public function splitRR() {
        $rr_split = json_decode($_POST['rr_split']);
        foreach ($rr_split as $products) {
            $new_rr = $this->assignRR($products[0]->rr_no);
            
            echo $new_rr;
            foreach ($products as $product) {
                //update quantity rr_printing
                    $sql = $this->prepare("UPDATE RRPrinting set quantity = ? where rr_no = ? AND product_no = ?");
                    $sql->execute([$product->q_out, $product->rr_no, $product->product_no]);
                //update total rr_printing
                    $sql = $this->prepare("UPDATE RRPrinting set total_purchase_price = quantity*purchase_price where rr_no = ? AND product_no = ?");
                    $sql->execute([$product->rr_no, $product->product_no]);
				//get cost from prev rr_printing
                    $sql = $this->prepare("SELECT purchase_no_vat,purchase_vat,purchase_price FROM RRPrinting where rr_no = ? AND product_no = ?");
                    $sql->execute([$product->rr_no, $product->product_no]);
                    $total = $sql->fetchAll(PDO::FETCH_ASSOC)[0];
                //insert new rr into rr_printing
                    $sql = $this->prepare("INSERT INTO RRPrinting(rr_no, so_no, product_no, purchase_no_vat, purchase_vat, purchase_price, quantity, total_purchase_price, cancelled, note) VALUES (?,'-',?,?,?,?,?,?,0,NULL)");
                    $sql->execute([$new_rr, $product->product_no,$total["purchase_no_vat"],$total["purchase_vat"],$total["purchase_price"], $product->q_remain, $total["purchase_price"] * $product->q_remain]);
				
				//update old stock in
					$sql = $this->prepare("UPDATE StockIn SET quantity_in = ? where file_no = ? AND product_no = ?");
					$sql->execute([$product->q_out,$product->rr_no, $product->product_no]);
				//insert into new stock in
					$sql = $this->prepare("insert into StockIn (product_no, file_no, file_type, date, time, quantity_in, lot) select ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, count(*) + 1 from StockIn where product_no = ?");
					$sql->execute([$product->product_no, $new_rr, 'RR', $product->q_remain, $product->product_no]); 
            }
			//update old rr
            	$sql = $this->prepare(" SELECT
            	                            SUM(quantity*purchase_price) as total_purchase_price,
            	                            SUM(quantity*purchase_vat) as total_purchase_vat,
            	                            SUM(quantity*purchase_no_vat) as total_purchase_no_vat
            	                        FROM
            	                            RRPrinting
            	                        WHERE
            	                            rr_no = ?
            	                        group by rr_no;");
            	$sql->execute([$product->rr_no]);
            	$total = $sql->fetchAll(PDO::FETCH_ASSOC)[0];
                $sql = $this->prepare("UPDATE RR set total_purchase_no_vat = ?,total_purchase_vat = ?,total_purchase_price = ? where rr_no = ?");
                $sql->execute([$total['total_purchase_no_vat'],$total['total_purchase_vat'],$total['total_purchase_price'] , $product->rr_no]);
			
			
				//insert new rr
				$sql = $this->prepare(" SELECT
											SUM(quantity*purchase_price) as total_purchase_price,
											SUM(quantity*purchase_vat) as total_purchase_vat,
											SUM(quantity*purchase_no_vat) as total_purchase_no_vat
										FROM
											RRPrinting
										WHERE
											rr_no = ?
										group by rr_no;");
				$sql->execute([$new_rr]);
				$total_new = $sql->fetchAll(PDO::FETCH_ASSOC)[0];
				$sql = $this->prepare("SELECT po_no FROM RR where rr_no = ?");
				$sql->execute([$product->rr_no]);
				$po_no = $sql->fetchAll(PDO::FETCH_ASSOC)[0]['po_no'];
                $sql = $this->prepare("INSERT into RR (rr_no, rr_date, approved_employee, supplier_no, invoice_no, total_purchase_no_vat, total_purchase_vat, total_purchase_price, cancelled, po_no) values(?, CURRENT_TIMESTAMP, ?, ?, ?, ?, ?, ?, 0, ?)");
                $sql->execute([$new_rr,"IS001",substr($product->product_no,8,3),'-',(double) $total_new['total_purchase_no_vat'],(double) $total_new['total_purchase_vat'],(double) $total_new['total_purchase_price'],$po_no]);
            
        }

    }

    private function assignRR($rr_no) {
        $rrPrefix = $rr_no[0].'RR-';
        $sql = $this->prepare("select ifnull(max(rr_no),0) as max from RR where rr_no like ?");
        $sql->execute([$rrPrefix.'%']);
        $maxRrNo = $sql->fetchAll()[0]['max'];
        $runningNo = '';
        if($maxRrNo=='0') {
            $runningNo = '00001';
        } else {
            $latestRunningNo = (int) substr($maxRrNo, 4) + 1;
            if(strlen($latestRunningNo) == 5) {
                $runningNo = $latestRunningNo;
            } else {
                for ($x = 1; $x <= 5 - strlen($latestRunningNo); $x++) {
                    $runningNo .= '0';
                }
                $runningNo .= $latestRunningNo;
            }
        }
        return $rrPrefix.$runningNo;
    }  
    
    //to fix split RR since it only consider view_stockIRD and not view_invoiceStock
    public function splitStockOut() {
        //select problemetic product
        $sql = $this->prepare(" SELECT
                                    View_InvoiceStock.*
                                FROM
                                    View_InvoiceStock
                                RIGHT JOIN(
                                    SELECT
                                        product_no
                                    FROM
                                        `View_InvoiceStock`
                                    WHERE
                                        `balance` < 0
                                ) AS product_err
                                ON
                                    product_err.product_no = View_InvoiceStock.product_no
                                ORDER BY
                                    `View_InvoiceStock`.`product_no` ASC,
                                    View_InvoiceStock.file_no ASC;");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            $stock = $sql->fetchAll(PDO::FETCH_ASSOC);
            // echo count($stock);
            // print_r($stock);
        } else return;

		$i = -1;
		$productArr = [[]];
		$prevProduct_no = "";

		//group data by product no
		foreach ($stock as $value){
			if($value["product_no"] == $prevProduct_no) {
				array_push($productArr[$i],$value);
				
			} else {
				$i++;
				$productArr[$i] = [$value];
				$prevProduct_no = $value["product_no"];
			}
		}
        // echo '<pre>';
        // print_r($productArr);
        // echo '</pre>';
		echo '<pre>';

		foreach ($productArr as $product){
			//check
			$negative = 0;


			echo '<b>'.$product[0]['product_no'].'</b>';
			echo '<br />';
			foreach($product as $RR) {
				//prev balance is negative. move stock out to  this one
				if($negative == 1) {

					// move bad stock to new RR
					foreach($stockToMove as $eachStockOut) {
						$sql = $this->prepare("UPDATE StockOut SET rr_no = ? where file_no = ? AND rr_no = ? AND product_no = ? AND file_type = 'IV'");
						$sql->execute([$RR["file_no"],$eachStockOut["file_no"],$eachStockOut["rr_no"],$RR["product_no"]]);
						if($sql->errorInfo()[0] != '00000') print_r($sql->errorInfo());
						if($sql->errorInfo()[1] == '1062') { //probly duplicate primary key. then add this q to the duplicated q
							$sql = $this->prepare("DELETE FROM StockOut where file_no = ? AND rr_no = ? AND product_no = ? AND file_type = 'IV'");
							$sql->execute([$eachStockOut["file_no"],$eachStockOut["rr_no"],$RR["product_no"]]);
							if($sql->errorInfo()[0] != '00000') print_r($sql->errorInfo());
							$sql = $this->prepare("UPDATE StockOut SET quantity_out = quantity_out + ? where file_no = ? AND rr_no = ? AND product_no = ? AND file_type = 'IV'");
							$sql->execute([$eachStockOut['quantity_out'],$eachStockOut["file_no"],$RR["file_no"],$RR["product_no"]]);
							if($sql->errorInfo()[0] != '00000') print_r($sql->errorInfo());
						}
						
						
						echo 'move STOCK OUT from : ';
						echo '<br />';
						print_r($eachStockOut);
						echo 'to RR : ';
						echo '<br />';
						print_r($RR);
						echo '<br />';
						echo '----------------------------';
						echo '<br />';
					}


					if( $defict != 0) { //defict not exactly 0. have to split stock out
						$lastStockOut = end($stockToMove);
						$sql = $this->prepare("UPDATE StockOut SET quantity_out = ?,rr_no = ? where file_no = ? AND rr_no = ? AND product_no = ? AND file_type = 'IV'");
						$sql->execute([$defict,$lastStockOut["rr_no"],$lastStockOut["file_no"],$RR["file_no"],$RR["product_no"]]);

						$sql = $this->prepare("insert into StockOut (product_no, file_no, file_type, date, time, quantity_out, lot, note, rr_no) values (?, ?, 'IV', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, 0, NULL, ?)");
                		$sql->execute([$RR["product_no"], $lastStockOut["file_no"],$lastStockOut["quantity_out"] - $defict, $RR["file_no"]]);     

						echo 'but defict != 0';
						echo 'remove '. $defict.' from STOCK OUT : ';
						echo '<br />';
						print_r($lastStockOut);
						echo '<br />';
						echo 'and put '.$defict.' into RR : '; 
						echo '<br />';
						print_r($RR);
						echo '<br />';
						echo '----------------------------';
						echo '<br />';
					}
					$negative = 0;
				}

				//check if negative. prep bad stock to remove
				if ($RR["balance"] < 0) {
					$negative = 1;
					//get stock out newest first
					$sql = $this->prepare("SELECT * FROM StockOut WHERE `product_no` = ? AND rr_no = ?  AND `file_type` = 'IV' ORDER BY concat(date, ' ', Time) DESC;");
					$sql->execute([$RR["product_no"],$RR["file_no"]]);
					$stockOut = $sql->fetchAll(PDO::FETCH_ASSOC);

					$defict = $RR["balance"];
					$n = 0;
					$stockToMove = [];
					
					while($defict < 0) {
						$defict  = $defict + $stockOut[$n]["quantity_out"];
						array_push($stockToMove,$stockOut[$n]);
						$n++;
					}
				} else $negative = 0;

			}
		//	break; //for test one only
			echo '----------------------------<br />';
			echo '----------------------------<br />';
			echo '----------------------------<br />';
		}

		echo '</pre>';
    }



    public function checkWaveProgress() {
        $sql = $this->prepare("SELECT COUNT(*),`stage`,GROUP_CONCAT(`sp_id`) FROM `WaveToSuccess` GROUP BY `stage`;");
        $sql->execute();

        echo '<pre>';

        if ($sql->rowCount() > 0) {
            $dats = $sql->fetchAll(PDO::FETCH_ASSOC);
        foreach ($dats as $dat){
            echo 'stage : ';
            echo $dat['stage'];
            echo '. count = ';
            echo $dat['COUNT(*)'];
            echo '<br />';
            echo 'sp id : ';
            echo $dat["GROUP_CONCAT(`sp_id`)"];
            echo '<br /><br />----------------------------<br /><br />';
        }



        } else echo "no data";


        echo '</pre>';
    }


    public function getCboin() {

        $sql = $this->prepare("SELECT * FROM `View_Cboin` where employee_id = ?");
        $sql->execute([$_POST['sp_id']]);
        if ($sql->rowCount() > 0) echo $sql->fetchAll(PDO::FETCH_ASSOC)[0]["coin"];
        else echo 0;
    }

    public function addCboin() {
        $sql = $this->prepare(" INSERT INTO `CboinLog`(
                                    `employee_id`,
                                    `coin`,
                                    `type`,
                                    `note`
                                )
                                VALUES(
                                    ?,
                                    ?,
                                    ?,
                                    ?
                                )");
        $sql->execute([$_POST['sp_id'],$_POST["coin"],"on site",json_decode(session::get('employee_detail'), true)['employee_id']]);

        if($sql->errorInfo()[0] == '00000') echo 'ok';
        else print_r($sql->errorInfo());
    }

    public function postPoint() {

        $pointArr = json_decode($_POST["cboinArray"]);
            // $statement = "  INSERT into PointLog 
            //                     (date,time,employee_id,point,remark,note,type,cancelled)
            //                 values ";
        echo $pointArr;
        foreach($pointArr as $value) {

            $sql = $this->prepare(" INSERT INTO `CboinLog`(
                            `employee_id`,
                            `coin`,
                            `type`,
                            `note`
                        )
                        VALUES(
                            ?,
                            ?,
                            ?,
                            ?
                        )");
            $sql->execute([$value[0],$value[1],$value[2],json_decode(session::get('employee_detail'), true)['employee_id']]);
                                    
            if($sql->errorInfo()[0] == '00000') echo 'ok';
            else print_r($sql->errorInfo());
        }
    }

    public function getCboinForDashboard() {

        $sql = $this->prepare(' SELECT cboin_detail.*,View_Cboin.coin FROM View_Cboin 
                                LEFT JOIN (SELECT
                                    cbachula_master2022.CboinLog.employee_id AS employee_id,
                                    SUM(CASE WHEN cbachula_master2022.CboinLog.type = "line game : find your treasure" THEN 1 ELSE 0 END) AS gameACount,
                                    SUM(CASE WHEN cbachula_master2022.CboinLog.type = "line game : find your treasure" THEN CboinLog.coin ELSE 0 END) as gameAbalance,
                                    SUM(CASE WHEN cbachula_master2022.CboinLog.type = "line game : bet your direction" THEN 1 ELSE 0 END) AS gameBCount,
                                    SUM(CASE WHEN cbachula_master2022.CboinLog.type = "line game : bet your direction" THEN CboinLog.coin ELSE 0 END) AS gameBbalance,
                                    SUM(CASE WHEN cbachula_master2022.CboinLog.type = "line game : upgrade your weapon" THEN 1 ELSE 0 END) AS gameCCount,
                                    SUM(CASE WHEN cbachula_master2022.CboinLog.type = "line game : upgrade your weapon" THEN CboinLog.coin ELSE 0 END) AS gameCbalance,
                                    SUM(CASE WHEN cbachula_master2022.CboinLog.type REGEXP "^line [0-9]" THEN 1 ELSE 0 END) AS promoCount,
                                    SUM(CASE WHEN cbachula_master2022.CboinLog.type REGEXP "^line [0-9]" THEN CboinLog.coin ELSE 0 END) AS promoBalance,
                                    COALESCE(GROUP_CONCAT(CASE WHEN cbachula_master2022.CboinLog.type REGEXP "^line [0-9]" THEN SUBSTRING(CboinLog.type,6,10) ELSE NULL END), "") AS promoArr,
                                    SUM(CASE WHEN cbachula_master2022.CboinLog.type = "Check Name" THEN 1 ELSE 0 END) AS checkInCount
                                FROM
                                    cbachula_master2022.CboinLog
                                GROUP BY
                                    cbachula_master2022.CboinLog.employee_id) as cboin_detail on cboin_detail.employee_id = View_Cboin.employee_id;');
        $sql->execute();
        if ($sql->rowCount() > 0) return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        else return json_encode([], JSON_UNESCAPED_UNICODE);
    }
    
}