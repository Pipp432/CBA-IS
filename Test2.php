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
                $sql = $this->prepare("insert into Invoice (invoice_no, invoice_date, invoice_time, employee_id, customer_name, customer_address, id_no, file_no,
                                        file_type, total_sales_no_vat, total_sales_vat, total_sales_price, discount, sales_price_thai, point, commission, approved_employee, cr_no, cancelled, note)
                                        values (?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?, ?, 'SO', ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, ?)");  
                $sql->execute([
                    $iv_no,
                    $value['employee_id'],
                    input::post('cusName'),
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
            
            	echo 'เกิดข้อผิดพลาด รบกวนออก IVCR ใหม่';
               
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