<?php

namespace model;

use _core\model;
use _core\helper\session;
use _core\helper\input;
use _core\helper\uri;
use _core\helper\thaiNum;
use PDO;

class scmModel extends model {
    
    // CS Module
    public function getSoxsForCs() {
        $sql = $this->prepare("select
                                	SOX.sox_no,
                                    SOX.sox_datetime,
                                    SOX.employee_id,
                                    SOX.tracking_number,
									SOX.note,
                                    Employee.employee_nickname_thai,
                                    SOX.total_sales_price as sox_total_sales_price,
                                    SOX.ird_no,
                                	SOXPrinting.product_line,
                                	SOXPrinting.so_no,
                                    Invoice.invoice_no,
                                    Invoice.total_sales_no_vat,
                                    Invoice.total_sales_vat,
                                    Invoice.total_sales_price,
                                    Invoice.commission,
                                    Product.product_no,
                                	Product.product_name,
                                    Product.product_type,
                                    SO.so_no,
                                    SOPrinting.sales_no_vat,
                                    SOPrinting.sales_vat,
                                    SOPrinting.sales_price,
                                    SOPrinting.quantity,
                                    SOPrinting.total_sales
                                from SOX
                                inner join SOXPrinting on SOX.sox_no = SOXPrinting.sox_no
                                inner join SOPrinting on SOXPrinting.so_no = SOPrinting.so_no
                                inner join SO on SOPrinting.so_no = SO.so_no
                                inner join Product on Product.product_no = SOPrinting.product_no
                                inner join Invoice on Invoice.file_no = SOXPrinting.so_no
                                inner join Employee on Employee.employee_id = SOX.employee_id
                                where SOX.done = 0 and SOX.cancelled = 0 and not Product.product_type = 'Install' and not SOX.ird_no = '-' and SOX.tracking_number is not null
								ORDER BY SOX.ird_no, SOX.sox_no ASC");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return json_encode([]);
    }
    
    // CS Module
    public function addCs() {
        
        $csItemsArray = json_decode(input::post('csItems'), true); 
        $csItemsArray = json_decode($csItemsArray, true);
        
        $soxList = array();
        
        foreach($csItemsArray as $value) {
            
            if (array_key_exists($value['invoice_no'], $soxList)) {
                
                $irno = $soxList[$value['invoice_no']];
                
            } else {
                
                $irno = $this->assignIr($value['invoice_no']);
                $soxList += [$value['invoice_no']=>$irno];
                
                // update SOX 
                $sql = $this->prepare("update SOX set done = 1 where sox_no = ?");
                $sql->execute([$value['sox_no']]); 
                
                // insert IRforACC
                //$sql = $this->prepare("insert into IRforACC (ir_no, ir_date, approved_employee, invoice_no, total_sales_no_vat, total_sales_vat, total_sales_price, cancelled, note)
                //                        values (?, CURRENT_TIMESTAMP, ?, ?, ?, ?, ?, 0, null)");
                //$sql->execute([
                //    $irno,
                //    json_decode(session::get('employee_detail'), true)['employee_id'],
                //    $value['invoice_no'],
                //    $value['total_sales_no_vat'],
                //    $value['total_sales_vat'],
                //    $value['total_sales_price']
                //]);
                
                // insert AccountDetail sequence 1
                // Dr รายได้รับล่วงหน้า - โครงการ X
                $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                        values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                $sql->execute([$value['invoice_no'], '4', '24-1'.$value['invoice_no'][0].'00', (double) $value['total_sales_no_vat'], 0, 'IV']);
                    
                    
                // insert AccountDetail sequence 2
                // Cr ขาย - โครงการ X
                $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                        values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                $sql->execute([$value['invoice_no'], '5', '41-1'.$value['invoice_no'][0].'00', 0, (double) $value['total_sales_no_vat'], 'IV']);
                    
                    
                // insert AccountDetail sequence 3
                // Dr ค่า Commission
                $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                        values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                $sql->execute([$value['invoice_no'], '6', '52-1000', (double) $value['commission'], 0, 'IV']);
                    
                    
                // insert AccountDetail sequence 4
                // Cr ค่า Commission ค้างจ่าย
                $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                      values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                $sql->execute([$value['invoice_no'], '7', '22-0000', 0, (double) $value['commission'], 'IV']);

            
                // //13.2
                // $sql = $this->prepare("SELECT
                //                         SO.vat_type
                //                     from Invoice
                //                     left join SO on Invoice.file_no = SO.so_no
                //                     where Invoice.invoice_no = ?");
                // $sql->execute([$value['invoice_no']]);
                // $temp = $sql->fetchAll(PDO::FETCH_ASSOC);
                // $vat_type = intval($temp[0]["vat_type"]);

                // $sql = $this->prepare("SELECT
                //                         Invoice.payment_type
                //                     from Invoice
                //                     where Invoice.invoice_no = ?");
                // $sql->execute([$value['$iv_no']]);
                // $tempp = $sql->fetchAll(PDO::FETCH_ASSOC);
                // $payment_type = $tempp[0]["payment_type"];

                // if($payment_type == 'CC'){

                //     if($vat_type < 3) { //vat type = 1 , 2
                //         $acc_13_2 = (((double) $value['total_sales_price'])*102.45/100) *100/107;
                //         $acc_13_2_3 = ((double) $value['total_sales_price'])*102.45/100;
                //         //dr รายได้รับล่วงหน้า 24-1x00 seq 6 iv
                //         $sql = $this->prepare("INSERT into AccountDetail(file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                //                                     values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                //         $sql->execute([$value['invoice_no'],'6','24-1'.$value['invoice_no'][0].'00',(double) $acc_13_2,0,'IV']);
                //         //cr ขาย 41-1x00 seq 7 iv
                //         $sql = $this->prepare("INSERT into AccountDetail(file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                //                                     values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                //         $sql->execute([$value['invoice_no'],'7','41-1'.$value['invoice_no'][0].'00',0,(double) $acc_13_2,'IV']);

                //     } else  { //vat type = 3
                //         //dr รายได้รับล่วงหน้า 24-1x00 seq 6 iv
                //         $sql = $this->prepare("INSERT into AccountDetail(file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                //                                     values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                //         $sql->execute([$value['invoice_no'],'6','24-1'.$value['invoice_no'][0].'00',(double) $acc_13_2_3,0,'IV']);
                //         //cr ขาย 41-1x00 seq 7 iv
                //         $sql = $this->prepare("INSERT into AccountDetail(file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                //                                     values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                //         $sql->execute([$value['invoice_no'],'7','41-1'.$value['invoice_no'][0].'00',0,(double) $acc_13_2_3,'IV']);
                //     }
                // }

                // if($payment_type == 'FB'){
                //     if($vat_type < 3) { //vat type = 1 , 2
                //         $acc_15_2 = (((double) $value['total_sales_price'])*102.75/100) *100/107;
                //         $acc_15_2_3 = ((double) $value['total_sales_price'])*102.75/100;
                //         //dr รายได้รับล่วงหน้า 24-1x00 seq 6 iv
                //         $sql = $this->prepare("INSERT into AccountDetail(file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                //                                     values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                //         $sql->execute([$value['invoice_no'],'6','24-1'.$value['invoice_no'][0].'00',(double) $acc_15_2,0,'IV']);
                //         //cr ขาย 41-1x00 seq 7 iv
                //         $sql = $this->prepare("INSERT into AccountDetail(file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                //                                     values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                //         $sql->execute([$value['invoice_no'],'7','41-1'.$value['invoice_no'][0].'00',0,(double) $acc_15_2,'IV']);

                //     } else  { //vat type = 3
                //         //dr รายได้รับล่วงหน้า 24-1x00 seq 6 iv
                //         $sql = $this->prepare("INSERT into AccountDetail(file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                //                                     values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                //         $sql->execute([$value['invoice_no'],'6','24-1'.$value['invoice_no'][0].'00',(double) $acc_15_2_3,0,'IV']);
                //         //cr ขาย 41-1x00 seq 7 iv
                //         $sql = $this->prepare("INSERT into AccountDetail(file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                //                                     values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                //         $sql->execute([$value['invoice_no'],'7','41-1'.$value['invoice_no'][0].'00',0,(double) $acc_15_2_3,'IV']);
                //     }
                // }

                // //dr commission 52-0x00 seq 8 iv
                // $sql = $this->prepare("INSERT into AccountDetail(file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                //                             values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                // $sql->execute([$value['invoice_no'] , '8' , '52-0'.$value['invoice_no'][0].'00' , (double) $value['commission'] , 0 , 'IV']);
                // //cr commission ค้างจ่าย 22-1x00 seq 9 iv
                // $sql = $this->prepare("INSERT into AccountDetail(file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                //                             values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                // $sql->execute([$value['invoice_no'] , '9' , '22-1'.$value['invoice_no'][0].'00' , 0 , (double) $value['commission'] , 'IV']);


            }
          
          if ($value['product_no'] != 'X') {
              
              // insert IRPrinting
              $sql = $this->prepare("insert into IRPrinting (ir_no, product_no, sales_no_vat, sales_vat, sales_price, quantity, total_sales_price, cancelled, note)
                                      values (?, ?, ?, ?, ?, ?, ?, 0, null)");
              $sql->execute([$value['ird_no'], $value['product_no'], $value['sales_no_vat'], $value['sales_vat'], $value['sales_price'], $value['quantity'], $value['total_sales']]); 
              
              // insert StockOut
              //$sql = $this->prepare("insert into StockOut (product_no, file_no, file_type, date, time, quantity_out, lot, rr_no)
              //                        values (?, ?, 'IRD2', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, 0, '-')");
              //$sql->execute([$value['product_no'], $value['ird_no'], $value['quantity']]);
              
			}
            
        }
        
    }
    
    // CS Module
    private function assignIr($iv_no) {
        $irPrefix = $iv_no[0].'IR-';
        $sql = $this->prepare("select ifnull(max(ir_no),0) as max from IRforACC where ir_no like ?");
        $sql->execute([$irPrefix.'%']);
        $maxIrNo = $sql->fetchAll()[0]['max'];
        $runningNo = '';
        if($maxIrNo=='0') {
            $runningNo = '00001';
        } else {
            $latestRunningNo = (int) substr($maxIrNo, 4) + 1;
            if(strlen($latestRunningNo) == 5) {
                $runningNo = $latestRunningNo;
            } else {
                for ($x = 1; $x <= 5 - strlen($latestRunningNo); $x++) {
                    $runningNo .= '0';
                }
                $runningNo .= $latestRunningNo;
            }
        }
        return $irPrefix.$runningNo;
    } 

    // CPO Module
    public function getPosForCpo() {
        $sql = $this->prepare("select
                                	PO.po_no,
                                    PO.po_date,
                                    PO.supplier_no,
                                    PO.total_purchase_price as po_total_purchase_price,
                                	POPrinting.product_no,
                                	POPrinting.quantity,
                                    POPrinting.total_purchase_price,
                                	Product.product_name,
                                    Product.purchase_price,
                                    PO.product_line
                                from POPrinting
                                inner join PO on PO.po_no = POPrinting.po_no
                                inner join Product on Product.product_no = POPrinting.product_no
                                where PO.received = -1 and POPrinting.cancelled = 0 and PO.product_type = 'Stock'
                                order by PO.po_no");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return json_encode([]);
    }
    
    // CPO Module
    public function addCpo() {
        
        $cpoItemsArray = json_decode(input::post('cpoItems'), true); 
        $cpoItemsArray = json_decode($cpoItemsArray, true);
        
        $poList = array();
        
        foreach($cpoItemsArray as $cpoItem) {
            
            if (!in_array($cpoItem['po_no'], $poList)) {
                  
                $poList += [$cpoItem['po_no']];
                echo $cpoItem['po_no'].' ';
                
                // update received in PO & POPrinting
                $sql = $this->prepare("update PO inner join POPrinting on POPrinting.po_no = PO.po_no
                                        set PO.received = 0, POPrinting.received = 0 
                                        where PO.po_no = ? and PO.cancelled = 0 and POPrinting.cancelled = 0");                                             
                $sql->execute([$cpoItem['po_no']]);
                
            }
            
        }
        
    }

    public function addadjCpo() {
        
        $cpoItemsArray = json_decode(input::post('cpoItems'), true); 
        $cpoItemsArray = json_decode($cpoItemsArray, true);
        
        $poList = array();
        
        foreach($cpoItemsArray as $cpoItem) {
            
            if (!in_array($cpoItem['po_no'], $poList)) {
                  
                $poList += [$cpoItem['po_no']];
                echo $cpoItem['po_no'].' ';
                
                // update received in PO & POPrinting
                $sql = $this->prepare("update PO inner join POPrinting on POPrinting.po_no = PO.po_no
                                        set POPrinting.quantity = ? 
                                        where PO.po_no = ? and PO.cancelled = 0 and POPrinting.cancelled = 0");                                             
                $sql->execute([$cpoItem['quantity'],$cpoItem['po_no']]);
                
            }
            
        }
        
    }
    
    // RR Module
    public function getPosForRr() {
        $sql = $this->prepare("select
                                	PO.po_no,
                                    PO.po_date,
                                    PO.supplier_no,
                                    Supplier.supplier_name,
                                    PO.total_purchase_no_vat as po_total_purchase_no_vat,
                                    PO.total_purchase_vat as po_total_purchase_vat,
                                    PO.total_purchase_price as po_total_purchase_price,
                                    PO.product_type,
                                	POPrinting.product_no,
                                	POPrinting.so_no,
                                    POPrinting.purchase_no_vat,
                                    POPrinting.purchase_vat,
                                    POPrinting.purchase_price,
                                	POPrinting.quantity,
                                    POPrinting.total_purchase_price,
                                	Product.product_name
                                from POPrinting
                                inner join PO on PO.po_no = POPrinting.po_no
                                inner join Product on Product.product_no = POPrinting.product_no
                                inner join Supplier on Supplier.supplier_no = PO.supplier_no and Supplier.product_line = PO.product_line
                                where POPrinting.received = 0 and POPrinting.cancelled = 0 and PO.product_type in ('Stock','Order')
                                order by PO.po_no");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return json_encode([]);
    }
    
    // RR Module
    public function addRr() {
        
        $rrItemsArray = json_decode(input::post('rrItems'), true); 
        $rrItemsArray = json_decode($rrItemsArray, true);
        
        $poList = array();
        
        foreach($rrItemsArray as $value) {
            
            if (array_key_exists($value['po_no'], $poList)) {
                
                $rr_no = $poList[$value['po_no']];
                
            } else {
                 
                $rr_no = $this->assignRr($value['po_no']);
                $poList += [$value['po_no']=>$rr_no];
                
                // insert RR
                $sql = $this->prepare("insert into RR (rr_no, rr_date, approved_employee, supplier_no, invoice_no, total_purchase_no_vat, total_purchase_vat, total_purchase_price, cancelled, po_no)
                                        values(?, CURRENT_TIMESTAMP, ?, ?, ?, ?, ?, ?, 0, ?)");
                $sql->execute([
                    $rr_no,
                    json_decode(session::get('employee_detail'), true)['employee_id'],
                    $value['supplier_no'],
                    '-',
                    (double) $value['po_total_purchase_no_vat'],
                    (double) $value['po_total_purchase_vat'],
                    (double) $value['po_total_purchase_price'],
                    $value['po_no']
                ]); 
                
                // update received in PO & POPrinting
                $sql = $this->prepare("update PO inner join POPrinting on POPrinting.po_no = PO.po_no
                                        set PO.received = 1, POPrinting.received = 1 
                                        where PO.po_no = ? and PO.cancelled = 0 and POPrinting.cancelled = 0");                                             
                $sql->execute([$value['po_no']]);
                
                if($value['product_type'] == 'Order') {
                    
                    // update received in Invoice
                    $sql = $this->prepare("update InvoicePrinting
                                            inner join Invoice on Invoice.invoice_no = InvoicePrinting.invoice_no
                                            inner join SO on SO.so_no = Invoice.file_no
                                            inner join PO on PO.po_no = SO.po_no
                                            set rr_no = ?
                                            where PO.po_no = ? and SO.so_no = ?");                                             
                    $sql->execute([$rr_no, $value['po_no'], $value['so_no']]);
                    
                }
                
                // ============================================================================================================================================================
                // NEW CBA2020 ACC
                
                // insert AccountDetail sequence 1
                // Dr ซื้อ - โครงการ X
                $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                        values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                $sql->execute([$rr_no, '1', '51-1'.$rr_no[0].'00', (double) $value['po_total_purchase_no_vat'], 0, 'RR']);
                
                // insert AccountDetail sequence 2
                // Cr เจ้าหนี้การค้ารอ Tax Invoice - Supplier XXX
                $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                        values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                $sql->execute([$rr_no, '2', '21-2'.$value['supplier_no'], 0, (double) $value['po_total_purchase_no_vat'], 'RR']);
                
                // ============================================================================================================================================================
                // END CBA2020 ACC
                
                // // insert AccountDetail sequence 1
                // // Dr ซื้อ - โครงการ X
                // $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                //                         values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                // $sql->execute([$rr_no, '1', '51-1'.$rr_no[0].'00', (double) $value['po_total_purchase_no_vat'], 0, 'RR']);
                
                // // insert AccountDetail sequence 2
                // // Cr เจ้าหนี้การค้ารอ Tax Invoice - Supplier XXX
                // $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                //                         values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                // $sql->execute([$rr_no, '2', '21-2'.$value['supplier_no'], 0, (double) $value['po_total_purchase_no_vat'], 'RR']);
                
                // // insert AccountDetail sequence 3
                // // Dr สินค้าคงเหลือ
                // $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                //                         values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                // $sql->execute([$rr_no, '3', '14-0000', (double) $value['po_total_purchase_no_vat'], 0, 'RR']); 
                
                // // insert AccountDetail sequence 4
                // // Cr 14-0001
                // $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                //                         values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                // $sql->execute([$rr_no, '4', '14-0001', 0, (double) $value['po_total_purchase_no_vat'], 'RR']);
                
                echo $rr_no.' ('.$value['po_no'].') ';
                
            }
            
            // insert RRPrinting
            $sql = $this->prepare("insert into RRPrinting (rr_no, so_no, product_no, purchase_no_vat, purchase_vat, purchase_price, quantity, total_purchase_price, cancelled)
                                    values (?, ?, ?, ?, ?, ?, ?, ?, 0)");  
            $sql->execute([
                $rr_no,
                $value['so_no'],
                $value['product_no'],
                (double) $value['purchase_no_vat'],
                (double) $value['purchase_vat'],
                (double) $value['purchase_price'],
                (double) $value['quantity'],
                (double) $value['total_purchase_price']
            ]); 
            
            // insert StockIn
            $sql = $this->prepare("insert into StockIn (product_no, file_no, file_type, date, time, quantity_in, lot) 
                                    select ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, count(*) + 1 from StockIn where product_no = ?");
            $sql->execute([$value['product_no'], $rr_no, 'RR', (double) $value['quantity'], $value['product_no']]); 
            
        }
        
    }  
    
    // RR Module
    private function assignRr($po_no) {
        $rrPrefix = $po_no[0].'RR-';
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
    
    // CS Module
    public function getCsNotConfirmed() {
        $sql = $this->prepare("select
                                	CS.cs_no,
                                    CS.cs_date,
                                    CSLocation.location_name,
                                    CSPrinting.product_no,
                                    Product.product_name,
                                    CSPrinting.quantity,
                                    CS.approved_employee as employee_id,
                                    ce.employee_nickname_thai
                                from CS
                                join CSPrinting on CSPrinting.cs_no = CS.cs_no
                                left join Product on Product.product_no = CSPrinting.product_no
                                left join CSLocation on CSLocation.location_no = CS.location_no
                                left join Employee ce on ce.employee_id = CS.approved_employee
                                where CS.confirmed = 0 and CS.cancelled = 0
                                order by CS.cs_date");
        $sql->execute();
        if ($sql->rowCount()>0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return json_encode([]);
    }
    
    // CS Module
    public function addCounterSalesOut() {
        
        $sql = $this->prepare("update CS set confirmed = 1 where cs_no = ?");
        $sql->execute([input::post('cs_no')]);
        
        $csItemsArray = json_decode(input::post('csItems'), true); 
        $csItemsArray = json_decode($csItemsArray, true);
        
        foreach($csItemsArray as $value) {
            
            $sql = $this->prepare("update CSPrinting set quantity = ?, total_sales_price = ? * sales_price where product_no = ? and cs_no = ?");                                             
            $sql->execute([$value['quantity'], $value['quantity'], $value['product_no'], input::post('cs_no')]);
            
            $sql = $this->prepare("update StockOut set quantity_out = ? where product_no = ? and file_no = ?");                                             
            $sql->execute([$value['quantity'], $value['product_no'], input::post('cs_no')]);
            
        }
        
    }
    
    // CS Module
    public function getCsConfirmed() {
        $sql = $this->prepare("select
                                	CS.cs_no,
                                    CS.cs_date,
                                    CSLocation.location_name,
                                    CSPrinting.product_no,
                                    Product.product_name,
                                    CSPrinting.quantity,
                                    CSPrinting.quantity_out,
                                    CS.approved_employee as employee_id,
                                    ce.employee_nickname_thai
                                from CS
                                join CSPrinting on CSPrinting.cs_no = CS.cs_no
                                left join Product on Product.product_no = CSPrinting.product_no
                                left join CSLocation on CSLocation.location_no = CS.location_no
                                left join Employee ce on ce.employee_id = CS.approved_employee
                                where CS.confirmed = 1 and CS.cancelled = 0
                                order by CS.cs_date");
        $sql->execute();
        if ($sql->rowCount()>0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return json_encode([]);
    }
    
    // CS Module
    public function addCounterSalesIn() {
        
        $sql = $this->prepare("update CS set CS.confirmed = -2 where CS.cs_no = ?");
        $sql->execute([input::post('cs_no')]);
        
        $csItemsArray = json_decode(input::post('csItems'), true); 
        $csItemsArray = json_decode($csItemsArray, true);
        
        foreach($csItemsArray as $value) {
            
            $sql = $this->prepare("update CSPrinting set quantity_out = ? where product_no = ? and cs_no = ?");                                             
            $sql->execute([$value['quantity'] - $value['quantity_in'], $value['product_no'], input::post('cs_no')]);
            
            $sql = $this->prepare("insert into StockIn (product_no, file_no, file_type, date, time, quantity_in, lot, note) 
                                    values (?, ?, 'CS', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, 0, NULL)");
            $sql->execute([$value['product_no'], input::post('cs_no'), $value['quantity_in']]);   
            
        }
        
    }
    
    // Dashboard Module
    public function getDashboard() {
        $sql = $this->prepare("select 
                                	PO.*,
									RR.rr_no,
                                    Employee.employee_nickname_thai,
                                	(case when received=-1 and PO.cancelled=0 then 1
                                        when received=0 and PO.cancelled=0 then 2
                                        when received=1 and PO.cancelled=0 then 3
                                        when PO.cancelled=1 then 4
                                        else 0 end) as status
                                from PO
                                inner join Employee on Employee.employee_id = PO.approved_employee
                                LEFT JOIN RR on RR.po_no = PO.po_no
                                order by po_no desc");
        $sql->execute();
        if ($sql->rowCount()>0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return json_encode([]);
    }
	
	public function getDashboard2() {
		$sql = $this->prepare("select
									IRD.ird_no,
									IRD.ird_date,
									IRD.ird_time,
									IRD.approved_employee,
									IRD.box_count									
								from IRD where cancelled = 0 order by ird_date DESC");
		$sql->execute();
		if ($sql->rowCount()>0) {
			return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
		}
		return json_encode([]);
	}
	
	// Dashboard Module
	
	public function getSOXinIRD($ird_no){
		$sql = $this->prepare("(select
									concat(Customer.customer_name,' ',Customer.customer_surname) as customer_name,
									SOX.address,
									RIGHT(SOX.address, 5) as zip_code,
									Customer.customer_tel,
									Customer.email,
									'Order' as sox_type,
									SOX.sox_no,
									substring(SOX.sox_datetime,1,10) as sox_date,
									substring(SOXPrinting.so_no,1,1) as company,
									SOX.note,
									InvoicePrinting.invoice_no,
									Invoice.invoice_date,
									SOX.fin_form,
									SOX.transportation_price,
									SOPrinting.so_no,
									Product.product_no,
									Product.product_name,
									InvoicePrinting.sales_no_vat,
									InvoicePrinting.sales_vat,
									InvoicePrinting.sales_price,
									InvoicePrinting.quantity,
									Invoice.total_sales_price,
									SOX.box_size
								from InvoicePrinting                               
								inner join Invoice on Invoice.invoice_no = InvoicePrinting.invoice_no
								inner join SOPrinting on SOPrinting.so_no = Invoice.file_no and SOPrinting.product_no = InvoicePrinting.product_no
                                INNER JOIN SO ON SO.so_no = Invoice.file_no
                                INNER JOIN PO ON SO.po_no = PO.po_no
								left join Product on Product.product_no = InvoicePrinting.product_no
								inner join CustomerTransaction on CustomerTransaction.so_no = SOPrinting.so_no
								inner join Customer on Customer.customer_tel = CustomerTransaction.customer_tel
								inner join SOXPrinting on SOXPrinting.so_no = SOPrinting.so_no
								inner join SOX on SOX.sox_no = SOXPrinting.sox_no
								where Product.product_type = 'Order' and SOX.cancelled = 0 and ird_no = ? AND PO.received = 1)
								union
								(select
									concat(Customer.customer_name,' ',Customer.customer_surname) as customer_name,
									SOX.address,
									RIGHT(SOX.address, 5) as zip_code,
									Customer.customer_tel,
									Customer.email,
									'Stock' as sox_type,
									SOX.sox_no,
									substring(SOX.sox_datetime,1,10) as sox_date,
									substring(SOXPrinting.so_no,1,1) as company,
									SOX.note,
									InvoicePrinting.invoice_no,
									Invoice.invoice_date,
									SOX.fin_form,
									SOX.transportation_price,
									SOPrinting.so_no,
									Product.product_no,
									Product.product_name,
									InvoicePrinting.sales_no_vat,
									InvoicePrinting.sales_vat,
									InvoicePrinting.sales_price,
									InvoicePrinting.quantity,
									Invoice.total_sales_price,
									SOX.box_size
								from InvoicePrinting
								inner join Invoice on Invoice.invoice_no = InvoicePrinting.invoice_no
								inner join SOPrinting on SOPrinting.so_no = Invoice.file_no and SOPrinting.product_no = InvoicePrinting.product_no
								left join Product on Product.product_no = InvoicePrinting.product_no
								inner join CustomerTransaction on CustomerTransaction.so_no = SOPrinting.so_no
								inner join Customer on Customer.customer_tel = CustomerTransaction.customer_tel
								inner join SOXPrinting on SOXPrinting.so_no = SOPrinting.so_no
								inner join SOX on SOX.sox_no = SOXPrinting.sox_no
								where Product.product_type = 'Stock' and SOX.cancelled = 0 and ird_no = ?)
								order by invoice_date ASC");
		
		$sql->execute([$ird_no, $ird_no]);
		if ($sql->rowCount()>0) {
            return $sql->fetchAll();
        }
        return [];
		
	}
    
    // Dashboard Module
    public function getDashboardSo() {
        $sql = $this->prepare("select 
                                	PO.*,
                                    Employee.employee_nickname_thai,
                                	(case when received=-1 and cancelled=0 then 1
                                        when received=0 and cancelled=0 then 2
                                        when received=1 and cancelled=0 then 3
                                        when cancelled=1 then 4
                                        else 0 end) as status
                                from PO
                                inner join Employee on Employee.employee_id = PO.approved_employee
                                order by po_no desc");
        $sql->execute();
        if ($sql->rowCount()>0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return json_encode([]);
    }
	
	// IRD Module
	public function ird_download($ird_no) {
        $sql = $this->prepare("SELECT IRDPrinting.ird_no, Customer.customer_name,Customer.customer_surname , SOX.address, 
									SOX.customer_tel, IRDPrinting.sox_no,
									SUM(Product.weight*SOPrinting.quantity) as weight FROM `IRDPrinting` 
									left join SOX on IRDPrinting.sox_no=SOX.sox_no
									left join Customer on Customer.customer_tel=SOX.customer_tel
									inner join SOPrinting on IRDPrinting.so_no=SOPrinting.so_no
									left join Product on SOPrinting.product_no=Product.product_no
									WHERE IRDPrinting.ird_no=?
									GROUP BY IRDPrinting.sox_no");
        $sql->execute([$ird_no]);
        if ($sql->rowCount() > 0) {
            return $sql->fetchAll();
        }
        return [];
    }
	public function getShippingRequest(){
		$sql = $this->prepare("(select
									concat(Customer.customer_name,' ',Customer.customer_surname) as customer_name,
									SOX.address,
									RIGHT(SOX.address, 5) as zip_code,
									Customer.customer_tel,
									Customer.email,
									'Order' as sox_type,
									SOX.sox_no,
									substring(SOX.sox_datetime,1,10) as sox_date,
									substring(SOXPrinting.so_no,1,1) as company,
									SOX.note,
									InvoicePrinting.invoice_no,
									SOX.fin_form,
									SOX.transportation_price,
									SOPrinting.so_no,
									Product.product_no,
									Product.product_name,
									InvoicePrinting.sales_no_vat,
									InvoicePrinting.sales_vat,
									InvoicePrinting.sales_price,
									InvoicePrinting.quantity,
									Invoice.total_sales_price,
									SOX.box_size,
 									if(Special.sox_no is null, null, Special.sox_no) as special,
									concat(SOX.sox_no,'Order') as u
								from InvoicePrinting                               
								inner join Invoice on Invoice.invoice_no = InvoicePrinting.invoice_no
								inner join SOPrinting on SOPrinting.so_no = Invoice.file_no and SOPrinting.product_no = InvoicePrinting.product_no
                                INNER JOIN SO ON SO.so_no = Invoice.file_no
                                INNER JOIN PO ON SO.po_no = PO.po_no
								left join Product on Product.product_no = InvoicePrinting.product_no
								inner join CustomerTransaction on CustomerTransaction.so_no = SOPrinting.so_no
								inner join Customer on Customer.customer_tel = CustomerTransaction.customer_tel
								inner join SOXPrinting on SOXPrinting.so_no = SOPrinting.so_no
								inner join SOX on SOX.sox_no = SOXPrinting.sox_no
 								LEFT JOIN (SELECT DISTINCT t1.sox_no, ifnull(IRDPrinting.ird_no,'-') as ird_no 
                                           from (SELECT * FROM (SELECT SOXPrinting.sox_no, SOXPrinting.so_no, 
                                                                count(DISTINCT(product_type)) as c FROM SOXPrinting 
                                                                left JOIN SO on SOXPrinting.so_no = SO.so_no
                                                                GROUP BY sox_no
                                                                ORDER BY c desc) as t
                                                 WHERE c = 2  
                                                 ORDER BY `t`.`sox_no`  DESC) as t1
                                           LEFT JOIN IRDPrinting on IRDPrinting.sox_no = t1.sox_no  
                                           ORDER BY `t1`.`sox_no`  DESC) as Special on Special.sox_no = SOX.sox_no
								where Product.product_type = 'Order' and SOX.cancelled = 0 and SOX.ird_no = '-' AND PO.received = 1)
								union
								(select
									concat(Customer.customer_name,' ',Customer.customer_surname) as customer_name,
									SOX.address,
									RIGHT(SOX.address, 5) as zip_code,
									Customer.customer_tel,
									Customer.email,
									'Stock' as sox_type,
									SOX.sox_no,
									substring(SOX.sox_datetime,1,10) as sox_date,
									substring(SOXPrinting.so_no,1,1) as company,
									SOX.note,
									InvoicePrinting.invoice_no,
									SOX.fin_form,
									SOX.transportation_price,
									SOPrinting.so_no,
									Product.product_no,
									Product.product_name,
									InvoicePrinting.sales_no_vat,
									InvoicePrinting.sales_vat,
									InvoicePrinting.sales_price,
									InvoicePrinting.quantity,
									Invoice.total_sales_price,
									SOX.box_size,
                                 	if(Special.sox_no is null, null, Special.sox_no) as special,
									concat(SOX.sox_no,'Stock') as u
								from InvoicePrinting
								inner join Invoice on Invoice.invoice_no = InvoicePrinting.invoice_no
								inner join SOPrinting on SOPrinting.so_no = Invoice.file_no and SOPrinting.product_no = InvoicePrinting.product_no
								left join Product on Product.product_no = InvoicePrinting.product_no
								inner join CustomerTransaction on CustomerTransaction.so_no = SOPrinting.so_no
								inner join Customer on Customer.customer_tel = CustomerTransaction.customer_tel
								inner join SOXPrinting on SOXPrinting.so_no = SOPrinting.so_no
								inner join SOX on SOX.sox_no = SOXPrinting.sox_no
                                LEFT JOIN (SELECT DISTINCT t1.sox_no, ifnull(IRDPrinting.ird_no,'-') as ird_no 
                                           from (SELECT * FROM (SELECT SOXPrinting.sox_no, SOXPrinting.so_no, 
                                                                count(DISTINCT(product_type)) as c FROM SOXPrinting 
                                                                left JOIN SO on SOXPrinting.so_no = SO.so_no
                                                                GROUP BY sox_no
                                                                ORDER BY c desc) as t
                                                 WHERE c = 2  
                                                 ORDER BY `t`.`sox_no`  DESC) as t1
                                           LEFT JOIN IRDPrinting on IRDPrinting.sox_no = t1.sox_no  
                                           ORDER BY `t1`.`sox_no`  DESC) as Special on Special.sox_no = SOX.sox_no
								where Product.product_type = 'Stock' and SOX.cancelled = 0 and SOX.ird_no = '-') 
								order by sox_date ASC, sox_no ASC;");
		
		$sql->execute();
		if ($sql->rowCount()>0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return json_encode([]);
		
	}
	
	
		
	// IRD Module
	public function addIRD(){
		
		$irdItemsArray = json_decode(input::post('irdItems'), true); 
        $irdItemsArray = json_decode($irdItemsArray, true);
		
		$irdno = $this->assignIRD($irdItemsArray[0]['so_no'][0]);
		
		// insert IRD
		$sql = $this->prepare("insert into IRD (ird_no, ird_date, ird_time, approved_employee, file_uploaded, box_count, status, cancelled, note)		
							values(?,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,?,0, ?,0,0,'-')");

		$sql->execute([$irdno, strtoupper(session::get('employee_id')), (int) input::post('box_count')]);
		
		
		foreach($irdItemsArray as $value) {

			// insert IRDPrinting	
			$sql = $this->prepare("insert into IRDPrinting(ird_no, sox_no, so_no, cancelled)
								values(?, ?, ?, 0)");
			$sql->execute([$irdno, $value['sox_no'], $value['so_no']]);

			// insert StockOut
			//$sql = $this->prepare("insert into StockOut (product_no, file_no, file_type, date, time, quantity_out, lot, rr_no)
			//								values (?, ?, 'IRD1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, 0, '-')");
			//$sql->execute([$value['product_no'], $irdno, $value['quantity']]);

			// update SOX
			$sql = $this->prepare("update SOX set ird_no = ? where sox_no = ?");
			$sql->execute([$irdno, $value['sox_no']]);
            //9 is done
            $sql = $this->prepare("update SOX set sox_status = ? where sox_no = ?");
			$sql->execute([9, $value['sox_no']]);
			
		}
		
		$sql = $this->prepare("insert into StockOut
								SELECT product_no, 
										IRDPrinting.ird_no as file_no,
										'IRD1' as file_type, 
										IRD.ird_date as date, 
										IRD.ird_time as time, 
										sum(SOPrinting.quantity) as quantity_out,  
										0 as lot,
										NULL as note,
										'-' as rr_no
								FROM `IRDPrinting`
								INNER JOIN SOPrinting ON SOPrinting.so_no = IRDPrinting.so_no
								INNER JOIN IRD on IRD.ird_no = IRDPrinting.ird_no
								WHERE IRD.ird_no = ?
								GROUP BY product_no, file_no");
		$sql->execute([$irdno]);
		
		echo $irdno;
			
		
	}
	
    //ปุ่มยืนยัน-ช่องล่าง
    public function updateIRD(){
        //$sql=$this->prepare("UPDATE `SOX` SET `sox_status`=? WHERE sox_no=?");
        //$sql->execute([1,$sox_no]);
        $irdItemsArray = json_decode(input::post('irdItems'), true); 
        $irdItemsArray = json_decode($irdItemsArray, true);
		
        //1 is done for confirm
        foreach ($irdItemsArray as $value){
            $sql=$this->prepare("update SOX SET sox_status=? where sox_no=?");
            $sql->execute([1,$value['sox_no']]);
        }

    }

    public function get_detail(){
        $sql=$this->prepare("SELECT * FROM Product INNER JOIN SOPrinting ON Product.product_no=SOPrinting.product_no");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return null;
    }
    //ปุ่มยืนยัน-ช่องบน
    public function download_sox(){
        //$sql=$this->prepare("SELECT * FROM SOX WHERE SOX.done=0");
        $sql=$this->prepare("SELECT * FROM SOX 
        INNER JOIN SOXPrinting ON SOX.sox_no=SOXPrinting.sox_no 
        INNER JOIN SO ON SOXPrinting.so_no=SO.so_no 
        INNER JOIN SOPrinting ON SO.so_no=SOPrinting.so_no
        INNER JOIN Product ON Product.product_no=SOPrinting.product_no
        INNER join Invoice ON Invoice.file_no = SO.so_no
        INNER join InvoicePrinting ON InvoicePrinting.invoice_no = Invoice.invoice_no and InvoicePrinting.product_no = SOPrinting.product_no
        WHERE SO.product_type IN ('Stock','Order') AND SOX.done=0 AND SOX.slip_uploaded = 1 AND SOX.sox_status = 0");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        //echo "<script>console.log('Debug Objects: " . $sql->errorInfo()[2]. "' );</script>";
        //return json_encode([]);
        return null;
        
    }
    //ปุ่มird-ช่องล่าง
    public function change_status(){
        $irdItemArray = json_decode( input::post( 'irdItems' ), true );
        $irdItemArray = json_decode( $irdItemArray, true );
        $irdList = array();
        foreach ( $irdItemArray as $value ) {
            if ( array_key_exists( $value[ 'sox_no' ], $irdList ) ) {
        
            } else {
                $sql=$this->prepare("UPDATE `SOX` SET `sox_status`=? WHERE sox_no=?");
                $sql->execute([9,$value[ 'sox_no' ]]);
            }
        }
    }
    //ปุ่มird-ช่องบน
    public function get_status(){
        $sql=$this->prepare("SELECT * FROM SOX 
        INNER JOIN SOXPrinting ON SOX.sox_no=SOXPrinting.sox_no 
        INNER JOIN SO ON SOXPrinting.so_no=SO.so_no
        INNER JOIN SOPrinting ON SO.so_no=SOPrinting.so_no
        INNER JOIN Product ON Product.product_no=SOPrinting.product_no
        INNER JOIN Invoice ON Invoice.file_no=SO.so_no
        INNER JOIN InvoicePrinting ON Invoice.invoice_no=InvoicePrinting.invoice_no AND InvoicePrinting.product_no = Product.product_no
        
        WHERE SO.product_type IN ('Stock','Order') AND SOX.done=0 AND SOX.slip_uploaded = 1 AND SOX.sox_status = 1;");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return null;
    }

	// IRD Module
	public function assignIRD($company){
		$IRDPrefix = $company.'IR-';
        $sql = $this->prepare("select ifnull(max(ird_no),0) as max from IRD where ird_no like ?");
        $sql->execute([$IRDPrefix.'%']);
        $maxIRDNo = $sql->fetchAll()[0]['max'];
        $runningNo = '';
        if($maxSoNo == '0') {
            $runningNo = '00001';
        } else {
            $latestRunningNo = (int) substr($maxIRDNo, 4) + 1;
            if(strlen($latestRunningNo) == 5) {
                $runningNo = $latestRunningNo;
            } else {
                for ($x = 1; $x <= 5 - strlen($latestRunningNo); $x++) {
                    $runningNo .= '0';
                }
                $runningNo .= $latestRunningNo;
            }
        }
        return $IRDPrefix.$runningNo;
	}
	
	// IRD Module
	public function getIrdForUpload(){
		$sql = $this->prepare("select * from IRD where file_uploaded = 0 and cancelled  = 0");
		$sql->execute();
		
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return json_encode([]);
	}
	
	// IRD Module
	public function uploadIrd(){
		
		//$irdItemsArray = json_decode(input::post('ird_no'), true); 
        //$irdItemsArray = json_decode($irdItemsArray, true); 
		
		$fileName = $_FILES['irdFile']['name'];
		$fileData = file_get_contents($_FILES['irdFile']['tmp_name']);
		$fileData = base64_encode($fileData);
		$fileType = $_FILES['irdFile']['type'];
		
        
        $sql = $this->prepare("update  IRD set file_uploaded = 1, file_name = ?, file_type = ? , file_data = ? where ird_no = ?");
        $sql->execute([$fileName,$fileType,$fileData,input::post('ird_no')]);
        //print_r($sql->errorInfo());
		
    }
	
	
	public function getREreport() {
        $sql = $this->prepare("SELECT RE.`re_no`,RE.`re_date`,concat(Supplier.supplier_no,' : ',Supplier.supplier_name) as re_supplier ,RE.total_return_price as re_total 
								FROM RE join Supplier on Supplier.supplier_no = RE.supplier_no");
        $sql->execute();
        return $sql->fetchAll();
    }
	
	public function getStockIRD() {
		$sql = $this->prepare("SELECT supplier_no, 
										supplier_name, 
										product_no, 
										product_name,
										if(product_type = 'S','Stock','Order') as product_type, 
										stock_in, 
										stock_out, 
										stock_left, 
										GROUP_CONCAT(ifnull(rr_no,'-') SEPARATOR '\n') as rr_no
								FROM (SELECT supplier_no, supplier_name, View_StockIRD.product_no, product_name, stock_in, 
									  stock_out, stock_left , substring(View_StockIRD.product_no,3,1) as product_type, ifnull(RRPrinting.rr_no,'-') as rr_no
									  FROM View_StockIRD 
									  left JOIN RRPrinting on RRPrinting.product_no = View_StockIRD.product_no
									  WHERE product_name not LIKE '%ค่าส่ง%' AND product_name not LIKE '%ส่วนลด%') as t  
                                GROUP BY product_no
                                ORDER BY  supplier_no ASC, product_no ASC, rr_no asc");
		$sql->execute();
		if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return json_encode([]);
	}
	
	public function getStockIRDforDownload() {
    $sql = $this->prepare( "SELECT supplier_no, 
										supplier_name, 
										product_no, 
										product_name,
										if(product_type = 'S','Stock','Order') as product_type, 
										stock_in, 
										stock_out, 
										stock_left, 
										GROUP_CONCAT(ifnull(rr_no,'-') SEPARATOR '\n') as rr_no
								FROM (SELECT supplier_no, supplier_name, View_StockIRD.product_no, product_name, stock_in, 
									  stock_out, stock_left , substring(View_StockIRD.product_no,3,1) as product_type, ifnull(RRPrinting.rr_no,'-') as rr_no
									  FROM View_StockIRD 
									  left JOIN RRPrinting on RRPrinting.product_no = View_StockIRD.product_no
									  WHERE product_name not LIKE '%ค่าส่ง%' AND product_name not LIKE '%ส่วนลด%') as t  
                                GROUP BY product_no
                                ORDER BY  supplier_no ASC, product_no ASC, rr_no asc" );
    $sql->execute();
    if ( $sql->rowCount() > 0 ) {
      return $sql->fetchAll();
    }
    return [];
  }
	
    public function getAddress(){
        $sql=$this->prepare("SELECT distinct SOX.sox_no,Invoice.invoice_no,SOX.note,Invoice.customer_name,SOX.address,SOX.customer_tel 
        FROM SOX INNER JOIN SOXPrinting ON SOX.sox_no=SOXPrinting.sox_no 
        INNER JOIN SO ON SOXPrinting.so_no=SO.so_no 
        INNER JOIN SOPrinting ON SO.so_no=SOPrinting.so_no 
        INNER JOIN Product ON Product.product_no=SOPrinting.product_no 
		INNER JOIN Invoice ON Invoice.file_no=SO.so_no
        INNER JOIN InvoicePrinting ON Product.product_no=InvoicePrinting.product_no 
        WHERE sox_status=1 AND SOX.done=0;");
        $sql->execute();
        if ( $sql->rowCount() > 0 ) {
            return $sql->fetchAll();
        }
        return [];
    }

	public function getSOXnoIRD(){
        $sql=$this->prepare("SELECT SO.so_no,SOX.sox_no,SOX.box_size, Invoice.invoice_no, Product.product_no,Product.product_name , SOPrinting.quantity 
        FROM SOX 
        INNER JOIN SOXPrinting ON SOX.sox_no=SOXPrinting.sox_no 
        INNER JOIN SO ON SOXPrinting.so_no=SO.so_no 
        INNER JOIN SOPrinting ON SO.so_no=SOPrinting.so_no 
        INNER JOIN Product ON Product.product_no=SOPrinting.product_no 
        INNER JOIN Invoice ON Invoice.file_no = SO.so_no
        INNER JOIN InvoicePrinting ON Product.product_no=InvoicePrinting.product_no AND Invoice.invoice_no = InvoicePrinting.invoice_no
        
        WHERE sox_status=1 AND SOX.done=0;");
        $sql->execute();
        if ( $sql->rowCount() > 0 ) {
            return $sql->fetchAll();
        }
        return [];
    }

	public function getSOXnoIRD2() {
		$sql = $this->prepare("SELECT DISTINCT iv_no, product_no, product_name, quantity, customer_name, address, zip_code, customer_tel, t.sox_type FROM 
								((select
									concat(Customer.customer_name,' ',Customer.customer_surname) as customer_name,
									SOX.address,
									RIGHT(SOX.address, 5) as zip_code,
									Customer.customer_tel,
									Customer.email,
									'Order' as sox_type,
									SOX.sox_no,
									substring(SOX.sox_datetime,1,10) as sox_date,
									substring(SOXPrinting.so_no,1,1) as company,
									SOX.note,
									InvoicePrinting.invoice_no,
									SOX.fin_form,
									SOX.transportation_price,
									SOPrinting.so_no,
									Product.product_no,
									Product.product_name,
									InvoicePrinting.sales_no_vat,
									InvoicePrinting.sales_vat,
									InvoicePrinting.sales_price,
									InvoicePrinting.quantity,
									Invoice.total_sales_price,
									SOX.box_size,
 									if(Special.sox_no is null, null, Special.sox_no) as special,
									concat(SOX.sox_no,'Order') as u
								from InvoicePrinting                               
								inner join Invoice on Invoice.invoice_no = InvoicePrinting.invoice_no
								inner join SOPrinting on SOPrinting.so_no = Invoice.file_no and SOPrinting.product_no = InvoicePrinting.product_no
                                INNER JOIN SO ON SO.so_no = Invoice.file_no
                                INNER JOIN PO ON SO.po_no = PO.po_no
								left join Product on Product.product_no = InvoicePrinting.product_no
								inner join CustomerTransaction on CustomerTransaction.so_no = SOPrinting.so_no
								inner join Customer on Customer.customer_tel = CustomerTransaction.customer_tel
								inner join SOXPrinting on SOXPrinting.so_no = SOPrinting.so_no
								inner join SOX on SOX.sox_no = SOXPrinting.sox_no
 								LEFT JOIN (SELECT DISTINCT t1.sox_no, ifnull(IRDPrinting.ird_no,'-') as ird_no 
                                           from (SELECT * FROM (SELECT SOXPrinting.sox_no, SOXPrinting.so_no, 
                                                                count(DISTINCT(product_type)) as c FROM SOXPrinting 
                                                                left JOIN SO on SOXPrinting.so_no = SO.so_no
                                                                GROUP BY sox_no
                                                                ORDER BY c desc) as t
                                                 WHERE c = 2  
                                                 ORDER BY `t`.`sox_no`  DESC) as t1
                                           LEFT JOIN IRDPrinting on IRDPrinting.sox_no = t1.sox_no  
                                           ORDER BY `t1`.`sox_no`  DESC) as Special on Special.sox_no = SOX.sox_no
								where Product.product_type = 'Order' and SOX.cancelled = 0 and SOX.ird_no = '-' AND PO.received = 1)
								union
								(select
									concat(Customer.customer_name,' ',Customer.customer_surname) as customer_name,
									SOX.address,
									RIGHT(SOX.address, 5) as zip_code,
									Customer.customer_tel,
									Customer.email,
									'Stock' as sox_type,
									SOX.sox_no,
									substring(SOX.sox_datetime,1,10) as sox_date,
									substring(SOXPrinting.so_no,1,1) as company,
									SOX.note,
									InvoicePrinting.invoice_no,
									SOX.fin_form,
									SOX.transportation_price,
									SOPrinting.so_no,
									Product.product_no,
									Product.product_name,
									InvoicePrinting.sales_no_vat,
									InvoicePrinting.sales_vat,
									InvoicePrinting.sales_price,
									InvoicePrinting.quantity,
									Invoice.total_sales_price,
									SOX.box_size,
                                 	if(Special.sox_no is null, null, Special.sox_no) as special,
									concat(SOX.sox_no,'Stock') as u
								from InvoicePrinting
								inner join Invoice on Invoice.invoice_no = InvoicePrinting.invoice_no
								inner join SOPrinting on SOPrinting.so_no = Invoice.file_no and SOPrinting.product_no = InvoicePrinting.product_no
								left join Product on Product.product_no = InvoicePrinting.product_no
								inner join CustomerTransaction on CustomerTransaction.so_no = SOPrinting.so_no
								inner join Customer on Customer.customer_tel = CustomerTransaction.customer_tel
								inner join SOXPrinting on SOXPrinting.so_no = SOPrinting.so_no
								inner join SOX on SOX.sox_no = SOXPrinting.sox_no
                                LEFT JOIN (SELECT DISTINCT t1.sox_no, ifnull(IRDPrinting.ird_no,'-') as ird_no 
                                           from (SELECT * FROM (SELECT SOXPrinting.sox_no, SOXPrinting.so_no, 
                                                                count(DISTINCT(product_type)) as c FROM SOXPrinting 
                                                                left JOIN SO on SOXPrinting.so_no = SO.so_no
                                                                GROUP BY sox_no
                                                                ORDER BY c desc) as t
                                                 WHERE c = 2  
                                                 ORDER BY `t`.`sox_no`  DESC) as t1
                                           LEFT JOIN IRDPrinting on IRDPrinting.sox_no = t1.sox_no  
                                           ORDER BY `t1`.`sox_no`  DESC) as Special on Special.sox_no = SOX.sox_no
								where Product.product_type = 'Stock' and SOX.cancelled = 0 and SOX.ird_no = '-') 
								order by sox_date ASC, sox_no ASC) as t;");
		
		$sql->execute();
		if ( $sql->rowCount() > 0 ) {
		  return $sql->fetchAll();
		}
		return [];
		}

        public function getIRDs($ird_no){
            $sql=$this->prepare("SELECT * FROM `IRD` WHERE ird_no =? ");
            $data=$sql->execute([$ird_no]);

            if($sql->rowCount() > 0){
                header('Content-type: '.$data['quotation_type']);
              
               return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE) ;
           }else{
               echo $sql;
               echo "Hello";
           }
        }
    
    public function getIRDLoad(){
        $sql=$this->prepare("SELECT IRDPrinting.ird_no, IRDPrinting.sox_no , IRDPrinting.so_no, Product.product_no, Product.product_name, SOPrinting.quantity 
        FROM IRDPrinting 
        INNER JOIN SO ON IRDPrinting.so_no = SO.so_no  
        INNER JOIN SOPrinting ON SO.so_no = SOPrinting.so_no
        INNER JOIN Product ON Product.product_no=SOPrinting.product_no;");
        $sql->execute();
        if ( $sql->rowCount() > 0 ) {
            return $sql->fetchAll();
        }
        return [];
    }

    public function updateTrackingNo() {
        foreach($_POST["trackingNumArray"] as $value) {
            $sql = $this->prepare("UPDATE SOX set tracking_number = ? WHERE sox_no = ?"); 
            $sql->execute([$value[1],$value[0]]);
        }

        echo "yes";
    }
	
}