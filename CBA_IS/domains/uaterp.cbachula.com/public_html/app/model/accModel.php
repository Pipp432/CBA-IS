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
                // Dr ลูกหนี้การค้า - โครงการ X
                $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                        values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                $sql->execute([$iv_no, '1', '13-1'.$iv_no[0].'00', (double) $total_sales_price, 0, 'IV']);
                
                // insert AccountDetail sequence 2
                // Cr ลูกหนี้รอ IV - โครงการ X
                $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                        values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                $sql->execute([$iv_no, '2', '13-1'.$iv_no[0].'10', 0, (double) $total_sales_no_vat, 'IV']);
                
                // insert AccountDetail sequence 3
                // Cr ภาษีขาย - โครงการ X
                $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                        values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                $sql->execute([$iv_no, '3', '62-1'.$iv_no[0].'00', 0, (double) $total_sales_vat, 'IV']);
                
				
                // ============================================================================================================================================================
                // END CBA2020 ACC
				} else {
					echo 'เกิดข้อผิดพลาด รบกวนออก IV ใหม่';
					return 'เกิดข้อผิดพลาด รบกวนออก IV ใหม่';
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
    
    public function getpvd() {
        $sql = "SELECT * from PVD where PVD_status = 0";
        $statement = $this->prepare($sql);
        $statement->execute([]);

        if ($statement->rowCount() > 0) {
            return json_encode($statement->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        } else return []; 
    }

    public function updatePVDCreditNote() {
        $sql = "UPDATE PVD SET 
        employee_id = ?,
        employee_line = ?,
        total_amount = ?,
        vat_id = ?,
        sox_no = ?,
        invoice_no = ?,
        note = ? 
        where PVD_status = 0 AND pvd_no = ?"; 
        $statement = $this->prepare($sql);
        $success = $statement->execute([
            input::post("employee_id"),
            input::post("employee_line"),
            input::post("total_amount"),
            input::post("vat_id"),
            input::post("sox_no"),
            input::post("invoice_no"),
            input::post("note"),
            input::post("pvd_no"),
        ]);
        if($success) echo 'success';
        else echo print_r($statement->errorInfo());

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
    
    // CN(PV-D) Module
    public function addCn() {
        
        //$iv_no = $this->assignIv(input::post('file_no')); 
        
        // $cnItemsArray = json_decode(input::post('cnItems'), true); 
        // $cnItemsArray = json_decode($cnItemsArray, true); 
        
        //update PVD status 
        $sql = "UPDATE PVD SET 
            PVD_status = 1
        where PVD_status = 0 AND pvd_no = ?"; 
        $statement = $this->prepare($sql);
        $success = $statement->execute([
            input::post("pvd_no"),
        ]);

        if($success) {
            echo ' success(';
            echo input::post("pvd_no");
            echo')';
        } else {
            echo ' failed(';
            echo input::post("pvd_no");
            echo')';
        }

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
        // // Dr รายได้รับล่วงหน้า - โครงการ X
        // $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
        //                         values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
        // $sql->execute([$iv_no, '1', '24-1'.$iv_no[0].'00', (double) input::post('diff_total_sales_no_vat'), 0, 'CN']);
        
        // // insert AccountDetail sequence 3
        // // Dr ภาษีขาย - โครงการ X
        // $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
        //                         values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
        // $sql->execute([$iv_no, '2', '62-1'.$iv_no[0].'00', (double) input::post('diff_total_sales_vat'), 0, 'CN']);
        
        // // insert AccountDetail sequence 1
        // // Cr เงินฝากออมทรัพย์ - โครงการ X
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
        $sql = "SELECT * from PVD where PVD_status = 1";
        $statement = $this->prepare($sql);
        $statement->execute([]);

        if ($statement->rowCount() > 0) {
            return json_encode($statement->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        } else return json_encode([]); 
    }

    public function updatePVDForPV() {

        $sql = "UPDATE PVD SET  
            company_code = ?, recipent = ?, bank = ?, bank_no = ?, recipent_address = ?
            WHERE PVD_status = 1 AND pvd_no = ?";
        $statement = $this->prepare($sql);
        $success = $statement->execute([
            input::post("company_code"),
            input::post("recipent"),
            input::post("bank"),
            input::post("bank_no"),
            input::post("address"),
            input::post("pvd_no"),
        ]);
        if($success) echo ' สำเร็จ';
        else print_r($statement->errorInfo());
    }

    
    public function postPVDForPV() {
        $sql = "UPDATE PVD SET 
            PVD_status = 2, company_code = ?
        where PVD_status = 1 AND pvd_no = ?"; 
        $statement = $this->prepare($sql);
        $success = $statement->execute([
            input::post("company_code"),
            input::post("pvd_no"),
        ]);
        if($success) echo ' สำเร็จ';
        else print_r($statement->errorInfo());
    }

    public function getPVDConfirmPV() {
        $sql = "SELECT 
                    pvd_no as pv_no, 
                    pvd_date as pv_date,
                    total_amount as total_paid,
                    slipName as receipt_name
                from PVD where PVD_status = 3";
        $sql = $this->prepare($sql);
        $sql->execute([]);

        if ($sql->rowCount() > 0) {
            return json_encode($ret, JSON_UNESCAPED_UNICODE);
        } else return json_encode([]); 
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
                                    Supplier.supplier_name,
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
                                    Supplier.supplier_name,
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
                                inner join Supplier on Supplier.supplier_no = View_CIRR_NOIV.supplier_no
                                union
                                select
                                	RE.re_no,
                                    RE.re_date,
                                    RE.approved_employee,
                                    RE.supplier_no,
                                    '-',
                                    RE.total_return_no_vat * -1,
                                    RE.total_return_vat * -1,
                                    RE.total_return_price * -1,
                                    RE.cancelled,
                                    RE.note,
                                    PO.po_no,
                                    PO.product_type,
                                    Supplier.supplier_name,
                                    REPrinting.product_no,
                                    Product.product_name,
                                    '-',
                                    REPrinting.purchase_no_vat * -1,
                                    REPrinting.purchase_vat * -1,
                                    REPrinting.purchase_price * -1,
                                    REPrinting.quantity,
                                    Product.unit,
                                    REPrinting.total_purchase_price * -1,
                                    'RE'
                                from RE
                                join REPrinting on REPrinting.re_no = RE.re_no
                                left join RR on REPrinting.rr_no = RR.rr_no
                                left join PO on PO.po_no = RR.po_no
                                left join Product on Product.product_no = REPrinting.product_no
                                left join Supplier on Supplier.supplier_no = Product.supplier_no
                                where RR.invoice_no = '-'");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return json_encode([]);
    }
    
    // IVRC Module
    public function addIvrc() {
        

        $ivrcItemsArray = json_decode($_POST['ivrcItems'], true);  
        //$ivrcItemsArray = json_decode(input::post('ivrcItems'), true);  
        $ivrcItemsArray = json_decode($ivrcItemsArray, true); 


        $rrciList = array();
        
        foreach($ivrcItemsArray as $value) {
            if (!in_array($value['ci_no'], $rrciList)) {
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
                //echo print_r($sql->errorInfo());



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



                //echo print_r($sql->errorInfo());
                
                // ============================================================================================================================================================
                // NEW CBA2020 ACC
                
                if($value['type'] == 'RR' || $value['type'] == 'CI') {
                    
                    // insert AccountDetail sequence 8
                    // Dr เจ้าหนี้การค้ารอ Tax Invoice - Supplier XXX
                    $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                            values (?, ?, ?, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                    $sql->execute([$value['ci_no'], '8', $_POST['ivrcDate'], '21-2'.$value['supplier_no'], (double) $value['confirm_subtotal'], 0, 'CIV']);
                    
                    // insert AccountDetail sequence 9
                    // Dr ภาษีซื้อ
                    $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                            values (?, ?, ?, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                    $sql->execute([$value['ci_no'], '9', $_POST['ivrcDate'], '61-1'.$value['ci_no'][0].'00', (double) $value['confirm_vat'], 0, 'CIV']);
                    
                    // insert AccountDetail sequence 10
                    // Cr เจ้าหนี้การค้า - Supplier XXX
                    $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                            values (?, ?, ?, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                    $sql->execute([$value['ci_no'], '10', $_POST['ivrcDate'], '21-1'.$value['supplier_no'], 0, (double) $value['confirm_total'], 'CIV']);
                
                } else if ($value['type'] == 'RE') {
                    
                    // insert AccountDetail sequence 8
                    // Dr เจ้าหนี้การค้า - Supplier XXX
                    $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                            values (?, ?, ?, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                    $sql->execute([$value['ci_no'], '8', $_POST['ivrcDate'], '21-1'.$value['supplier_no'], (double) $value['confirm_total'] * -1, 0, 'CIV']);
                    
                    // insert AccountDetail sequence 9
                    // Cr เจ้าหนี้การค้ารอ Tax Invoice - Supplier XXX
                    $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                            values (?, ?, ?, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                    $sql->execute([$value['ci_no'], '9', $_POST['ivrcDate'], '21-2'.$value['supplier_no'], 0, (double) $value['confirm_subtotal'] * -1, 'CIV']);
                    
                    // insert AccountDetail sequence 10
                    // Cr ภาษีซื้อ
                    $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                            values (?, ?, ?, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                    $sql->execute([$value['ci_no'], '10', $_POST['ivrcDate'], '61-1'.$value['ci_no'][0].'00', 0, (double) $value['confirm_vat'] * -1, 'CIV']);
                    
                }
                
                // ============================================================================================================================================================
                // END CBA2020 ACC
                
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
            header('Content-type: '.$data['fileType']);
            echo base64_decode($data['fileData']);
        } else {
            echo 'ไม่มีใบ'.$type.'ของเลข WS นี้';
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
            header('Content-type: '.$data['fileType']);
            echo base64_decode($data['fileData']);
        } else {
            echo 'ไม่มีใบ'.$type.'ของเลข pv นี้';
        }
    }

    public function getPVBCR($pv_no){
        $sql = "SELECT * from PV where pv_no = ?";
        $sql = $this->prepare($sql); 
        $sql->execute([$pv_no]);

        if ($sql->rowCount()>0) {
            $data = $sql->fetchAll()[0];
            header('Content-type: '.$data['cr_type']);
            echo $data['cr_data'];
        } else {
            echo 'ไม่มีใบ CR ของเลข pv นี้';
        }
    }


    
    // PV-A Module
    public function addPVA() { //depecated move to db PVA and PVA_bundle
        
        $pvno = $this->assignPv('A', input::post('company_code'));
        
        $sql = $this->prepare("insert into PV (pv_no, pv_date, pv_type, vat_type, supplier_no, pv_name, pv_address, total_paid, approved_employee, paid, cancelled, note, thai_text, total_vat, due_date, bank)
                                values (?, CURRENT_TIMESTAMP, 'เติมเงินรองจ่าย', 1, ?, ?, ?, ?, ?, 0, 0, ?, ?, ?, ?, ?)");  
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
            
            // update status in WS
            $sql = $this->prepare("update WS set pv_no = ?, status = 2 where form_no = ?");  
            $sql->execute([$pvno, $pvItem['rr_no']]);
            $i++;

            // insert AccountDetail sequence 1
            // Dr เงินรองจ่าย - โครงการ x
            $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
            $sql->execute([$pvno, 1, '11-1'.$pvno[0].'10', (double) $pvItem['total_paid'], 0, 'PV']);
            
            // insert AccountDetail sequence 2
            // Dr เงินรองจ่าย - โครงการ x
            $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
            $sql->execute([$pvno, 2, '12-1300', 0, (double) $pvItem['total_paid'], 'PV']);

            // insert AccountDetail sequence 3
            // Dr ค่าใช้จ่าย
			if ($pvItem['debit'] != NULL && $pvItem['debit'] != '' ){
				$sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
									values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
				$sql->execute([$pvno, 3, $pvItem['debit'], (double) $pvItem['total_paid'], 0, 'PV']);
			}
            
			// insert AccountDetail sequence 4
			// Dr ภาษีซื้อ - โครงการ x (ถ้ามี)
			if ($pvItem["vat_check"]=='1'){
				$sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
									values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
				$sql->execute([$pvno, 4, '61-1'.$pvno[0].'00', (double) $pvItem['total_paid'], 0, 'PV']);
			}

            // insert AccountDetail sequence 5
            // Cr เงินรองจ่าย - โครงการ x
            $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
            $sql->execute([$pvno, 5, '11-1'.$pvno[0].'10', 0, (double) $pvItem['total_paid'], 'PV']);

        }
        
        echo $pvno;
    }   
	
    // PV-B Module
	public function getRRCINOPV() {
        $sql = $this->prepare("select cirr_no_pv.*, Supplier.supplier_name, Supplier.address from (select 
                                	View_CIRR_NOPV.*,
                                    Product.product_no,
                                	Product.product_name,
                                	CIPrinting.purchase_no_vat,
                                	CIPrinting.purchase_vat,
                                	CIPrinting.purchase_price,
                                	CIPrinting.quantity,
                                    Product.unit,
                                	'CI' as type
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
                                	'RR' as type
                                from View_CIRR_NOPV
                                inner join RR on RR.rr_no = View_CIRR_NOPV.ci_no
                                inner join RRPrinting on RRPrinting.rr_no = View_CIRR_NOPV.ci_no
                                left join Product on Product.product_no = RRPrinting.product_no
                                union
                                select
                                	RE.re_no,
                                    RE.re_date,
                                    RE.approved_employee,
                                    RE.supplier_no,
                                    RR.invoice_no,
                                    RE.total_return_no_vat * -1,
                                    RE.total_return_vat * -1,
                                    RE.total_return_price * -1,
                                    PO.po_no,
                                    Supplier.vat_type,
                                    REPrinting.product_no,
                                    Product.product_name,
                                    REPrinting.purchase_no_vat * -1,
                                    REPrinting.purchase_vat * -1,
                                    REPrinting.purchase_price * -1,
                                    REPrinting.quantity,
                                    Product.unit,
                                    'RE'
                                from RE
                                join REPrinting on REPrinting.re_no = RE.re_no
                                left join RR on REPrinting.rr_no = RR.rr_no
                                left join PO on PO.po_no = RR.po_no
                                left join Product on Product.product_no = REPrinting.product_no
                                left join Supplier on Supplier.supplier_no = Product.supplier_no
                                where RR.invoice_no <> '-') as cirr_no_pv
                                inner join Supplier on Supplier.supplier_no = cirr_no_pv.supplier_no
                                left join PVPrinting on PVPrinting.rr_no = cirr_no_pv.ci_no
                                where PVPrinting.rr_no is null");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return null;
    }
    
    // PV-B Module
    public function getRRCIInvoice($rrci_no) {
        
        $sql = $this->prepare("select * from RRCI_Invoice where rrci_no = ?");
        $sql->execute([$rrci_no]);
        
        if ($sql->rowCount()>0) {
            $data = $sql->fetchAll()[0];            
    		header('Content-type: '.$data['rrci_invoice_type']);
            echo base64_decode($data['rrci_invoice_data']);
        } else {
            echo 'ไม่มีใบวางบิล/ใบกำกับภาษีของ RR/CI นี้'; 
        }
}
    
    // PV-B Module
    public function addPVB() {   
        $pvno = $this->assignPv('B', input::post('company_code'));
        
        $sql = $this->prepare("insert into PV (pv_no, pv_date, pv_type, vat_type, supplier_no, pv_name, pv_address, total_paid, approved_employee, paid, cancelled, note, thai_text, total_vat, due_date, bank)
                                values (?, CURRENT_TIMESTAMP, 'Supplier', 1, ?, ?, ?, ?, ?, 0, 0, ?, ?, ?, ?, ?)");  
        $sql->execute([
            $pvno,
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

        //add pv_no to 	IVPC_Files

        $sql = $this->prepare("update IVPC_Files set pv_no = ? where rrci_no = ?");     
        $sql->execute([
            $pvno,
            input::post('ci_no')
        ]);
        
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
                $pvItem['rr_no'],
                $pvItem['detail'],
                (double) $pvItem['total_paid'],
                (double) $pvItem['vat']
            ]);
            
            $i++;
            
            // insert AccountDetail sequence 11
            // Dr เงินฝากออมทรัพย์ส่วนบุคคล - โครงการ x
            $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
            $sql->execute([$pvItem['rr_no'], 11, '12-1'.$pvno[0].'10', (double) $pvItem['total_paid'], 0, 'CI']);

            // insert AccountDetail sequence 12
            // Dr เงินฝากออมทรัพย์ส่วนบุคคล - โครงการ x
            $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
            $sql->execute([$pvItem['rr_no'], 12, '12-1'.$pvno[0].'00', 0, (double) $pvItem['total_paid'], 'CI']);
            
        }
        
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
            echo 'ไม่มีใบเบิกค่าใช้จ่ายของเลข WS นี้';
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
            echo 'ไม่มีใบกำกับภาษีของเลข WS นี้';
        }
        
    }
	
	// PV-C Module
    public function addPVC() {
        
        $pvno = $this->assignPv('C', input::post('company_code'));
        
        $sql = $this->prepare("insert into PV (pv_no, pv_date, pv_type, vat_type, supplier_no, pv_name, pv_address, total_paid, approved_employee, paid, cancelled, note, thai_text, total_vat, due_date, bank)
                                values (?, CURRENT_TIMESTAMP, 'Expense', 1, ?, ?, ?, ?, ?, 0, 0, ?, ?, ?, ?, ?)");  
        $sql = $this->prepare("INSERT INTO PVC (pv_no,ex_no,re_req_no,vat_type,pv_name,pv_date,pv_details,pv_due_date,pv_type,approved_employee,pv_address,total_paid,total_paid_thai) 
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");  
        $sql->execute([
            $pvno,
            '',
            input::post('pv_name'),
            input::post('pv_date'),
            input::post('pv_detail'),
            input::post('dueDate'),
           
            "Expense",
            json_decode(session::get('employee_detail'), true)['employee_id'],
            input::post('pv_address'),
            (double) input::post('totalPaid'),
            json_decode(session::get('employee_detail'), true)['employee_id'],
            '',
            input::post('totalPaidThai'),
            (double) input::post('totalVat'),
            input::post('dueDate'),
            input::post('bank')
        ]);
        
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
            
            // update status in WS
            $sql = $this->prepare("update WS set pv_no = ?, status = 2 where form_no = ?");  
            $sql->execute([$pvno, $pvItem['rr_no']]);
            
            $i++;

            // insert AccountDetail sequence 1
            // Dr เงินฝากออมทรัพย์ส่วนบุคคล - โครงการ x
            $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
            $sql->execute([$pvno, 1, '12-1'.$pvno[0].'10', (double) $pvItem['total_paid'], 0, 'PV']);

            // insert AccountDetail sequence 2
            // Cr เงินฝากออมทรัพย์ - โครงการ x
            $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
            $sql->execute([$pvno, 2, '12-1'.$pvno[0].'00', 0, (double) $pvItem['total_paid'], 'PV']);
            
            // insert AccountDetail sequence 3
            // Dr ค่าใช้จ่าย 
			if ($pvItem['debit'] != NULL && $pvItem['debit'] != '' ){
				$sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
									values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
				$sql->execute([$pvno, 3, $pvItem['debit'], (double) $pvItem['total_paid']/1.07, 0, 'PV']);
			}
            
            // insert AccountDetail sequence 4
            // Dr ภาษีซื้อ - โครงการ X
			if ($pvItem["vat_check"]=='1'){
				$sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
									values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
				$sql->execute([$pvno, 4, '61-1'.$pvno[0].'00', ((double) $pvItem['total_paid'])*7/107, 0, 'PV']);
			}

            // insert AccountDetail sequence 5
            // Cr เงินฝากออมทรัพย์ส่วนบุคคล - โครงการ x
            $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
            $sql->execute([$pvno, 5, '12-1'.$pvno[0].'10', 0, (double) $pvItem['total_paid'], 'PV']);
            
            
        }
        
        echo $pvno;
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
        $sql = $this->prepare("select
                                	PV.pv_no,
                                    PV.pv_date,
                                    PV.pv_type,
                                    PV.supplier_no,
                                    PV.vat_type,
                                    PV.total_paid,
                                    PV.total_vat,
                                    PV.receipt_name,
                                    PV.paid,
                                    PVPrinting.*
                                from PVPrinting
                                inner join PV on PV.pv_no = PVPrinting.pv_no
                                where PV.receipt_data is not null and PV.paid = 0");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return json_encode([]);
    }

    public function getPVAConfirmPV() {
        $sql = $this->prepare("select
                                	pv_no,
                                    pv_time,
                                    pv_date,
                                    total_paid,
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
        
        $sql = $this->prepare("select * from PV where PV.pv_no = ?");
        $sql->execute([$pv_no]);
        
        if ($sql->rowCount()>0) {
            $data = $sql->fetchAll()[0];
    		header('Content-type: '.$data['slip_type']);
            echo $data['slip_data'];
        } else {
            echo 'ไม่มีสลิปโอนเงินของ PV นี้';
        }
        
    }
    
    // Confirm PV Module
    public function getReceiptData($pv_no) {
        
        $sql = $this->prepare("select * from PV where PV.pv_no = ?");
        $sql->execute([$pv_no]);
        
        if ($sql->rowCount()>0) {
            $data = $sql->fetchAll()[0];
    		header('Content-type: '.$data['receipt_type']);
            echo $data['receipt_data'];
        } else {
            echo 'ไม่มีใบสำคัญรับเงิน / ใบเสร็จรับเงินของ PV นี้';
        }
    }

    // Confirm PV Module
    public function confirmPV() {
        
        $cpvItemsArray = json_decode(input::post('cpvItems'), true); 
        $cpvItemsArray = json_decode($cpvItemsArray, true);
        $i = 1;
        
        // ============================================================================================================================================================
        // NEW CBA2020 ACC
        
        foreach($cpvItemsArray as $value) {
            
            // insert AccountDetail sequence i
            // Dr ต่างๆ
            $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                    values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)");
            $sql->execute([$value['pv_no'], $i, $value['debit'], (double) $value['paid_total'], 0, 'PV']);
            
            $i++;
            
        }
        
        // insert AccountDetail sequence i+1
        // Cr เงินฝากออมทรัพย์ - โครงการ X
        $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
        $sql->execute([$cpvItemsArray[0]['pv_no'], $i, '12-1'.$cpvItemsArray[0]['pv_no'][0].'10', 0, (double) $cpvItemsArray[0]['total_paid'], 'PV']);
        
        // ============================================================================================================================================================
        // END CBA2020 ACC
        
        
        
        
        // } else if ($value['pv_type']=='Expense') {
            
        //     // insert AccountDetail sequence 1
        //     // Dr เงินฝากกระแสรายวัน - โครงการ X
        //     $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
        //                             values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
        //     $sql->execute([$value['pv_no'], '1', '12-3000', (double) $value['total_paid'], 0, 'PV']);
            
        //     // insert AccountDetail sequence 2
        //     // Cr เงินฝากออมทรัพย์ - โครงการ 3
        //     $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
        //                             values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
        //     $sql->execute([$value['pv_no'], '2', '12-1300', 0, (double) $value['total_paid'], 'PV']);
            
        //     if ($value['vat_type']==0) {
                
        //         // insert AccountDetail sequence 3
        //         // Dr ค่าใช้จ่าย
        //         $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
        //                                 values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
        //         $sql->execute([$value['pv_no'], '5', '53-1100', (double) $value['total_paid'], 0, 'PV']);
                
        //         // insert AccountDetail sequence 4
        //         // Cr เงินฝากกระแสรายวัน - โครงการ X ***
        //         $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
        //                                 values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
        //         $sql->execute([$value['pv_no'], '6', '12-3300', 0, (double) $value['total_paid'], 'PV']);
                
        //     } else {
                
        //         $totalVat = ((double) $value['total_paid']) * 7 / 107;
        //         $totalNoVat = ((double) $value['total_paid']) / 1.07;
                
        //         // insert AccountDetail sequence 3
        //         // Dr ค่าใช้จ่าย
        //         $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
        //                                 values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
        //         $sql->execute([$value['pv_no'], '5', '53-1100', $totalNoVat, 0, 'PV']);
                
        //         // insert AccountDetail sequence 4
        //         // Dr ภาษีซื้อ - โครงการ 0 ***
        //         $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
        //                                 values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
        //         $sql->execute([$value['pv_no'], '6', '61-1000', $totalVat, 0, 'PV']);
                
        //         // insert AccountDetail sequence 5
        //         // Cr เงินฝากกระแสรายวัน - โครงการ X ***
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
            $sql->execute([$value]);
            echo $value;
            echo ' ';
        }
    }

    public function confirmPVA() {
        $cpvItemsArray = json_decode(input::post('cpvItems'), true); 
        $pv_no_array = json_decode($cpvItemsArray, true);
        foreach($pv_no_array as $value) {
            $success = true;
            $sql = $this->prepare("UPDATE PVA_bundle SET pv_status = 5 WHERE pv_no = ?");
            $success = $success && $sql->execute([$value]);
            if($success) {
                $sql = $this->prepare("UPDATE PVA SET pv_status = 5 WHERE pv_no = ?");
                $success = $success && $sql->execute([$value]);
            }
            if($success) {
                echo $value;
                echo ' ';
            } else {
                $sql = $this->prepare("UPDATE PVA_bundle SET pv_status = 4 WHERE pv_no = ?");
                $sql->execute([$value]);
                echo "error confirming ".$value." ";
                echo print_r($sql->errorInfo());
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
    }   
	
	// Search PO RR CI 
	public function searchPoRrCi() {
		$sql = $this->prepare("SELECT t.* FROM 
								(SELECT PO.po_no, 
										concat(PO.approved_employee, ' ', Employee.employee_nickname_thai) as po_approver, 
										if(concat(ifnull(CI.ci_no,'-'), 
												  ' (', ifnull(CI.approved_employee,'-'), ' ',ifnull(t.employee_nickname_thai,'-'),')') = '- (- -)', 
										   'ไม่มี rrci',concat(ifnull(CI.ci_no,'-'), ' (', ifnull(CI.approved_employee,'-'), ' ',ifnull(t.employee_nickname_thai,'-'),')')) 
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
										   'ไม่มี rrci',concat(ifnull(RR.rr_no,'-'), ' (', ifnull(RR.approved_employee,'-'), ' ',ifnull(t.employee_nickname_thai,'-'),')')) 
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
    public function getDashboardIv() {
        $sql = $this->prepare("select
                                	invoice_no as file_no,
                                    invoice_date as file_date,
                                    invoice_time as file_time,
                                    invoice_type,
                                    approved_employee as file_emp_id,
                                    employee_nickname_thai as file_emp_name,
                                    file_no as temp
                                from Invoice 
                                inner join Employee on Employee.employee_id = Invoice.approved_employee
                                where confirmPrint = 1 and acc_confirm = 1
                                order by invoice_date desc, invoice_time desc");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return null;
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
                                    PV.receipt_name
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
                                    pv_status
                                from PVA_bundle");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return json_encode([]);
    }

    public function getDashboardPvb() {
        $sql = $this->prepare("SELECT
                                	pv_no as file_no,
                                    pv_date as file_date,
                                    pv_type as temp,
                                    approved_employee as file_emp_id,
                                    employee_nickname_thai as file_emp_name,
                                    PV.slip_name,
                                    PV.receipt_name,
                                    PV.cr_name
                                from PV
                                inner join Employee on Employee.employee_id = PV.approved_employee
								where cancelled = 0 AND pv_type = 'Supplier'
                                order by pv_date desc");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return json_encode([]);
    }

    public function getDashboardPvd() {
        $sql = $this->prepare("SELECT pvd_no,invoice_no,sox_no,pvd_date,pvd_time,PVD_status FROM PVD");
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
        return [];
    }

    public function getDashboardPvc_confirm() {
        $sql = $this->prepare("select pv_no, pv_date, total_paid, approved_employee, employee_nickname_thai from PVC inner join Employee on approved_employee=employee_id where ex_no is not null");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return [];
    }
    
    // Dashboard Module
    public function getDashboardPo() {
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
                                where PO.cancelled = 0  
                                order by Supplier.supplier_no desc");
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
   
       // CS Module ยังไม่ได้เพิ่มใน Controller
    public function addPointCommission () {
        $sql = $this->prepare("select cs_no, SUM(quantity*point) as total_point, SUM(quantity*commission) as total_commission,
            SUM(quantity*Product.sales_price) as total_sales from CSPrinting 
            inner join Product on Product.product_no = CSPrinting.product_no 
            where cs_no = ? group by cs_no;");
        $sql->execute([input::post('cs_no')]); //ส่ง cs_no มา
        $data -> $sql->fetchAll()[0];
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
                // NEW CBA2020 ACC
                
                // insert AccountDetail sequence 1
                // Dr เงินฝากออมทรัพย์
                $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                        values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                $sql->execute([$iv_no, '1', '12-0000', (double) $total_sales_price, 0, 'IV']);
                
                // insert AccountDetail sequence 2
                // Cr ขาย - โครงการ X
                $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                        values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                $sql->execute([$iv_no, '2', '41-1'.$iv_no[0].'00', 0, (double) $total_sales_no_vat, 'IV']);
                
                // insert AccountDetail sequence 3
                // Cr ภาษีขาย - โครงการ X
                $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                        values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                $sql->execute([$iv_no, '3', '62-1'.$iv_no[0].'00', 0, (double) $total_sales_vat, 'IV']);
                
                // insert AccountDetail sequence 4 
                // Dr ค่า Commission
                $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                        values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                $sql->execute([$value['cs_no'], '4', '52-1000', $SumCommission, 0, 'CS']);
                
                // insert AccountDetail sequence 5
                // Cr ค่า Commission ค้างจ่าย
                $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                        values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
                $sql->execute([$value['cs_no'], '5', '22-0000', 0, $SumCommission, 'CS']);
                
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
    public function getREProduct() {
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
        $sql->execute();
        if ($sql->rowCount()>0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return json_encode([]);
       }
       
     public function addRE() {

        
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
        
        $reno = $this->assignRE($company);


        $sql = $this->prepare("insert into RE (re_no, re_date, supplier_no,  total_return_no_vat, total_return_vat, total_return_price,total_return_price_thai, approved_employee, cancelled)
                                values (?, CURRENT_TIMESTAMP, ?, ?, ?, ?, ?, ?, 0)");
        $sql->execute([
            $reno,
            input::post('supplierNo'),
            (double) input::post('totalNoVat'),
            (double) input::post('totalVat'),
            (double) input::post('totalPrice'),
            (input::post('ThaiPrice')),
            session::get('employee_id'),
        ]);

        $reItemsArray = json_decode(input::post('reItems'), true); 
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

                $sql = $this->prepare("insert into REPrinting (re_no, product_no,  purchase_no_vat, purchase_vat, purchase_price, quantity, total_purchase_price,  cancelled, rr_no)
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
                
        
                $sql = $this->prepare("insert into StockOut (product_no, file_no,  file_type, date, time, quantity_out, lot,rr_no)
                values (?, ?,'RE',CURRENT_TIMESTAMP,CURRENT_TIMESTAMP, ?,'0' ,?)");
                $sql->execute([
                $value['product_no'],
                $reno,
                (int) $cutStock,
                $rrno
                ]);

            
                $accumStock = (int) $accumStock - (int) $cutStock;
            
            
            }
            $debitRE = '21-2'.(input::post('supplierNo'));
            $creditRE = '51-1'.$company.'10';
            //RE Account1
            $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
            values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
            $sql->execute([$reno, '1', $debitRE, (double) input::post('totalNoVat'), 0 , 'RE']);
            //RE Account2
            $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
            values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
            $sql->execute([$reno, '2', $creditRE, 0 , (double) input::post('totalNoVat') , 'RE']);
            
            
        
            
        }

        
        echo $reno;
        
    }
    private function assignRE($company) {
        
        if($company == '1')
        {
            $rePrefix = '1RE-';
        }
        else if($company == '2' )
        {
            $rePrefix = '2RE-';
        }
        else{
            $rePrefix = '3RE-';
        }

        
        $sql=$this->prepare("select ifnull(max(re_no),0) as max from RE where re_no like ?");
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
    public function getREreport() {
        $sql = $this->prepare("SELECT RE.`re_no`,RE.`re_date`,concat(Supplier.supplier_no,' : ',Supplier.supplier_name) as re_supplier ,RE.total_return_price as re_total 
FROM RE 
join Supplier on Supplier.supplier_no = RE.supplier_no");
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
            echo 'ไม่มีไฟล์ของ IRD นี้';
        }
        
    }
		
	public function confirmIRD() {
		
		$cirdItemsArray = json_decode(input::post('cirdItems'), true); 
        $cirdItemsArray = json_decode($cirdItemsArray, true);
        
        foreach($cirdItemsArray as $value) {
			
			$sql = $this->prepare("update IRD 
									set status = '1',
										total_purchase = ?,
										total_sales = ?
									where ird_no = ? and cancelled = 0");
			$sql->execute([$value['ird_total_purchase'],$value['ird_total_sales'],$value['ird_no']]);
			
		}
	}


    



    public function getConfirmIV() {
        $sql = $this->prepare("select
                                Invoice.invoice_no,
                                Invoice.invoice_date,
                                Invoice.invoice_time,
                                Invoice.approved_employee,
                                Invoice.file_no,
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
        //// change var 
		// $pivArray = json_decode(input::post('pivValue'), true); 
        // $pivArray = json_decode($pivArray, true);

        $invoice_no = input::post('invoice_no');

        // foreach($pivArray as $value) {
			$sql = $this->prepare("UPDATE Invoice 
									set confirmPrint = '1'
									where invoice_no = ? ");
            // $sql = $this->prepare("insert into Invoice (acc_confirm) 
            //                         value (?) ");
			$sql->execute([$invoice_no]);

		//}
	}
    public function getReReqDetail($re_req_no){
        $sql = $this->prepare("SELECT * FROM `Reimbursement_Request` WHERE ex_no IS NOT NULL AND confirmed != '1' ");
        $data =$sql->execute([$re_req_no]);   
        if($sql->rowCount() > 0){
           return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE) ;
       }else{
           echo $sql;
           echo "Hello";
       } 
        
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
         //     echo 'ไม่มีใบกำกับภาษีของเลข WS นี้';
         // }
         
     }
    public function getQuotation($PVC_No) {
         $sql = $this->prepare("SELECT  PVC_Demo.quotation_name,PVC_Demo.quotation_type,PVC_Demo.quotation_image FROM `PVC_Demo` WHERE PVC_Demo.PVC_No = ?");
         $sql->execute([$PVC_No]);
         if ($sql->rowCount() > 0) {
             $data = $sql->fetchAll()[0];
             header('Content-type: '.$data['quotation_type']);
             echo base64_decode($data['quotation_image']);
         } else {
             echo 'ไม่มีใบเบิกค่าใช้จ่ายของเลข WS นี้';
         }
     } 
    public function postDueDate($PVC_No){
             $sql=$this->prepare("UPDATE PVC_Demo SET due_date=? WHERE PVC_No =?");
             $sql->execute([
                 input::post('due_date'),
             $PVC_No]);
    }
    public function postConfirm($ex_no){
        $sql=$this->prepare("UPDATE Reimbursement_Request SET debit=?, pv_date=?, pv_details=?,total_paid=?, pv_name=?, pv_address=?, pv_company=?,  return_tax=? WHERE ex_no =?");
        $success=$sql->execute([
            input::post('debit'),
            input::post('pv_date'),
            input::post('details'),
            input::post('total_paid'),
            input::post('pv_name'),
            input::post('pv_address'),
            input::post('selected_company'),
            
            input::post('return_tax'),
            $ex_no
           

        ]);
        return $sql->errorInfo()[2];



    }
    public function getPVCConfirmPV(){
        $sql=$this->prepare("SELECT * FROM PVC WHERE confirmed IS NULL");
        $sql->execute([]);
        if ($sql->rowCount() > 0) return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE) ;
        else return "error";
    }
}
    




