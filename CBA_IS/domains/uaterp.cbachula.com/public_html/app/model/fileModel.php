<?php

namespace model;

use _core\model;
use _core\helper\session;
use _core\helper\input;
use _core\helper\uri;
use _core\helper\thaiNum;
use PDO;

class fileModel extends model {
	
	public function getPo($po_no) {
		$sql = $this->prepare("select
    								PO.po_no,
    							    PO.po_date,
                                    PO.product_type,
    							    Supplier.supplier_no,
    							    Supplier.supplier_name,
    							    Supplier.address,
    							    POPrinting.product_no,
    							    Product.product_description,
    							    POPrinting.quantity,
    							    Product.unit,
    							    POPrinting.purchase_price,
    							    POPrinting.total_purchase_price,
    							    PO.total_purchase_no_vat as 'po_total_purchase_no_vat',
    							    PO.total_purchase_vat as 'po_total_purchase_vat',
    							    PO.total_purchase_price as 'po_total_purchase_price',
                                    Customer.customer_name,
                                    Customer.customer_surname,
                                    Customer.customer_tel,
                                    SOX.address as customer_address
    							from POPrinting
    							inner join PO on PO.po_no = POPrinting.po_no
    							inner join Supplier on Supplier.supplier_no = PO.supplier_no and Supplier.product_line = PO.product_line
    							left join Product on Product.product_no = POPrinting.product_no
                                left join SO on SO.po_no = PO.po_no and SO.so_no = POPrinting.so_no
                                left join SOXPrinting on SOXPrinting.so_no = SO.so_no
                                left join SOX on SOX.sox_no = SOXPrinting.sox_no
                                left join CustomerTransaction on CustomerTransaction.so_no = SO.so_no
                                left join Customer on Customer.customer_tel = CustomerTransaction.customer_tel
    							where PO.po_no = ?");
        $sql->execute([$po_no]);
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return null;
	}
    
    public function getPo2($po_no) {
		$sql = $this->prepare("select
    								PO.po_no,
    							    PO.po_date,
                                    PO.product_type,
    							    Supplier.supplier_no,
    							    Supplier.supplier_name,
    							    Supplier.address,
    							    POPrinting.product_no,
    							    Product.product_description,
    							    POPrinting.quantity,
    							    Product.unit,
    							    POPrinting.purchase_price,
    							    POPrinting.total_purchase_price,
    							    PO.total_purchase_no_vat as 'po_total_purchase_no_vat',
    							    PO.total_purchase_vat as 'po_total_purchase_vat',
    							    PO.total_purchase_price as 'po_total_purchase_price',
                                    SOX.sox_no,
                                    SOX.total_sales_price
    							from POPrinting
    							inner join PO on PO.po_no = POPrinting.po_no
    							inner join Supplier on Supplier.supplier_no = PO.supplier_no and Supplier.product_line = PO.product_line
    							left join Product on Product.product_no = POPrinting.product_no
                                left join SO on SO.po_no = PO.po_no and SO.so_no = POPrinting.so_no
                                left join SOXPrinting on SOXPrinting.so_no = SO.so_no
                                left join SOX on SOX.sox_no = SOXPrinting.sox_no
                                left join CustomerTransaction on CustomerTransaction.so_no = SO.so_no
                                left join Customer on Customer.customer_tel = CustomerTransaction.customer_tel
    							where PO.po_no = ?");
        $sql->execute([$po_no]);
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return null;
	}
    
    public function getIv($iv_no) {
		$sql = $this->prepare("SELECT
                                	Invoice.invoice_no,
                                    Invoice.invoice_date,
                                    Invoice.file_no,
                                    Invoice.customer_name,
                                    Invoice.customer_title,
                                    Invoice.employee_id,
                                    Invoice.customer_address,
                                    Invoice.id_no,
                                    InvoicePrinting.product_no,
                                    Product.product_name,
                                    InvoicePrinting.quantity,
                                    Product.unit,
                                    InvoicePrinting.sales_price,
                                    InvoicePrinting.total_sales_price,
                                    Invoice.discount,
                                    Invoice.total_sales_no_vat as invoice_total_purchase_no_vat,
                                    Invoice.total_sales_vat as invoice_total_sales_vat,
                                    Invoice.total_sales_price as invoice_total_sales_price,
                                    Invoice.sales_price_thai
                                from InvoicePrinting
                                inner join Invoice on Invoice.invoice_no = InvoicePrinting.invoice_no
                                left join Product on Product.product_no = InvoicePrinting.product_no
    							where Invoice.invoice_no = ? ");
        $sql->execute([$iv_no]);
       
    


        
        return  json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
       
	}
    
    public function getCn($iv_no) {
		$sql = $this->prepare("select
                                	Invoice.invoice_no,
                                    Invoice.invoice_date,
                                    Invoice.file_no,
                                    exInvoice.customer_name,
                                    exInvoice.employee_id,
                                    exInvoice.customer_address,
                                    exInvoice.invoice_no as ex_invoice_no,
                                    exInvoice.invoice_date as ex_invoice_date,
                                    Invoice.note,
                                    exInvoice.id_no,
                                    InvoicePrinting.product_no,
                                    Product.product_name,
                                    InvoicePrinting.quantity,
                                    Product.unit,
                                    InvoicePrinting.sales_price,
                                    InvoicePrinting.total_sales_price,
                                    Invoice.discount,
                                    exInvoice.total_sales_no_vat as ex_total_sales_no_vat,
                                    (exInvoice.total_sales_no_vat - Invoice.total_sales_no_vat) as diff_total_sales_no_vat,
                                    Invoice.total_sales_no_vat as cn_total_sales_no_vat,
                                    Invoice.total_sales_vat as cn_total_sales_vat,
                                    Invoice.total_sales_price as cn_total_sales_price,
                                    Invoice.sales_price_thai
                                from InvoicePrinting
                                inner join Invoice on Invoice.invoice_no = InvoicePrinting.invoice_no
                                left join Invoice as exInvoice on exInvoice.invoice_no = Invoice.file_no
                                left join Product on Product.product_no = InvoicePrinting.product_no
    							where Invoice.invoice_no = ?");
        $sql->execute([$iv_no]);
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return null;
	}
    
    public function getCr($cr_no) {
		$sql = $this->prepare("select
                                	CR.cr_no,
                                    CR.cr_date,
                                    CR.customer_name,
                                    CR.employee_id,
                                    CR.customer_address,
                                    InvoicePrinting.product_no,
									Invoice.id_no,
                                    Product.product_name,
                                    InvoicePrinting.quantity,
                                    Product.unit,
                                    InvoicePrinting.sales_price,
                                    InvoicePrinting.total_sales_price,
                                    Invoice.discount,
                                    Invoice.total_sales_no_vat as invoice_total_purchase_no_vat,
                                    Invoice.total_sales_vat as invoice_total_sales_vat,
                                    Invoice.total_sales_price as invoice_total_sales_price,
                                    Invoice.sales_price_thai
                                from CR
                                inner join Invoice on Invoice.cr_no = CR.cr_no
                                inner join InvoicePrinting on InvoicePrinting.invoice_no = Invoice.invoice_no
                                left join Product on Product.product_no = InvoicePrinting.product_no
    							where CR.cr_no = ?");
        $sql->execute([$cr_no]);
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return null;
	}
	
	public function getPv($pv_no) {
		$sql = $this->prepare("select
                                    PV.pv_no,
                                    PV.pv_date,
                                    PV.pv_name,
                                    PV.pv_type,
                                    PV.pv_address,
                                    PV.supplier_no,
                                    PVPrinting.file_date,
                                    PVPrinting.iv_no,
                                    PVPrinting.detail,
                                    PVPrinting.rr_no,
                                    PVPrinting.paid_total,
                                    PVPrinting.note,
                                    PV.thai_text,
                                    PV.total_paid,
                                    PV.due_date,
                                    PV.bank
    							from PVPrinting
    							inner join PV on PV.pv_no = PVPrinting.pv_no
    							where PV.pv_no = ?");
        $sql->execute([$pv_no]);
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return null;
	}
    public function getPVC($pv_no) {
		$sql = $this->prepare("SELECT * FROM PVC where PVC.pv_no = ?");
        $sql->execute([$pv_no]);
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return "error";
	}
    public function getReReq($re_req_no) {
		$sql = $this->prepare("SELECT * FROM `Reimbursement_Request` WHERE re_req_no = ? ");
        $sql->execute([$re_req_no]);
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return null;
	}
    
    public function getPva($pv_no) {
		$sql = $this->prepare("SELECT * FROM PVA_bundle WHERE pv_no = ? ");
        $sql->execute([$pv_no]);
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return json_encode([$pv_no]);
	}

    public function getCs($cs_no) {
		$sql = $this->prepare("select
                                	CS.cs_no,
                                    CS.cs_date,
                                    concat(CS.approved_employee, ' ', ce.employee_nickname_thai) as employee_name,
                                    CSLocation.location_name,
                                    CSPrinting.product_no,
                                    Product.product_name,
                                    Product.unit,
                                    CSPrinting.sales_price,
                                    CSPrinting.quantity
                                from CSPrinting
                                inner join CS on CS.cs_no = CSPrinting.cs_no
                                left join Product on Product.product_no = CSPrinting.product_no
                                left join CSLocation on CSLocation.location_no = CS.location_no
                                left join Employee ce on ce.employee_id = CS.approved_employee
    							where CS.cs_no = ?");
        $sql->execute([$cs_no]);
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return null;
	}
    public function getRE($re_no) {
		$sql = $this->prepare("select RE.re_no,
        RE.re_date,
        RE.total_return_no_vat,
        RE.total_return_vat,
        RE.total_return_price,
        RE.total_return_price_thai,
        Supplier.supplier_no,
        Supplier.supplier_name,
        Supplier.address,
        REPrinting.product_no,
        Product.product_name,
        Product.product_description,
        Product.unit,
        REPrinting.purchase_price,
        REPrinting.quantity,
        REPrinting.total_purchase_price
        FROM RE
        inner join REPrinting on RE.re_no = REPrinting.re_no
        inner join Supplier on Supplier.supplier_no = RE.supplier_no
        left join Product on Product.product_no = REPrinting.product_no
        where RE.re_no = ?");
        $sql->execute([$re_no]);
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return null;
	}
	
	public function getRR($rr_no) {
		$sql = $this->prepare("select RR.rr_no,
        RR.rr_date,
        RR.total_purchase_no_vat,
        RR.total_purchase_vat,
        RR.total_purchase_price as total_price ,
        RR.total_purchase_price_thai,
        Supplier.supplier_no,
        Supplier.supplier_name,
        Supplier.address,
        RRPrinting.product_no,
        Product.product_name,
        Product.product_description,
        Product.unit,
        RRPrinting.purchase_price,
        RRPrinting.quantity,
        RRPrinting.total_purchase_price
        FROM RR
        inner join RRPrinting on RR.rr_no = RRPrinting.rr_no
        inner join Supplier on Supplier.supplier_no = RR.supplier_no
        left join Product on Product.product_no = RRPrinting.product_no
        where RR.rr_no = ?");
        $sql->execute([$rr_no]);
        if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return null;
	}
	
	public function getTR($tr_no=NULL) {
		if ($tr_no==NULL){
			$sql = $this->prepare("SELECT
                                  CASE
                                    WHEN cr_no LIKE '1%'   THEN '1'
                                    WHEN cr_no LIKE '2%'   THEN '2'
                                    WHEN cr_no LIKE '3%'   THEN '3'
                                END AS project_no
                                , SUM(total_price) AS total
                                FROM CR
                                WHERE cancelled='0'AND tr_no IS NULL
                                GROUP BY 
                                  CASE
                                    WHEN cr_no LIKE '1%'   THEN '1'
                                    WHEN cr_no LIKE '2%'   THEN '2'
                                    WHEN cr_no LIKE '3%'   THEN '3'
                                  END");   
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
								WHERE TR.tr_no = ?");
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
	
	
	public function getIRD($ird_no) {
		$sql = $this->prepare("select IRD.ird_no, 
								IRD.ird_date, 
								IRD.ird_time, 
								IRD.approved_employee,
								substring(IRD.ird_no,1,1) as company,
								IRD.box_count,
                                IRDPrinting.sox_no, 
								IRDPrinting.so_no, 
								SO.total_sales_price,
								SOPrinting.product_no, 
								SOPrinting.quantity, 
                                Product.product_name,
								Product.unit,
								SOPrinting.total_sales 
								from IRD
								inner join IRDPrinting on IRD.ird_no = IRDPrinting.ird_no
								inner join SO on SO.so_no = IRDPrinting.so_no
								inner join SOPrinting on SOPrinting.so_no = SO.so_no
                                left join Product on Product.product_no=SOPrinting.product_no
								where IRD.cancelled = 0 and IRD.ird_no = ?");
		$sql->execute([$ird_no]);
		if ($sql->rowCount() > 0) {
            return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        }
        return null;
	}

}