<?php

namespace model;

use _core\model;
use _core\helper\session;
use _core\helper\input;
use _core\helper\uri;
use _core\helper\thaiNum;
use PDO;

class accModel extends model {
	public function getDashboardIv2() {
        
        // $sql = $this->prepare("select
        //                         	distinct Invoice.invoice_no,
        //                             invoice_date,
        //    ca                         Invoice.employee_id,
        //                             customer_name,
        //                             id_no,
        //                             customer_address,
        //                             SO.product_type,
        //                             vat_type.vat_type,
        //                             Invoice.total_sales_no_vat,
        //                             Invoice.total_sales_vat,
        //                             Invoice.total_sales_price,
        //                             Invoice.sales_price_thai,
        //                             Invoice.file_no
        //                         from Invoice
        //                         inner join SO on SO.so_no = Invoice.file_no
        //                         inner join (select distinct invoice_no, substring(product_no, 4, 1) as vat_type from InvoicePrinting where not product_no like 'X') as vat_type on vat_type.invoice_no = Invoice.invoice_no
        //                         order by Invoice.invoice_date, Invoice.invoice_time");
        $sql = $this->prepare("select
                                	distinct Invoice.invoice_no,
                                    invoice_date,
                                    Invoice.employee_id,
                                    customer_name,
                                    id_no,
                                    SO.product_type,
                                    vat.vat_type,
                                   	case
                                    	when Invoice.invoice_type = 'IV' then Invoice.total_sales_no_vat
                                        when Invoice.invoice_type = 'CN' then Invoice.total_sales_no_vat * -1
                                    end as total_sales_no_vat,
                                    case
                                    	when Invoice.invoice_type = 'IV' then Invoice.total_sales_vat
                                        when Invoice.invoice_type = 'CN' then Invoice.total_sales_vat * -1
                                    end as total_sales_vat,
                                    case
                                    	when Invoice.invoice_type = 'IV' then Invoice.total_sales_price
                                        when Invoice.invoice_type = 'CN' then Invoice.total_sales_price * -1
                                    end as total_sales_price,
                                    Invoice.sales_price_thai,
                                    Invoice.file_no
                                from Invoice
                                left join SO on SO.so_no = Invoice.file_no
                                left join (select so_no, vat_type from SO) vat on vat.so_no = Invoice.file_no
                                order by Invoice.invoice_date, Invoice.invoice_time");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return json_encode([]);                          
        
    }
	public function getSoxsForIv() {
        $sql = $this->prepare("select 
                                    SOX.sox_no,
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
                                    SO.commission as so_commission,
                                    CI.ci_no,
                                    POPrinting.po_no,
                                    Invoice.invoice_no
                                from SOXPrinting 
                                inner join SOX on SOX.sox_no = SOXPrinting.sox_no
                                inner join SO on SO.so_no = SOXPrinting.so_no
                                inner join SOPrinting on SOPrinting.so_no = SO.so_no
                                inner join Employee on Employee.employee_id = SOX.employee_id
                                left join Product on Product.product_no = SOPrinting.product_no
                                inner join Customer on Customer.customer_tel = SOX.customer_tel
                                left join Invoice on SO.so_no=Invoice.file_no
                                left join POPrinting on SO.so_no=POPrinting.so_no 
                                left join CI on CI.po_no=POPrinting.po_no
                                where SOX.cancelled = 0 and SO.payment = 1 and Invoice.invoice_no is null order by SOX.slip_datetime");  
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return json_encode([]);
    }
	public function addIV_noCR() {
        
       
        $crItemsArray = json_decode(input::post('ivItems'), true); 
        $crItemsArray = json_decode($crItemsArray, true); 
        
        $soList = array();
        $iv_no = "";
        
        foreach($crItemsArray as $value) {
            
            if (array_key_exists($value['so_no'], $soList)) {
                $iv_no = $soList[$value['so_no']];
                
            } else {
                  
                $iv_no = $this->assignIv($value['so_no']);  
                $soList += [$value['so_no']=>$iv_no];
                
                echo $iv_no.' ('.$value['so_no'].') ';
                
                if($value['so_total_sales_vat2'] != 0) {
                    $total_sales_no_vat = ((double) $value['so_total_sales_price2']) / 1.07;
                    $total_sales_vat = ((double) $value['so_total_sales_price2'] / 107) * 7;
                    $total_sales_price = (double) $value['so_total_sales_price2'];
                } else {
                    $total_sales_no_vat = (double) $value['so_total_sales_price2'];
                    $total_sales_vat = 0;
                    $total_sales_price = (double) $value['so_total_sales_price2'];
                }
                
                         
                     
                $sql = $this->prepare("insert into Invoice (invoice_no, invoice_date, invoice_time, employee_id, customer_name, customer_address, id_no, file_no,
                                        file_type, total_sales_no_vat, total_sales_vat, total_sales_price, discount, sales_price_thai, point, commission, approved_employee, cr_no, cancelled, note)
                                        values (?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?, ?, 'SO', ?, ?, ?, ?, ?, ?, ?, ?, null, 0, ?)");  
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
                    input::post('noted')
                ]); 
				
                $check = $sql->errorInfo()[0];

				if($check == '00000') {
                // ============================================================================================================================================================
                // NEW CBA2020 ACC
                
                // insert AccountDetail sequence 1
                // Dr ??????????????????????????????????????? - ????????????????????? X
                $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                        values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                $sql->execute([$iv_no, '1', '13-1'.$iv_no[0].'00', (double) $total_sales_price, 0, 'IV']);
                
                // insert AccountDetail sequence 2
                // Cr ??????????????????????????? IV - ????????????????????? X
                $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                        values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                $sql->execute([$iv_no, '2', '13-1'.$iv_no[0].'10', 0, (double) $total_sales_no_vat, 'IV']);
                
                // insert AccountDetail sequence 3
                // Cr ????????????????????? - ????????????????????? X
                $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                        values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                $sql->execute([$iv_no, '3', '62-1'.$iv_no[0].'00', 0, (double) $total_sales_vat, 'IV']);
                
				
                // ============================================================================================================================================================
                // END CBA2020 ACC
				} else {
					echo '?????????????????????????????????????????? ???????????????????????? IV ????????????';
					return '?????????????????????????????????????????? ???????????????????????? IV ????????????';
				}
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
                $value['ci_no'] 
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

    } 
    
    // CN(PV-D) Module
    public function getWSD() {
        $sql = "SELECT 
                    WSD.wsd_no,
                    WSD.wsd_date,
                    WSD.wsd_time,
                    WSD.employee_id,
                    WSD.invoice_no,
                    WSD.sox_no,
                    WSD.vat_id,
                    WSD.total_amount,
                    WSD.note,
                    WSD.wsd_status,
                    Invoice.total_sales_no_vat as iv_total_sales
                    from WSD 
                    Left join Invoice on WSD.invoice_no = Invoice.invoice_no
       
                    where WSD.wsd_status = 0 ";
        $statement = $this->prepare($sql);
        $statement->execute([]);

        if ($statement->rowCount() > 0) {
            return json_encode($statement->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        } else return []; 
    }


    
    public function updateWSDCreditNote() {
        $sql = "UPDATE WSD SET 
        total_amount = ?,
        note = ?
        where  wsd_status = 0 and wsd_no = ?"; 
        $statement = $this->prepare($sql);
        $success = $statement->execute([
            input::post("total_amount"),
            input::post("note"),
            input::post("wsd_no")
        ]);
        if($success) echo 'success';
        else echo print_r($statement->errorInfo());

        // $statement = $this->prepare("UPDATE WSD set
        //                                 wsd_status");
    }

    private function assignCN($company) {
        $ivPrefix = $company.'CN-';
        $sql=$this->prepare("select ifnull(max(cn_no),0) as max from CN where cn_no like ?");
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

    public function getIvForCn() {
        $sql = $this->prepare("select
                                	Invoice.invoice_no,
                                    Invoice.invoice_date,
                                    Invoice.total_sales_no_vat as iv_total_sales_no_vat,
                                    Invoice.total_sales_vat as iv_total_sales_vat,
                                    Invoice.total_sales_price as iv_total_sales_price,
                                    InvoicePrinting.product_no,
                                    Product.product_name,
                                    Product.commission,
                                    InvoicePrinting.sales_no_vat,
                                    InvoicePrinting.sales_vat,
                                    InvoicePrinting.sales_price,
                                    InvoicePrinting.quantity,
                                    InvoicePrinting.total_sales_price
                                from InvoicePrinting
                                inner join Invoice on Invoice.invoice_no = InvoicePrinting.invoice_no
                                left join Product on Product.product_no = InvoicePrinting.product_no
                                where Invoice.invoice_no = ?");
        $sql->execute([input::postAngular('iv_no')]);
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return '';   
    }

    public function cancelEXD() {
        $sql = "UPDATE WSD SET wsd_status = -1
        where  wsd_status = 0 and wsd_no = ?"; 
        $statement = $this->prepare($sql);
        $success = $statement->execute([
            input::post("wsd_no")
        ]);
        if($success) echo ' success';
        else echo print_r($statement->errorInfo());
    }

    // CN(PV-D) Module
    public function addCn() {

        $cn_no = $this->assignCN(input::post('company'));
        
        $new_price = input::post('new_total_sales_price');
        if($new_price == null){
            $new_price =0;
        }
        $comm = input::post('total_commission');
        if($comm == null){
            $comm =0.0;
        }

        $sql = $this->prepare("INSERT into CN(cn_no, cn_date, cn_time, employee_id, wsd_no, company_code, total_commission, iv_total_sales, new_total_sales_price, diff_total_sales_vat, vat_total_sales_no_vat, sum_total_sales, new_sales_price_thai )
                        values (?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        // $statement = $this->prepare($sql);
        $success = $sql->execute([
            $cn_no,  
            json_decode(session::get('employee_detail'), true)['employee_id'],
            input::post('wsd_no'),
            input::post('company'),
            $comm,
            input::post('iv_total_sales'),
            $new_price,
            input::post('diff_total_sales_vat'),
            input::post('vat_total_sales_no_vat'),
            input::post('sum_total_sales'),
            input::post('new_sales_price_thai'),
        ]);
        print_r($sql->errorInfo());

        if($success) {
            echo ' success(';
            echo $cn_no;
            echo')';
        } else {
            echo ' failed(';
            echo $cn_no;
            echo')';
        }
        

        $items = json_decode( input::post( 'cnItems' ), true );
        $items = json_decode( $items, true );

        $invoice_no = input::post('invoice_no');
        foreach ( $items as $value ) {
  
            // insert SOPrinting
            $sql = $this->prepare( "INSERT into CNPrinting(wsd_no, cn_no, product_no, sales_price, new_quantity, new_total_sales)
                                           values ( ?, ?, ?, ?, ?, ?)" );
            $sql->execute([
                input::post('wsd_no'),
                $cn_no,
                $value[ 'product_no' ],
                // input::post('iv_total_sales'),
                // $new_price,
                // input::post('diff_total_sales_vat'),
                // input::post('vat_total_sales_no_vat'),
                // input::post('sum_total_sales'),
                // input::post('new_sales_price_thai'),
                ( double )$value[ 'sales_price' ],
                ( double )$value[ 'quantity' ],
                ( double )$value[ 'quantity' ] * $value[ 'sales_price' ]
            ] );
            // print_r($sql->errorInfo());

          }
    
        // $sql = $this->prepare("SELECT
        //                         Invoice.id_no
        //                     from Invoice
        //                     where Invoice.invoice_no = ?");
        // $sql->execute([$invoice_no]);
        // $temp = $sql->fetchAll(PDO::FETCH_ASSOC);
        // $vat_id = intval($tempp[0]["id_no"]);
    
  
        $sql = $this->prepare("UPDATE WSD set wsd_status=1
                                            --   vat_id = ?,
                                            -- total_amount = ?
                                where WSD.wsd_no = ?");
        $sql->execute([
            // $vat_id,
            // input::post("sum_total_sales"),
            input::post('wsd_no')
        ]);

        // $sql = $this->prepare("UPDATE SOX 
        //                 LEFT JOIN SOXPrinting on SOX.sox_no = SOXPrinting.sox_no
        //                 LEFT JOIN SO ON SOXPrinting.so_no = SO.so_no
        //                 LEFT JOIN SOPrinting on SO.so_no = SOPrinting.so_no
        //                 LEFT JOIN PointLog on SOXPrinting.so_no = PointLog.note
        //                 SET
        //                 SOX.cancelled = 1,
        //                 SO.cancelled = 1,
        //                 SOPrinting.cancelled = 1,
        //                 PointLog.cancelled = 1
        //                 WHERE SOX.sox_no = ? ;
        //                         ");             
        // $sql->execute([
        //     input::post('sox_no')
        // ]);
        


        //CBA2022 ???????????????????????????????????????????????????????????? ??????????????????????????????????????????????????? (PV-D)
        //sequence 11 CN
        // Dr 11.1 ??????????????????????????????????????????????????????????????? 41-1x10
        $sql = $this->prepare("INSERT into AccountDetail(file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
        $sql->execute([
            $invoice_no,
            '11',
            '41-1'.$invoice_no[0].'10',
            (double) input::post('diff_total_sales_vat'),
            0,
            'CN'
        ]);
        //sequence 12 CN
         // Dr 11.1 ????????????????????? 62-1x00
        $sql = $this->prepare("INSERT into AccountDetail(file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
        $sql->execute([
            $invoice_no,
            '12',
            '62-1'.$invoice_no[0].'00',
            (double) input::post('vat_total_sales_no_vat'),
            0,
            'CN'
        ]);

        //sequence 13 CN
        // Cr 11.1 ?????????????????????????????????????????????????????????????????? - ????????????????????? X
        $sql = $this->prepare("INSERT into AccountDetail(file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
        $sql->execute([
            $invoice_no,
            '13',
            '24-1'.$invoice_no[0].'20',
            0,
            (double) input::post('sum_total_sales'),
            'CN'
        ]);

    

        //moved to end of pvd process also in acc ??????

        // // insert IV(CN)
        // $sql = $this->prepare("insert into Invoice (invoice_no, invoice_date, invoice_time, employee_id, customer_name, customer_address, id_no, file_no,
        //                         file_type, total_sales_no_vat, total_sales_vat, total_sales_price, discount, sales_price_thai, point, commission, approved_employee, cr_no, cancelled, note, invoice_type)
        //                         values (?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, '-', '-', '-', '-', ?, 'IV', ?, ?, ?, ?, ?, 0, 0, ?, null, 0, ?, 'CN')");  
        // $sql->execute([
        //     $iv_no,
        //     input::post('file_no'),
        //     (double) input::post('diff_total_sales_no_vat'),
        //     (double) input::post('diff_total_sales_vat'),
        //     (double) input::post('diff_total_sales_price'),
        //     0,
        //     input::post('diff_total_sales_price_thai'),
        //     json_decode(session::get('employee_detail'), true)['employee_id'],
        //     input::post('cn_detail')
        // ]); 
        
        // // update canclled in ex-IV
        // $sql = $this->prepare("update Invoice set cancelled = 1 where invoice_no = ?");  
        // $sql->execute([input::post('file_no')]); 
        
        // ============================================================================================================================================================
        // NEW CBA2020 ACC
        
        // // insert AccountDetail sequence 2
        // // Dr ??????????????????????????????????????????????????? - ????????????????????? X
        // $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
        //                         values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
        // $sql->execute([$iv_no, '1', '24-1'.$iv_no[0].'00', (double) input::post('diff_total_sales_no_vat'), 0, 'CN']);
        
        // // insert AccountDetail sequence 3
        // // Dr ????????????????????? - ????????????????????? X
        // $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
        //                         values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
        // $sql->execute([$iv_no, '2', '62-1'.$iv_no[0].'00', (double) input::post('diff_total_sales_vat'), 0, 'CN']);
        
        // // insert AccountDetail sequence 1
        // // Cr ???????????????????????????????????????????????? - ????????????????????? X
        // $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
        //                         values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
        // $sql->execute([$iv_no, '3', '12-1'.$iv_no[0].'00', 0, (double) input::post('diff_total_sales_price'), 'CN']);
        
        // // ============================================================================================================================================================
        // // END CBA2020 ACC
            
        // foreach($cnItemsArray as $value) {
            
        //     // insert InvoicePrinting
        //     $sql = $this->prepare("insert into InvoicePrinting (invoice_no, product_no, sales_no_vat, sales_vat, sales_price, quantity, total_sales_price, cancelled, rr_no)
        //                             values (?, ?, ?, ?, ?, ?, ?, 0, 'CN')");  
        //     $sql->execute([
        //         $iv_no,
        //         $value['product_no'],
        //         (double) $value['sales_no_vat'],
        //         (double) $value['sales_vat'],
        //         (double) $value['sales_price'],
        //         (double) $value['quantity'],
        //         (double) $value['total_sales_price']
        //     ]);
            
        // }
        
        
        
    }



    //pvd module
    public function getPVDForPV() {
        $sql = "SELECT 
                    WSD.wsd_no,
                    WSD.bank,
                    WSD.bank_no,
                    WSD.recipient,
                    Invoice.customer_address as recipient_address,
                    WSD.invoice_no,
                    WSD.sox_no,
                    WSD.vat_id,
                    WSD.note,
                    WSD.wsd_status,
                    CN.cn_no,
                    CN.cn_date,
                    CN.cn_time,
                    CN.employee_id,
                    CN.company_code,
                    CN.new_total_sales_price,
                    CN.iv_total_sales,
                    CN.vat_total_sales_no_vat,
                    CN.sum_total_sales,
                    CN.new_sales_price_thai
                    
                from CN
                left join WSD on CN.wsd_no = WSD.wsd_no
                left join CNPrinting on CN.wsd_no = CNPrinting.wsd_no
                left join Invoice on WSD.invoice_no = Invoice.invoice_no
                where wsd_status = 1";

        $statement = $this->prepare($sql);
        $statement->execute([]);

        if ($statement->rowCount() > 0) {
            return json_encode($statement->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        } else return json_encode([]); 
    }

    public function updatePVDForPV() {
        $sql = "UPDATE CN SET  
            company_code = ?
            WHERE cn_no = ?";
        $statement = $this->prepare($sql);
        $success = $statement->execute([
            input::post("company_code"),
            input::post("cn_no")
        ]);
        if($success) echo ' ??????????????????';
        else print_r($statement->errorInfo());

        $sql = "UPDATE WSD SET  
            recipient = ?, bank = ?, bank_no = ?, recipient_address = ?
            WHERE wsd_no = ?";
        $statement = $this->prepare($sql);
        $success = $statement->execute([
            input::post("recipient"),
            input::post("bank"),
            input::post("bank_no"),
            input::post("recipient_address"),
            input::post("wsd_no")
        ]);
    }
    
    public function postPVDForPV() {
        $pvd_no = $this->assignPVD(input::post('company_code'));

        $sql = $this->prepare("INSERT into PVD(pvd_no, cn_no, wsd_no, pvd_date, pvd_time, employee_id, sum_total_sales,PVD_status)
                                    values (?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, 2)");  
        $success = $sql->execute([
            $pvd_no,
            input::post("cn_no"),
            input::post("wsd_no"),
            json_decode(session::get('employee_detail'), true)['employee_id'],
            input::post("sum_total_sales")
        ]);
        // if($success) echo ' ??????????????????';
        // else print_r($statement->errorInfo());

        if($success) {
            echo ' success(';
            echo input::post("pvd_no");
            echo')';
        } else {
            echo ' failed(';
            echo input::post("pvd_no");
            echo')';
        }

        // $sql = $this->prepare("UPDATE WSD set wsd_status=2
        //                         where WSD.wsd_no = ?");
        // $sql->execute([
        //     input::post('wsd_no')
        // ]);


        $sql = "UPDATE WSD SET  
            recipient = ?, bank = ?, bank_no = ?, recipient_address = ?, wsd_status=2
            WHERE WSD.wsd_no = ?";
        $statement = $this->prepare($sql);
        $success = $statement->execute([
            input::post("recipient"),
            input::post("bank"),
            input::post("bank_no"),
            input::post("recipient_address"),
            input::post("wsd_no")
        ]);

        $sql = "UPDATE CN SET  
            company_code = ?
            WHERE cn_no = ?";
        $statement = $this->prepare($sql);
        $success = $statement->execute([
            input::post("company_code"),
            input::post("cn_no")
        ]);
        
    }

    public function getPVDConfirmPV() {
        $sql = $this->prepare("SELECT 
                                pvd_no as pv_no,
                                pvd_date as pv_date,
                                sum_total_sales as total_paid,
                                slipName as receipt_name,
                                cn_no
                                
                                from PVD 
                                where PVD_status = 3");
        $sql->execute([]);

        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        } return json_encode([]); 
    }

    private function assignPVD($company) {
        $ivPrefix = $company.'PD-';
        $sql=$this->prepare("select ifnull(max(pvd_no),0) as max from PVD where pvd_no like ?");
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

    // CN(PV-D) Module
    private function assignIv($iv_no) {
        $ivPrefix = $iv_no[0].'IV-';
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
    
    // IVRC Module
    public function getRRCINOIV() {
		//$sql = $this->prepare("select 
        //                        	View_CIRR_NOIV.*,
        //                            Supplier.supplier_name,
        //                            Product.product_no,
        //                        	Product.product_name,
        //                            CIPrinting.so_no,
        //                        	CIPrinting.purchase_no_vat,
        //                        	CIPrinting.purchase_vat,
        //                        	CIPrinting.purchase_price,
        //                        	CIPrinting.quantity,
        //                            Product.unit,
        //                        	CIPrinting.total_purchase_price,
        //                        	'CI' as type 
        //                        from View_CIRR_NOIV
        //                        inner join CI on CI.ci_no = View_CIRR_NOIV.ci_no
        //                        inner join CIPrinting on CIPrinting.ci_no = View_CIRR_NOIV.ci_no
        //                        left join Product on Product.product_no = CIPrinting.product_no
        //                        inner join Supplier on Supplier.supplier_no = View_CIRR_NOIV.supplier_no
        //                        union
        //                        select 
        //                        	View_CIRR_NOIV.*,
        //                            Supplier.supplier_name,
        //                            Product.product_no,
        //                        	Product.product_name,
        //                            RRPrinting.so_no,
        //                        	RRPrinting.purchase_no_vat,
        //                        	RRPrinting.purchase_vat,
        //                        	RRPrinting.purchase_price,
        //                        	RRPrinting.quantity,
        //                            Product.unit,
        //                        	RRPrinting.total_purchase_price,
        //                        	'RR' as type 
        //                        from View_CIRR_NOIV
        //                        inner join RR on RR.rr_no = View_CIRR_NOIV.ci_no
        //                        inner join RRPrinting on RRPrinting.rr_no = View_CIRR_NOIV.ci_no
        //                        left join Product on Product.product_no = RRPrinting.product_no
        //                        inner join Supplier on Supplier.supplier_no = View_CIRR_NOIV.supplier_no
        //                        union
        //                        select
        //                        	RE.re_no,
        //                            RE.re_date,
        //                            RE.approved_employee,
        //                            RE.supplier_no,
        //                            '-',
        //                            RE.total_return_no_vat * -1,
        //                            RE.total_return_vat * -1,
        //                            RE.total_return_price * -1,
        //                            RE.cancelled,
        //                            RE.note,
        //                            PO.po_no,
        //                            PO.product_type,
        //                            Supplier.supplier_name,
        //                            REPrinting.product_no,
        //                            Product.product_name,
        //                            '-',
        //                            REPrinting.purchase_no_vat * -1,
        //                            REPrinting.purchase_vat * -1,
        //                            REPrinting.purchase_price * -1,
        //                            REPrinting.quantity,
        //                            Product.unit,
        //                            REPrinting.total_purchase_price * -1,
        //                            'RE'
        //                        from RE
        //                        join REPrinting on REPrinting.re_no = RE.re_no
        //                        left join RR on REPrinting.rr_no = RR.rr_no
        //                        left join PO on PO.po_no = RR.po_no
        //                        left join Product on Product.product_no = REPrinting.product_no
        //                        left join Supplier on Supplier.supplier_no = Product.supplier_no
        //                        where RR.invoice_no = '-'");
        $sql = $this->prepare("select 
                                 View_CIRR_NOIV.*,
                                    Supplier.*,
                                    Product.product_no,
                                 Product.product_name,
                                    CIPrinting.so_no,
                                 CIPrinting.purchase_no_vat,
                                 CIPrinting.purchase_vat,
                                 CIPrinting.purchase_price,
                                 CIPrinting.quantity,
                                    Product.unit,
                                 CIPrinting.total_purchase_price,
                                 'CI' as type 
                                from View_CIRR_NOIV
                                inner join CI on CI.ci_no = View_CIRR_NOIV.ci_no
                                inner join CIPrinting on CIPrinting.ci_no = View_CIRR_NOIV.ci_no
                                left join Product on Product.product_no = CIPrinting.product_no
                                inner join Supplier on (Supplier.supplier_no = View_CIRR_NOIV.supplier_no AND Supplier.product_line = Product.product_line)
                                union
                                select 
                                	View_CIRR_NOIV.*,
                                    Supplier.*,
                                    Product.product_no,
                                	Product.product_name,
                                    RRPrinting.so_no,
                                	RRPrinting.purchase_no_vat,
                                	RRPrinting.purchase_vat,
                                	RRPrinting.purchase_price,
                                	RRPrinting.quantity,
                                    Product.unit,
                                	RRPrinting.total_purchase_price,
                                	'RR' as type 
                                from View_CIRR_NOIV
                                inner join RR on RR.rr_no = View_CIRR_NOIV.ci_no
                                inner join RRPrinting on RRPrinting.rr_no = View_CIRR_NOIV.ci_no
                                left join Product on Product.product_no = RRPrinting.product_no
                                inner join Supplier on Supplier.supplier_no = View_CIRR_NOIV.supplier_no AND Product.product_line = Supplier.product_line
                                union
                                select
                                	RI.ri_no,
                                    RI.ri_date,
                                    RI.approved_employee,
                                    RI.supplier_no,
                                    '-',
                                    RI.total_return_no_vat * -1,
                                    RI.total_return_vat * -1,
                                    RI.total_return_price * -1,
                                    RI.cancelled,
                                    RI.note,
                                    PO.po_no,
                                    PO.product_type,
                                    Supplier.*,
                                    RIPrinting.product_no,
                                    Product.product_name,
                                    '-',
                                    RIPrinting.purchase_no_vat * -1,
                                    RIPrinting.purchase_vat * -1,
                                    RIPrinting.purchase_price * -1,
                                    RIPrinting.quantity,
                                    Product.unit,
                                    RIPrinting.total_purchase_price * -1,
                                    'RI'
                                from RI
                                join RIPrinting on RIPrinting.ri_no = RI.ri_no
                                left join RR on RIPrinting.rr_no = RR.rr_no
                                left join PO on PO.po_no = RR.po_no
                                left join Product on Product.product_no = RIPrinting.product_no
                                left join Supplier on Supplier.supplier_no = Product.supplier_no
                                where RR.invoice_no = '-'");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return json_encode([]);
    }
    public function debugIVRC(){
         print_r($_POST);
       
       
       
         // $ivrcItemsArray = json_decode($_POST['ivrcItems'], true);  
        // //$ivrcItemsArray = json_decode(input::post('ivrcItems'), true);  
        // $ivrcItemsArray = json_decode($ivrcItemsArray, true); 


        // $rrciList = array();
        
        // foreach($ivrcItemsArray as $value)

        
        //     print_r ($value);
        
    }
    
    // IVRC Module
    public function addIvrc() {
        

        $ivrcItemsArray = json_decode($_POST['ivrcItems'], true);  
        //$ivrcItemsArray = json_decode(input::post('ivrcItems'), true);  
        $ivrcItemsArray = json_decode($ivrcItemsArray, true); 


        $rrciList = array();
        
        foreach($ivrcItemsArray as $value) {
            if (!in_array($value['ci_no'], $rrciList)) {
                echo $value['ci_no'];
                array_push($rrciList, $value['ci_no']);
                
                // update invoice in RR/CI
                if($value['type'] == 'RR') {
                    $sql=$this->prepare("update RR set invoice_no = ? where rr_no = ?");
                    $sql->execute([$_POST['iv'], $value['ci_no']]);
                } else if ($value['type'] == 'CI') {
                    $sql=$this->prepare("update CI set invoice_no = ? where ci_no = ?");
                    $sql->execute([$_POST['iv'], $value['ci_no']]);
                   
                }
                
                $fileName = $_FILES['taxIV']['name'];
                $fileData = file_get_contents($_FILES['taxIV']['tmp_name']);
                $fileData = base64_encode($fileData);
                $fileType = $_FILES['taxIV']['type'];
                
                // insert invoice file in RRCI_Invoice
                $sql = $this->prepare("insert into RRCI_Invoice (rrci_no, rrci_invoice_name, rrci_invoice_type, rrci_invoice_data) values (?, ?, ?, ?)");
                $sql->execute([$value['ci_no'], $fileName, $fileType, $fileData]);
                print_r($sql->errorInfo());
               
                



                $bill_file_name = $_FILES['bill']['name'];
                $bill_no = $_POST['bill_no'];
                $bill_file_data = base64_encode(file_get_contents($_FILES['bill']['tmp_name']));
                $bill_file_type = $fileType = $_FILES['bill']['type'];

                $tax_file_name = $_FILES['tax']['name'];
                $tax_form_no = $_POST['tax_form_no'];
                $tax_file_data = base64_encode(file_get_contents($_FILES['tax']['tmp_name']));
                $tax_file_type = $_FILES['tax']['type'];

                $debt_reduce_file_name = $_FILES['tax_reduce_upload']['name'];
                $tax_reduce_no = $_POST['tax_reduce_no'];
                $debt_reduce_file_data = base64_encode(file_get_contents($_FILES['tax_reduce_upload']['tmp_name']));
                $debt_reduce_file_type = $_FILES['tax_reduce_upload']['type'];



                // insert invoice file in IVPC_Files
                //echo $bill_file_name;
                //echo $bill_file_type;
                $sql = $this->prepare("insert into IVPC_Files (rrci_no,
                                        bill_file_name,bill_no,bill_file_type,bill_file_data,
                                        tax_file_name,tax_no,tax_file_type,tax_file_data,
                                        debt_reduce_file_name,debt_reduce_no,debt_reduce_file_type,debt_reduce_file_data) 
                                        values (?,?,?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $sql->execute([
                    $value['ci_no'], 
                    $bill_file_name,
                    $bill_no,
                    $bill_file_type,
                    $bill_file_data,

                    $tax_file_name ,
                    $tax_form_no,
                    $tax_file_type ,
                    $tax_file_data ,

                    $debt_reduce_file_name,
                    $tax_reduce_no,
                    $debt_reduce_file_type,
                    $debt_reduce_file_data,
                ]);
                print_r($sql->errorInfo());
                



                //echo print_r($sql->errorInfo());
                
                // ============================================================================================================================================================
                // NEW CBA2022 ACC
                //8.???????????????????????????????????????????????????????????? supplier (?????????????????? Install) 
                //ACC ?????????????????? Tax Invoice ????????? Supplier
                if($value['type'] == 'RR' || $value['type'] == 'CI') { 
                    // insert AccountDetail sequence 8
                    // Dr ???????????????????????????????????????????????? Tax Invoice - Supplier XXX
                    $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                            values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                    $sql->execute([$value['ci_no'], '8',  '21-2'.$value['supplier_no'], (double) $value['confirm_subtotal'], 0, 'CIV']);
                    print_r($sql->errorInfo());
                    
                    // insert AccountDetail sequence 9
                    // Dr ????????????????????????
                    $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                            values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                    $sql->execute([$value['ci_no'], '9', '61-1'.$value['ci_no'][0].'00', (double)  $value['confirm_vat'], 0, 'CIV']);
                    
                    
                    // insert AccountDetail sequence 10
                    // Dr ???????????????????????????????????????????????????-????????????????????? X (???????????????) (51-2X00)
                    $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                    values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                    $sql->execute([$value['ci_no'], '10', '51-2'.$value['ci_no'][0].'00', (double) $value['??'], 0, 'CIV']);

                    // insert AccountDetail sequence 11
                    // Cr ?????????????????????????????????????????? - Supplier XXX
                    $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                            values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                    $sql->execute([$value['ci_no'], '11', '21-1'.$value['supplier_no'], 0, (double)  $value['confirm_total'], 'CIV']);
                    
                
                // } else if ($value['type'] == 'RI') {
                    
                //     // insert AccountDetail sequence 8
                //     // Dr ?????????????????????????????????????????? - Supplier XXX
                //     $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                //                             values (?, ?, ?, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                //     $sql->execute([$value['ci_no'], '8', $_POST['ivrcDate'], '21-1'.$value['supplier_no'], (double) $value['confirm_total'] * -1, 0, 'CIV']);
                    
                //     // insert AccountDetail sequence 9
                //     // Cr ???????????????????????????????????????????????? Tax Invoice - Supplier XXX
                //     $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                //                             values (?, ?, ?, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                //     $sql->execute([$value['ci_no'], '9', $_POST['ivrcDate'], '21-2'.$value['supplier_no'], 0, (double) $value['confirm_subtotal'] * -1, 'CIV']);
                    
                //     // insert AccountDetail sequence 10
                //     // Cr ????????????????????????
                //     $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                //                             values (?, ?, ?, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                //     $sql->execute([$value['ci_no'], '10', $_POST['ivrcDate'], '61-1'.$value['ci_no'][0].'00', 0, (double) $value['confirm_vat'] * -1, 'CIV']);
                    
                }
                if(!$did){ //use first ci_no as ref
                    $did = true;

                    if($_POST["DRCR_cash"] == "61-1100") $diff = $_POST["DRCR_cash"];
                    else $diff = -$_POST["DRCR_cash"];
                    if($value['type'] == 'RR') {
                        $sql=$this->prepare("update RR set diff = ?, diff_dr_sup = ?, diff_dr_tax = ?, diff_cr_sup = ? where rr_no = ?");
                        $sql->execute([$diff,(double) $_POST["diff_dr_sup_cash"],(double) $_POST["diff_dr_tax_cash"],(double) $_POST["diff_cr_sup_cash"], $value['ci_no']]);
                    } else if ($value['type'] == 'CI') {
                        $sql=$this->prepare("update CI set diff = ?, diff_dr_sup = ?, diff_dr_tax = ?, diff_cr_sup = ? where ci_no = ?");
                        $sql->execute([$diff,(double) $_POST["diff_dr_sup_cash"],(double) $_POST["diff_dr_tax_cash"],(double) $_POST["diff_cr_sup_cash"], $value['ci_no']]);
                    }
                    if($_POST["DR"]){
                        // insert AccountDetail sequence 12
                        // DR tax diff
                        $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                        values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                        $sql->execute([$value['ci_no'], '12',$_POST["DR"],(double) $_POST["DRCR_cash"], 0, 'CIV']);
                       
                        $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                                values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                        $sql->execute([$value['ci_no'], '13', $_POST["CR"], 0, (double) $_POST["DRCR_cash"], 'CIV']);
                       
                        $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                        values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                        $sql->execute([$value['ci_no'], '14',$_POST["diff_dr_1_sup"],(double) $_POST["diff_dr_sup_cash"], 0, 'CIV']);
                        

                        $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                                values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                        $sql->execute([$value['ci_no'], '15', $_POST["diff_cr_sup"], 0, (double) $_POST["diff_cr_sup_cash"], 'CIV']);

                        $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                                values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                        $sql->execute([$value['ci_no'], '16', $_POST["diff_dr_2_sup"],  (double) $_POST["diff_dr_tax_cash"],0, 'CIV']);
                    }
                }
            }   
        }
    }

    public function getIVPCFiles($type,$ci_no) { 
        switch($type) {
            case 'bill':
                $statement = "SELECT bill_file_type AS fileType,bill_file_data AS fileData from IVPC_Files where rrci_no = ?";
                break;
            case 'tax':
                $statement = "SELECT tax_file_type AS fileType,tax_file_data AS fileData from IVPC_Files where rrci_no = ?";
                break;
            case 'debt':
                $statement = "SELECT debt_reduce_file_type AS fileType,debt_reduce_file_data AS fileData from IVPC_Files where rrci_no = ?";
                break;
            default:
                header("Location: https://uaterp.cbachula.com/error404"); /* Redirect browser */
                die();
            }


        $sql = $this->prepare($statement);
        $sql->execute([$ci_no]);
        
        if ($sql->rowCount()>0) {
            $data = $sql->fetchAll()[0];
            if($data['fileType']){
                header('Content-type: '.$data['fileType']);
                echo base64_decode($data['fileData']);
            } else echo '??????????????? '.$type.' ?????????????????? pv ?????????';
        } else {
            echo '???????????????????????? pv ?????????';
        }
    }

    public function getIVPCFilesDashboard($type,$pv_no) {
        switch($type) {
            case 'bill':
                $sql = "SELECT bill_file_type AS fileType,bill_file_data AS fileData from IVPC_Files where pv_no = ?";
                break;
            case 'tax':
                $sql = "SELECT tax_file_type AS fileType,tax_file_data AS fileData from IVPC_Files where pv_no = ?";
                break;
            case 'debt':
                $sql = "SELECT debt_reduce_file_type AS fileType,debt_reduce_file_data AS fileData from IVPC_Files where pv_no = ?";
                break;
            default:
                $sql = "SELECT debt_reduce_file_type from IVPC_Files where pv_no = ? AND 0";
          }


        $sql = $this->prepare($sql); 
        $sql->execute([$pv_no]); 
        
        if ($sql->rowCount()>0) {
            $data = $sql->fetchAll()[0];
            if($data['fileType']){
                header('Content-type: '.$data['fileType']);
                echo base64_decode($data['fileData']);
            } else echo '??????????????? '.$type.' ?????????????????? pv ?????????';
        } else {
            echo '???????????????????????? pv ?????????';
        }
    }

    public function getPVBCR($pv_no){
        $sql = "SELECT cr_type,cr_data from PV where pv_no = ?";
        $sql = $this->prepare($sql); 
        $sql->execute([$pv_no]);

        
        if ($sql->rowCount()>0) {
            $data = $sql->fetchAll()[0];
            header('Content-type: '.$data['cr_type']);
            echo $data['cr_data'];
        } else {
            echo '????????????????????? CR ?????????????????? pv ?????????';
        }
    }


    
    // PV-A Module
    public function addPVA() { //depecated move to db PVA and PVA_bundle
        
        $pvno = $this->assignPv('A', input::post('company_code'));
        
        $sql = $this->prepare("insert into PV (pv_no, pv_date, pv_type, vat_type, supplier_no, pv_name, pv_address, total_paid, approved_employee, paid, cancelled, note, thai_text, total_vat, due_date, bank)
                                values (?, CURRENT_TIMESTAMP, '?????????????????????????????????????????????', 1, ?, ?, ?, ?, ?, 0, 0, ?, ?, ?, ?, ?)");  
        $sql->execute([
            $pvno,
            '',
            input::post('pv_name'),
            input::post('pv_address'),
            (double) input::post('totalPaid'),
            json_decode(session::get('employee_detail'), true)['employee_id'],
            '',
            input::post('totalPaidThai'),
            (double) input::post('totalVat'),
            input::post('dueDate'),
            input::post('bank')
        ]);

        
        
        // print_r($sql->errorInfo());
        
        $pvItemsArray = json_decode(input::post('pvItems'), true); 
        $pvItemsArray = json_decode($pvItemsArray, true); 
        
        $i = 1;
        
        foreach($pvItemsArray as $pvItem) {
            
            $sql = $this->prepare("insert into PVPrinting (pv_no, sequence, file_date, debit, iv_no, rr_no, detail, paid_total, cancelled, note, vat)
                                    values (?, ?, ?, ?, ?, ?, ?, ?, 0, null, ?)");  
            $sql->execute([
                $pvno,
                $i,
                $pvItem['date'],
                $pvItem['debit'],
                $pvItem['iv_no'],
                '',
                $pvItem['detail'],
                (double) $pvItem['total_paid'],
                (double) $pvItem['vat']
            ]);
            //CBA2022
            //???
            // update status in WS
            $sql = $this->prepare("update WS set pv_no = ?, status = 2 where form_no = ?");  
            $sql->execute([$pvno, $pvItem['rr_no']]);
            $i++;
            //
            // insert AccountDetail sequence 1
            // Dr ????????????????????????????????? - ????????????????????? x
            //$sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
            //                    values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
            //$sql->execute([$pvno, 1, '11-1'.$pvno[0].'10', (double) $pvItem['total_paid'], 0, 'PV']);
            
            // insert AccountDetail sequence 2
            // Dr ????????????????????????????????? - ????????????????????? x
            //$sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
            //                    values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
            //$sql->execute([$pvno, 2, '12-1300', 0, (double) $pvItem['total_paid'], 'PV']);
            //CBA2022
            //????????????????????????2022
            // insert AccountDetail sequence 1
            // Dr ??????????????????????????????
			//if ($pvItem['debit'] != NULL && $pvItem['debit'] != '' ){
			//	$sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
			//						values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
			//	$sql->execute([$pvno, 1, $pvItem['debit'], (double) $pvItem['total_paid'], 0, 'PV']);
			//}
            
			// insert AccountDetail sequence 2
			// Dr ???????????????????????? - ????????????????????? x (???????????????)
			//if ($pvItem["vat_check"]=='1'){
			//	$sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
			//						values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
			//	$sql->execute([$pvno, 2, '61-1'.$pvno[0].'00', (double) $pvItem['total_paid'], 0, 'PV']);
			//}

            // insert AccountDetail sequence 5
            // Cr ????????????????????????????????? - ????????????????????? x
           // $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
           //                     values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
           // $sql->execute([$pvno, 5, '11-1'.$pvno[0].'10', 0, (double) $pvItem['total_paid'], 'PV']);

        }
        
        echo $pvno;
    }   

    public function getPVAForPV() {
        $sql = $this->prepare("SELECT
                                	PVA_bundle.internal_bundle_no,
                                    PVA_bundle.pv_time,
                                    PVA_bundle.pv_date,
                                    PVA_bundle.total_paid,
                                    PVA_bundle.product_names,
                                    PVA_bundle.additional_cash,
                                    PVA_bundle.additional_cash_reason,
                                    PVA_bundle.employee_id,
                                    Employee.employee_nickname_eng
                                from PVA_bundle
                                INNER JOIN Employee ON BINARY Employee.employee_id = BINARY PVA_bundle.employee_id
                                where PVA_bundle.pv_status = 2");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return json_encode([]);
    }

    public function getPVAChild() {
        $sql = $this->prepare("SELECT
                                	internal_pva_no,
                                    pv_time,
                                    pv_date,
                                    total_paid,
                                    product_names,
                                    tax,
                                    ivrc_name,
                                    slip_name,
                                    fin_slip_name
                                from PVA
                                where internal_bundle_no = ?");
        $sql->execute([$_POST['internal_bundle_no']]);
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return json_encode([]);
    }

    

    public function getPettyCashStatement($bundle_no){
        $sql = "SELECT PCS_type,PCS_data from PVA_bundle where internal_bundle_no = ?";
        $sql = $this->prepare($sql); 
        $sql->execute([$bundle_no]);

        if ($sql->rowCount()>0) {
            $data = $sql->fetchAll()[0];
            header('Content-type: '.$data['PCS_type']);
            echo base64_decode($data['PCS_data']);
        } else {
            echo '????????????????????? petty cash statement ?????????????????? BPA ?????????';
        }
    }
    //???
    public function postPVAForPV() {
        $success = true;
        $pva_childs = $_POST['pva_child'];
        $pv_no = $this->assignPVA($_POST['program']);
        $sql = $this->prepare("UPDATE PVA_bundle SET approve_employee_id = ?, approve_date = ? ,notes = ?, pv_status = 3, pv_no = ? WHERE internal_bundle_no = ?");
        $success = $success && $sql->execute([json_decode(session::get('employee_detail'),true)['employee_id'],$_POST['approve_date'],$_POST["notes"], $pv_no,$_POST['internal_bundle_no']]);
        if(!$success){
            print_r($sql->errorInfo());
        }
        if($success) {

            if($success) {
                // $n = 1;
                foreach($pva_childs as $pva_child) {

                    //convert string bool to int
                    //if($pva_child["tax"] == "true") $pva_child["tax"] = 1;
                    //else $pva_child["tax"] = 0;

                    //$pva_child["tax"] == (int) $pva_child["tax"]; 


                    // Dr ????????????????????????????????? - ????????????????????? 3
                    $sql = $this->prepare("UPDATE PVA SET tax = ?,debit = ?,pv_status = 3, pv_no = ? WHERE internal_pva_no = ?");
                    $success = $success && $sql->execute([$pva_child['tax'],$pva_child["debit"],$pv_no,$pva_child['internal_pva_no']]);
                    if(!$success){
                        print_r($sql->errorInfo());
                        break;
                    }


                    if($pva_child["tax"] == "true") {
                        // Dr ?????????????????????????????? with tax   
                        if(!empty($pva_child["debit"])){ 
                            $sql = $this->prepare("INSERT into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                            values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                            $success = $success && $sql->execute([$pva_child["internal_pva_no"], 1, $pva_child["debit"], (double) ($pva_child["total_paid"] *100/107), 0, 'PVA']);
                            $n++;
                            if(!$success){
                                print_r($sql->errorInfo());
                                break;
                            }
                        }
                        
                        $sql = $this->prepare("INSERT into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                        values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                        $success = $success && $sql->execute([$pva_child["internal_pva_no"], 2, "61-1300", (double) ($pva_child["total_paid"] *7/107), 0, 'PVA']);
                        $n++;
                        if(!$success){
                            print_r($sql->errorInfo());
                            break;
                        }

                    } else {
                        // Dr ?????????????????????????????? without tax
                        if(!empty($pva_child["debit"])){ 
                            $sql = $this->prepare("INSERT into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                            values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                            $success = $success && $sql->execute([$pva_child["internal_pva_no"], 1, $pva_child["debit"], (double) ($pva_child["total_paid"]), 0, 'PVA']);
                            $n++;
                            if(!$success){
                                print_r($sql->errorInfo());
                                break;
                            }
                        }
                    }
                }
            }
            // Cr ????????????????????????????????? - ????????????????????? 3 
            $sql = $this->prepare("INSERT into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
            values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
            $success = $success && $sql->execute([$pv_no, 1, "11-1310", 0, (double) ($_POST["total_no_add"]), 'PVA']);
            if(!$success){
                print_r($sql->errorInfo());
            }
        }
        if($success) {
            echo $pv_no;
        } else {
            echo $pv_no;
            echo "error";
            $sql = $this->prepare("UPDATE PVA_bundle SET pv_status = 2, pv_no = null WHERE pv_no = ?");
            $sql->execute([$pv_no]);
            $sql = $this->prepare("UPDATE PVA SET pv_status = 2, pv_no = null WHERE pv_no = ?");
            $sql->execute([$pv_no]);
            $sql = $this->prepare("DELETE FROM AccountDetail file_no = ?"); 
            $sql->execute([$pv_no]);
            $sql = $this->prepare("DELETE FROM AccountDetail file_no = ?"); 
            $sql->execute([$pva_childs[0]["internal_pva_no"]]);
        }
        
    }

    private function assignPVA($program) {
        $rqPrefix = $program.'PA-';
        $sql = $this->prepare( "select ifnull(max(pv_no),0) as max from PVA where pv_no like ?" );
        $sql->execute( [ $program.'PA-%' ] );
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
     
	
    // PV-B Module
	public function getRRCINOPV() {
        $array1 = [];
        $array2 = [];
        $array3 = [];
       

        $sql = $this->prepare("SELECT 
        PVB.*,
        IVPC_Files.tax_no
    FROM
        (select cirr_no_pv.*, Supplier.supplier_name, Supplier.address from (select 
                                        View_CIRR_NOPV.*,
                                        Product.product_no,
                                        Product.product_name,
                                        CIPrinting.purchase_no_vat,
                                        CIPrinting.purchase_vat,
                                        CIPrinting.purchase_price,
                                        CIPrinting.quantity,
                                        Product.unit,
                                        'CI' as type,
                                        CI.diff,CI.diff_dr_sup, CI.diff_cr_sup,CI.diff_dr_tax
                                    from View_CIRR_NOPV
                                    inner join CI on CI.ci_no = View_CIRR_NOPV.ci_no
                                    inner join CIPrinting on CIPrinting.ci_no = View_CIRR_NOPV.ci_no
                                    left join Product on Product.product_no = CIPrinting.product_no
                                    union
                                    select 
                                        View_CIRR_NOPV.*,
                                        Product.product_no,
                                        Product.product_name,
                                        RRPrinting.purchase_no_vat,
                                        RRPrinting.purchase_vat,
                                        RRPrinting.purchase_price,
                                        RRPrinting.quantity,
                                        Product.unit,
                                        'RR' as type,
                                        RR.diff,RR.diff_dr_sup,RR.diff_cr_sup,RR.diff_dr_tax
                                    from View_CIRR_NOPV
                                    inner join RR on RR.rr_no = View_CIRR_NOPV.ci_no
                                    inner join RRPrinting on RRPrinting.rr_no = View_CIRR_NOPV.ci_no
                                    left join Product on Product.product_no = RRPrinting.product_no
                                    union
                                    select
                                        RI.ri_no,
                                        RI.ri_date,
                                        RI.approved_employee,
                                        RI.supplier_no,
                                        RR.invoice_no,
                                        RI.total_return_no_vat * -1,
                                        RI.total_return_vat * -1,
                                        RI.total_return_price * -1,
                                        PO.po_no,
                                        Supplier.vat_type,
                                        RIPrinting.product_no,
                                        Product.product_name,
                                        RIPrinting.purchase_no_vat * -1,
                                        RIPrinting.purchase_vat * -1,
                                        RIPrinting.purchase_price * -1,
                                        RIPrinting.quantity,
                                        Product.unit,
                                        'RI',
                                        0 as diff,diff_dr_sup,diff_dr_tax,diff_cr_sup
                                    from RI
                                    join RIPrinting on RIPrinting.ri_no = RI.ri_no
                                    left join RR on RIPrinting.rr_no = RR.rr_no
                                    left join PO on PO.po_no = RR.po_no
                                    left join Product on Product.product_no = RIPrinting.product_no
                                    left join Supplier on Supplier.supplier_no = Product.supplier_no
                                    where RR.invoice_no <> '-') as cirr_no_pv
                                    inner join Supplier on Supplier.supplier_no = cirr_no_pv.supplier_no
                                    left join PVPrinting on PVPrinting.rr_no = cirr_no_pv.ci_no
                                    where PVPrinting.rr_no is null
    ORDER BY `cirr_no_pv`.`ci_no`  DESC) AS PVB 
    LEFT join IVPC_Files on BINARY(PVB.ci_no) = BINARY(IVPC_Files.rrci_no)");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
            
        }else{
            return "error or empty";
        }   
        
     
        
    }
    
    // PV-B Module
    public function getRRCIInvoice($rrci_no) {
        
        $sql = $this->prepare("select * from RRCI_Invoice where rrci_no = ?");
        $sql->execute([$rrci_no]);
    
        if ($sql->rowCount()>0) {
            $data = $sql->fetchAll()[0];
            if($data['rrci_invoice_type']){
                header('Content-type: '.$data['rrci_invoice_type']);
                echo base64_decode($data['rrci_invoice_data']);
            } else echo '???????????????????????????????????????/?????????????????????????????????????????? RR/CI ?????????'; 
        } else {
            echo '????????????????????? RR/CI ?????????'; 
        }
}
    
    // PV-B Module
    public function addPVB() {   
        $pvno = $this->assignPv('B', input::post('company_code'));
        
        $sql = $this->prepare("insert into PV (pv_no, pv_date, pv_type, vat_type, supplier_no, pv_name, pv_address, total_paid, approved_employee, paid, cancelled, note, thai_text, total_vat, due_date, bank)
                                values (?, CURRENT_TIMESTAMP, 'Supplier', ?, ?, ?, ?, ?, ?, 0, 0, ?, ?, ?, ?, ?)");  
        $sql->execute([
            $pvno,
            input::post('vat_type'),
            input::post('supplier_no'),
            input::post('pv_name'),
            input::post('pv_address'),
            (double) input::post('totalPaid'),
            json_decode(session::get('employee_detail'), true)['employee_id'],
            '',
            input::post('totalPaidThai'),
            (double) input::post('totalVat'),
            input::post('dueDate'),
            input::post('bank')
        ]);
        print_r( $sql->errorInfo());
        //add pv_no to 	IVPC_Files

        $sql = $this->prepare("update IVPC_Files set pv_no = ? where rrci_no = ?");     
        $sql->execute([
            $pvno,
            input::post('ci_no')
        ]); 
        print_r( $sql->errorInfo());
        
        $pvItemsArray = json_decode(input::post('pvItems'), true); 
        $pvItemsArray = json_decode($pvItemsArray, true); 
        
        $i = 1;
        
        foreach($pvItemsArray as $pvItem) {
            
            $sql = $this->prepare("insert into PVPrinting (pv_no, sequence, file_date, debit, iv_no, rr_no, detail, paid_total, cancelled, note, vat,diff_supplier)
                                    values (?, ?, ?, ?, ?, ?, ?, ?, 0, null, ?,?)");  
            $sql->execute([
                $pvno,
                $i,
                $pvItem['date'],
                $pvItem['debit'],
                $pvItem['iv_no'],
                $pvItem['rr_no'],
                $pvItem['detail'],
                (double) $pvItem['total_paid'] + (double)input::post('diff_sup'),
                (double) $pvItem['vat'],
                input::post('diff_sup')
            ]);
            // echo " debug: ". $sql->errorInfo()[0]." ";
            
            $i++;
            //???????????????????????????????????????????????????????????? supplier  (?????? Confirm payment voucher : PV-B) 
            // insert AccountDetail sequence 3
            // // Dr ?????????????????????????????????????????? - Supplier XXX
           

            // insert AccountDetail sequence 12
            // Dr ??????????????????????????????????????????????????????????????????????????? - ????????????????????? x
            //$sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
            //                    values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
            //$sql->execute([$pvItem['rr_no'], 12, '12-1'.$pvno[0].'00', 0, (double) $pvItem['total_paid'], 'CI']);
            
            // insert AccountDetail sequence 11
            // Dr ??????????????????????????????????????????????????????????????????????????? - ????????????????????? x
            //$sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
            //                    values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
            //$sql->execute([$pvItem['rr_no'], 11, '12-1'.$pvno[0].'10', (double) $pvItem['total_paid'], 0, 'CI']);

            // insert AccountDetail sequence 12
            // Dr ??????????????????????????????????????????????????????????????????????????? - ????????????????????? x
            //$sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
            //                    values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
            //$sql->execute([$pvItem['rr_no'], 12, '12-1'.$pvno[0].'00', 0, (double) $pvItem['total_paid'], 'CI']);
            
        }
        print_r( $sql->errorInfo());
        
        echo $pvno;
       
    } 
    
    // PV-C Module
    public function getWS() {
        $sql = $this->prepare("select
                                    WS.ws_no,
                                    WS.date,
									WS.ws_type,
                                    WS_Form.form_no,
                                    WS_Form.form_name,
                                    WS_IV.iv_no,
                                    WS_IV.iv_name,
                                    WS_IV.iv2_name,
                                    WS_IV.iv3_name,
                                    WS_IV.slip_name,
                                    Employee.employee_id,
                                    Employee.employee_nickname_thai
                                from WS
                                left join WS_Form on WS_Form.form_no = WS.form_no
                                left join WS_IV on WS_IV.iv_no = WS.iv_no
                                inner join Employee on Employee.employee_id = WS.requested_employee
                                where WS.status = 1");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return null;
    }
    
    // PV-C Module
    public function getWsForm($ws_no) {
        
        $sql = $this->prepare("select * from WS left join WS_Form on WS_Form.form_no = WS.form_no
                                where WS.status = 1 and ws_no = ?");
        $sql->execute([$ws_no]);
        
        if ($sql->rowCount() > 0) {
            $data = $sql->fetchAll()[0];
    		header('Content-type: '.$data['form_type']);
            echo $data['form_data'];
        } else {
            echo '????????????????????????????????????????????????????????????????????????????????? WS ?????????';
        }
        
    }
    
    // PV-C Module
    public function getWsIv($ws_no, $file_no) {
        if ($file_no=='iv1'){
			$sql = $this->prepare("select iv_type as file_type, iv_data as file_data from WS left join WS_IV on WS_IV.iv_no = WS.iv_no
                                where WS.status = 1 and ws_no = ?");
        	$sql->execute([$ws_no]);
		} else if ($file_no=='iv2'){
			$sql = $this->prepare("select iv2_type as file_type, iv2_data as file_data from WS left join WS_IV on WS_IV.iv_no = WS.iv_no
                                where WS.status = 1 and ws_no = ?");
        	$sql->execute([$ws_no]);
		} else if ($file_no=='iv3'){
			$sql = $this->prepare("select iv3_type as file_type, iv3_data as file_data from WS left join WS_IV on WS_IV.iv_no = WS.iv_no
                                where WS.status = 1 and ws_no = ?");
        	$sql->execute([$ws_no]);
		} else if ($file_no=='slip'){
			$sql = $this->prepare("select slip_type as file_type, slip_data as file_data from WS left join WS_IV on WS_IV.iv_no = WS.iv_no
                                where WS.status = 1 and ws_no = ?");
        	$sql->execute([$ws_no]);
		}

        if ($sql->rowCount() > 0) {
			$data = $sql->fetchAll()[0];
			header('Content-type: '.$data['file_type']);
			echo $data['file_data'];
            
        } else {
            echo '?????????????????????????????????????????????????????????????????? WS ?????????';
        }
        
    }
	
	// PV-C Module
    private function assignPvc($type, $companyNo) {
        $pvPrefix = $companyNo.'P'.$type.'-';
        $sql = $this->prepare("select ifnull(max(pv_no),0) as max from PVC where pv_no like ?");
        $sql->execute([$pvPrefix.'%']);
        $maxPvNo = $sql->fetchAll()[0]['max'];
        $runningNo = '';
        if($maxPvNo == '0') {
            $runningNo = '00001';
        } else {
            $latestRunningNo = (int) substr($maxPvNo, 4) + 1;
            if(strlen($latestRunningNo) == 5) {
                $runningNo = $latestRunningNo;
            } else {
                for ($x = 1; $x <= 5 - strlen($latestRunningNo); $x++) {
                    $runningNo .= '0';
                }
                $runningNo .= $latestRunningNo;
            }
        }
        return $pvPrefix.$runningNo;
    }   
    public function addPVC() {
        
        $pvno = $this->assignPvc('C', input::post('company_code'));
        
        // $sql = $this->prepare("insert into PV (pv_no, pv_date, pv_type, vat_type, supplier_no, pv_name, pv_address, total_paid, approved_employee, paid, cancelled, note, thai_text, total_vat, due_date, bank)
        //                         values (?, CURRENT_TIMESTAMP, 'Expense', 1, ?, ?, ?, ?, ?, 0, 0, ?, ?, ?, ?, ?)");  
        $sql = $this->prepare("INSERT INTO PVC (pv_no,ex_no,re_req_no,vat_type,pv_details,pv_date,exc_date,pv_due_date,pv_type,approved_employee,pv_address,total_paid,total_paid_thai,pv_payout,pv_payto,bank_book_number,bank_book_name,bank_name) 
        VALUES (?,?,?,?,?,?,CURRENT_TIMESTAMP,?,?,?,?,?,?,?,?,?,?,?)");  
        $sql->execute([
            $pvno,
            input::post('ex_no'),
            input::post('re_req_no'),
            '1',
            
            input::post('detail'),
            input::post('pv_date'),
            
            input::post('dueDate'),
            
           
            "Expense",
            json_decode(session::get('employee_detail'), true)['employee_id'],
            input::post('pv_address'),
            (double) input::post('totalPaid'),
          
            input::post('totalPaidThai'),
            input::post('payout'),
            input::post('payTo'),
            input::post('bankBookNumber'),
            input::post('bankBookName'),
            input::post('bankName')
           
           
            
        ]);
        print_r($sql->errorInfo());
        
        
      
        $sql = $this->prepare("UPDATE `Reimbursement_Request` SET confirmed='1' WHERE re_req_no =?");
        $sql->execute([ input::post('re_req_no')]);
        print_r($sql->errorInfo());
        
      
            
            $sql = $this->prepare("insert into PVPrinting (pv_no, sequence, file_date, debit,  detail, paid_total, cancelled, note, vat)
                                    values (?, ?, ?, ?, ?, ?,  0, null, ?)");  
            $sql->execute([
                $pvno,
                1,
                input::post('pv_date'),
                input::post('debit'),
                input::post('pv_detail'),
                (double)   input::post('totalPaid'),
                (double)   input::post('totalVat'),
            ]);
            print_r($sql->errorInfo());
            
            // update status in WS
            // $sql = $this->prepare("update WS set pv_no = ?, status = 2 where form_no = ?");  
            // $sql->execute([$pvno, $pvItem['rr_no']]);
            
            //! NANI
          
            
            // insert AccountDetail sequence 3
            // Dr ?????????????????????????????? 
		if (input::post('debit') != NULL && input::post('debit') != '' ){
            $sql = $this->prepare("INSERT into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                    values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
			$sql->execute([$pvno, 1, input::post('debit'), (double)  input::post('totalPaid')*100/107, 0, 'PVC']);
            // echo "100/107 check";
            print_r($sql->errorInfo());
		
            
        //     // insert AccountDetail sequence 4
        //     // Dr ???????????????????????? - ????????????????????? X
		
            $sql = $this->prepare("INSERT into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                    values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
			$sql->execute([$pvno, 2, '61-1'.$pvno[0].'00', ((double)  input::post('totalPaid'))*7/107, 0, 'PVC']);
            print_r($sql->errorInfo());
		

        //     // insert AccountDetail sequence 5
        //     // Cr ??????????????????????????????????????????????????????????????????????????? - ????????????????????? x
             $sql = $this->prepare("INSERT into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                        values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
            $sql->execute([$pvno, 3, '23-1'.$pvno[0].'00', 0, (double)  input::post('totalPaid'), 'PVC']);
            print_r($sql->errorInfo());
        // insert AccountDetail sequence 1
        //     // Dr ??????????????????????????????????????????????????????????????????????????? - ????????????????????? x
           
            
        // }x
           
        
        }   
    
    // echo $sql->errorInfo()[0];
    return $pvno;   
}
	// PV Module
    private function assignPv($type, $companyNo) {
        
        $pvPrefix = $companyNo.'P'.$type.'-';
        $sql = $this->prepare("select ifnull(max(pv_no),0) as max from PV where pv_no like ?");
        $sql->execute([$pvPrefix.'%']);
        $maxPvNo = $sql->fetchAll()[0]['max'];
        $runningNo = '';
        if($maxPvNo == '0') {
            $runningNo = '00001';
        } else {
            $latestRunningNo = (int) substr($maxPvNo, 4) + 1;
            if(strlen($latestRunningNo) == 5) {
                $runningNo = $latestRunningNo;
            } else {
                for ($x = 1; $x <= 5 - strlen($latestRunningNo); $x++) {
                    $runningNo .= '0';
                }
                $runningNo .= $latestRunningNo;
            }
        }
        return $pvPrefix.$runningNo;
    }   
    
    // Confirm PV Module
	public function getRRCIPV() {
        $sql = $this->prepare("SELECT
                                	PV.pv_no,
                                    PV.pv_date,
                                    PV.pv_type,
                                    PV.supplier_no,
                                    PV.vat_type,
                                    PV.total_paid,
                                    PV.total_vat,
                                    IFNULL(PV.cr_name,'????????????????????????????????? CR') AS cr_name,
                                    PV.receipt_name,
                                    PV.paid,
                                    PVPrinting.*
                                from PVPrinting
                                inner join PV on PV.pv_no = PVPrinting.pv_no
                                where (PV.slip_name is not null or PV.cr_name is not null) and PV.paid = 0"); 
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return json_encode([]);
    }

    public function getPVAConfirmPV() {
        $sql = $this->prepare("SELECT
                                	pv_no,
                                    pv_time,
                                    pv_date,
                                    (total_paid + additional_cash) as total_paid,
                                    product_names
                                from PVA_bundle
                                where pv_status = 4");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return json_encode([]);
    }

    public function getPVAChildConfirmPV() {
        $sql = $this->prepare("SELECT
                                	pv_no,
                                    pv_time,
                                    pv_date,
                                    (total_paid + additional_cash) as total_paid,
                                    product_names
                                from PVA_bundle
                                where pv_status = 4");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return json_encode([]);
    }



    // Confirm PV Module
    public function getSlipData($pv_no) {
        
        $sql = $this->prepare("select * from PVC where PVC.pv_no = ?");
        $sql->execute([$pv_no]);
        
        if ($sql->rowCount()>0) {
            $data = $sql->fetchAll()[0];
    		header('Content-type: '.$data['slip_type']);
           echo base64_decode($data['slip_data']);
        } else {
            echo '????????????????????????????????????????????????????????? PV ?????????';
        }
        
    }
    public function getIVData($pv_no) {
        
        $sql = $this->prepare("select * from PVC where PVC.pv_no = ?");
        $sql->execute([$pv_no]);
        
        if ($sql->rowCount()>0) {
            $data = $sql->fetchAll()[0];
    		header('Content-type: '.$data['iv_type']);
           echo base64_decode($data['iv_data']);
        } else {
            echo '????????????????????????????????????????????????????????? PV ?????????';
        }
        
    }

    public function getSlipPVB($pv_no) {
        
        $sql = $this->prepare("select * from PV where PV.pv_no = ?");
        $sql->execute([$pv_no]);
        
        if ($sql->rowCount()>0) {
            $data = $sql->fetchAll()[0];
    		header('Content-type: '.$data['slip_type']);
            echo $data['slip_data'];
        } else {
            echo '????????????????????????????????????????????????????????? PV ?????????';
        }
        
    }
    
    // Confirm PV Module
    public function getReceiptData($pv_no) {
        
        $sql = $this->prepare("select receipt_type,receipt_data from PV where PV.pv_no = ?");
        $sql->execute([$pv_no]);
        
        if ($sql->rowCount()>0) {
            $data = $sql->fetchAll()[0];
    		header('Content-type: '.$data['receipt_type']);
            echo $data['receipt_data'];
        } else {
            echo '????????????????????????????????????????????????????????? / ??????????????????????????????????????????????????? PV ?????????';
        }
    }

    public function getPVAReceiptData($pv_no) {
        $sql = $this->prepare("SELECT slip_type,slip_data from PVA_bundle where pv_no = ?");
        $sql->execute([$pv_no]);
        
        if ($sql->rowCount()>0) {
            $data = $sql->fetchAll()[0];
    		header('Content-type: '.$data['slip_type']);
            echo base64_decode($data['slip_data']);
        } else {
            echo '????????????????????????????????????????????????????????? PV ?????????'; 
        }
    }

    // Confirm PV Module
    public function confirmPV() { //only B use this
        
        $cpvItemsArray = json_decode(input::post('cpvItems'), true); 
        $cpvItemsArray = json_decode($cpvItemsArray, true);
        $i = 3; //start from 3
        
        // ============================================================================================================================================================
        // NEW CBA2020 ACC
        
        foreach($cpvItemsArray as $value) {
            
            // insert AccountDetail sequence i
            // Dr ???????????????
            $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                    values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)");
            $sql->execute([$value['pv_no'], $i, $value['debit'], (double) $value['paid_total'], 0, 'PVB']);
            
            $i++;
            
        }
        
        // insert AccountDetail sequence i+1
        // Cr ???????????????????????????????????????????????? - ????????????????????? X
        $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
        $sql->execute([$cpvItemsArray[0]['pv_no'], $i, '12-1'.$cpvItemsArray[0]['pv_no'][0].'00', 0, (double) $cpvItemsArray[0]['total_paid'], 'PVB']);
        
        // ============================================================================================================================================================
        // END CBA2020 ACC
        
        
        
        //CBA 2022 PVA 
        // } else if ($value['pv_type']=='Expense') {
            
        //     // insert AccountDetail sequence 1
        //     // Dr ?????????????????????????????????????????????????????? - ????????????????????? X
        //     $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
        //                             values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
        //     $sql->execute([$value['pv_no'], '1', '12-3000', (double) $value['total_paid'], 0, 'PV']);
            
        //     // insert AccountDetail sequence 2
        //     // Cr ???????????????????????????????????????????????? - ????????????????????? 3
        //     $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
        //                             values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
        //     $sql->execute([$value['pv_no'], '2', '12-1300', 0, (double) $value['total_paid'], 'PV']);
            
        //     if ($value['vat_type']==0) {
                
        //         // insert AccountDetail sequence 3
        //         // Dr ??????????????????????????????
        //         $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
        //                                 values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
        //         $sql->execute([$value['pv_no'], '5', '53-1100', (double) $value['total_paid'], 0, 'PV']);
                
        //         // insert AccountDetail sequence 4
        //         // Cr ?????????????????????????????????????????????????????? - ????????????????????? X ***
        //         $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
        //                                 values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
        //         $sql->execute([$value['pv_no'], '6', '12-3300', 0, (double) $value['total_paid'], 'PV']);
                
        //     } else {
                
        //         $totalVat = ((double) $value['total_paid']) * 7 / 107;
        //         $totalNoVat = ((double) $value['total_paid']) / 1.07;
                
        //         // insert AccountDetail sequence 3
        //         // Dr ??????????????????????????????
        //         $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
        //                                 values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
        //         $sql->execute([$value['pv_no'], '5', '53-1100', $totalNoVat, 0, 'PV']);
                
        //         // insert AccountDetail sequence 4
        //         // Dr ???????????????????????? - ????????????????????? 0 ***
        //         $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
        //                                 values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
        //         $sql->execute([$value['pv_no'], '6', '61-1000', $totalVat, 0, 'PV']);
                
        //         // insert AccountDetail sequence 5
        //         // Cr ?????????????????????????????????????????????????????? - ????????????????????? X ***
        //         $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
        //                                 values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
        //         $sql->execute([$value['pv_no'], '7', '12-3300', 0, (double) $value['total_paid'], 'PV']);
                
        //     }
            
        // }
        
        // update paid in PV
        $sql = $this->prepare("update PV set paid = 1 where pv_no = ?");  
        $sql->execute([$value['pv_no']]);
        
        echo $value['pv_no'];
        
    }  

    public function confirmPVD() {
        $cpvItemsArray = json_decode(input::post('cpvItems'), true); 
        $pv_no_array = json_decode($cpvItemsArray, true);
        foreach($pv_no_array as $value) {
            $sql = $this->prepare("UPDATE PVD SET PVD_status = 4 WHERE pvd_no = ?");    
            $success = $sql->execute([$value]);
            echo $value;
            echo ' ';

    // $sql = $this->prepare("SELECT
    //                         Invoice.customer_address
    //                         FROM
    //                             SOX
    //                         LEFT JOIN SOXPrinting ON SOX.sox_no = SOXPrinting.sox_no
    //                         LEFT JOIN Invoice ON Invoice.file_no = SOXPrinting.so_no
    //                         where SOX.sox_no = ?
    //                       ");
    // $sql->execute([input::post('sox_no')]);
    // if($sql->rowCount() > 0) {             
    //   $add = $sql->fetchAll(PDO::FETCH_ASSOC)[0]['customer_address']; 
    // }

            if($success) {
                $sql = $this->prepare("SELECT
                                        PVD.sum_total_sales
                                    from PVD
                                    where pvd_no = ?");
                $sql->execute([$value]);
                if($sql->rowCount() > 0) {             
                    $sum_total_sales = $sql->fetchAll(PDO::FETCH_ASSOC)[0]['sum_total_sales']; 
                }
                // $temp = $sql->fetchAll(PDO::FETCH_ASSOC);
                // $sum_total_sales = floatval($temp[0]["sum_total_sales"]);
                //sequence 1 PD
                //dr ?????????????????????????????????????????????????????????????????? 24-1x20
                $sql = $this->prepare("INSERT into AccountDetail(file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                        values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                $sql->execute([$value,'1','24-1'.$value[0].'20',(double) $sum_total_sales,0,'PVD']);

                //sequence 2 PD
                //cr ???????????????????????????????????????????????? 12-1x00
                $sql = $this->prepare("INSERT into AccountDetail(file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                        values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                $sql->execute([$value,'2','12-1'.$value[0].'00',0,(double) $sum_total_sales,'PVD']);


                

                // $sql = $this->prepare("SELECT
                //                         CN.total_commission
                //                     from CN
                //                     left join PVD on PVD.cn_no =CN.cn_no
                //                     where pvd_no = ?");
                // $sql->execute([$value]);
                // if($sql->rowCount() > 0) {             
                //     $total_commission = $sql->fetchAll(PDO::FETCH_ASSOC)[0]['total_commission']; 
                // }
                // $tempp = $sql->fetchAll(PDO::FETCH_ASSOC);
                // $total_commission = floatval($tempp[0]["total_commission"]);

                //sequence 3 PD
                //dr ??????????????????????????????????????????????????????????????? 22-1x00
                // $sql = $this->prepare("INSERT into AccountDetail(file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                //                         values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                // $sql->execute([$value,'3','22-1'.$value[0].'00',(double) $total_commission,0,'PVD']);

                 //sequence 4 PD
                //cr ??????????????????????????????????????? 52-0x00
                // $sql = $this->prepare("INSERT into AccountDetail(file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                //                         values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                // $sql->execute([$value,'4','52-0'.$value[0].'00',0,(double) $total_commission,'PVD']);
            }
        }
    }


    public function confirmPVC() {
        $cpvItemsArray = json_decode(input::post('cpvItems'), true); 
        $pv_no_array = json_decode($cpvItemsArray, true);
        $pvno = input::post('pv_no');
        foreach($pv_no_array as $value) {
            $sql = $this->prepare("UPDATE PVC SET confirmed = 1,confirmed_employee=? WHERE pv_no = ?");    
            $sql->execute([
                json_decode(session::get('employee_detail'), true)['employee_id']
            ,$value]);
            echo $value;
          
        }
        $sql = $this->prepare("INSERT into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                        values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)");  
                $sql->execute([$value, 4, '23-1'.$pvno[0].'00', (double) input::post('totalPaid'), 0, 'PVC']);
               

                //cr ????????????????????????????????????????????????
                $sql = $this->prepare("INSERT into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                        values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                 $sql->execute([$value, 5, '12-1'.$pvno[0].'00', 0, (double) input::post('totalPaid'), 'PVC']);
                
        
    }

    public function confirmPVA() {
        $cpvItemsArray = json_decode(input::post('cpvItems'), true); 
        $pv_no_array = json_decode($cpvItemsArray, true);
        foreach($pv_no_array as $value) {
            $success = true;
            $sql = $this->prepare("UPDATE PVA_bundle SET pv_status = 5 WHERE pv_no = ?");
            $success = $success && $sql->execute([$value]);
            if($success) {
                //calculate real total
                $sql = $this->prepare("SELECT
                                        total_paid,
                                        additional_cash
                                    from PVA_bundle
                                    where pv_no = ?");
                $sql->execute([$value]);
                $temp = $sql->fetchAll(PDO::FETCH_ASSOC);
                $tot = floatval($temp[0]["total_paid"]) + floatval($temp[0]["additional_cash"]);


                //dr ?????????????????????????????????
                $sql = $this->prepare("INSERT into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                        values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)");  
                $success = $success && $sql->execute([$value, 4, '11-1310', (double) $tot, 0, 'PVA']);
                if(!$success) {
                    print_r($sql->errorInfo());
                }

                //cr ????????????????????????????????????????????????
                $sql = $this->prepare("INSERT into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                        values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                $success = $success && $sql->execute([$value, 5, '12-1300', 0, (double) $tot, 'PVA']);
                if(!$success) {
                    print_r($sql->errorInfo());
                }

                $sql = $this->prepare("UPDATE PVA SET pv_status = 5 WHERE pv_no = ?");
                $success = $success && $sql->execute([$value]);
                if(!$success) {
                    print_r($sql->errorInfo());
                }
            }
            if($success) { //there is no check on acc detail
                echo $value;
                echo ' ';
            } else {
                $sql = $this->prepare("UPDATE PVA_bundle SET pv_status = 4 WHERE pv_no = ?");
                $sql->execute([$value]);
                echo "error confirming ".$value." ";
            }
            
        }
    }


    
    // General Journal Module
    public function getAccountDetail() {
        $sql = $this->prepare("select
                                	AccountDetail.date,
                                    AccountName.account_name,
                                	AccountDetail.account_no,
                                    AccountDetail.debit,
                                    AccountDetail.credit,
                                    ifnull(RR.invoice_no, ifnull(CI.invoice_no, AccountDetail.file_no)) as file_no
                                from AccountDetail 
                                left join AccountName on AccountName.account_no = AccountDetail.account_no
                                left join RR on RR.rr_no = AccountDetail.file_no and AccountDetail.note = 'CIV'
                                left join CI on CI.ci_no = AccountDetail.file_no and AccountDetail.note = 'CIV'
                                where AccountDetail.cancelled = 0 and (AccountDetail.debit <> 0 or AccountDetail.credit <> 0)
                                order by date DESC, time, sequence");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return null;
    }
    
    // PO Calculator Module
    public function calculatePo() {
        $sql = $this->prepare("select  
                                    sum(total_purchase_no_vat) as p_no_vat,
                                    sum(total_purchase_vat) as p_vat,
                                    sum(total_purchase_price) as p_price
                                from PO where po_no in (".input::postAngular('po_no').")");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return null;
        //3.??????????????????????????????????????????????????????????????????????????? Stock ????????? Order
                //DR.???????????? - ????????????????????? X 
        $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
        $sql->execute([$iv_no, '1', '51-1'.$rr_no[0].'00', (double) $total_purchase_price, 0, 'RR']);
        
        // Cr .???????????????????????????????????????????????? Tax Invoice - ???????????? Supplier
        $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
        $sql->execute([$iv_no, '2', '21-2'.$value['supplier_no'], 0, (double) $total_purchase_price, 'RR']);
    }   
	
	// Search PO RR CI 
	public function searchPoRrCi() {
		$sql = $this->prepare("SELECT t.* FROM 
								(SELECT PO.po_no, 
										concat(PO.approved_employee, ' ', Employee.employee_nickname_thai) as po_approver, 
										if(concat(ifnull(CI.ci_no,'-'), 
												  ' (', ifnull(CI.approved_employee,'-'), ' ',ifnull(t.employee_nickname_thai,'-'),')') = '- (- -)', 
										   '??????????????? rrci',concat(ifnull(CI.ci_no,'-'), ' (', ifnull(CI.approved_employee,'-'), ' ',ifnull(t.employee_nickname_thai,'-'),')')) 
										as rrci
								FROM PO
								LEFT JOIN CI on PO.po_no = CI.po_no
								INNER JOIN Employee on Employee.employee_id = PO.approved_employee
								LEFT JOIN (SELECT employee_id, employee_nickname_thai FROM Employee) as t on t.employee_id = CI.approved_employee  
								WHERE PO.product_type = 'install'
								UNION
								SELECT PO.po_no, 
										concat(PO.approved_employee, ' ', Employee.employee_nickname_thai) as po_approver, 
										if(concat(ifnull(RR.rr_no,'-'), 
												  ' (', ifnull(RR.approved_employee,'-'), ' ',ifnull(t.employee_nickname_thai,'-'),')') = '- (- -)', 
										   '??????????????? rrci',concat(ifnull(RR.rr_no,'-'), ' (', ifnull(RR.approved_employee,'-'), ' ',ifnull(t.employee_nickname_thai,'-'),')')) 
										as rrci
								FROM PO
								LEFT JOIN RR on PO.po_no = RR.po_no
								INNER JOIN Employee on Employee.employee_id = PO.approved_employee
								LEFT JOIN (SELECT employee_id, employee_nickname_thai FROM Employee) as t on t.employee_id = RR.approved_employee  
								WHERE PO.product_type != 'install') as t
								where t.po_no in (".input::postAngular('po_no').")");
		$sql->execute();
		if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return null;
	}
     
    // Dashboard Module
    public function getDashboardIv($fetchNum) {
        if($fetchNum==0){
            $sql = $this->prepare("select
            invoice_no as file_no,
            invoice_date as file_date,
            invoice_time as file_time,
            invoice_type,
            approved_employee as file_emp_id,
            employee_nickname_thai as file_emp_name,
            file_no as temp,
            SOX.sox_no,
            SOX.cancelled,
            SOX.done,
            Invoice.confirmPrint,
            Invoice.acc_confirm
        from Invoice 
        inner join Employee on Employee.employee_id = Invoice.approved_employee
        inner join SOXPrinting on SOXPrinting.so_no = file_no
        inner join SOX on SOX.sox_no =SOXPrinting.sox_no
        where acc_confirm = 1 and confirmPrint = 1
        order by invoice_date desc, invoice_time desc
        LIMIT 100");
        $sql->execute();

        return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);

        }
        if($fetchNum==999){
            $sql = $this->prepare("select
            invoice_no as file_no,
            invoice_date as file_date,
            invoice_time as file_time,
            invoice_type,
            approved_employee as file_emp_id,
            employee_nickname_thai as file_emp_name,
            file_no as temp,
            SOX.sox_no,
            SOX.cancelled,
            SOX.done,
            Invoice.confirmPrint,
            Invoice.acc_confirm
        from Invoice 
        inner join Employee on Employee.employee_id = Invoice.approved_employee
        inner join SOXPrinting on SOXPrinting.so_no = file_no
        inner join SOX on SOX.sox_no =SOXPrinting.sox_no
        where acc_confirm = 1 and confirmPrint = 1
        order by invoice_date desc, invoice_time desc
        ");
        $sql->execute();

        return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);

        }
        $sql = $this->prepare("select
                                	invoice_no as file_no,
                                    invoice_date as file_date,
                                    invoice_time as file_time,
                                    invoice_type,
                                    approved_employee as file_emp_id,
                                    employee_nickname_thai as file_emp_name,
                                    file_no as temp,
                                    SOX.sox_no,
                                    SOX.cancelled,
                                    SOX.done,
                                    Invoice.confirmPrint,
                                    Invoice.acc_confirm
                                from Invoice 
                                inner join Employee on Employee.employee_id = Invoice.approved_employee
                                inner join SOXPrinting on SOXPrinting.so_no = file_no
                                inner join SOX on SOX.sox_no =SOXPrinting.sox_no
                                where acc_confirm = 1 and confirmPrint = 1
                                order by invoice_date desc, invoice_time desc
                                LIMIT ". $fetchNum.", 100");
        $sql->execute();
        
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        
        
       
    }
	
	// Dashboard Module
	public function getDashboardCr() {
        $sql = $this->prepare("select
                                	CR.cr_no as file_no,
                                    CR.cr_date as file_date,
                                    CR.cr_time as file_time,
                                    Invoice.approved_employee as file_emp_id,
                                    Employee.employee_nickname_thai as file_emp_name,
                                    Invoice.invoice_no as temp
                                from CR 
                                left join Invoice on CR.cr_no = Invoice.cr_no 
                                left join Employee on Employee.employee_id = Invoice.approved_employee
                                order by cr_date desc, cr_time desc");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return null;
    }
    
    // Dashboard Module
    public function getDashboardPv() {
        $sql = $this->prepare("select
                                	pv_no as file_no,
                                    pv_date as file_date,
                                    pv_type as temp,
                                    approved_employee as file_emp_id,
                                    employee_nickname_thai as file_emp_name,
                                    PV.slip_name,
                                    PV.receipt_name,
                                    PV.pv_name,
                                    PV.paid
                                from PV
                                inner join Employee on Employee.employee_id = PV.approved_employee
								where cancelled = 0
                                order by pv_date desc");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return [];
    }

    public function getDashboardPva() {
        $sql = $this->prepare("select
                                	pv_no,
                                    pv_time,
                                    pv_date,
                                    total_paid,
                                    product_names,
                                    additional_cash,
                                    additional_cash_reason,
                                    pv_status
                                from PVA_bundle");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return json_encode([]);
    }

    public function getDashboardExa() {
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

    public function getDashboardPvb() {
        $sql = $this->prepare("SELECT
                                	PV.pv_no as file_no,
                                    pv_date as file_date,
                                    pv_type as temp,
                                    approved_employee as file_emp_id,
                                    employee_nickname_thai as file_emp_name,
                                    PV.slip_name,
                                    PV.receipt_name,
                                    PV.cr_name,
                                    PV.pv_name,
                                    IVPC_Files.rrci_no,
                                    PV.paid
                                from PV
                                inner join Employee on Employee.employee_id = PV.approved_employee
                                left JOIN IVPC_Files on BINARY IVPC_Files.pv_no = BINARY PV.pv_no
								where cancelled = 0 AND pv_type = 'Supplier'
                                order by pv_date desc");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return json_encode([]);
    }

    public function getDashboardPvd() {
        $sql = $this->prepare("SELECT 
                                    PVD.pvd_no,
                                    PVD.pvd_date,
                                    PVD.pvd_time,
                                    PVD.PVD_status,
                                    WSD.wsd_no,
                                    WSD.wsd_status,
                                    WSD.invoice_no,
                                    CN.cn_no,
                                    CN.cn_date,
                                    CN.cn_time

                                    FROM PVD
                                    left join CN on CN.cn_no = PVD.cn_no
                                    left join WSD on CN.wsd_no = WSD.wsd_no
                                ");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return json_encode([]);
    }
    public function getDashboardPrePvd() {
        $sql = $this->prepare("SELECT 
                                    WSD.wsd_no,
                                    WSD.sox_no,
                                    WSD.wsd_status,
                                    WSD.invoice_no,
                                    CN.cn_no,
                                    CN.cn_date,
                                    CN.cn_time

                                    FROM WSD
                                    Left join CN on CN.wsd_no = WSD.wsd_no
        
                                ");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return json_encode([]);
    }
    
    public function getDashboardPvc() {
        $sql = $this->prepare("select ex_no, withdraw_date, total_paid, employee_id, employee_nickname_thai from Reimbursement_Request inner join Employee using(employee_id) where ex_no is not null");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return  $sql->errorInfo();
       
    }

    public function getDashboardPvc_confirm() {
        $sql = $this->prepare("SELECT * FROM `PVC`");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return $sql->errorInfo();
       
    }

    public function getPVCRR($re_req_no){
        $sql = $this->prepare("select quotation_type, quotation_name, quotation_data from Reimbursement_Request where re_req_no=?");
        $sql->execute([$re_req_no]);
        if ($sql->rowCount()>0) {
            $data = $sql->fetchAll()[0];
            header('Content-type: '.$data['quotation_type']);
            echo base64_decode($data['quotation_data']);
        } else {
            echo '??????????????????????????????';
            print_r($sql->errorInfo());
        }
    }
    
    // Dashboard Module
    public function getDashboardPo($company) {
        $sql = $this->prepare("select
                                	PO.po_no as file_no,
                                    po_date as file_date,
                                    concat(Supplier.supplier_no, ' ',Supplier.supplier_name) as temp,
                                    PO.approved_employee as file_emp_id,
                                    employee_nickname_thai as file_emp_name,
                                    RR.rr_no as rr,
                                    CI.ci_no as ci,
                                    ifnull(SO.so_no,'-') as so
                                from PO
                                inner join Employee on Employee.employee_id = PO.approved_employee
                                inner join Supplier on Supplier.supplier_no = PO.supplier_no and Supplier.product_line = PO.product_line
                                LEFT JOIN SO on SO.po_no = PO.po_no
                                left join RR on RR.po_no = PO.po_no
                                left join CI on CI.po_no = PO.po_no
                                where PO.cancelled = 0  AND PO.po_no like '".$company.
                                "%'order by Supplier.supplier_no desc");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return null;
    }
    
    // Dashboard Module
    public function getDashboardPo2() {
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
    
   // Invoice for CS
   
       // CS Module ???????????????????????????????????????????????? Controller
    public function addPointCommission () {
        $sql = $this->prepare("select cs_no, SUM(quantity*point) as total_point, SUM(quantity*commission) as total_commission,
            SUM(quantity*Product.sales_price) as total_sales from CSPrinting 
            inner join Product on Product.product_no = CSPrinting.product_no 
            where cs_no = ? group by cs_no;");
        $sql->execute([input::post('cs_no')]); //????????? cs_no ??????
        $data = $sql->fetchAll()[0];
        $total_point = (double) $data['total_point'];
        $total_commission = (double) $data['total_commission']; 
        $total_sales = (double) $data['total_sales']; 

        $sql = $this->prepare("select Count(*) as count from CSDetail where cs_no = ?;");
        $sql->execute([input::post('cs_no')]);   
        $Divided = $sql->fetchAll()[0]['sum_commission'];  
              
        $sql = $this->prepare("update CSDetail set point = ?, commission = ?, sales = ? where cs_no = ?");
        $sql->execute([$total_point/$Divided, $total_commission/$Divided, $total_sales/$Divided, input::post('cs_number')]);   
    }
    
       // CS Module
    public function getCSforIV() {
        $sql = $this->prepare("select 
                                    CS.cs_no,
                                    CS.cs_date,
                                    CSDetail.employee_id,
                                    CSDetail.point,
                                    CSDetail.commission,
                                    CS.approved_employee,
                                    Emp.employee_nickname_thai as Emp_nickname,
                                    CE.employee_nickname_thai as CE_nickname,
                                    CE.employee_name_thai,
                                    CE.national_id,
                                    CS.location_no,   
                                    CSPrinting.product_no,
                                    Product.product_name,
                                    CSPrinting.sales_no_vat as cs_sales_no_vat,
                                    CSPrinting.sales_vat as cs_sales_vat,
                                    CSPrinting.sales_price as cs_sales_price,
                                    CSPrinting.quantity as cs_quantity,
                                    CSPrinting.quantity_out as cs_quantity_out,
                                    (CSPrinting.quantity_out * CSPrinting.sales_price) as total_sales_price,
                                    CSLocation.location_name
                                from CS 
                                inner join CSPrinting on CS.cs_no = CSPrinting.cs_no
                                left join CSDetail on CSDetail.cs_no = CS.cs_no
                                left join Employee as Emp on Emp.employee_id = CSDetail.employee_id
                                left join Employee as CE  on CE.employee_id = CS.approved_employee
                                inner join CSLocation on CSLocation.location_no = CS.location_no
                                left join Product on Product.product_no = CSPrinting.product_no                                
                                where CS.cancelled = 0 and CS.confirmed = -2 order by CS.cs_no");  
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return json_encode([]);
    }
    
    // CS Module
    public function addIV() {
        
        $sql = $this->prepare("update CS set CS.cancelled = 1, CS.confirmed = -1 where CS.cs_no = ?");
        $sql->execute([input::post('cs_number')]);
        
        $crItemsArray = json_decode(input::post('crItems'), true); 
        $crItemsArray = json_decode($crItemsArray, true); 
        
        $csList = array();
        $csProductList = array();
        $csEmpList = array();
        $iv_no = "";
        
        foreach($crItemsArray as $value) {
            
            if (array_key_exists($value['cs_no'], $csList)) {
                
                $iv_no = $csList[$value['cs_no']];
                
            } else {
                  
                $iv_no = $this->assignIc($value['cs_no']); 
                $csList += [$value['cs_no']=>$iv_no];
                
                echo $iv_no.' ('.$value['cs_no'].') ';
                
                $sql = $this->prepare("select SUM(quantity*sales_vat) as cs_total_sales_vat 
                    from CSPrinting  where cs_no = ?;");
                $sql->execute([$value['cs_no']]);
                $cs_total_sales_vat = $sql->fetchAll()[0]['cs_total_sales_vat'];                
                
                if($cs_total_sales_vat != 0) {
                    $total_sales_no_vat = ((double) input::post('cs_total_sales_price')) / 1.07;
                    $total_sales_vat = ((double) input::post('cs_total_sales_price')) / 107 * 7;
                    $total_sales_price = (double) input::post('cs_total_sales_price');
                } else {
                    $total_sales_no_vat = (double) input::post('cs_total_sales_price');
                    $total_sales_vat = 0;
                    $total_sales_price = (double) input::post('cs_total_sales_price');
                }
                
                // insert IV          
                $sql = $this->prepare("select IFNULL(SUM(point),0)  as sum_point from CSDetail where cs_no = ?");
                $sql->execute([$value['cs_no']]);
                $SumPoint = $sql->fetchAll()[0]['sum_point'];
                
                    
                $sql = $this->prepare("select IFNULL(SUM(commission),0)  as sum_commission from CSDetail where cs_no = ?");
                $sql->execute([$value['cs_no']]);
                $SumCommission = $sql->fetchAll()[0]['sum_commission'];            
                     
                $sql = $this->prepare("insert into Invoice (invoice_no, invoice_date, invoice_time, employee_id, customer_name, customer_address, id_no, file_no,
                                        file_type, total_sales_no_vat, total_sales_vat, total_sales_price, discount, sales_price_thai, point, commission, approved_employee, cr_no, cancelled, note)
                                        values (?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?, ?, 'SO', ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, ?)");  
                $sql->execute([
                    $iv_no,
                    $value['approved_employee'],
                    input::post('cusName'),
                    input::post('cusAddress'),
                    input::post('cusId'),
                    $value['cs_no'],
                    $total_sales_no_vat,
                    $total_sales_vat,
                    $total_sales_price,
                    0,
                     input::post('priceInThai'), 
                    $SumPoint,                    
                    $SumCommission,
                    json_decode(session::get('employee_detail'), true)['employee_id'],
                    $iv_no,
                    input::post('noted')
                ]);
                
                // ============================================================================================================================================================
                //CBA 2022
               
                // NEW CBA2022 ACC
                // insert AccountDetail sequence 1
                //?????????????????????????????????????????????????????? Stock ????????? Order 
                //????????????????????????????????????????????????????????? ( ACC ???????????????????????? Tax invoice) sequence 1-3 IV
                // Dr ????????????????????????????????????????????????
                $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                        values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                $sql->execute([$iv_no, '1', '12-0000', (double) $total_sales_price, 0, 'IV']);
                
                // insert AccountDetail sequence 2
                // Cr ????????? - ????????????????????? X
                $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                        values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                $sql->execute([$iv_no, '2', '24-1'.$iv_no[0].'00', 0, (double) $total_sales_no_vat, 'IV']);
                
                // insert AccountDetail sequence 3
                // Cr ????????????????????? - ????????????????????? X
                $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                        values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                $sql->execute([$iv_no, '3', '62-1'.$iv_no[0].'00', 0, (double) $total_sales_vat, 'IV']);
                
                // Dr ????????????????????????????????????????????????
                $sql = $this->prepare("INSERT into AccountDetail(file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                        values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)");
                $sql->execute([$iv_no, '4', '24-1'.$iv_no[0].'00', (double) $total_sales_price, 0, 'IV']);
                
                // insert AccountDetail sequence 2
                // Cr ????????? - ????????????????????? X
                $sql = $this->prepare("INSERT into AccountDetail(file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                        values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                $sql->execute([$iv_no, '5', '41-1'.$iv_no[0].'00', 0, (double) $total_sales_price, 'IV']);
                
                // Dr ????????? Commission
                $sql = $this->prepare("INSERT into AccountDetail(file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                        values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                $sql->execute([$value,'6','52-0'.$value[0].'00',(double) $total_commission,0,'CS']);

                // Cr ????????? Commission ????????????????????????
                $sql = $this->prepare("INSERT into AccountDetail(file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                        values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                $sql->execute([$value,'7','22-1'.$value[0].'00',0,(double) $total_commission,'CS']);
                         

                //FIN ?????????????????????????????? Pool ??????????????????????????????????????????????????? (?????? TR)
                //Dr. ???????????????????????????????????????????????? ??? ????????????????????? X 
                $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                         values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                $sql->execute([$iv_no, '1', '12-1'.$iv_no[0].'00', (double) $total_sales_price, 0, 'CR']);
                //Cr.  ????????????????????????????????????????????????
                $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                        values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                $sql->execute([$iv_no, '1', '12-0000',0, (double) $total_sales_price, 'CR']);
                // ============================================================================================================================================================
                // END CBA2020 ACC
                
            }
            
            //CSProduct IVPrinting
            if (!(array_key_exists($value['cs_no'].$value['product_no'], $csProductList))) {
            
            //
            $csProductList += [$value['cs_no'].$value['product_no']=>0];
            $accumStock = 0;
            $accumStock  = (double) $value['cs_quantity_out']; 
            
            //rr_no for Stock
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
                                        values (?, ?, 'IC', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, 0, NULL, ?)");
                $sql->execute([$value['product_no'], $iv_no, $cutStock, $rr_no]);     
                

                $sql = $this->prepare("insert into InvoicePrinting (invoice_no, product_no, sales_no_vat, sales_vat, sales_price, quantity, total_sales_price, cancelled, rr_no)
                                    values (?, ?, ?, ?, ?, ?, ?, 0, ?)");  
                $sql->execute([
                    $iv_no,
                    $value['product_no'],
                    (double) $value['cs_sales_no_vat'],
                    (double) $value['cs_sales_vat'],
                    (double) $value['cs_sales_price'],
                    $cutStock,
                    (double) $value['cs_sales_price'] * $cutStock,
                    $rr_no 
                ]);
            
                $accumStock = $accumStock - $cutStock;
            
                }        
              
            }
            
            //CSEmp PointLog
            if (!(array_key_exists($value['cs_no'].$value['employee_id'], $csEmpList))) {
                //
                $csEmpList += [$value['cs_no'].$value['employee_id']=>0];
                                  
                //PointLog
                $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note, cancelled)
                                    values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, 'CS', ?, 0)");  
                $sql->execute([
                    $value['employee_id'],
                    (double) $value['point'],
                    $value['cs_no'] 
                ]);
                     
            }
          
        }

    } 
    
    // CS Module
    private function assignIc($iv_no) {
        $ivPrefix = $iv_no[0].'IC-';
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
    
    
    //RE Product

    public function getSuppliers() {
        $sql = $this->prepare("select supplier_no, supplier_name, id_no, address, product_line, vat_type from Supplier");
        $sql->execute();
        if ($sql->rowCount()>0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return null;
    }
    
    public function getRIProduct() {
        $sql=$this->prepare("select
									Product.product_no,
									Product.product_name,
									Product.product_type,
									Product.product_line,
									Product.supplier_no,
									Supplier.supplier_name,
									ProductCategory.category_name,
									Product.category_no,
									Product.sub_category,
									Product.unit,
									Product.purchase_no_vat,
									Product.purchase_vat,
									Product.purchase_price,
									ifnull(View_SOStock.stock, ifnull(stockXiaomi.stockXiaomi,0)) as stock
								from Product
								left join ProductCategory on (ProductCategory.category_no = Product.category_no and ProductCategory.product_line = Product.product_line)
								inner join Supplier on (Supplier.supplier_no = Product.supplier_no and Supplier.product_line = Product.product_line)
								left join View_SOStock on View_SOStock.stock_product_no = Product.product_no
								left join (select Product.product_no, StockInXiaomi.quantity_in - ifnull(outt.quan_out,0) as stockXiaomi from StockInXiaomi 
											left join Product on Product.product_description = StockInXiaomi.product_description
											left join (select StockOutXiaomi.product_no, sum(StockOutXiaomi.quantity_out) as quan_out from StockOutXiaomi where done = 0 group by StockOutXiaomi.product_no) outt on outt.product_no = Product.product_no) stockXiaomi on stockXiaomi.product_no = Product.product_no");
        $sql->execute([]);
        if ($sql->rowCount()>0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return json_encode([]);
       }
       
     public function addRI() {

        
        $productline = input::post('productLine');
        $company = '1';
        
        if($productline == '1' || $productline == '2' || $productline == '3')
        {
            $company = '1';
        }
        else if($productline == '4' || $productline == '5' )
        {
            $company = '2';
        }
        else{
            $company = '3';
        }
        
        $reno = $this->assignRI($company);


        $sql = $this->prepare("insert into RI (ri_no, ri_date, supplier_no,  total_return_no_vat, total_return_vat, total_return_price,total_return_price_thai, approved_employee, cancelled)
                                values (?, CURRENT_TIMESTAMP, ?, ?, ?, ?, ?, ?, 0)");
                                
        $sql->execute([
            $reno,
            input::post('supplier_no'),
            (double) input::post('totalNoVat'),
            (double) input::post('totalVat'),
            (double) input::post('totalPrice'),
            (input::post('ThaiPrice')),
            session::get('employee_id'),
        ]);

        // print_r($sql->errorInfo());
        
        $reItemsArray = json_decode(input::post('riItems'), true); 
        $reItemsArray = json_decode($reItemsArray, true); 

        foreach($reItemsArray as $value) {
            
            $accumStock = $value['quantity'];
            while( $accumStock > 0)
            {
                $sql = $this->prepare("select * from View_InvoiceStock WHERE product_no = ? and balance > 0 order by file_no");
                $sql -> execute([$value['product_no']]);
                $rrTable = $sql->fetchAll()[0];
                
                $rrStock = (int) $rrTable['balance'];
                $rrno = $rrTable['file_no'];

                if( $accumStock > $rrStock )
                {
                    $cutStock = $rrStock;
                }
                else $cutStock = $accumStock;

                // echo $sql->errorInfo()[0];

                $sql = $this->prepare("insert into RIPrinting (ri_no, product_no,  purchase_no_vat, purchase_vat, purchase_price, quantity, total_purchase_price,  cancelled, rr_no)
                values (?, ?, ?, ?, ?, ?, ?, 0 ,?)");
                $sql->execute([
                $reno,
                $value['product_no'],
                (double) $value['purchase_no_vat'],
                (double) $value['purchase_vat'],
                (double) $value['purchase_price'],
                (double) $cutStock,
                (double) $cutStock * $value['purchase_price'],
                $rrno
                ]);
                // echo $sql->errorInfo()[0];
        
                $sql = $this->prepare("insert into StockOut (product_no, file_no,  file_type, date, time, quantity_out, lot,rr_no)
                values (?, ?,'RI',CURRENT_TIMESTAMP,CURRENT_TIMESTAMP, ?,'0' ,?)");
                $sql->execute([
                $value['product_no'],
                $reno,
                (int) $cutStock,
                $rrno
                ]);
                // echo $sql->errorInfo()[0];
            
                $accumStock = (int) $accumStock - (int) $cutStock;
            
            
            }

            $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                    values (?, ?,CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
            $sql->execute([$reno, '1', '21-1'.(double) input::post('supplier_no'), (double) input::post('totalPrice') * -1, 0, 'RI']);
            print_r($sql->errorInfo());  
            
            // insert AccountDetail sequence 9
            // Cr ???????????????????????????????????????????????? Tax Invoice - Supplier XXX
           //  $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
           //                          values (?, ?, ?, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
           //  $sql->execute([$value['ci_no'], '9', $_POST['ivrcDate'], '21-2'.$value['supplier_no'], 0, (double) $value['confirm_subtotal'] * -1, 'RI']);
                           
            // insert AccountDetail sequence 10
            // Cr ????????????????????????
            $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                    values (?, ?,CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
            $sql->execute([$reno, '2', '51-1'.$reno[0].'10', 0, (double) input::post('totalPrice') * -1, 'RI']);
            print_r($sql->errorInfo());

           //note 2022 $reno[0] = $reno['supplier_no'] 
                
            // $debitRI = '21-2'.(input::post('supplierNo'));
            // $creditRI = '51-1'.$company.'10';
            // //RE Account1
            // $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
            // values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
            // $sql->execute([$reno, '8', $debitRI, (double) input::post('totalNoVat'), 0 , 'RI']);
            // //RE Account2
            // $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
            // values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
            // $sql->execute([$reno, '9', $creditRI, 0 , (double) input::post('totalNoVat') , 'RI']);
            
            
        
            
        }

        
        echo $reno;

        // if($value['type'] == 'RI') {
                    
            // insert AccountDetail sequence 8
            // Dr ?????????????????????????????????????????? - Supplier XXX
            
       
    }
        
    
    private function assignRI($company) {
        
        if($company == '1')
        {
            $rePrefix = '1RI-';
        }
        else if($company == '2' )
        {
            $rePrefix = '2RI-';
        }
        else{
            $rePrefix = '3RI-';
        }

        
        $sql=$this->prepare("select ifnull(max(ri_no),0) as max from RI where ri_no like ?");
        $sql->execute([$rePrefix.'%']);
        $maxReNo = $sql->fetchAll()[0]['max'];
        $runningNo = '';
        if($maxReNo=='0') {
            $runningNo = '00001';
        } else {
            $latestRunningNo = (int) substr($maxReNo, 4) + 1;
            if(strlen($latestRunningNo)==5) {
                $runningNo = $latestRunningNo;
            } else {
                for ($x = 1; $x <= 5 - strlen($latestRunningNo); $x++) {
                    $runningNo .= '0';
                }
                $runningNo .= $latestRunningNo;
            }
        }
        return $rePrefix.$runningNo;
    }
    public function getRIreport() {
        $sql = $this->prepare("SELECT RI.`ri_no`,RI.`ri_date`,concat(Supplier.supplier_no,' : ',Supplier.supplier_name) as ri_supplier ,RI.total_return_price as ri_total 
FROM RI
join Supplier on Supplier.supplier_no = RI.supplier_no");
        $sql->execute();
        return $sql->fetchAll();
    }   
    
	public function getIRDforConfirm(){
		$sql = $this->prepare("SELECT 
									IRD.ird_no,
									IRD.ird_date,
									concat(IRD.approved_employee,' ',Employee.employee_nickname_thai) as approver,
									IRD.file_uploaded,
									IRD.file_name,
									IRD.file_type,
                                    t.ird_total_sales,
                                    t3.ird_total_purchase,
									IRDPrinting.so_no,
									IRDPrinting.sox_no, 
									Invoice.invoice_no,
									SOPrinting.product_no,
									Product.purchase_no_vat,
									Product.purchase_vat,
									Product.purchase_price,
									SOPrinting.quantity,
									(Product.purchase_price * SOPrinting.quantity) as total_purchase,
									SOPrinting.total_sales
								from IRD        
								INNER JOIN IRDPrinting on IRDPrinting.ird_no = IRD.ird_no
								INNER JOIN SOPrinting on SOPrinting.so_no = IRDPrinting.so_no
								INNER JOIN Product on Product.product_no = SOPrinting.product_no
                                INNER JOIN Employee on Employee.employee_id = IRD.approved_employee
                                INNER JOIN Invoice on IRDPrinting.so_no = Invoice.file_no
                                INNER JOIN (SELECT IRD.ird_no, sum(SOPrinting.total_sales) as ird_total_sales from IRD 
                                            INNER JOIN IRDPrinting on IRDPrinting.ird_no = IRD.ird_no
                                            INNER JOIN SOPrinting on SOPrinting.so_no = IRDPrinting.so_no
                                            WHERE IRD.cancelled = 0
                                			group by ird_no) as t on IRD.ird_no = t.ird_no
								INNER JOIN (select ird_no, sum(so_total_purchase) as ird_total_purchase from 
												(select ird_no, so_no, sum(product_total_purchase) as so_total_purchase from 
													(SELECT IRDPrinting.ird_no, IRDPrinting.so_no, Product.product_no, sum(SOPrinting.quantity * Product.purchase_price) as product_total_purchase FROM `IRDPrinting`
													INNER JOIN SOPrinting on SOPrinting.so_no = IRDPrinting.so_no
													INNER JOIN Product on Product.product_no = SOPrinting.product_no
													GROUP BY Product.product_no, IRDPrinting.so_no) as t1
												GROUP BY so_no) as t2
											GROUP BY ird_no) as t3 on IRD.ird_no = t3.ird_no
								WHERE IRD.cancelled = 0 AND IRD.status = 0
								ORDER BY IRD.ird_no asc");
		$sql->execute();
		if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return json_encode([]);
	}
	
	public function getIrdFile($ird_no) {
        
        $sql = $this->prepare("select * from IRD where ird_no = ? and file_uploaded = 1 and status = 0");
        $sql->execute([$ird_no]);
        
        if ($sql->rowCount()>0) {
            $data = $sql->fetchAll()[0];
    		header('Content-type: '.$data['file_type']);
            echo base64_decode($data['file_data']);
        } else {
            echo '???????????????????????????????????? IRD ?????????';
        }
        
    }
		
	//public function confirmIRD() {
	//	
	//	$cirdItemsArray = json_decode(input::post('cirdItems'), true); 
    //    $cirdItemsArray = json_decode($cirdItemsArray, true);
    //    
    //    foreach($cirdItemsArray as $value) {
	//		
	//		$sql = $this->prepare("update IRD 
	//								set status = '1',
	//									total_purchase = ?,
	//									total_sales = ?
	//								where ird_no = ? and cancelled = 0");
	//		$sql->execute([$value['ird_total_purchase'],$value['ird_total_sales'],$value['ird_no']]);
	//		
	//	}
	//}


    



    public function getConfirmIV() {
        $sql = $this->prepare("select
                                Invoice.invoice_no,
                                Invoice.invoice_date,
                                Invoice.invoice_time,
                                Invoice.approved_employee,
                                Invoice.file_no,
                                Invoice.file_type,
                                Invoice.id_no,
                                Invoice.acc_confirm,
                                SO.product_type,
                                SO.vat_type
    
                                from Invoice
                                left join SO on  Invoice.file_no = SO.so_no 
                                where Invoice.acc_confirm = 0
                                order by Invoice.invoice_date, Invoice.invoice_time");
            $sql->execute();
            if ($sql->rowCount() > 0) {
                return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
            }
            return null;
        }
    
    
        public function confirmIV() {
            
            // $civArray = json_decode(input::post('civValue'), true); 
            // $civArray = json_decode($civArray, true);
    
            $invoice_no = input::post('invoice_no');
            
            // foreach($civArray as $value) {
                $sql = $this->prepare("update Invoice 
                                        set acc_confirm = '1'
                                        where invoice_no  = ? ");
                $sql->execute([$invoice_no]);
                
            // }
        }
    



    public function getPrintIV() {
        $sql = $this->prepare("SELECT
                                Invoice.invoice_no,
                                Invoice.invoice_date,
                                Invoice.invoice_time,
                                Invoice.approved_employee,
                                Invoice.acc_confirm,
                                Invoice.file_no,
                                Invoice.id_no,
                                Invoice.customer_name,
                                SO.product_type,
                                Customer.email,
                                SOX.customer_tel,
                                Invoice.customer_address,
                                SOX.fin_form

                                FROM Invoice
                                left join SO on  Invoice.file_no = SO.so_no 
                                left join SOXPrinting on Invoice.file_no = SOXPrinting.so_no
                                left join SOX on SOXPrinting.sox_no=SOX.sox_no
                                left join Customer on SOX.customer_tel = Customer.customer_tel
                                where Invoice.confirmPrint = 0 and Invoice.acc_confirm = 1
                                order by Invoice.invoice_date, Invoice.invoice_time");
            $sql->execute();
            if ($sql->rowCount() > 0) {
                return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
            }
        return null;
    }

    public function confirmPrintIV() {
        $invoice_no = input::post('invoice_no');

		$sql = $this->prepare("UPDATE Invoice 
								set confirmPrint = '1'
								where invoice_no = ? ");
		$sql->execute([$invoice_no]);

		//}
	}
    public function getReReqsDetail(){
        $sql = $this->prepare("SELECT re_req_no, ex_no, withdraw_date, 
        authorize_date,due_date ,withdraw_name, employee_id, line_id, tax_number, 
        bank_name, bank_book_name, bank_book_number, authorizer_name, details,
        evidence, company, debit, return_tax, confirmed, pv_name, pv_address, 
        pv_date, pv_company, total_paid, pv_details, pv_payout,create_date,
         pv_payto,quotation_name FROM `Reimbursement_Request` WHERE Reimbursement_Request.ex_no IS NOT NULL AND Reimbursement_Request.confirmed != '1' AND Reimbursement_Request.confirmed != '-1'");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return null;
        
    }



    public function getPVCs($PVC_No) {
        $sql = $this->prepare("SELECT * FROM `PVC_Demo` WHERE 1");
        $data =$sql->execute([$PVC_No]);        
        
        if($sql->rowCount() > 0){
             header('Content-type: '.$data['quotation_type']);
           
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE) ;
        }else{
            echo $sql;
            echo "Hello";
        }
        
        
        
         // if ($sql->rowCount() > 0) {
         // 	$data = $sql->fetchAll()[0];
         // 	header('Content-type: '.$data['file_type']);
         // 	echo $data['file_data'];
             
         // } else {
         //     echo '?????????????????????????????????????????????????????????????????? WS ?????????';
         // }
         
     }
    public function getQuotation($re_req_no) {
         $sql = $this->prepare("SELECT  quotation_name,quotation_type,quotation_data FROM `Reimbursement_Request` WHERE re_req_no = ?");
         $sql->execute([$re_req_no]);
         if ($sql->rowCount() > 0) {
             $data = $sql->fetchAll()[0];
             header('Content-type: '.$data['quotation_type']);
             echo base64_decode($data['quotation_data']);
         } else {
             echo '????????????????????????????????????????????????????????????????????????????????? WS ?????????';
         }
     } 
    public function postDueDate($PVC_No){
             $sql=$this->prepare("UPDATE PVC_Demo SET due_date=? WHERE PVC_No =?");
             $sql->execute([
                 input::post('due_date'),
             $PVC_No]);
    }
    public function postConfirm($ex_no){
        $sql=$this->prepare("UPDATE Reimbursement_Request SET debit=?, pv_date=?, pv_details=?,total_paid=?, pv_name=?, pv_address=?, pv_company=?, tax_number = ? ,return_tax=?,pv_payout=?,pv_payto=? WHERE ex_no =?");
        $success=$sql->execute([
            input::post('debit'),
            input::post('pv_date'),
            input::post('details'),
            input::post('total_paid'),
            input::post('pv_name'),
            input::post('pv_address'),
            input::post('selected_company'),
            input::post('tax_number'),
            input::post('return_tax'),
            input::post('pv_payout'),
            input::post('pv_payto'),
            $ex_no
           

        ]);
        print_r($sql->errorInfo());
        return $sql->errorInfo()[0];



    }
    public function getPVCConfirmPV(){
        $sql=$this->prepare("SELECT * FROM PVC INNER JOIN Reimbursement_Request ON PVC.ex_no = Reimbursement_Request.ex_no WHERE PVC.confirmed = '0'");
        $sql->execute([]);
        if ($sql->rowCount() > 0) return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE) ;
        else return "error";
    }
    public function getPVCReceiptData($pv_no){
        $sql = $this->prepare("SELECT slip_type,slip_data from PVC where pv_no = ?");
        $sql->execute([$pv_no]);
        
        if ($sql->rowCount()>0) {
            $data = $sql->fetchAll()[0];
    		header('Content-type: '.$data['slip_type']);
            echo base64_decode($data['slip_data']);
        } else {
            echo '????????????????????????????????????????????????????????? PV ?????????'; 
        }
    }
   
    public function  getPVCReceiptIVData($pv_no){
        $sql = $this->prepare("SELECT iv_type,iv_data from PVC where pv_no = ?");
        $sql->execute([$pv_no]);
        
        if ($sql->rowCount()>0) {
            $data = $sql->fetchAll()[0];
    		header('Content-type: '.$data['iv_type']);
            echo base64_decode($data['iv_data']);
        } else {
            echo '????????????????????????????????????????????????????????? PV ?????????'; 
        }
    }

    
    public function getPVDReceiptData($pvd_no){
        $sql = $this->prepare("SELECT 
                                pvd_no as pv_no,
                                slipType as receipt_type ,
                                slipData as receipt_data ,
                                slipName as receipt_name 
                                
                                from PVD 
                                where pvd_no = ?");
        $sql->execute([$pvd_no]);
        
        if ($sql->rowCount()>0) {
            $data = $sql->fetchAll()[0];
    		header('Content-type: '.$data['receipt_type']);
            echo base64_decode($data['receipt_data']);
        } else {
            echo '????????????????????????????????????????????????????????? PV ?????????'; 
        }
    }


    public function getforecastvat() {
        $sql = $this->prepare( "SELECT Invoice.invoice_no,SUBSTRING_INDEX(Invoice.invoice_no, 'IV', '1') as ?????????????????????,Invoice.invoice_date, extract(month from Invoice.invoice_date), Invoice.total_sales_no_vat,Invoice.total_sales_vat,Invoice.total_sales_price FROM Invoice;" );
        $sql->execute();
        if ( $sql->rowCount() > 0 ) {
          return $sql->fetchAll();
        }
        return [];
    }

    
    public function getStm() {
        $stmType =  input::post('selected_stm_type');
        $startdate = input::post('start_date');
        $duedate = input::post('due_date');
       
        
        if($stmType == 'Stm1'){                  
            $sql = "SELECT 
                        AccountName.account_name,
                        AccountDetail.account_no, 
                        IF(AccountDetail.account_no in ('11-1100','11-1110','12-1100','13-1100','13-1110','13-3100','14-0100','15-0100','16-1100','16-1110'), SUM(AccountDetail.debit-AccountDetail.credit) , SUM(AccountDetail.credit-AccountDetail.debit) ) as total_amount,
                        SUBSTRING(AccountDetail.account_no, 1, 4) AS prefix

                        FROM `AccountDetail` 
                        left JOIN AccountName ON AccountName.account_no = AccountDetail.account_no  

                        WHERE (AccountDetail.account_no like '21-11%' 
                        or AccountDetail.account_no like '21-12%' 
                        or AccountDetail.account_no like '21-13%' 
                        or AccountDetail.account_no like '21-21%' 
                        or AccountDetail.account_no like '21-22%'
                        or AccountDetail.account_no like '21-23%'
                        or AccountDetail.account_no in ('11-1100','11-1110','12-1100','13-1100','13-1110','13-3100','14-0100','15-0100','16-1100','16-1110','22-1100','23-1100','24-1100','24-1110','24-1120','25-1100','31-0100','32-0100'))
                        and (AccountDetail.date between ? and ?)

                        group by AccountDetail.account_no  
                        ORDER BY `AccountDetail`.`account_no` ASC";
            $statement = $this->prepare($sql);
            $statement->execute([$startdate,$duedate]);

            if ($statement->rowCount() > 0) {
                return json_encode($statement->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
            } else return json_encode([]); 

        }else if($stmType == 'Stm2'){
            $sql = "SELECT 
                        AccountName.account_name,
                        AccountDetail.account_no, 
                        IF(AccountDetail.account_no in ('11-1200','11-1210','12-1200','13-1200','13-1210','13-3200','14-0200','15-0200','16-1200','16-1210'), SUM(AccountDetail.debit-AccountDetail.credit), SUM(AccountDetail.credit-AccountDetail.debit)  ) as total_amount,
                        SUBSTRING(AccountDetail.account_no, 1, 4) AS prefix

                        FROM `AccountDetail` 
                        left JOIN AccountName ON AccountName.account_no = AccountDetail.account_no  
                        
                        WHERE (AccountDetail.account_no like '21-14%' 
                        or AccountDetail.account_no like '21-15%' 
                        or AccountDetail.account_no like '21-24%' 
                        or AccountDetail.account_no like '21-25%' 
                        or AccountDetail.account_no in ('11-1200','11-1210','12-1200','13-1200','13-1210','13-3200','14-0200','15-0200','16-1200','16-1210','22-1200','23-1200','24-1200','24-1210','24-1220','25-1200','31-0200','32-0200'))
                        and (AccountDetail.date between ? and ?)

                        group by AccountDetail.account_no  
                        ORDER BY `AccountDetail`.`account_no` ASC";
            $statement = $this->prepare($sql);
            $statement->execute([$startdate,$duedate]);

            if ($statement->rowCount() > 0) {
                return json_encode($statement->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
            } else return json_encode([]); 

        }else if($stmType == 'Stm3'){          
             $sql = "SELECT 
                        AccountName.account_name,
                        AccountDetail.account_no , 
                        IF(AccountDetail.account_no in ('11-1300','11-1310','12-1300','13-1300','13-1310','13-3300','14-0300','15-0300','16-1300','16-1310'), SUM(AccountDetail.debit-AccountDetail.credit) , SUM(AccountDetail.credit-AccountDetail.debit) ) as total_amount,
                        SUBSTRING(AccountDetail.account_no, 1, 4) AS prefix

                        FROM `AccountDetail` 
                        left JOIN AccountName ON AccountName.account_no = AccountDetail.account_no  

                        WHERE (AccountDetail.account_no like '21-16%' 
                        or AccountDetail.account_no like '21-17%' 
                        or AccountDetail.account_no like '21-18%' 
                        or AccountDetail.account_no like '21-19%' 
                        or AccountDetail.account_no like '21-10%' 
                        or AccountDetail.account_no like '21-26%' 
                        or AccountDetail.account_no like '21-27%' 
                        or AccountDetail.account_no like '21-28%' 
                        or AccountDetail.account_no like '21-29%' 
                        or AccountDetail.account_no like '21-20%' 
                        or AccountDetail.account_no in ('11-1300','11-1310','12-1300','13-1300','13-1310','13-3300','14-0300','15-0300','16-1300','16-1310','22-1300','23-1300','24-1300','24-1310','24-1320','25-1300','31-0300','32-0300'))
                        and (AccountDetail.date between ? and ?)

                        group by AccountDetail.account_no  
                        ORDER BY `AccountDetail`.`account_no` ASC";
            $statement = $this->prepare($sql);
            $statement->execute([$startdate,$duedate]);

            if ($statement->rowCount() > 0) {
                return json_encode($statement->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
            } else return json_encode([]); 

        }
        // else if($stmType == 'Stmspe1'){         
        //     $sql = "SELECT 
        //                 AccountName.account_name,
        //                 AccountDetail.account_no , 
        //                 IF(AccountDetail.account_no in ('11-2100','12-2100','13-2100','16-2100','16-2110'), SUM(AccountDetail.debit-AccountDetail.credit) , SUM(AccountDetail.credit-AccountDetail.debit) ) as total_amount,
        //                 SUBSTRING(AccountDetail.account_no, 1, 4) AS prefix

        //                 FROM `AccountDetail` 
        //                 left JOIN AccountName ON AccountName.account_no = AccountDetail.account_no  

        //                 WHERE AccountDetail.account_no in ('11-2100','12-2100','13-2100','16-2100','16-2110','23-2100','24-2100','25-2100','31-1100','32-1100') and (AccountDetail.date between ? and ?)

        //                 group by AccountDetail.account_no  
        //                 ORDER BY `AccountDetail`.`account_no` ASC";
        //     $statement = $this->prepare($sql);
        //     $statement->execute([$startdate,$duedate]);

        //     if ($statement->rowCount() > 0) {
        //         return json_encode($statement->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        //     } else return json_encode([]); 

        // }else if($stmType == 'Stmspe2'){
        //     $sql = "SELECT 
        //                 AccountName.account_name,
        //                 AccountDetail.account_no , 
        //                 IF(AccountDetail.account_no in ('11-2100','12-2100','13-2100','16-2100','16-2110'), SUM(AccountDetail.debit-AccountDetail.credit) , SUM(AccountDetail.credit-AccountDetail.debit) ) as total_amount,
        //                 SUBSTRING(AccountDetail.account_no, 1, 4) AS prefix

        //                 FROM `AccountDetail` 
        //                 left JOIN AccountName ON AccountName.account_no = AccountDetail.account_no  

        //                 WHERE AccountDetail.account_no in ('11-2200','12-2200','13-2200','16-2200','16-2210','23-2200','24-2200','25-2200','31-1200','32-1200') and (AccountDetail.date between ? and ?)

        //                 group by AccountDetail.account_no  
        //                 ORDER BY `AccountDetail`.`account_no` ASC";
        //     $statement = $this->prepare($sql);
        //     $statement->execute([$startdate,$duedate]);

        //     if ($statement->rowCount() > 0) {
        //         return json_encode($statement->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        //     } else return json_encode([]); 

        // }
        else if($stmType == 'Stmprofit1'){ 
            $sql = "SELECT 
                        AccountName.account_name,
                        AccountDetail.account_no , 
                        IF(AccountDetail.account_no in ('42-1100','43-1100','44-1100','41-1100','41-1110'), SUM(AccountDetail.credit-AccountDetail.debit) , SUM(AccountDetail.debit-AccountDetail.credit) ) as total_amount,
                        SUBSTRING(AccountDetail.account_no, 1, 4) AS prefix

                        FROM `AccountDetail` 
                        left JOIN AccountName ON AccountName.account_no = AccountDetail.account_no  

                        WHERE AccountDetail.account_no in ('51-1100','51-1110','51-2100','14-0100','52-0100','52-1101','52-1102','52-1103','52-1100','52-2100','52-2110','52-3100','52-3110','52-3120','52-3130','52-3140','52-3150','52-3160','52-3199','53-1100','53-1120','53-2100','53-3100','53-4100','53-5100','63-1100','41-1100','41-1110','42-1100','43-1100','44-1100')
                        and (AccountDetail.date between ? and ?)

                        group by AccountDetail.account_no  
                        ORDER BY `AccountDetail`.`account_no` ASC
            ";
            $statement = $this->prepare($sql);
            $statement->execute([$startdate,$duedate]);

            if ($statement->rowCount() > 0) {
                return json_encode($statement->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
            } else return json_encode([]); 

        }else if($stmType == 'Stmprofit2'){
            $sql = "SELECT 
                        AccountName.account_name,
                        AccountDetail.account_no , 
                        IF(AccountDetail.account_no in ('42-1200','43-1200','44-1200','41-1200','41-1210'), SUM(AccountDetail.credit-AccountDetail.debit) , SUM(AccountDetail.debit-AccountDetail.credit) ) as total_amount,
                        SUBSTRING(AccountDetail.account_no, 1, 4) AS prefix

                        FROM `AccountDetail` 
                        left JOIN AccountName ON AccountName.account_no = AccountDetail.account_no  

                        WHERE AccountDetail.account_no in ('51-1200','51-1210','51-2200','14-0200','52-0200','52-1104','52-1105','52-1200','52-2200','52-2210','52-3200','52-3210','52-3220','52-3230','52-3240','52-3250','52-3260','52-3299','53-1200','53-1220','53-2200','53-3200','53-4200','53-5200','63-1200','41-1200','41-1210','42-1200','43-1200','44-1200')
                        and (AccountDetail.date between ? and ?)

                        group by AccountDetail.account_no  
                        ORDER BY `AccountDetail`.`account_no` ASC
            ";
            $statement = $this->prepare($sql);
            $statement->execute([$startdate,$duedate]);

            if ($statement->rowCount() > 0) {
                return json_encode($statement->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
            } else return json_encode([]); 

        }else if($stmType == 'Stmprofit3'){
            $sql = "SELECT 
                        AccountName.account_name,
                        AccountDetail.account_no , 
                        IF(AccountDetail.account_no in ('41-1300','41-1310','42-1300','43-1300','44-1300'), SUM(AccountDetail.credit-AccountDetail.debit) , SUM(AccountDetail.debit-AccountDetail.credit) ) as total_amount,
                        SUBSTRING(AccountDetail.account_no, 1, 4) AS prefix

                        FROM `AccountDetail` 
                        left JOIN AccountName ON AccountName.account_no = AccountDetail.account_no  

                        WHERE AccountDetail.account_no in ('51-1300','51-1310','51-2300','14-0300','52-0300','52-1106','52-1107','52-1108','52-1109','52-1110','52-1300','52-2300','52-2310','52-3300','52-3310','52-3320','52-3330','52-3340','52-3350','52-3360','52-3399','53-1300','53-1320','53-2300','53-3300','53-4300','53-5300','63-1300','41-1300','41-1310','42-1300','43-1300','44-1300')
                        and (AccountDetail.date between ? and ?)

                        group by AccountDetail.account_no  
                        ORDER BY `AccountDetail`.`account_no` ASC
            ";
            $statement = $this->prepare($sql);
            $statement->execute([$startdate,$duedate]);

            if ($statement->rowCount() > 0) {
                return json_encode($statement->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
            } else return json_encode([]); 
        }
	}



    
    public function gettotalCN() {
        $sql = $this->prepare( "SELECT CN.cn_no,CN.company_code,CN.cn_date, extract(month from CN.cn_date),CN.iv_total_sales, CN.new_total_sales_price,CN.diff_total_sales_vat,CN.vat_total_sales_no_vat,CN.sum_total_sales FROM CN;" );
        $sql->execute();
        if ( $sql->rowCount() > 0 ) {
          return $sql->fetchAll();
        }
        return [];
    }
}