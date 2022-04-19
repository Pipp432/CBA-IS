<?php

namespace model;

use _core\model;
use _core\helper\session;
use _core\helper\input;
use PDO;

class finModel extends model {
    // CR Module
    public function getSoxsForCr() {
        $sql = $this->prepare("select 
                                    SOX.sox_no,
                                    SOX.sox_datetime,
                                    SOX.employee_id,
                                    Employee.employee_nickname_thai,
                                    Customer.customer_name,
                                    Customer.customerTitle,
                                    Customer.customer_surname,
                                    Customer.address,
                                    Customer.national_id,
                                    SO.so_no,
                                    SO.product_type,
                                    SOPrinting.product_no,
                                    Product.product_name,
                                    SOPrinting.sales_no_vat,
                                    SOPrinting.sales_vat,
                                    SOPrinting.sales_price,
                                    SOPrinting.quantity,
                                    SOPrinting.total_sales,
                                    SOX.transportation_no_vat,
                                    SOX.transportation_vat,
                                    SOX.transportation_price,
                                    SOX.so_total_discount,
                                    SOXPrinting.total_sales_no_vat as so_total_sales_no_vat,
                                    SOXPrinting.total_sales_vat as so_total_sales_vat,
                                    SOXPrinting.total_sales_price as so_total_sales_price,
                                    SOX.total_sales_price as sox_sales_price,
                                    SO.discountso,
									SO.point as so_point,
                                    SO.payment as payment,
                                    SO.commission as so_commission,
                                    SOX.slip_uploaded,
                                    SOX.slip_datetime,
                                    SOX.payment_date,
                                    SOX.payment_time,
									SOX.payment_amount,
                                    SOX.fin_form,
                                    Bank_Statement.id
                                from SOXPrinting 
                                inner join SOX on SOX.sox_no = SOXPrinting.sox_no
                                inner join SO on SO.so_no = SOXPrinting.so_no
                                inner join SOPrinting on SOPrinting.so_no = SO.so_no
                                inner join Employee on Employee.employee_id = SOX.employee_id
                                left join Product on Product.product_no = SOPrinting.product_no
                                inner join Customer on Customer.customer_tel = SOX.customer_tel
                                left join Bank_Statement on SOX.payment_date=Bank_Statement.payment_date and 
                                SOX.payment_time=Bank_Statement.payment_time and 
                                SOX.payment_amount=Bank_Statement.payment_amount
                                where SOX.done = -1 and SOX.cancelled = 0 and (SO.payment = 0 or SO.payment is null) order by SOX.slip_datetime");  
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return json_encode([]);
    }
	public function getSoxsForCr_withIV() {
        $sql = $this->prepare("select 
                                    distinct SOX.sox_no,
                                    SOX.sox_datetime,
                                    SOX.employee_id,
                                    Employee.employee_nickname_thai,
                                    Customer.customer_name,
                                    Customer.customer_surname,
                                    Customer.address,
                                    Customer.national_id,
                                    SO.so_no,
                                    SO.product_type,
                                    SOPrinting.product_no,
                                    Product.product_name,
                                    SOPrinting.sales_no_vat,
                                    SOPrinting.sales_vat,
                                    SOPrinting.sales_price,
                                    SOPrinting.quantity,
                                    SOPrinting.total_sales,
                                    SOX.transportation_no_vat,
                                    SOX.transportation_vat,
                                    SOX.transportation_price,
                                    SOX.so_total_discount,
                                    SOXPrinting.total_sales_no_vat as so_total_sales_no_vat,
                                    SOXPrinting.total_sales_vat as so_total_sales_vat,
                                    SOXPrinting.total_sales_price as so_total_sales_price,
                                    SOX.total_sales_price as sox_sales_price,
                                    SO.discountso,
									SO.point as so_point,
                                    SO.payment as payment,
                                    SO.commission as so_commission,
                                    SOX.slip_uploaded,
                                    SOX.slip_datetime,
                                    SOX.payment_date,
                                    SOX.payment_time,
									SOX.payment_amount,
                                    SOX.fin_form,
                                    Bank_Statement.id,
                                    Invoice.invoice_no,
                                    POPrinting.po_no,
                                    CI.ci_no,
									DATEDIFF(CI.ci_date, CURDATE()) as date_diff_ci
                                from SOXPrinting 
                                inner join SOX on SOX.sox_no = SOXPrinting.sox_no
                                inner join SO on SO.so_no = SOXPrinting.so_no
                                inner join SOPrinting on SOPrinting.so_no = SO.so_no
                                inner join Employee on Employee.employee_id = SOX.employee_id
                                left join Product on Product.product_no = SOPrinting.product_no
                                inner join Customer on Customer.customer_tel = SOX.customer_tel
                                left join Bank_Statement on SOX.payment_date=Bank_Statement.payment_date and 
                                SOX.payment_time=Bank_Statement.payment_time and 
                                SOX.payment_amount=Bank_Statement.payment_amount
                                left join Invoice on SO.so_no = Invoice.file_no
                                left join POPrinting on POPrinting.so_no=SO.so_no 
                                left join CI on CI.po_no=POPrinting.po_no  
                                where SOX.done = 1 and SOX.cancelled = 0 and SO.payment = 1 and Invoice.invoice_no is not null order by SOX.slip_datetime");  
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return json_encode([]);
    }
	public function addCr_withIV() {
        
        $sql = $this->prepare("update SOX set SOX.done = 2 where SOX.sox_no = ?");
        $sql->execute([input::post('sox_number')]);
        
        $crItemsArray = json_decode(input::post('crItems'), true); 
        $crItemsArray = json_decode($crItemsArray, true); 
        
        $soList = array();
        $iv_no = "";
        $cr_no = "";
        
        foreach($crItemsArray as $value) {
            
            if (array_key_exists($value['so_no'], $soList)) {
                
                $iv_no = $soList[$value['so_no']];
                
            } else {
                 // update received in InvoicePrinting
				$sql = $this->prepare("update InvoicePrinting
										inner join Invoice on Invoice.invoice_no = InvoicePrinting.invoice_no
										inner join SO on SO.so_no = Invoice.file_no
										inner join PO on PO.po_no = SO.po_no
										set rr_no = ?
										where PO.po_no = ? and SO.so_no = ?");                                             
				$sql->execute([$cino, $value['po_no'], $value['so_no']]);
				
                $iv_no = $value['invoice_no'];
				
                $cr_no = $iv_no[0].'CR'.substr($iv_no, 3);
                $soList += [$value['so_no']=>$iv_no];
                
				$sql = $this->prepare("select so_no from SOXPrinting where sox_no=?");                                             
				$sql->execute([$cino]);
				
                
                if($value['so_total_sales_vat2'] != 0) {
                    $total_sales_no_vat = ((double) $value['so_total_sales_price2']) / 1.07;
                    $total_sales_vat = (((double) $value['so_total_sales_price2']) / 107) * 7;
                    $total_sales_price = (double) $value['so_total_sales_price2'];
                } else {
                    $total_sales_no_vat = (double) $value['so_total_sales_price2'];
                    $total_sales_vat = 0;
                    $total_sales_price = (double) $value['so_total_sales_price2'];
                }
                
                $sql = $this->prepare("insert into CR (cr_no, cr_date, cr_time, employee_id, customer_name, customer_address, id_no, total_price_no_vat, total_price_vat, total_price, commission, 
                                        approved_employee, payment_type, note, transfer_date, transfer_time, remark_check_date, cancelled, tr_no, total_text, time, slip, noted)
                                        values(?,CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?, ?, ?, ?, ?, ?, NULL, NULL, NULL, NULL, NULL, 0, NULL, ?, NULL, NULL, NULL)");
                $sql->execute([
                    $cr_no,
                    $value['employee_id'],
                    input::post('cusName'),
                    input::post('cusAddress'),
                    input::post('cusId'),
                    $total_sales_no_vat,
                    $total_sales_vat,
                    $total_sales_price,
                    (double) $value['so_commission'],
                    json_decode(session::get('employee_detail'), true)['employee_id'],
                    $value['priceInThai']
                ]);
				
				$sql = $this->prepare("update Invoice
										set customer_name = ?, customer_address = ?, id_no=?, cr_no=?
										where invoice_no=?");                                             
				$sql->execute([input::post('cusName'),
                    			input::post('cusAddress'),
                    			input::post('cusId'),
							   	$cr_no,
							   	$iv_no]);
				
				// insert AccountDetail sequence 1
                // Dr เงินฝากธนาคาร
                $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                        values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                $sql->execute([$cr_no, '1', '12-0000', (double) $total_sales_price, 0, 'CR']);
                // insert AccountDetail sequence 2
                // Cr ลูกหนี้การค้า - โครงการ X
                $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                        values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                $sql->execute([$cr_no, '2', '13-1'.$cr_no[0].'00', 0, (double) $total_sales_price, 'CR']);
                // insert AccountDetail sequence 3
                // DR commission
                $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                        values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                $sql->execute([$cr_no, '3', '52-1000', (double) $value['so_commission'], 0, 'CR']);
				// insert AccountDetail sequence 4
				// CR commission
                $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                        values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                $sql->execute([$cr_no, '4', '22-0000', 0, (double) $value['so_commission'], 'CR']);
                // ============================================================================================================================================================
                // END CBA2020 ACC
				
				//Pro Week 7-8
                     if (($CheckSoDate == '2021-07-12' && $CheckSoTime >= '14:00:00') || $CheckSoDate == '2021-07-13' || $CheckSoDate == '2021-07-14' || 
                         $CheckSoDate == '2021-07-15' || $CheckSoDate == '2021-07-16' || $CheckSoDate == '2021-07-17' || 
						 $CheckSoDate == '2021-07-19' || $CheckSoDate == '2021-07-20' || $CheckSoDate == '2021-07-21' || 
                         $CheckSoDate == '2021-07-22' || $CheckSoDate == '2021-07-23' || $CheckSoDate == '2021-07-24') {
						 
						 // check range
						 $sql = $this->prepare("select lp_range from CboinRange where employee_id = ?");
                         $sql->execute([$value['employee_id']]);
						 $range = $sql->fetchAll()[0]['lp_range'];
						 //echo ' range ='.$range;
						 
						 // check จำนวครั้งที่เคยได้ c-boin จากการขาย
						 $sql = $this->prepare("select sum(cboin) as count from CboinLog where remark = ? and cancelled = 0 and employee_id = ?");
						 $sql->execute(['Sales - Range '.$range, $value['employee_id']]);
						 $count = $sql->fetchAll()[0]['count'];
						 //echo ' count ='.$count;
						 
						 if ($count < 90) {
							 
							 // check ยอดจาด iv ทั้งหมดที่เคยขา รวมปัจจุบันด้วย
							 $sql = $this->prepare("select ifnull(sum(Invoice.total_sales_price),0) as TotalSold from Invoice 
													INNER JOIN SO on SO.so_no = Invoice.file_no
													where Invoice.employee_id = ? and Invoice.cancelled = 0 and file_type = 'SO' and SO.cancelled = 0 
													AND ((so_date = '2021-07-12' and so_time >= '14:00:00') 
															OR (so_date between '2021-07-13' AND '2021-07-17') 
															OR (so_date between '2021-07-19' AND '2021-07-24'))");
							 $sql->execute([$value['employee_id']]);
							 $totalSold = $sql->fetchAll()[0]['TotalSold'];
							 //echo ' total sold ='.$totalSold;
							 
							 // ดูยอดที่ต้องทำได้ต่อ 30 c-boin ของแต่ละ range
							 $targetSales = 0;
							 if ($range == '1') {
								 $targetSales = 1000;
							 }
							 else if ($range == '2') {
								 $targetSales = 1500;
							 }
							 else {
								 $targetSales = 3000;
							 }
							 
							 //echo ' target sales ='.$targetSales;
							 
							 $times=$count;
							 $sold=$times*$targetSales;
							 $new_total=$totalSold-$sold;
							 $p_times=intdiv($new_total,$targetSales);
							 
							 $new_p_times = 3 - $count;
							 if ($p_times > $new_p_times){
								 $p_times = $new_p_times;
							 }
							 
							 $extraPoint = ($p_times >= 1) ? $p_times*30: 0;
							 
							 if ($extraPoint > 0) {
								 $sql = $this->prepare("insert into CboinLog (date, time, employee_id, cboin, remark, note, cancelled)
							 						values(CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?, 0)");
								 $sql->execute([$value['employee_id'], $extraPoint, 'Sales - Range '.$range, $cr_no]);
							 }
							 
							 //echo ' inserted ja!';
							 
							 
							 
						 }
						 
						 
						 
                     }
               
                
				
                
                
            }

        }
        return $cr_no;
    } 
	public function getPVCHECK() {
        $sql = $this->prepare("SELECT RRCI_Invoice.rrci_no, PVPrinting.pv_no, PVPrinting.iv_no, PVPrinting.paid_total, PV.thai_text FROM `RRCI_Invoice`
		LEFT JOIN PVPrinting ON RRCI_Invoice.rrci_no=PVPrinting.rr_no
		LEFT JOIN PV ON PVPrinting.pv_no=PV.pv_no");  
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return json_encode([]);
    }
    public function getReReq(){
        $sql = $this->prepare("SELECT * from Reimbursement_Request where ex_no IS Null and evidence IS Null and company is null");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return null;
        
    }
    public function getReReqDetails($re_req_no){
        $sql = $this->prepare("select * from Reimbursement_Request where re_req_no=?");
        $sql->execute([$re_req_no]);
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return null;
        
    }
    public function assign_ex_no() {

        $rqPrefix = 'EXC-';
        $sql = $this->prepare( "select ifnull(max(ex_no),0) as max from Reimbursement_Request where ex_no like ?" );
        $sql->execute( [ 'EXC-%' ] );
        $maxRqNo = $sql->fetchAll()[ 0 ][ 'max' ];
        $runningNo = '';
        if ( $maxRqNo == '0' ) {
          $runningNo = '00001';
        } else {
          $latestRunningNo = ( int )substr( $maxRqNo, 4 ) + 1;
          if ( strlen( $latestRunningNo ) == 5 ) {
            $runningNo = $latestRunningNo;
          } else {
            for ( $x = 1; $x <= 5 - strlen( $latestRunningNo ); $x++ ) {
              $runningNo .= '0';
            }
            $runningNo .= $latestRunningNo;
          }
        }
        return $rqPrefix . $runningNo;
      }
    public function postAdditionReReqDetail(){
        echo "<script>console.log('pain' );</script>";
        $sql = $this->prepare("update Reimbursement_Request set Reimbursement_Request.evidence=?,Reimbursement_Request.company=?,Reimbursement_Request.ex_no=? where re_req_no=? ");
        $ex_no = $this->assign_ex_no();
        $sql-> execute([
        input::post('proof'),
        input::post('project'),
        $ex_no,
       input::post('re_req_number')
    ]);
    }
    
    // CR Module
    public function addCr() {
        
        $sql = $this->prepare("update SOX set SOX.done = 0 where SOX.sox_no = ?");
        $sql->execute([input::post('sox_number')]);
        
        $crItemsArray = json_decode(input::post('crItems'), true); 
        $crItemsArray = json_decode($crItemsArray, true); 
       
        $soList = array();
        $iv_no = "";
        $cr_no = "";
        
        foreach($crItemsArray as $value) {
            
            if (array_key_exists($value['so_no'], $soList)) {
                
                $iv_no = $soList[$value['so_no']];
                
            } else {
                  
                $iv_no = $this->assignIv($value['so_no']); 
                $cr_no = $iv_no[0].'CR'.substr($iv_no, 3);
                $soList += [$value['so_no']=>$iv_no];
                
                echo $iv_no.' ('.$value['so_no'].') ';
                
                if($value['so_total_sales_vat2'] != 0) {
                    $total_sales_no_vat = ((double) $value['so_total_sales_price2']) / 1.07;
                    $total_sales_vat = ((double) $value['so_total_sales_price2']) / 107 * 7;
                    $total_sales_price = (double) $value['so_total_sales_price2'];
                } else {
                    $total_sales_no_vat = (double) $value['so_total_sales_price2'];
                    $total_sales_vat = 0;
                    $total_sales_price = (double) $value['so_total_sales_price2'];
                }
                
                // insert IV
                $sql = $this->prepare("insert into Invoice (invoice_no, invoice_date, invoice_time, employee_id, customer_name, customer_title,customer_address, id_no, file_no,
                                        file_type, total_sales_no_vat, total_sales_vat, total_sales_price, discount, sales_price_thai, point, commission, approved_employee, cr_no, cancelled, note)
                                        values (?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,?, ?, 'SO', ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, ?)");  
                $sql->execute([
                    $iv_no,
                    $value['employee_id'],
                    input::post('cusName'),
                    input::post('customer_title'),
                    input::post('cusAddress'),
                    input::post('cusId'),
                    $value['so_no'],
                    $total_sales_no_vat,
                    $total_sales_vat,
                    $total_sales_price,
                    (double) $value['discountso'],
                    $value['priceInThai'], 
                    (double) $value['so_point'],                    
                    (double) $value['so_commission'],
                    json_decode(session::get('employee_detail'), true)['employee_id'],
                    $cr_no,
                    input::post('noted')
                ]); 
                $check = $sql->errorInfo()[0];

				if($check == '00000') {
                //$text = $this->Convert($total_sales_price);
                //insert CR
                $sql = $this->prepare("insert into CR (cr_no, cr_date, cr_time, employee_id, customer_name, customer_address, id_no, total_price_no_vat, total_price_vat, total_price, commission, 
                                        approved_employee, payment_type, note, transfer_date, transfer_time, remark_check_date, cancelled, tr_no, total_text, time, slip, noted)
                                        values(?,CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?, ?, ?, ?, ?, ?, NULL, NULL, NULL, NULL, NULL, 0, NULL, ?, NULL, NULL, NULL)");
                $sql->execute([
                    $cr_no,
                    $value['employee_id'],
                    input::post('cusName'),
                    input::post('cusAddress'),
                    input::post('cusId'),
                    $total_sales_no_vat,
                    $total_sales_vat,
                    $total_sales_price,
                    (double) $value['so_commission'],
                    json_decode(session::get('employee_detail'), true)['employee_id'],
                    $value['priceInThai']
                ]);
					
				//echo 'before';

                // if(is_numeric($value['employee_id'])) {
					
				// 	//echo 'after isnumeric';
                    
                //     // Archery (week5)
                //     // $this->updateSalesPro5($value['so_no'], $total_sales_price, $value['employee_id']);
                    
                //     $sql = $this->prepare("select so_date as SO_date from SO where so_no = ?");
                //     $sql->execute([$value['so_no']]);
                //     $CheckSoDate = $sql->fetchAll()[0]['SO_date'];
					
				// 	//echo 'check date';
					
				// 	$sql = $this->prepare("select so_time as SO_time from SO where so_no = ?");
                //     $sql->execute([$value['so_no']]);
                //     $CheckSoTime = $sql->fetchAll()[0]['SO_time'];
					
				// 	//echo 'check time';
                    
                //     //$sql = $this->prepare("select point as goody_point from goody_point_log where datetime = ? and employee_id = ?");
                //     //$sql->execute([$CheckSoDate, $value['employee_id']]);
                //     //$CheckGoody = $sql->fetchAll()[0]['goody_point'];
                //     //
                //     //// Goody Point
                //     //if($CheckGoody == 0) {
                //     //    $this->updatePointGoody($value['so_no'], $value['employee_id']);
                //     //}
                    
                //     //Pro Week 7-8
                //      if (($CheckSoDate == '2021-07-12' && $CheckSoTime >= '14:00:00') || $CheckSoDate == '2021-07-13' || $CheckSoDate == '2021-07-14' || 
                //          $CheckSoDate == '2021-07-15' || $CheckSoDate == '2021-07-16' || $CheckSoDate == '2021-07-17' || 
				// 		 $CheckSoDate == '2021-07-19' || $CheckSoDate == '2021-07-20' || $CheckSoDate == '2021-07-21' || 
                //          $CheckSoDate == '2021-07-22' || $CheckSoDate == '2021-07-23' || $CheckSoDate == '2021-07-24') {
						 
				// 		 // check range
				// 		 $sql = $this->prepare("select lp_range from CboinRange where employee_id = ?");
                //          $sql->execute([$value['employee_id']]);
				// 		 $range = $sql->fetchAll()[0]['lp_range'];
				// 		 //echo ' range ='.$range;
						 
				// 		 // check จำนวครั้งที่เคยได้ c-boin จากการขาย
				// 		 $sql = $this->prepare("select sum(cboin) as count from CboinLog where remark = ? and cancelled = 0 and employee_id = ?");
				// 		 $sql->execute(['Sales - Range '.$range, $value['employee_id']]);
				// 		 $count = $sql->fetchAll()[0]['count'];
				// 		 //echo ' count ='.$count;
						 
				// 		 if ($count < 90) {
							 
				// 			 // check ยอดจาด iv ทั้งหมดที่เคยขา รวมปัจจุบันด้วย
				// 			 $sql = $this->prepare("select ifnull(sum(Invoice.total_sales_price),0) as TotalSold from Invoice 
				// 									INNER JOIN SO on SO.so_no = Invoice.file_no
				// 									where Invoice.employee_id = ? and Invoice.cancelled = 0 and file_type = 'SO' and SO.cancelled = 0 
				// 									AND ((so_date = '2021-07-12' and so_time >= '14:00:00') 
				// 											OR (so_date between '2021-07-13' AND '2021-07-17') 
				// 											OR (so_date between '2021-07-19' AND '2021-07-24'))");
				// 			 $sql->execute([$value['employee_id']]);
				// 			 $totalSold = $sql->fetchAll()[0]['TotalSold'];
				// 			 //echo ' total sold ='.$totalSold;
							 
				// 			 // ดูยอดที่ต้องทำได้ต่อ 30 c-boin ของแต่ละ range
				// 			 $targetSales = 0;
				// 			 if ($range == '1') {
				// 				 $targetSales = 1000;
				// 			 }
				// 			 else if ($range == '2') {
				// 				 $targetSales = 1500;
				// 			 }
				// 			 else {
				// 				 $targetSales = 3000;
				// 			 }
							 
				// 			 //echo ' target sales ='.$targetSales;
							 
				// 			 //จำนวนครั้งที่ได้แต้มไปแล้ว
				// 			 $times=$count;
							 
				// 			 //ยอดที่เคยขายและได้แต้มไปแล้ว
				// 			 $sold=$times*$targetSales;
							 
				// 			 //ยอดที่ขายไป (รวมล่าสุด) ที่ยังไม่เคยได้แต้ม
				// 			 $new_total=$totalSold-$sold;
							 
				// 			 //ยอดที่ขายไป (รวมล่าสุด) ที่ยังไม่เคยได้แต้ม  หารด้วย ยอดต่อการได้แต้ม 1 ครั้ง
				// 			 $p_times=intdiv($new_total,$targetSales);
							 
				// 			 // set จำนวนครั้งที่คูณแต้ม ตามจำนวน max
				// 			 $new_p_times = 3 - $count;
				// 			 if ($p_times > $new_p_times){
				// 				 $p_times = $new_p_times;
				// 			 }
							 
							 
				// 			 $extraPoint = ($p_times >= 1) ? $p_times*30: 0;
							 
				// 			 if ($extraPoint > 0) {
				// 				 $sql = $this->prepare("insert into CboinLog (date, time, employee_id, cboin, remark, note, cancelled)
				// 			 						values(CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?, 0)");
				// 				 $sql->execute([$value['employee_id'], $extraPoint, 'Sales - Range '.$range, $iv_no]);
				// 			 }
							 
				// 			 //echo ' inserted ja!';
							 
							 
							 
				// 		 }
						 
						 
						 
                //      }
                    
                     
                    
                // }
                
                // ============================================================================================================================================================
                // NEW CBA2020 ACC
                
                // insert AccountDetail sequence 1
                // Dr เงินฝากธนาคาร
                $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                        values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                $sql->execute([$iv_no, '1', '12-0000', (double) $total_sales_price, 0, 'IV']);
                
                // insert AccountDetail sequence 2
                // Cr รายได้รับล่วงหน้า - โครงการ X
                $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                        values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                $sql->execute([$iv_no, '2', '24-1'.$iv_no[0].'00', 0, (double) $total_sales_no_vat, 'IV']);
                
                // insert AccountDetail sequence 3
                // Cr ภาษีขาย - โครงการ X
                $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                        values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                $sql->execute([$iv_no, '3', '62-1'.$iv_no[0].'00', 0, (double) $total_sales_vat, 'IV']);
                
                // ============================================================================================================================================================
                // END CBA2020 ACC
                } else {
            
            	echo 'เกิดข้อผิดพลาด รบกวนออก IVCR ใหม่ กรุณาแคปหน้าเจอให้กับทางทีม IS ขออภัยในความไม่สะดวก';
                $check = $sql->errorInfo()[2];
               
				return $check;
				}
            }
            
            //rr_no for Install / Order / Transport
            if($value['product_type'] == 'Install' || $value['product_type'] == 'Order') { 
                
                $rr_no = 'pending';  
                 
            } else if($value['product_type'] == 'Transport') { 
                
                $rr_no = '-';  
                 
            } else {
                
                $rr_no = 'error';
                
            }
            
            // insert InvoicePrinting for Install / Order / Transport
            if  ($value['product_type'] != 'Stock') {
            $sql = $this->prepare("insert into InvoicePrinting (invoice_no, product_no, sales_no_vat, sales_vat, sales_price, quantity, total_sales_price, cancelled, rr_no)
                                    values (?, ?, ?, ?, ?, ?, ?, 0, ?)");  
            $sql->execute([
                $iv_no,
                $value['product_no'],
                (double) $value['sales_no_vat'],
                (double) $value['sales_vat'],
                (double) $value['sales_price'],
                (double) $value['quantity'],
                (double) $value['total_sales'],
                $rr_no 
            ]);
                     
            }
            
            $accumStock = 0;
            //rr_no for Stock
            if ($value['product_type'] == 'Stock') { $accumStock  = (double) $value['quantity']; }
            
            while( $accumStock > 0) {
                
                $cutStock = 0;
                $sql = $this->prepare("select * from View_InvoiceStock where product_no = ? and balance <> 0 order by file_no");
                $sql->execute([$value['product_no']]);
                $rrTable = $sql->fetchAll()[0];
                $rrStock = (int) $rrTable['balance'];
                $rr_no = $rrTable['file_no'];
                
                if( $accumStock > $rrStock )
                {
                    $cutStock = $rrStock;
                }
                else $cutStock = $accumStock;

                $sql = $this->prepare("insert into StockOut (product_no, file_no, file_type, date, time, quantity_out, lot, note, rr_no) 
                                        values (?, ?, 'IV', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, 0, NULL, ?)");
                $sql->execute([$value['product_no'], $iv_no, $cutStock, $rr_no]);     
                
            // insert InvoicePrinting for Stock
                if  ($value['product_type'] == 'Stock') {
                $sql = $this->prepare("insert into InvoicePrinting (invoice_no, product_no, sales_no_vat, sales_vat, sales_price, quantity, total_sales_price, cancelled, rr_no)
                                    values (?, ?, ?, ?, ?, ?, ?, 0, ?)");  
                $sql->execute([
                    $iv_no,
                    $value['product_no'],
                    (double) $value['sales_no_vat'],
                    (double) $value['sales_vat'],
                    (double) $value['sales_price'],
                    $cutStock,
                    (double) $value['total_sales'],
                    $rr_no 
                ]);
                     
                }
            
                $accumStock = $accumStock - $cutStock;
            
            }
            
        }
        return $cr_no;
    } 
    
    // CR Module
    private function assignIv($so_no) {
        $ivPrefix = $so_no[0].'IV-';
        $sql=$this->prepare("select ifnull(max(invoice_no),0) as max from Invoice where invoice_no like ?");
        $sql->execute([$ivPrefix.'%']);
        $maxIvNo = $sql->fetchAll()[0]['max'];
        $runningNo = '';
        if($maxIvNo=='0') {
            $runningNo = '00001';
        } else {
            $latestRunningNo = (int) substr($maxIvNo, 4) + 1;
            if(strlen($latestRunningNo)==5) {
                $runningNo = $latestRunningNo;
            } else {
                for ($x = 1; $x <= 5 - strlen($latestRunningNo); $x++) {
                    $runningNo .= '0';
                }
                $runningNo .= $latestRunningNo;
            }
        }
        return $ivPrefix.$runningNo;
    }
    

    // CR Module
    public function getSoxReceipt($sox_no) {
        
        $sql = $this->prepare("select * from SOX where sox_no = ? and slip_uploaded = 1");
        $sql->execute([$sox_no]);
        
        if ($sql->rowCount()>0) {
            $data = $sql->fetchAll()[0];
    		header('Content-type: '.$data['slip_type']);
            echo base64_decode($data['slip_data']);
        } else {
            echo 'ไม่มีหลักฐานการชำระเงินของ SOX นี้';
        }
        
    }
	public function getPVSlip($ci_no) {
        
        $sql = $this->prepare("SELECT * FROM RRCI_Invoice WHERE rrci_no = ?");
        $sql->execute([$ci_no]);
        
        if ($sql->rowCount()>0) {
            $data = $sql->fetchAll()[0];
    		header('Content-type: '.$data['rrci_invoice_type']);
            echo base64_decode($data['rrci_invoice_data']);
        } else {
            echo 'Not Found';
        }
        
    }
    
    
    

        public function insertfile($filename,$filedata,$filetype,$fileid)
        {
           
            $filedata=base64_decode($filedata);
           
            $sql=$this->prepare("insert into UploadTest (name,type,data,id) values (?,?,?,?)");
            $sql->execute([$filename,$filetype,$filedata,$fileid]);
            return true;
        }
        public function GetUploadData($id)
        {
            $sql = $this->prepare("select * from UploadTest where employee_id = ?");
            $sql->execute([$id]);
            return $sql->fetchAll()[0];
    
        }
       
     public function insertivtype3($name,$type,$data,$ivno)
    {
        $data=base64_decode($data);
    
        $sql= $this->prepare("update WS_IV set iv2_name = ? , iv2_type = ?, iv2_data = ? where iv_no = ?");
        $sql->execute([$name,$type,$data,$ivno]);
        return true;
    }
    public function insertwstype1($emid,$type,$id,$name1,$type1,$data1,$name2,$type2,$data2,$name3,$type3,$data3,$name4,$type4,$data4,$name5,$type5,$data5)
    {
        //createws
        $ws_no = $this->assignWS();
        $sql = $this->prepare("insert into WS (ws_no,date,status,approved_employee,requested_employee,ws_type) values (?,CURRENT_TIMESTAMP,?,?,?,?)");
        $sql->execute([$ws_no,"1",session::get('employee_id'),$emid,$type]);
        //form
        $data1=base64_decode($data1);
      
        $sql = $this->prepare("insert into WS_Form (form_no,form_name,form_type,form_data) values (?,?,?,?)");
        $sql->execute([$id,$name1,$type1,$data1]);
        $sql= $this->prepare("update WS set form_no = ? where ws_no = ?");
        $sql ->execute([$id,$ws_no]);
        //iv
        $data2=base64_decode($data2);
		if ($data3 != NULL ||$data3 != 'NULL'){
			$data3=base64_decode($data3);
		}
        if ($data4 != NULL ||$data4 != 'NULL'){
			$data4=base64_decode($data4);
		}
		if ($data5 != NULL ||$data5 != 'NULL'){
			$data5=base64_decode($data5);
		}

        $wi_no = $this->assignWI();
        $sql = $this->prepare("insert into WS_IV 
										(iv_no, 
										iv_name, iv_type, iv_data, 
										iv2_name, iv2_type, iv2_data, 
										iv3_name, iv3_type, iv3_data, 
										slip_name, slip_type, slip_data) 
                                values (?,
										?,?,?,
										?,?,?,
										?,?,?,
										?,?,?)");
        $sql->execute([$wi_no,
					   $name2,$type2,$data2,
					   $name3,$type3,$data3,
					   $name4,$type4,$data4,
					   $name5,$type5,$data5]);
        
        $sql= $this->prepare("update WS set iv_no = ? where ws_no = ?");
        $sql ->execute([$wi_no,$ws_no]);
        
        return true;
    }
    /*public function insertwstype2($emid,$type,$id,$name1,$type1,$data1,$name2,$type2,$data2,$name3,$type3,$data3)
    {
         //createws
         $ws_no = $this->assignWS();
        $sql = $this->prepare("insert into WS (ws_no,date,status,approved_employee,requested_employee,ws_type) values (?,CURRENT_TIMESTAMP,?,?,?,?)");
        $sql->execute([$ws_no,"1",session::get('employee_id'),$emid,$type]);
       //form
       $data1=base64_decode($data1);
      
        $sql = $this->prepare("insert into WS_Form (form_no,form_name,form_type,form_data) values (?,?,?,?)");
        $sql->execute([$id,$name1,$type1,$data1]);
        $sql= $this->prepare("update WS set form_no = ? where ws_no = ?");
        $sql ->execute([$id,$ws_no]);
        //iv
       
        $data2=base64_decode($data2);
        $data3=base64_decode($data3);
        $wi_no = $this->assignWI();
        $sql = $this->prepare("insert into WS_IV (iv_no,iv_name,iv_type,iv_data,iv2_name,iv2_type,iv2_data) values (?,?,?,?,?,?,?)");
        $sql->execute([$wi_no,$name2,$type2,$data2,$name3,$type3,$data3]);
        $sql= $this->prepare("update WS set iv_no = ? where ws_no = ?");
        $sql ->execute([$wi_no,$ws_no]);
        
        return true;
    }*/

   /* public function insertslip($name,$type,$data,$wfno)
    {
        $data=base64_decode($data);
        //$ts_no = $this->assignTS();
        $sql = $this->prepare("insert into WS_TS (ts_no,ts_name,ts_type,ts_data,approved_employee) values (?,?,?,?,?)");
        $sql->execute([$ts_no,$name,$type,$data,session::get('employee_id')]);
        $sql= $this->prepare("update WS set ts_no = ? , status = '3' where form_no = ?");
        $sql ->execute([$ts_no,$wfno]);
        return true;
        
    }*/
    public function insertslip($name,$type,$data,$pvno,$wfno)
    {
        $data=base64_decode($data);
        
        $sql = $this->prepare("update  PV set slip_name = ?, slip_type= ? , slip_data = ? ,tranferred_employee = ? where pv_no = ?");
        $sql->execute([$name,$type,$data,session::get('employee_id'),$pvno]);
        $sql= $this->prepare("update WS set  status = '3' where form_no = ?");
        $sql ->execute([$wfno]);
        return true;
        
    }
     public function insertreceipt($name,$type,$data,$pvno)
    {
        $data=base64_decode($data);
        
        $sql = $this->prepare("update  PV set receipt_name = ?, receipt_type= ? , receipt_data = ? where pv_no = ?");
        $sql->execute([$name,$type,$data,$pvno]);
        return true;
        
    }
    public function insertslipForSup($name,$type,$data,$name2,$type2,$data2,$pvno)
    {
        $data=base64_decode($data);
        $data2=base64_decode($data2);
        
        $sql = $this->prepare("update  PV set slip_name = ?, slip_type= ? , slip_data = ? , cr_name = ?, cr_type= ? , cr_data = ? , tranferred_employee = ? where pv_no = ?");
        $success = $sql->execute([$name,$type,$data,$name2,$type2,$data2,session::get('employee_id'),$pvno]);
        if($success) echo true;
        else print_r($sql->errorInfo());
        
    }
    public function GetStatus2Data()
    {
       
        $sql = $this->prepare("SELECT f.form_no as form_no,f.form_type as form_type,f.form_data as form_data,v.iv_no as iv_no,v.iv_type as iv_type,v.iv_data as iv_data,s.pv_no as pv_no , concat(e.employee_nickname_eng,' ',e.position,IFNULL(e.product_line, '')) as employee,p.total_paid as totalpaid
            FROM WS s
join WS_Form f on f.form_no=s.form_no
join WS_IV v on v.iv_no = s.iv_no
join Employee e on s.requested_employee = e.employee_id
join PV p on p.pv_no = s.pv_no
WHERE s.status='2'");
        $sql->execute();
        return $sql->fetchAll();
        
    }
    public function GetPVforReceipt()
      { 
        $sql = $this->prepare("SELECT pv_no,total_paid,pv_type,pv_name FROM PV WHERE isnull(receipt_data) and not isnull(slip_data)");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
        
    }
    
     public function GetPVforTranfer()
      { 
        $sql = $this->prepare("SELECT * FROM PV WHERE isnull(slip_data) AND pv_type = 'Supplier' and cancelled = 0");
        $sql->execute();
        return $sql->fetchAll();
        
    }
    
    public function GetWSType3()
      { 
        $sql = $this->prepare("select s.form_no as form_no,v.iv_no as iv_no ,v.iv_type as iv_type,v.iv_data as iv_data,p.total_paid as totalpaid,concat(e.employee_nickname_eng,' ',e.position,IFNULL(e.product_line, '')) as employee from WS s
join WS_IV v on s.iv_no = v.iv_no
join PV p on p.pv_no = s.pv_no
join Employee e on s.requested_employee = e.employee_id
where s.status = '3' and s.ws_type = '3' and isnull(v.iv2_data)");
        $sql->execute();
        return $sql->fetchAll();
        
    }
    public function findwfno($wfno)
    {
        
        $sql = $this->prepare("select * from WS where form_no = ? AND status = '2' ");
        $sql ->execute([$wfno]);
        if ($sql->rowCount()>0) {
            return true;
        }
        return false;
        
    }public function findformno($formid)
    {
        
        $sql = $this->prepare("select * from WS where form_no = ? ");
        $sql ->execute([$formid]);
        if ($sql->rowCount()>0) {
            return true;
        }
        return false;
        
    }
     private function assignWS() {
        $wsPrefix = 'WS-';
        $sql=$this->prepare("select ifnull(max(ws_no),0) as max from WS ");
        $sql->execute();
        $maxWSNo = $sql->fetchAll()[0]['max'];
        $runningNo = '';
        if($maxWSNo=='0') {
            $runningNo = '0001';
        } else {
            $latestRunningNo = (int) substr($maxWSNo, 3) + 1;
            if(strlen($latestRunningNo)==4) {
                $runningNo = $latestRunningNo;
            } else {
                for ($x = 1; $x <= 4 - strlen($latestRunningNo); $x++) {
                    $runningNo .= '0';
                }
                $runningNo .= $latestRunningNo;
            }
        }
        return $wsPrefix.$runningNo;
    }
    private function assignTS() {
        $tsPrefix = 'TS-';
        $sql=$this->prepare("select ifnull(max(ts_no),0) as max from WS_TS ");
        $sql->execute();
        $maxTSNo = $sql->fetchAll()[0]['max'];
        $runningNo = '';
        if($maxTSNo=='0') {
            $runningNo = '0001';
        } else {
            $latestRunningNo = (int) substr($maxTSNo, 3) + 1;
            if(strlen($latestRunningNo)==4) {
                $runningNo = $latestRunningNo;
            } else {
                for ($x = 1; $x <= 4 - strlen($latestRunningNo); $x++) {
                    $runningNo .= '0';
                }
                $runningNo .= $latestRunningNo;
            }
        }
        return $tsPrefix.$runningNo;
    }
   
    private function assignWI() {
        $wiPrefix = 'WI-';
        $sql=$this->prepare("select ifnull(max(iv_no),0) as max from WS_IV ");
        $sql->execute();
        $maxWINo = $sql->fetchAll()[0]['max'];
        $runningNo = '';
        if($maxWINo=='0') {
            $runningNo = '0001';
        } else {
            $latestRunningNo = (int) substr($maxWINo, 3) + 1;
            if(strlen($latestRunningNo)==4) {
                $runningNo = $latestRunningNo;
            } else {
                for ($x = 1; $x <= 4 - strlen($latestRunningNo); $x++) {
                    $runningNo .= '0';
                }
                $runningNo .= $latestRunningNo;
            }
        }
        return $wiPrefix.$runningNo;
    }
    private function assignWF() {
        $wfPrefix = 'WF-';
        $sql=$this->prepare("select ifnull(max(form_no),0) as max from WS_Form ");
        $sql->execute();
        $maxWFNo = $sql->fetchAll()[0]['max'];
        $runningNo = '';
        if($maxWFNo=='0') {
            $runningNo = '0001';
        } else {
            $latestRunningNo = (int) substr($maxWFNo, 3) + 1;
            if(strlen($latestRunningNo)==4) {
                $runningNo = $latestRunningNo;
            } else {
                for ($x = 1; $x <= 4 - strlen($latestRunningNo); $x++) {
                    $runningNo .= '0';
                }
                $runningNo .= $latestRunningNo;
            }
        }
        return $wfPrefix.$runningNo;
    }
    public function getWsForm($form_no) {
        
        $sql = $this->prepare("select * from WS_Form where form_no = ?");
        $sql->execute([$form_no]);
        
        if ($sql->rowCount()>0) {
            $data = $sql->fetchAll()[0];
    		header('Content-type: '.$data['form_type']);
            echo $data['form_data'];
        } else {
            echo 'ไม่มีใบเบิกค่าใช้จ่ายของเลข WS นี้';
        }
        
    }
    public function getWsIv($iv_no) { 
        
        $sql = $this->prepare("select * from WS_IV where iv_no = ?");
        $sql->execute([$iv_no]);
        
        if ($sql->rowCount()>0) {
            $data = $sql->fetchAll()[0];
    		header('Content-type: '.$data['iv_type']);
            echo $data['iv_data'];
        } else {
            echo 'ไม่มีใบกำกับภาษีของเลข WS นี้';
        }
        
    }
    public function getWsIv2($iv_no) {
        
        $sql = $this->prepare("select * from WS_IV where iv_no = ?");
        $sql->execute([$iv_no]);
        
        if ($sql->rowCount()>0) {
            $data = $sql->fetchAll()[0];
    		header('Content-type: '.$data['iv2_type']);
            echo $data['iv2_data'];
        } else {
            echo 'ไม่มีใบกำกับภาษีของเลข WS นี้';
        }
        
    }   
    
    // Dashboard Module
    public function getDashboard() {
        // $sql = $this->prepare("select
        //                             SOXPrinting.sox_no,
        //                             SOXPrinting.so_no,
        //                             SOX.sox_no,
        //                             SOX.sox_datetime,
        //                             SOX.total_sales_price,
        //                             Invoice.invoice_no,
        //                             Invoice.file_no,
        //                             Invoice.invoice_date,
        //                             Invoice.invoice_time,
        //                             CR.approved_employee,
        //                             Employee.employee_nickname_thai,
        //                             total_sales.total_sales,
        //                             total_sales1.total_sales1,
        //                             total_sales2.total_sales2,
        //                             total_sales3.total_sales3
        //                         from (select sum(total_sales_price) as total_sales from Invoice where cancelled = 0) as total_sales,
        //                         (select sum(total_sales_price) as total_sales1 from Invoice where invoice_no like '%1IV-%' and cancelled = 0) as total_sales1, 
        //                         (select sum(total_sales_price) as total_sales2 from Invoice where invoice_no like '%2IV-%' and cancelled = 0) as total_sales2, 
        //                         (select sum(total_sales_price) as total_sales3 from Invoice where invoice_no like '%3IV-%' and cancelled = 0) as total_sales3, SOX
        //                         inner join SOXPrinting on SOX.sox_no = SOXPrinting.sox_no
        //                         inner join Invoice on SOXPrinting.so_no = Invoice.file_no
        //                         left join CR on Invoice.cr_no = CR.cr_no
        //                         inner join Employee on Employee.employee_id = CR.approved_employee
        //                         order by Invoice.invoice_date desc, Invoice.invoice_time desc");
        
        // $sql = $this->prepare("select
        //                             SOXPrinting.sox_no,
        //                             SOXPrinting.so_no,
        //                             SOX.sox_no,
        //                             SOX.sox_datetime,
        //                             SOX.total_sales_price,
        //                             Invoice.invoice_no,
        //                             Invoice.file_no,
        //                             Invoice.invoice_date,
        //                             Invoice.invoice_time,
        //                             Invoice.approved_employee,
        //                             Employee.employee_nickname_thai,
        //                             total_sales.total_sales,
        //                             total_sales1.total_sales1,
        //                             total_sales2.total_sales2,
        //                             total_sales3.total_sales3
        //                         from (select sum(total_sales_price) as total_sales from Invoice where cancelled = 0) as total_sales,
        //                         (select sum(total_sales_price) as total_sales1 from Invoice where invoice_no like '%1IV-%' and cancelled = 0) as total_sales1, 
        //                         (select sum(total_sales_price) as total_sales2 from Invoice where invoice_no like '%2IV-%' and cancelled = 0) as total_sales2, 
        //                         (select sum(total_sales_price) as total_sales3 from Invoice where invoice_no like '%3IV-%' and cancelled = 0) as total_sales3, SOX
        //                         inner join SOXPrinting on SOX.sox_no = SOXPrinting.sox_no
        //                         inner join Invoice on SOXPrinting.so_no = Invoice.file_no
        //                         inner join Employee on Employee.employee_id = Invoice.approved_employee
        //                         order by Invoice.invoice_date desc, Invoice.invoice_time desc");
        
        $sql = $this->prepare("select
                                    SOXPrinting.sox_no,
                                    SOXPrinting.so_no,
                                    SOX.sox_no,
                                    SOX.sox_datetime,
                                    SOX.total_sales_price,
                                    Invoice.invoice_no,
                                    Invoice.file_no,
                                    Invoice.invoice_date,
                                    Invoice.invoice_time,
                                    Invoice.approved_employee,
                                    Employee.employee_nickname_thai
                                from SOX
                                inner join SOXPrinting on SOX.sox_no = SOXPrinting.sox_no
                                inner join Invoice on SOXPrinting.so_no = Invoice.file_no
                                inner join Employee on Employee.employee_id = Invoice.approved_employee
                                where SOX.cancelled = 0
                                order by Invoice.invoice_date desc, Invoice.invoice_time desc");
                                                         
        $sql->execute();
        if ($sql->rowCount()>0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return json_encode([]);
    }
	
	public function getTR($tr_no=NULL) {
		if ($tr_no==NULL){
			$sql = $this->prepare("SELECT
                                  CASE
                                    WHEN cr_no LIKE '1%'   THEN 'ยอดค้างโครงการ 1'
                                    WHEN cr_no LIKE '2%'   THEN 'ยอดค้างโครงการ 2'
                                    WHEN cr_no LIKE '3%'   THEN 'ยอดค้างโครงการ 3'
                                END AS project_no
                                , SUM(total_price) AS total
                                FROM CR
                                WHERE cancelled='0'AND tr_no IS NULL
                                GROUP BY 
                                  CASE
                                    WHEN cr_no LIKE '1%'   THEN 'ยอดค้างโครงการ 1'
                                    WHEN cr_no LIKE '2%'   THEN 'ยอดค้างโครงการ 2'
                                    WHEN cr_no LIKE '3%'   THEN 'ยอดค้างโครงการ 3'
                                  END 
                                 UNION ALL
								SELECT
                                 'ยอดค้างรวม' name
                                , SUM(total_price) AS total
                                FROM CR
                                WHERE cancelled='0'AND tr_no IS NULL");   
			$sql->execute();
		} else{
			$sql = $this->prepare("select
                                    TR.tr_no,
                                    TR.tr_date,
                                    TR.tr_time,
                                    TR.1_total_price as tot1,
                                    TR.2_total_price as tot2,
                                    TR.3_total_price as tot3,
                                    TR.total_price as tot,
                                    TR.approved_employee
                                from TR
								WHERE TR.tr_no = ? AND  cancelled='0'");
			$sql->execute([$tr_no]);
		}                                                 
        if ($sql->rowCount()>0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return json_encode([]);
    }
	public function get_TR_range($tr_no=NULL){
		if ($tr_no==NULL){
			$sql = $this->prepare("SELECT
                                  CASE
                                    WHEN cr_no LIKE '1%'   THEN '1'
                                    WHEN cr_no LIKE '2%'   THEN '2'
                                    WHEN cr_no LIKE '3%'   THEN '3'
                                END AS project_no
                                , MIN(cr_no) as min_cr
                                , MAX(cr_no) AS max_cr
                                FROM CR
                                WHERE tr_no IS NULL
                                GROUP BY 
                                  CASE
                                    WHEN cr_no LIKE '1%'   THEN '1'
                                    WHEN cr_no LIKE '2%'   THEN '2'
                                    WHEN cr_no LIKE '3%'   THEN '3'
                                  END");   
			$sql->execute();
		} else{
			$sql = $this->prepare("SELECT
                                  CASE
                                    WHEN cr_no LIKE '1%'   THEN '1'
                                    WHEN cr_no LIKE '2%'   THEN '2'
                                    WHEN cr_no LIKE '3%'   THEN '3'
                                END AS project_no
                                , MIN(cr_no) as min_cr
                                , MAX(cr_no) AS max_cr
                                FROM CR
                                WHERE tr_no = ?
                                GROUP BY 
                                  CASE
                                    WHEN cr_no LIKE '1%'   THEN '1'
                                    WHEN cr_no LIKE '2%'   THEN '2'
                                    WHEN cr_no LIKE '3%'   THEN '3'
                                  END");
			$sql->execute([$tr_no]);
		}                                                 
        if ($sql->rowCount()>0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return json_encode([]);
	}
	public function get_TR_note($tr_no=NULL){
       if ($tr_no==NULL){
			$sql = $this->prepare("SELECT cr_no,total_price from CR WHERE cancelled = 1 AND tr_no IS NULL");   
			$sql->execute();
		} else{
			$sql = $this->prepare("SELECT 
									TRPrinting.tr_no,
									TRPrinting.cr_no,
									TRPrinting.details,
									CR.total_price 
									FROM TRPrinting 
									LEFT JOIN 
									CR ON TRPrinting.cr_no = CR.cr_no 
									WHERE TRPrinting.details IS NOT NULL 
									AND TRPrinting.tr_no = ?");
			$sql->execute([$tr_no]);
		}                                                 
        if ($sql->rowCount()>0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return json_encode([]);
	}
	public function get_TR_list(){
		$sql = $this->prepare("select
                                    TR.tr_no,
                                    TR.tr_date,
                                    TR.tr_time,
                                    TR.1_total_price as tot1,
                                    TR.2_total_price as tot2,
                                    TR.3_total_price as tot3,
                                    TR.total_price as tot,
                                    TR.approved_employee
                                from TR");
                                                         
        $sql->execute();
        if ($sql->rowCount()>0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return json_encode([]);
    }
	public function addTR(){
		$tr_no= $this -> assignTR();
		$sql = $this -> prepare("UPDATE CR set tr_no = ? WHERE tr_no IS NULL");
		$sql -> execute([$tr_no]);
		$sql = $this -> prepare("SELECT cr_no,cancelled,total_price FROM CR WHERE tr_no=?");
		$sql -> execute([$tr_no]);
		$cr_list = $sql->fetchAll();
		foreach ($cr_list as $cr){
			$sql = $this -> prepare("INSERT INTO TRPrinting (tr_no,cr_no,details) VALUES(?,?, ?)");
			if ($cr["cancelled"]==1){
				$detail="ยกเลิก";
				$sql -> execute([$tr_no,$cr["cr_no"],$detail]);
			} else{
				$detail=NULL;
				$sql -> execute([$tr_no,$cr["cr_no"],$detail]);
				$sql = $this -> prepare("SELECT CR.cr_no,SO.payment FROM CR
									left join Invoice on CR.cr_no=Invoice.cr_no
									left join SO on Invoice.file_no=SO.so_no
									where CR.cr_no=?");
				$sql -> execute([$cr["cr_no"]]);
				$so_no = $sql->fetchAll();
				
				foreach ($so_no as $so){
					$payment=$so["payment"];
				}
				
				if ($payment=='1'){
					$sql = $this -> prepare("INSERT INTO AccountDetail (file_no,sequence,date,time,account_no,debit,credit,cancelled,note)
								VALUES(?,5, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP,?,?,0,0,'CR')");
					$p_num='12-1'.substr($cr["cr_no"],0,1).'00';
					$sql -> execute([$cr["cr_no"],$p_num,$cr["total_price"]]);
					$sql = $this -> prepare("INSERT INTO AccountDetail (file_no,sequence,date,time,account_no,debit,credit,cancelled,note)
									VALUES(?,6, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP,'12-0000',0,?,0,'CR')");
					$sql -> execute([$cr["cr_no"],$cr["total_price"]]);
				} else {
					$sql = $this -> prepare("INSERT INTO AccountDetail (file_no,sequence,date,time,account_no,debit,credit,cancelled,note)
								VALUES(?,1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP,?,?,0,0,'CR')");
					$p_num='12-1'.substr($cr["cr_no"],0,1).'00';
					$sql -> execute([$cr["cr_no"],$p_num,$cr["total_price"]]);
					$sql = $this -> prepare("INSERT INTO AccountDetail (file_no,sequence,date,time,account_no,debit,credit,cancelled,note)
									VALUES(?,2, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP,'12-0000',0,?,0,'CR')");
					$sql -> execute([$cr["cr_no"],$cr["total_price"]]);
				}
				
			}		
		}
		$sql = $this -> prepare("SELECT
                                  CASE
                                    WHEN cr_no LIKE '1%'   THEN '1'
                                    WHEN cr_no LIKE '2%'   THEN '2'
                                    WHEN cr_no LIKE '3%'   THEN '3'
                                END AS project_no
                                , SUM(total_price) AS total
                                FROM CR
                                WHERE cancelled='0'AND tr_no = ?
                                GROUP BY 
                                  CASE
                                    WHEN cr_no LIKE '1%'   THEN '1'
                                    WHEN cr_no LIKE '2%'   THEN '2'
                                    WHEN cr_no LIKE '3%'   THEN '3'
                                  END");
		$sql -> execute([$tr_no]);
		$sales=  $sql->fetchAll();
		foreach ($sales as $sale){
			if ($sale["project_no"] == "1"){
				$total_price1=$sale["total"];
			} elseif ($sale["project_no"] == "2"){
				$total_price2=$sale["total"];
			} elseif ($sale["project_no"] == "3"){
				$total_price3=$sale["total"];
			}
		}
		$sql = $this -> prepare("INSERT INTO TR
									(tr_no, tr_date, tr_time, 1_total_price, 2_total_price, 3_total_price, total_price, approved_employee, cancelled) VALUES (?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?, ?, 0)");
		$sql -> execute([
			$tr_no,
			$total_price1,
			$total_price2,
			$total_price3,
			$total_price1+$total_price2+$total_price3,
			json_decode(session::get('employee_detail'), true)['employee_id']
		]);
	}
    private function assignTR() {
        $sql=$this->prepare("select ifnull(max(tr_no),0) as max from TR");
        $sql->execute();
        $maxTRNo = $sql->fetchAll()[0]['max'];
        $runningNo = '';
        if($maxTRNo=='0') {
            $runningNo = '00001';
        } else {
            $latestRunningNo = (int) substr($maxTRNo, 4) + 1;
            if(strlen($latestRunningNo)==5) {
                $runningNo = $latestRunningNo;
            } else {
                for ($x = 1; $x <= 5 - strlen($latestRunningNo); $x++) {
                    $runningNo .= '0';
                }
                $runningNo .= $latestRunningNo;
            }
        }
        return 'XTR-'.$runningNo;
    }
    public function updateSalesPro5($so_no, $total_sales_price, $employee_id) {
        
        $sql = $this->prepare("select count(*) as countInvoice from Invoice 
                                inner join SO on SO.so_no = Invoice.file_no 
                                where so_no = ? and SO.cancelled = 0 and SO.so_date in ('2020-07-13','2020-07-14','2020-07-15','2020-07-16','2020-07-17','2020-07-18')");
        $sql->execute([$so_no]);
        $temp1 = $sql->fetchAll()[0];
        
        if ($temp1['countInvoice'] != 0) {
        
            $sql = $this->prepare("select level, sales, used from PromotionWeek5_1 where employee_id = ?");
            $sql->execute([$employee_id]);
            $temp = $sql->fetchAll()[0];
            
            if($temp['sales'] < 9 && $temp['used'] < 9) {
                if($temp['level'] == 1) {
                    $token = ($total_sales_price >= 250) ? 1 : 0;
                } else if($temp['level'] == 2) {
                    $token = ($total_sales_price >= 500) ? 1 : 0;
                } else if($temp['level'] == 3) {
                    $token = ($total_sales_price >= 1000) ? 1 : 0;
                } else if($temp['level'] == 4) {
                    $token = ($total_sales_price >= 2000) ? 1 : 0;
                } else if($temp['level'] == 5) {
                    $token = ($total_sales_price >= 10000) ? 1 : 0;
                } else {
                    $token = 0;
                }
            } else {
                $token = 0;
            }
            
            if($token != 0) {
                $sql = $this->prepare("update PromotionWeek5_1 set sales = sales + 1 where employee_id = ?");
                $sql->execute([$employee_id]);
            }
        
        }
        
    }
    
    // //Promotion Week 6 HorseStabbing
    // private function promotionWeek6($sono, $employee_id, $product_no, $total_sales_price){
        
    //     $productLine = $product_no[0];
        
    //     //First Item in Line - Token
    //     $sql = $this->prepare("select count(InvoicePrinting.total_sales_price) as total_rows from Invoice 
    //         join InvoicePrinting on Invoice.invoice_no = InvoicePrinting.invoice_no 
    //         where Invoice.employee_id = ? and substring(InvoicePrinting.product_no,1,1) = ? 
    //         and Invoice.cancelled = 0");
    //     $sql->execute([$employee_id, $productLine]);
        
    //     $total_rows = $sql->fetchAll()[0]['total_rows'];
        
    //     if($total_rows == 0 ){
    //         $note = 'Week6 - Line '.$productLine;
    //         $sql = $this->prepare("Update HorseStabbing set token = token + 2 where employee_id = ?;");
    //             $sql->execute([$employee_id]);
    //         }
            
    //     //Sales >= 999 First Time - LP
    //     $sql = $this->prepare("select total_sales_price as Check_Total from SO where so_no = ?");
    //     $sql->execute([$so_no]);
    //     $CheckSales = $sql->fetchAll()[0]['Check_Total'];
        
    //     $sql = $this->prepare("select count(remark) as Remark from PointLog where employee_id = ? and remark = 'PromotionWeek 6 - FirstMoreThan 999' and cancelled = 0 and ");
    //     $sql->execute([$employee_id]);
    //     $Check999 = $sql->fetchAll()[0]['Remark'];   
        
    //     if($Check999 == 0 and $CheckSales >= 999) {
    //         $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note, cancelled) 
    //             values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, 99, 'PromotionWeek 6 - FirstMoreThan 999', ?, 0)");
    //             $sql->execute([$employee_id, $sono]);          
    //     }
        
    //     //Token For Every 999 - Token
    //         //Update Total Sales
    //     $sql = $this->prepare("Update HorseStabbing set total_sales = total_sales + ? where employee_id = ?;");
    //     $sql->execute([$total_sales_price, $employee_id]);
    //         //Update Token and Used Sales
    //     $sql = $this->prepare("Update HorseStabbing set token = token + (((total_sales - used_sales) - ((total_sales - used_sales) % 999)) / 999),
    //                                 used_sales = used_sales + ((total_sales - used_sales) - ((total_sales - used_sales) % 999))
    //                                 where employee_id = ?;");
    //     $sql->execute([$employee_id]);            
            
    // }  
    
    // //Promotion Week 7 SlotMachine
    // private function promotionWeek7_Slot($employee_id, $CheckToken){
    //     $token = 0;
    //     if($CheckToken >= 500) {$token = 5;}
    //     else if ($CheckToken >= 400) {$token = 4;}
    //     else if ($CheckToken >= 300) {$token = 3;}
    //     else if ($CheckToken >= 200) {$token = 2;}
    //     else if ($CheckToken >= 100) {$token = 1;}
    //     //update token
    //     $sql = $this->prepare("Update SlotMachine set slot_token = ? where employee_id = ?;");
    //     $sql->execute([$token, $employee_id]);            
    // }
    
    // //Promotion Week 7 C(B)ASINO
    // private function promotionWeek7($sono, $employee_id, $product_no, $total_sales_price){
        
    //     $productLine = $product_no[0];
        
    //     //First Item in Line - Token
    //     $sql = $this->prepare("select count(InvoicePrinting.total_sales_price) as total_rows from Invoice 
    //                             join InvoicePrinting on Invoice.invoice_no = InvoicePrinting.invoice_no 
    //                             where Invoice.employee_id = ? and substring(InvoicePrinting.product_no,1,1) = ? 
    //                             and Invoice.cancelled = 0");
    //     $sql->execute([$employee_id, $productLine]);
        
    //     $total_rows = $sql->fetchAll()[0]['total_rows'];
        
    //     if($total_rows == 0 ){
    //         $note = 'Week7 - Line '.$productLine;
    //         $sql = $this->prepare("update PromotionWeek7 set token = token + 30 where employee_id = ?");
    //         $sql->execute([$employee_id]);
    //     }
        
    //     //Token For Every 999 - Token
    //     //Update Total Sales
    //     $sql = $this->prepare("update PromotionWeek7 set total_sales = total_sales + ? where employee_id = ?");
    //     $sql->execute([$total_sales_price, $employee_id]);
        
    //     $sql = $this->prepare("select * from View_SPRank where employee_id = ?");
    //     $sql->execute([$employee_id]);
        
    //     $point = $sql->fetchAll()[0]['point'];
        
    //     if($point <= 500) {
    //         $a = 50;
    //     } else if($point <= 750) {
    //         $a = 25;
    //     } else {
    //         $a = 15;
    //     }  
        
    //     //Update Token and Used Sales
    //     $sql = $this->prepare("update PromotionWeek7 set token = token + (((total_sales - used_sales) - ((total_sales - used_sales) % 1000)) / 1000) * ?,
    //                                 used_sales = used_sales + ((total_sales - used_sales) - ((total_sales - used_sales) % 1000))
    //                                 where employee_id = ?;");
    //     $sql->execute([$a, $employee_id]);            
            
    // }  
    
    // //เพิ่ม goody point ใน log 2 and add token
    // private function updatePointGoody($so_no, $employee_id){
    //     $sql = $this->prepare(" Update goody_point_log 
    //                             join SO on SO.so_date = goody_point_log.datetime 
    //                             set goody_point_log.point = goody_point_log.percent/10 
    //                             where SO.so_no = ? and goody_point_log.employee_id = ?;
                                
    //                             Update goody_point_log_2  
    //                             join goody_point_log on goody_point_log_2.employee_id = goody_point_log.employee_id 
    //                             set goody_point_log_2.total_point = goody_point_log_2.total_point + goody_point_log.percent/10
    //                             where goody_point_log_2.employee_id = ?;
                                
    //                             Update goody_point_log_2 
    //                             join PromotionWeek7 on PromotionWeek7.employee_id = goody_point_log_2.employee_id
    //                             set  PromotionWeek7.token = PromotionWeek7.token + 
    //                                 (((goody_point_log_2.total_point - goody_point_log_2.used_point_token) - ((goody_point_log_2.total_point - goody_point_log_2.used_point_token) % 5)) / 5) * 10,
    //                             goody_point_log_2.used_point_token = goody_point_log_2.used_point_token + 
    //                                 ((goody_point_log_2.total_point - goody_point_log_2.used_point_token) - ((goody_point_log_2.total_point - goody_point_log_2.used_point_token) % 5))
    //                             where goody_point_log_2.employee_id = ? and PromotionWeek7.employee_id = goody_point_log_2.employee_id;");
    //     $sql->execute([$so_no, $employee_id, $employee_id, $employee_id]);
    // }
    
    //เพิ่ม goody point ใน log 2 and add token
    private function updatePointGoody($so_no, $employee_id){
        $sql = $this->prepare(" Update goody_point_log 
                                join SO on SO.so_date = goody_point_log.datetime 
                                set goody_point_log.point = goody_point_log.percent/10 
                                where SO.so_no = ? and goody_point_log.employee_id = ?;
                                
                                Update goody_point_log_2  
                                join goody_point_log on goody_point_log_2.employee_id = goody_point_log.employee_id 
                                set goody_point_log_2.total_point = goody_point_log_2.total_point + goody_point_log.percent/10
                                where goody_point_log_2.employee_id = ?;");
        $sql->execute([$so_no, $employee_id, $employee_id]);
    }

public function getWsFormList() {
$sql = $this->prepare("select form_no from WS_Form ");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return $sql->fetchAll();
        }
        return null;
}

public function showWsForm($form_no) {
$sql = $this->prepare("select * from WS_Form where form_no = ?");  
        $sql->execute([$form_no]);
        if ($sql->rowCount() > 0) {
            $data = $sql->fetchAll()[0];
    		header('Content-type: '.$data['form_type']);
            echo $data['form_data'];
        } else {
            echo '';
        }
}
	public function getSalesReport() {
        $sql = $this->prepare("select
                                    SO.so_no,
                                    Week.week as so_week,
                                    SO.so_date,
                                    SO.so_time,
                                    Product.product_line,
                                    Product.product_no,
                                    Product.product_name,
                                    ProductCategory.category_name,
                                    Product.sub_category,
                                    Supplier.supplier_name,
                                    SOPrinting.quantity,
                                    SOPrinting.sales_no_vat * SOPrinting.quantity as total_no_vat,
                                    SOPrinting.total_sales,
                                    SOPrinting.total_point,
                                    SOPrinting.total_commission,
                                    SOPrinting.margin,
                                    concat(Employee.employee_id, ' ', Employee.employee_nickname_thai) as sp,
                                    Employee.ce_id as ce
                                from SOPrinting
                                inner join SO on SO.so_no = SOPrinting.so_no
                                inner join Employee on Employee.employee_id = SO.employee_id
                                left join Product on Product.product_no = SOPrinting.product_no
                                left join ProductCategory on ProductCategory.category_no = Product.category_no and ProductCategory.product_line = Product.product_line
                                inner join Supplier on Supplier.supplier_no = Product.supplier_no and Supplier.product_line = Product.product_line
                                inner join Week on Week.date = SO.so_date
                                where SOPrinting.cancelled = 0 and not ProductCategory.category_name like '%ส่ง%'");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return $sql->fetchAll();
        }
        return [];
    }

    //confirm petty cash request (pre pva)
    public function getMinorRequestForFin() {
        $sql = $this->prepare("SELECT internal_pva_no, pv_date, pv_time, employee_id, employee_name, line_id, product_names, total_paid, bank_name, bank_no from PVA where pv_status = 0");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return $sql->fetchAll();
        }
        return [];
    }
    
    public function getRe($internal_pva_no) {
        $sql = $this->prepare("select ivrc_type,ivrc_data from PVA where internal_pva_no = ?");
        $sql->execute([$internal_pva_no]);
        
        if ($sql->rowCount()>0) {
            $data = $sql->fetchAll()[0];
    		header('Content-type: '.$data['ivrc_type']);
            echo base64_decode($data['ivrc_data']);
        } else {
            echo 'ไม่มีใบกำกับภาษีของคำขอนี้';
        }
    }

    public function getIv($internal_pva_no) {
        $sql = $this->prepare("select slip_data,slip_type from PVA where internal_pva_no = ?");
        $sql->execute([$internal_pva_no]);
        
        if ($sql->rowCount()>0) {
            $data = $sql->fetchAll()[0];
            header('Content-type: '.$data['slip_type']);
            echo base64_decode($data['slip_data']);
        } else {
            echo 'ไม่มีใบกำกับภาษีของคำขอนี้';
        }
    }

    public function confirmPettyCashRequest(){

        $fin_slip_file_name = $_FILES['slip_file']['name'];
        $fin_slip_file_data = base64_encode(file_get_contents($_FILES['slip_file']['tmp_name']));
        $fin_slip_file_type = $_FILES['slip_file']['type'];

        $sql = $this->prepare("UPDATE PVA SET 
                                fin_slip_name = ?,
                                fin_slip_data = ?,
                                fin_slip_type = ?,
                                pv_status = 1 
                               WHERE internal_pva_no = ?");
        $success = $sql->execute([$fin_slip_file_name,$fin_slip_file_data,$fin_slip_file_type,$_POST['internal_pva_no']]);
        if($success) {
            echo 'success';
        } else {
            echo 'failed';
            print_r($sql->errorInfo());
        }
    }

    public function rejectPettyCashRequest(){
        $sql = $this->prepare("UPDATE PVA SET pv_status = -1 WHERE internal_pva_no = ?");
        $success = $sql->execute([$_POST['internal_pva_no']]);
        if($success) {
            echo 'success';
        } else {
            echo 'failed';
            print_r($sql->errorInfo());
        }
    }

    //bundle pva
    public function getPVAForCreation(){
        $sql = $this->prepare("SELECT internal_pva_no,pv_date,pv_time,employee_id,employee_name,line_id,product_names,total_paid,fin_slip_name,slip_name,ivrc_name from PVA where pv_status = 1");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return json_encode([]);
    }

    public function getFinSlipPVA($internal_pva_no){
        $sql = $this->prepare("SELECT fin_slip_data,fin_slip_type from PVA where internal_pva_no = ?");
        $sql->execute([$internal_pva_no]);
        
        if ($sql->rowCount()>0) {
            $data = $sql->fetchAll()[0];
            header('Content-type: '.$data['fin_slip_type']);
            echo base64_decode($data['fin_slip_data']);
        } else {
            echo 'หาใบไม่เจอ';
            print_r($sql->errorInfo());
        }
    }

    public function bundlePVA() {
        $pvas = $_POST['cpvItems'];
        $success = true;
        //$pva_no = $this->assignPVA($_POST['program']);
        $internal_bundle_no = $this->assignInternalBundleNo();
        $product_names = '';
        $total_paid = 0;

        foreach($pvas as $pva) {
            $sql = $this->prepare("UPDATE PVA SET 
                                 internal_bundle_no = ?,
                                 pv_status = 2 
                                WHERE internal_pva_no = ?");
            $success = $success && $sql->execute([$internal_bundle_no,$pva['internal_pva_no']]);
            $product_names = $product_names . $pva['product_names'] . "\r\n";
            $total_paid = $total_paid + $pva['total_paid'];
            if(!$success) break;
        }

        if($success) {
            $sql = $this->prepare("INSERT INTO PVA_bundle (internal_bundle_no,pv_date,pv_time,total_paid,product_names,pv_status) VALUES (?,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,?,?,2)");
            $success = $success && $sql->execute([$internal_bundle_no,$total_paid,$product_names]);
        }
        
        if($success) {
            echo $internal_bundle_no;
        } else {
            echo 'failed'; 
            print_r($sql->errorInfo());          
            foreach($pvas as $pva) {
                $sql = $this->prepare("UPDATE PVA SET 
                                     internal_bundle_no = null,
                                     pv_status = 1 
                                    WHERE internal_pva_no = ?");
                $sql->execute([$pva['internal_pva_no']]); //set pre pva back
            }
        }
    }

    private function assignInternalBundleNo() {
        if($scope.company_pva == '') {
            
        }
        $rqPrefix = 'exb-';
        $sql = $this->prepare( "select ifnull(max(internal_bundle_no),0) as max from PVA where internal_bundle_no like ?" );
        $sql->execute( [ 'exb-%' ] );
        $maxRqNo = $sql->fetchAll()[ 0 ][ 'max' ];
        $runningNo = '';
        if ( $maxRqNo == '0' ) {
            $runningNo = '00001';
        } else {
            $latestRunningNo = ( int )substr( $maxRqNo, 4 ) + 1;
            if ( strlen( $latestRunningNo ) == 5 ) {
                $runningNo = $latestRunningNo;
            } else {
                for ( $x = 1; $x <= 5 - strlen( $latestRunningNo ); $x++ ) {
                    $runningNo .= '0';
                }
                $runningNo .= $latestRunningNo;
            }
        }
        return $rqPrefix . $runningNo;
    }

    // private function assignPVA($program) {
    //     $rqPrefix = $program.'PA-';
    //     $sql = $this->prepare( "select ifnull(max(pv_no),0) as max from PVA where pv_no like ?" );
    //     $sql->execute( [ $program.'PA-%' ] );
    //     $maxRqNo = $sql->fetchAll()[ 0 ][ 'max' ];
    //     $runningNo = '';
    //     if ( $maxRqNo == '0' ) {
    //         $runningNo = '00001';
    //     } else {
    //         $latestRunningNo = ( int )substr( $maxRqNo, 4 ) + 1;
    //         if ( strlen( $latestRunningNo ) == 5 ) {
    //             $runningNo = $latestRunningNo;
    //         } else {
    //             for ( $x = 1; $x <= 5 - strlen( $latestRunningNo ); $x++ ) {
    //                 $runningNo .= '0';
    //             }
    //             $runningNo .= $latestRunningNo;
    //         }
    //     }
    //     return $rqPrefix . $runningNo;
    // }

    //real pva now

    public function GetPVAforWS() {
        $sql = $this->prepare("SELECT pv_no,product_names,total_paid from PVA_bundle where pv_status = 3 ORDER BY pv_no ASC");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return json_encode([]);
    }

        public function postSlipPVA() {
        $slip_name = $_FILES['slip_file']['name'];
        $slip_data = base64_encode(file_get_contents($_FILES['slip_file']['tmp_name']));
        $slip_type = $_FILES['slip_file']['type'];

        $sql = $this->prepare("UPDATE PVA_bundle SET 
                                slip_name = ?,
                                slip_data = ?,
                                slip_type = ?,
                                pv_status = 4 
                               WHERE pv_no = ?");
        $success = $sql->execute([$slip_name,$slip_data,$slip_type,$_POST['pv_no']]);

        if($success){
            $sql = $this->prepare("UPDATE PVA SET 
                                    pv_status = 4 
                                   WHERE pv_no = ?");
            $success = $success && $sql->execute([$_POST['pv_no']]);
        }
        if($success) {
            echo 'success';
        } else {
            echo 'failed';
            print_r($sql->errorInfo());
        }
    }

    public function getPVD() {
        $sql = $this->prepare("SELECT
                                PVD.pvd_no,
                                PVD.pvd_time,
                                PVD.pvd_date,
                                PVD.total_amount,
                                PVD.invoice_no

                                From PVD
                                where PVD.PVD_status = 2
                                order by PVD.pvd_date, PVD.pvd_time");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return null;
    }




    public function confirmPVD() {
        $pvd_no = $_POST['pvd_no'];
        $fileName = $_FILES['slip_file']['name'];
        $fileData = base64_encode(file_get_contents($_FILES['slip_file']['tmp_name']));
        $fileType = $_FILES['slip_file']['type'];

        $sql = $this->prepare("UPDATE PVD SET 
                                PVD_status = 3,
                                slipData = ?,
                                slipName = ?,
                                slipType = ?
                                where pvd_no  = ? ");

        $success = $sql->execute([$fileData, $fileName, $fileType,$pvd_no]);
        
        if($success) echo 'success';  
        else echo implode(" ",$sql->errorInfo());
    }
    public function getPVCs(){
        $sql = $this->prepare("SELECT * FROM PVC WHERE slip_name IS NULL");
        $sql-> execute();
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return null;
    }
    public function addSlipToPVC($pv_no){
        $slip_name = $_FILES['file']['name'];
        $slip_data = base64_encode(file_get_contents($_FILES['file']['tmp_name']));
        $slip_type = $_FILES['file']['type'];

        $sql = $this->prepare("UPDATE PVC SET 
                                slip_name = ?,
                                slip_data = ?,
                                slip_type = ?
                               WHERE pv_no = ?");
        $success = $sql->execute([$slip_name,$slip_data,$slip_type,$pv_no]);

        if($success) {

            echo $_FILES['file'];
              
        } else {
            echo 'failed';
            print_r($sql->errorInfo());
        }
    }
    public function getPVCsForIV(){
        $sql = $this->prepare("SELECT * FROM PVC WHERE  iv_name IS NULL");
        $sql-> execute();
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return null;
    }
        
    public function addIVToPVC($pv_no){
        $iv_name = $_FILES['file']['name'];
        $iv_data = base64_encode(file_get_contents($_FILES['file']['tmp_name']));
        $iv_type = $_FILES['file']['type'];

        $sql = $this->prepare("UPDATE PVC SET 
                                iv_name = ?,
                                iv_data = ?,
                                iv_type = ?
                               WHERE pv_no = ?");
        $success = $sql->execute([$iv_name,$iv_data,$iv_type,$pv_no]);

        if($success) {

            echo $_FILES['file'];
              
        } else {
            echo 'failed';
            print_r($sql->errorInfo());
        }
        
    }

    public function getStatusPva() {
        $sql = $this->prepare("SELECT
                                	pv_no,
                                    pv_time,
                                    pv_date,
                                    total_paid,
                                    product_names,
                                    pv_status
                                from PVA_bundle");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return json_encode([]);
    }

    public function getStatusPrePva() {
        $sql = $this->prepare("select
                                    internal_pva_no, 
                                	pv_no,
                                    pv_time,
                                    pv_date,
                                    total_paid,
                                    product_names,
                                    pv_status
                                from PVA");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return json_encode([]);
    }
}



    
