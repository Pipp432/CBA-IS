<?php

namespace model;

use _core\model;
use _core\helper\session;
use _core\helper\input;
use _core\helper\uri;
use _core\helper\thaiNum;
use PDO;

class osModel extends model {

  public function getProducts() {
    $sql = $this->prepare( "select
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
                                    Product.sales_no_vat,
                                    Product.sales_vat,
                                    Product.sales_price,
                                    Product.point,
                                    Product.commission,
                                    Product.margin,
                                    Product.vat_type,
                                    Product.weight,
									Product.width,
									Product.height,
									Product.length,
                                    ifnull(View_SOStock.stock, ifnull(stockXiaomi.stockXiaomi,0)) as stock,
                                    ifnull(ProductDeposit.sales_no_vat, 0) as sd_sales_no_vat,
                                    ifnull(ProductDeposit.sales_vat, 0) as sd_sales_vat,
                                    ifnull(ProductDeposit.sales_price, 0) as sd_sales_price
                                from Product
                                left join ProductCategory on (ProductCategory.category_no = Product.category_no and ProductCategory.product_line = Product.product_line)
                                inner join Supplier on (Supplier.supplier_no = Product.supplier_no and Supplier.product_line = Product.product_line)
                                left join View_SOStock on View_SOStock.stock_product_no = Product.product_no
                                left join ProductDeposit on ProductDeposit.product_no = Product.product_no
                                left join (select Product.product_no, StockInXiaomi.quantity_in - ifnull(outt.quan_out,0) as stockXiaomi from StockInXiaomi 
                                            left join Product on Product.product_description = StockInXiaomi.product_description
                                            left join (select StockOutXiaomi.product_no, sum(StockOutXiaomi.quantity_out) as quan_out from StockOutXiaomi where done = 0 group by StockOutXiaomi.product_no) outt on outt.product_no = Product.product_no) stockXiaomi on stockXiaomi.product_no = Product.product_no
                                where Product.product_line = ? and Product.status = '1'" );
    $sql->execute( [ json_decode( session::get( 'employee_detail' ), true )[ 'product_line' ] ] );
    if ( $sql->rowCount() > 0 ) {
      return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
    }
    return json_encode( [] );
  }
  

  
  

}