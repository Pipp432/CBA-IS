<?php

namespace model;

use _core\model;
use _core\helper\session;
use _core\helper\input;
use _core\helper\uri;
use _core\helper\thaiNum;
use PDO;

class homeModel extends model {
	
	public function getUsernamePassword() {
        	$sql = $this->prepare("select * from Employee where national_id = ?");
                $sql->execute([input::post('national_id')]);
                if ($sql->rowCount()>0) {
                    return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
                }
                return '0';
	}
        
        public function addCustomer() {
                $sql = $this->prepare("insert into Customer (date,customerTitle, customer_name, customer_surname, customer_nickname, gender, customer_tel, email, province, address, national_id)
                                        values (CURRENT_TIMESTAMP, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)");  

                $sql->execute([
                                input::post('customerTitle'),
                                input::post('customerFirstName'),
                                input::post('customerLastName'),
                                input::post('customerNickName'),
                                input::post('customerTitle') == 'นาย'|| input::post('customerTitle') == 'เด็กชาย' ? 'M' : 'F',
                                input::post('customerTel'),
                                input::post('customerEmail'),
                                input::post('customerProvince'),
                                input::post('customerAddress'),
                                input::post('customerIdNo')
                ]);
                $errorLog = $sql->errorInfo()[0];
                echo $sql->errorInfo()[0];
                if($errorLog=="1062"){
                        echo " Duplicate customer address and number ";
                }
        }

                
              
        
	
	public function getCompanySales() {
		$sql = $this->prepare("select sum(SOPrinting.sales_no_vat * SOPrinting.quantity) as sales
                                from SOPrinting
                                inner join SO on SO.so_no = SOPrinting.so_no
                                inner join Employee on Employee.employee_id = SO.employee_id
                                left join Product on Product.product_no = SOPrinting.product_no
                                left join ProductCategory on ProductCategory.category_no = Product.category_no and ProductCategory.product_line = Product.product_line
                                inner join Supplier on Supplier.supplier_no = Product.supplier_no and Supplier.product_line = Product.product_line
                                inner join Week on Week.date = SO.so_date
                                where SOPrinting.cancelled = 0 and not ProductCategory.category_name like 'ค่าจัดส่ง' and not ProductCategory.category_name like 'ค่าส่ง' and not ProductCategory.category_name like 'ค่าส่งสินค้า' and not ProductCategory.category_name like 'ค่าติดตั้ง' and Week.week between 1 and 10;");
		$sql->execute();
		if ( $sql->rowCount() > 0 ) {
		  return $sql->fetchAll();
		}
		return [];
	}
        

}