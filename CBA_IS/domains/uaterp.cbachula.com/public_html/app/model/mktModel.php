<?php

namespace model;

use _core\model;
use _core\helper\session;
use _core\helper\input;
use _core\helper\uri;
use _core\helper\thaiNum;
use PDO;

class mktModel extends model {

  // ==================================================================================================================================================================================================
  // COMMART 2020 - START
  // ==================================================================================================================================================================================================

  public function getCommartProduct() {
    $sql = $this->prepare( "select * from Product 
                                inner join ProductCategory on (ProductCategory.category_no = Product.category_no and ProductCategory.product_line = Product.product_line)
                                where Product.supplier_no = '510' and Product.product_type = 'Install'" );
    $sql->execute();
    if ( $sql->rowCount() > 0 ) {
      return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
    }
    return json_encode( [] );
  }

  public function getProductsForT() {
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
                                where Product.status = '1' and Product.product_no not in (SELECT product_no FROM Product WHERE product_type in ('stock','order') AND (weight = 0 OR width = 0 OR length = 0 OR height = 0) and Product.product_name not like '%ส่วนลด%' and Product.product_name not like '%บัตรเงินสด%')" );
    $sql->execute();
    if ( $sql->rowCount() > 0 ) {
      return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
    }
    return json_encode( [] );
  }
  // private function isBangkok( $address ) {
  //   return strpos( $address, 'กรุงเทพ' ) || strpos( $address, 'กทม' ) || strpos( $address, 'bangkok' ) || strpos( $address, 'Bangkok' ) || strpos( $address, 'สมุทรปราการ' ) || strpos( $address, 'นนทบุรี' ) || strpos( $address, 'ปทุมธานี' );
  // }

  // private function calculateWeight( $sos ) {
  //   $weight = 0;
  //   foreach ( $sos as $so ) {
  //     $weight += ( double )$so[ 'weight' ] * ( double )$so[ 'quantity' ];
  //   }
  //   return $weight;
  // }

  // public function calculate_T_price( $dimension, $weight, $bin_id, $address ) {
  //   if ( $dimension <= 40 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 25;
  //     } else {
  //       $dimension_price = 35;
  //     }
  //   } else if ( $dimension <= 50 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 30;
  //     } else {
  //       $dimension_price = 40;
  //     }
  //   } else if ( $dimension <= 60 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 35;
  //     } else {
  //       $dimension_price = 45;
  //     }
  //   } else if ( $dimension <= 70 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 40;
  //     } else {
  //       $dimension_price = 50;
  //     }
  //   } else if ( $dimension <= 80 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 45;
  //     } else {
  //       $dimension_price = 55;
  //     }
  //   } else if ( $dimension <= 85 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 60;
  //     } else {
  //       $dimension_price = 60;
  //     }
  //   } else if ( $dimension <= 90 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 70;
  //     } else {
  //       $dimension_price = 75;
  //     }
  //   } else if ( $dimension <= 95 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 80;
  //     } else {
  //       $dimension_price = 90;
  //     }
  //   } else if ( $dimension <= 100 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 95;
  //     } else {
  //       $dimension_price = 105;
  //     }
  //   } else if ( $dimension <= 105 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 100;
  //     } else {
  //       $dimension_price = 120;
  //     }
  //   } else if ( $dimension <= 110 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 110;
  //     } else {
  //       $dimension_price = 135;
  //     }
  //   } else if ( $dimension <= 115 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 120;
  //     } else {
  //       $dimension_price = 150;
  //     }
  //   } else if ( $dimension <= 120 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 130;
  //     } else {
  //       $dimension_price = 165;
  //     }
  //   } else if ( $dimension <= 125 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 140;
  //     } else {
  //       $dimension_price = 180;
  //     }
  //   } else if ( $dimension <= 130 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 150;
  //     } else {
  //       $dimension_price = 195;
  //     }
  //   } else if ( $dimension <= 135 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 160;
  //     } else {
  //       $dimension_price = 210;
  //     }
  //   } else if ( $dimension <= 140 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 170;
  //     } else {
  //       $dimension_price = 225;
  //     }
  //   } else if ( $dimension <= 145 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 180;
  //     } else {
  //       $dimension_price = 240;
  //     }
  //   } else if ( $dimension <= 150 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 190;
  //     } else {
  //       $dimension_price = 255;
  //     }
  //   } else if ( $dimension <= 155 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 200;
  //     } else {
  //       $dimension_price = 270;
  //     }
  //   } else if ( $dimension <= 160 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 210;
  //     } else {
  //       $dimension_price = 285;
  //     }
  //   } else if ( $dimension <= 165 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 220;
  //     } else {
  //       $dimension_price = 300;
  //     }
  //   } else if ( $dimension <= 170 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 230;
  //     } else {
  //       $dimension_price = 315;
  //     }
  //   } else if ( $dimension <= 175 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 240;
  //     } else {
  //       $dimension_price = 330;
  //     }
  //   } else if ( $dimension <= 180 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 250;
  //     } else {
  //       $dimension_price = 345;
  //     }
  //   } else if ( $dimension <= 185 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 260;
  //     } else {
  //       $dimension_price = 360;
  //     }
  //   } else if ( $dimension <= 190 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 270;
  //     } else {
  //       $dimension_price = 375;
  //     }
  //   } else if ( $dimension <= 195 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 280;
  //     } else {
  //       $dimension_price = 390;
  //     }
  //   } else if ( $dimension <= 200 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 290;
  //     } else {
  //       $dimension_price = 405;
  //     }
  //   } else if ( $dimension <= 205 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 300;
  //     } else {
  //       $dimension_price = 420;
  //     }
  //   } else if ( $dimension <= 210 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 310;
  //     } else {
  //       $dimension_price = 435;
  //     }
  //   } else if ( $dimension <= 215 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 320;
  //     } else {
  //       $dimension_price = 250;
  //     }
  //   } else if ( $dimension <= 220 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 330;
  //     } else {
  //       $dimension_price = 465;
  //     }
  //   } else if ( $dimension <= 225 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 340;
  //     } else {
  //       $dimension_price = 480;
  //     }
  //   } else if ( $dimension <= 230 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 350;
  //     } else {
  //       $dimension_price = 495;
  //     }
  //   } else if ( $dimension <= 235 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 360;
  //     } else {
  //       $dimension_price = 510;
  //     }
  //   } else if ( $dimension <= 240 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 370;
  //     } else {
  //       $dimension_price = 525;
  //     }
  //   } else if ( $dimension <= 245 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 380;
  //     } else {
  //       $dimension_price = 540;
  //     }
  //   } else if ( $dimension <= 250 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 390;
  //     } else {
  //       $dimension_price = 555;
  //     }
  //   } else if ( $dimension <= 255 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 400;
  //     } else {
  //       $dimension_price = 570;
  //     }
  //   } else if ( $dimension <= 260 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 410;
  //     } else {
  //       $dimension_price = 585;
  //     }
  //   } else if ( $dimension <= 265 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 420;
  //     } else {
  //       $dimension_price = 600;
  //     }
  //   } else if ( $dimension <= 270 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 430;
  //     } else {
  //       $dimension_price = 615;
  //     }
  //   } else if ( $dimension <= 275 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 440;
  //     } else {
  //       $dimension_price = 630;
  //     }
  //   } else if ( $dimension <= 280 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 450;
  //     } else {
  //       $dimension_price = 645;
  //     }
  //   }

  //   if ( $weight <= 1000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 25;
  //     } else {
  //       $weight_price = 35;
  //     }
  //   } else if ( $weight <= 2000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 30;
  //     } else {
  //       $weight_price = 40;
  //     }
  //   } else if ( $weight <= 3000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 35;
  //     } else {
  //       $weight_price = 45;
  //     }
  //   } else if ( $weight <= 4000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 40;
  //     } else {
  //       $weight_price = 50;
  //     }
  //   } else if ( $weight <= 5000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 45;
  //     } else {
  //       $weight_price = 55;
  //     }
  //   } else if ( $weight <= 6000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 60;
  //     } else {
  //       $weight_price = 60;
  //     }
  //   } else if ( $weight <= 7000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 70;
  //     } else {
  //       $weight_price = 75;
  //     }
  //   } else if ( $weight <= 8000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 80;
  //     } else {
  //       $weight_price = 90;
  //     }
  //   } else if ( $weight <= 9000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 95;
  //     } else {
  //       $weight_price = 105;
  //     }
  //   } else if ( $weight <= 10000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 100;
  //     } else {
  //       $weight_price = 120;
  //     }
  //   } else if ( $weight <= 11000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 110;
  //     } else {
  //       $weight_price = 135;
  //     }
  //   } else if ( $weight <= 12000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 120;
  //     } else {
  //       $weight_price = 150;
  //     }
  //   } else if ( $weight <= 13000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 130;
  //     } else {
  //       $weight_price = 165;
  //     }
  //   } else if ( $weight <= 14000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 140;
  //     } else {
  //       $weight_price = 180;
  //     }
  //   } else if ( $weight <= 15000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 150;
  //     } else {
  //       $weight_price = 195;
  //     }
  //   } else if ( $weight <= 16000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 160;
  //     } else {
  //       $weight_price = 210;
  //     }
  //   } else if ( $weight <= 17000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 170;
  //     } else {
  //       $weight_price = 225;
  //     }
  //   } else if ( $weight <= 18000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 180;
  //     } else {
  //       $weight_price = 240;
  //     }
  //   } else if ( $weight <= 19000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 190;
  //     } else {
  //       $weight_price = 255;
  //     }
  //   } else if ( $weight <= 20000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 200;
  //     } else {
  //       $weight_price = 270;
  //     }
  //   } else if ( $weight <= 21000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 210;
  //     } else {
  //       $weight_price = 285;
  //     }
  //   } else if ( $weight <= 22000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 220;
  //     } else {
  //       $weight_price = 300;
  //     }
  //   } else if ( $weight <= 23000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 230;
  //     } else {
  //       $weight_price = 315;
  //     }
  //   } else if ( $weight <= 24000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 240;
  //     } else {
  //       $weight_price = 330;
  //     }
  //   } else if ( $weight <= 25000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 250;
  //     } else {
  //       $weight_price = 345;
  //     }
  //   } else if ( $weight <= 26000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 260;
  //     } else {
  //       $weight_price = 360;
  //     }
  //   } else if ( $weight <= 27000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 270;
  //     } else {
  //       $weight_price = 375;
  //     }
  //   } else if ( $weight <= 28000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 280;
  //     } else {
  //       $weight_price = 390;
  //     }
  //   } else if ( $weight <= 29000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 290;
  //     } else {
  //       $weight_price = 405;
  //     }
  //   } else if ( $weight <= 30000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 300;
  //     } else {
  //       $weight_price = 420;
  //     }
  //   } else if ( $weight <= 31000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 310;
  //     } else {
  //       $weight_price = 435;
  //     }
  //   } else if ( $weight <= 32000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 320;
  //     } else {
  //       $weight_price = 250;
  //     }
  //   } else if ( $weight <= 33000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 330;
  //     } else {
  //       $weight_price = 465;
  //     }
  //   } else if ( $weight <= 34000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 340;
  //     } else {
  //       $weight_price = 480;
  //     }
  //   } else if ( $weight <= 35000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 350;
  //     } else {
  //       $weight_price = 495;
  //     }
  //   } else if ( $weight <= 36000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 360;
  //     } else {
  //       $weight_price = 510;
  //     }
  //   } else if ( $weight <= 37000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 370;
  //     } else {
  //       $weight_price = 525;
  //     }
  //   } else if ( $weight <= 38000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 380;
  //     } else {
  //       $weight_price = 540;
  //     }
  //   } else if ( $weight <= 39000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 390;
  //     } else {
  //       $weight_price = 555;
  //     }
  //   } else if ( $weight <= 40000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 400;
  //     } else {
  //       $weight_price = 570;
  //     }
  //   } else if ( $weight <= 41000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 410;
  //     } else {
  //       $weight_price = 585;
  //     }
  //   } else if ( $weight <= 42000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 420;
  //     } else {
  //       $weight_price = 600;
  //     }
  //   } else if ( $weight <= 43000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 430;
  //     } else {
  //       $weight_price = 615;
  //     }
  //   } else if ( $weight <= 44000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 440;
  //     } else {
  //       $weight_price = 630;
  //     }
  //   } else if ( $weight <= 45000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 450;
  //     } else {
  //       $weight_price = 645;
  //     }
  //   } else if ( $weight <= 46000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 460;
  //     } else {
  //       $weight_price = 660;
  //     }
  //   } else if ( $weight <= 47000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 470;
  //     } else {
  //       $weight_price = 675;
  //     }
  //   } else if ( $weight <= 48000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 480;
  //     } else {
  //       $weight_price = 690;
  //     }
  //   } else if ( $weight <= 49000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 490;
  //     } else {
  //       $weight_price = 705;
  //     }
  //   } else if ( $weight <= 50000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 500;
  //     } else {
  //       $weight_price = 720;
  //     }
  //   }

  //   if ( $weight_price >= $dimension_price ) {
  //     $final_price = $weight_price;
  //   } else if ( $weight_price < $dimension_price ) {
  //     $final_price = $dimension_price;
  //   }

  //   $final_price = $final_price;

  //   if ( $bin_id == '0,0' ) {
  //     $final_price += 1.45;
  //   } else if ( $bin_id == '0+4' ) {
  //     $final_price += 2.4;
  //   } else if ( $bin_id == 'A' ) {
  //     $final_price += 2.8;
  //   } else if ( $bin_id == '2A' ) {
  //     $final_price += 3.6;
  //   } else if ( $bin_id == 'B' ) {
  //     $final_price += 4.6;
  //   } else if ( $bin_id == '2B' ) {
  //     $final_price += 5.9;
  //   } else if ( $bin_id == 'C' ) {
  //     $final_price += 6.2;
  //   } 
  

  //   return $final_price;

  // }

  // public function calculate_T_price_Kerry( $dimension, $weight, $bin_id, $address ) {
  //   if ( $dimension <= 40 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 35;
  //     } else {
  //       $dimension_price = 55;
  //     }
  //   } else if ( $dimension <= 60 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 65;
  //     } else {
  //       $dimension_price = 80;
  //     }
  //   } else if ( $dimension <= 75 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 80;
  //     } else {
  //       $dimension_price = 90;
  //     }
  //   } else if ( $dimension <= 90 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 90;
  //     } else {
  //       $dimension_price = 100;
  //     }
  //   } else if ( $dimension <= 105 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 130;
  //     } else {
  //       $dimension_price = 145;
  //     }
  //   } else if ( $dimension <= 120 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 185;
  //     } else {
  //       $dimension_price = 205;
  //     }
  //   } else if ( $dimension <= 150 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 290;
  //     } else {
  //       $dimension_price = 330;
  //     }
  //   } else if ( $dimension <= 200 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $dimension_price = 380;
  //     } else {
  //       $dimension_price = 420;
  //     }
  //   } 

  //   if ( $weight <= 2000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 35;
  //     } else {
  //       $weight_price = 55;
  //     }
  //   } else if ( $weight <= 6000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 65;
  //     } else {
  //       $weight_price = 80;
  //     }
  //   } else if ( $weight <= 7000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 80;
  //     } else {
  //       $weight_price = 90;
  //     }
  //   } else if ( $weight <= 10000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 90;
  //     } else {
  //       $weight_price = 100;
  //     }
  //   } else if ( $weight <= 15000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 130;
  //     } else {
  //       $weight_price = 145;
  //     }
  //   } else if ( $weight <= 20000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 290;
  //     } else {
  //       $weight_price = 330;
  //     }
  //   } else if ( $weight <= 25000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $weight_price = 380;
  //     } else {
  //       $weight_price = 420;
  //     }
  //   } 

  //   if ( $weight_price >= $dimension_price ) {
  //     $final_price = $weight_price;
  //   } else if ( $weight_price < $dimension_price ) {
  //     $final_price = $dimension_price;
  //   }

  //   if ( $bin_id == 'A' ) {
  //     $final_price += 5.1;
  //   } else if ( $bin_id == '2A' ) {
  //     $final_price += 5.9;
  //   } else if ( $bin_id == 'B' ) {
  //     $final_price += 6.2;
  //   } else if ( $bin_id == 'C' ) {
  //     $final_price += 7.7;
  //   } else if ( $bin_id == '2C' ) {
  //     $final_price += 11.5;
  //   } else if ( $bin_id == 'D' ) {
  //     $final_price += 10.4;
  //   } else if ( $bin_id == 'E' ) {
  //     $final_price += 11.4;
  //   } else if ( $bin_id == 'I' ) {
  //     $final_price += 15.6;
  //   } else if ( $bin_id == 'S' ) {
  //     $final_price += 4.7;
  //   }

  //   return $final_price;

  // }
	
  // public function calculate_T_price_JT( $x_d, $weight, $bin_id, $address ) {
	// if ($weight>$x_d) {
	// 	$m_weight=$weight;
	// } else if ($x_d>$weight){
	// 	$m_weight=$x_d;
	// }
  //   if ( $m_weight <= 1000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $m_weight_price = 23;
  //     } else {
  //       $m_weight_price = 30;
  //     }
  //   } else if ( $m_weight <= 2000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $m_weight_price = 39;
  //     } else {
  //       $m_weight_price = 49;
  //     }
  //   } else if ( $m_weight <= 3000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $m_weight_price = 49;
  //     } else {
  //       $m_weight_price = 49;
  //     }
  //   } else if ( $m_weight <= 4000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $m_weight_price = 60;
  //     } else {
  //       $m_weight_price = 60;
  //     }
  //   } else if ( $m_weight <= 5000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $m_weight_price = 65;
  //     } else {
  //       $m_weight_price = 65;
  //     }
  //   } else if ( $m_weight <= 6000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $m_weight_price = 70;
  //     } else {
  //       $m_weight_price = 70;
  //     }
  //   } else if ( $m_weight <= 7000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $m_weight_price = 85;
  //     } else {
  //       $m_weight_price = 85;
  //     }
  //   } else if ( $m_weight <= 8000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $m_weight_price = 90;
  //     } else {
  //       $m_weight_price = 90;
  //     }
  //   } else if ( $m_weight <= 9000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $m_weight_price = 95;
  //     } else {
  //       $m_weight_price = 95;
  //     }
  //   } else if ( $m_weight <= 10000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $m_weight_price = 100;
  //     } else {
  //       $m_weight_price = 100;
  //     }
  //   } else if ( $m_weight <= 11000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $m_weight_price = 130;
  //     } else {
  //       $m_weight_price = 130;
  //     }
  //   } else if ( $m_weight <= 12000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $m_weight_price = 145;
  //     } else {
  //       $m_weight_price = 145;
  //     }
  //   } else if ( $m_weight <= 13000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $m_weight_price = 160;
  //     } else {
  //       $m_weight_price = 160;
  //     }
  //   } else if ( $m_weight <= 14000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $m_weight_price = 175;
  //     } else {
  //       $m_weight_price = 175;
  //     }
  //   } else if ( $m_weight <= 15000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $m_weight_price = 185;
  //     } else {
  //       $m_weight_price = 185;
  //     }
  //   } else if ( $m_weight <= 16000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $m_weight_price = 270;
  //     } else {
  //       $m_weight_price = 270;
  //     }
  //   } else if ( $m_weight <= 17000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $m_weight_price = 275;
  //     } else {
  //       $m_weight_price = 275;
  //     }
  //   } else if ( $m_weight <= 18000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $m_weight_price = 280;
  //     } else {
  //       $m_weight_price = 280;
  //     }
  //   } else if ( $m_weight <= 19000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $m_weight_price = 285;
  //     } else {
  //       $m_weight_price = 285;
  //     }
  //   } else if ( $m_weight <= 20000 ) {
  //     if ( $this->isBangkok( $address ) ) {
  //       $m_weight_price = 290;
  //     } else {
  //       $m_weight_price = 290;
  //     }
  //   } else if ( $m_weight > 20000 ) {
  //     $r_w=$m_weight-20000;
  //     $add_price=($r_w/1000)*10;
  //     if ( $this->isBangkok( $address ) ) {
  //       $m_weight_price = 290+$add_price;
  //     } else {
  //       $m_weight_price = 290+$add_price;
  //     }
  //   } 

  //   $final_price=$m_weight_price;

  //   if ( $bin_id == 'A' ) {
  //     $final_price += 5.1;
  //   } else if ( $bin_id == '2A' ) {
  //     $final_price += 5.9;
  //   } else if ( $bin_id == 'B' ) {
  //     $final_price += 6.2;
  //   } else if ( $bin_id == 'C' ) {
  //     $final_price += 7.7;
  //   } else if ( $bin_id == '2C' ) {
  //     $final_price += 11.5;
  //   } else if ( $bin_id == 'D' ) {
  //     $final_price += 10.4;
  //   } else if ( $bin_id == 'E' ) {
  //     $final_price += 11.4;
  //   } else if ( $bin_id == 'I' ) {
  //     $final_price += 15.6;
  //   } else if ( $bin_id == 'S' ) {
  //     $final_price += 4.7;
  //   }

  //   return $final_price;

  // }
	
  // public function calculateTransportationPrice() {

  //   $sos = json_decode( input::post( 'sos' ), true );
  //   $weight = $this->calculateWeight( $sos, 0 );
  //   if ( count( $sos ) == 1 && $sos[ 0 ][ 'quantity' ] == 1 ) {
  //     $data = [ 'w' => $sos[ 0 ][ 'width' ],
  //       'h' => $sos[ 0 ][ 'height' ],
  //       'd' => $sos[ 0 ][ 'length' ]
  //     ];
  //     if ( $data[ 'w' ] <= 14 && $data[ 'h' ] <= 6 && $data[ 'd' ] <= 20 ) {
  //       $bin_id = 'A';
  //       $weight += 80;
  //       $dimension = 14 + 6 + 20;
	// 	$x_d=(((14*20*6)/6000)*1000);
  //       //} else if($data['w']<=14 && $data['h']<=12 && $data['d']<=20) {
  //       //	$bin_id='2A';
  //       //	$weight+=90;
  //       //	$dimension=14+12+20;
  //       //} else if ($data['w']<=17 && $data['h']<=9 && $data['d']<=25) {
  //       //	$bin_id='B';
  //       //	$weight+=130;
  //       //	$dimension=17+9+25;
  //       //} else if ($data['w']<=20 && $data['h']<=11 && $data['d']<=30) {
  //       //	$bin_id='C';
  //       //	$weight+=190;
  //       //	$dimension=20+11+30;
  //       //} else if ($data['w']<=20 && $data['h']<=22 && $data['d']<=30) {
  //       //	$bin_id='2C';
  //       //	$weight+=200;
  //       //	$dimension=20+22+30;
  //       //} else if ($data['w']<=22 && $data['h']<=14 && $data['d']<=35) {
  //       //	$bin_id='D';
  //       //	$weight+=250;
  //       //	$dimension=22+14+35;
  //       //} else if ($data['w']<=24 && $data['h']<=17 && $data['d']<=40) {
  //       //	$bin_id='E';
  //       //	$weight+=310;
  //       //	$dimension=24+17+40;
  //       //} else if ($data['w']<=30 && $data['h']<=22 && $data['d']<=45) {
  //       //	$bin_id='I';
  //       //	$dimension=30+22+45;
  //       //	$weight+=360;
  //     } else {
  //       $dimension = $data[ 'w' ] + $data[ 'h' ] + $data[ 'd' ];
	// 	$x_d=((($data[ 'w' ]*$data[ 'd' ]*$data[ 'h' ])/6000)*1000);
  //       $bin_id = 'NONE';
  //     }
  //     $flash_price = $this->calculate_T_price( $dimension, $weight, $bin_id, ' กรุงเทพ ' );
  //     $flash_oprice = $this->calculate_T_price( $dimension, $weight, $bin_id, 'หี' );
  //     $kerry_price = $this->calculate_T_price_Kerry( $dimension, $weight, $bin_id, ' กรุงเทพ ' );
  //     $kerry_oprice = $this->calculate_T_price_Kerry( $dimension, $weight, $bin_id, 'หี' );
  //     $jt_price = $this->calculate_T_price_JT( $x_d, $weight, $bin_id, ' กรุงเทพ ' );
  //     $jt_oprice = $this->calculate_T_price_JT( $x_d, $weight, $bin_id, 'หี' );
  //     if ($sos[ 0 ][ 'product_line' ] != 0 && $sos[ 0 ][ 'product_line' ] != 1) {
	// 	  $flash_price +=10;
	// 	  $flash_oprice +=10;
	// 	  $kerry_price +=10;
	// 	  $kerry_oprice +=10;
	// 	  $jt_price +=10;
	// 	  $jt_oprice +=10;
	//   }
  //     return [ 'flash_price' => $flash_price, 'flash_oprice' => $flash_oprice,'kerry_price' => $kerry_price, 'kerry_oprice' => $kerry_oprice,'jt_price' => $jt_price, 'jt_oprice' => $jt_oprice, 'bin_id' => $bin_id, 'dimension' => $dimension, 'weight' => $weight ];
  //   } else {
  //     $data = [ 'username' => '6241153926@student.chula.ac.th',
  //       'api_key' => '9df6d9456992fede5095c28587c8ab32',
  //       'bins' => [
  //         ["w"=> 8.5, "h"=> 9, "d"=> 13, "id"=> "0,0"],
  //         ["w"=> 11, "h"=> 10, "d"=> 17, "id"=> "0+4"],
  //         ["w"=> 14, "h"=> 6, "d"=> 20, "id"=> "A"],
  //         ["w"=> 14, "h"=> 12, "d"=> 20, "id"=> "2A"],
  //         ["w"=> 17, "h"=> 9, "d"=> 25, "id"=> "B"],
  //         ["w"=> 17, "h"=> 18, "d"=> 25, "id"=> "2B"],
  //         ["w"=> 20, "h"=> 11, "d"=> 30, "id"=> "C"],
  //       ]
  //     ];

  //     $item_list = [];
  //     $not_free_pk= false;
  //     foreach ( $sos as $so ) {
  //       if ( $so[ 'width' ] > 0 && $so[ 'height' ] > 0 && $so[ 'length' ] > 0 ) {
  //         $item = [
  //           'w' => $so[ 'width' ],
  //           'h' => $so[ 'height' ],
  //           'd' => $so[ 'length' ],
  //           'q' => $so[ 'quantity' ],
  //           'vr' => '1',
  //           'id' => $so[ 'product_no' ]
  //         ];
  //         array_push( $item_list, $item );
  //       }
	// 	if ($so[ 'product_line' ] != 0 && $so[ 'product_line' ] != 1) {
	// 	  	$not_free_pk=true;
	//   	}
  //     }
  //     if ( count( $item_list ) == 0 ) {
  //       return [ 'flash_price' => 0,'kerry_price'=>0, 'jt_price'=>0 ,'bin_id' => 'NONE', 'errer' => 'ถ้าสินค้าไม่ใช่บัตรเงินสดติดต่อ IS' ];
  //     }
  //     if ( count( $item_list ) == 1 and $item_list[ 0 ][ 'q' ]== 1) {
  //       $data = [ 'w' => $item_list[ 0 ][ 'w' ],
  //       'h' => $item_list[ 0 ][ 'h' ],
  //       'd' => $item_list[ 0 ][ 'l' ]
	// 	  ];
	// 	  if ( $data[ 'w' ] <= 14 && $data[ 'h' ] <= 6 && $data[ 'd' ] <= 20 ) {
	// 		$bin_id = 'A';
	// 		$weight += 80;
	// 		$dimension = 14 + 6 + 20;
	// 		$x_d=(((14*20*6)/6000)*1000);
	// 	  } else {
	// 		$dimension = $data[ 'w' ] + $data[ 'h' ] + $data[ 'd' ];
	// 		$x_d=((($data[ 'w' ]*$data[ 'd' ]*$data[ 'h' ])/6000)*1000);
	// 		$bin_id = 'NONE';
	// 	  }
	// 	  $flash_price = $this->calculate_T_price( $dimension, $weight, $bin_id, ' กรุงเทพ ' );
	// 	  $flash_oprice = $this->calculate_T_price( $dimension, $weight, $bin_id, 'หี' );
	// 	  $kerry_price = $this->calculate_T_price_Kerry( $dimension, $weight, $bin_id, ' กรุงเทพ ' );
	// 	  $kerry_oprice = $this->calculate_T_price_Kerry( $dimension, $weight, $bin_id, 'หี' );
	// 	  $jt_price = $this->calculate_T_price_JT( $x_d, $weight, $bin_id, ' กรุงเทพ ' );
	// 	  $jt_oprice = $this->calculate_T_price_JT( $x_d, $weight, $bin_id, 'หี' );
	// 	  if ($sos[ 0 ][ 'product_line' ] != 0 && $sos[ 0 ][ 'product_line' ] != 1) {
	// 		  $flash_price +=10;
	// 		  $flash_oprice +=10;
	// 		  $kerry_price +=10;
	// 		  $kerry_oprice +=10;
	// 		  $jt_price +=10;
	// 		  $jt_oprice +=10;
	// 	  }
	// 	  return [ 'flash_price' => $flash_price, 'flash_oprice' => $flash_oprice,'kerry_price' => $kerry_price, 'kerry_oprice' => $kerry_oprice,'jt_price' => $jt_price, 'jt_oprice' => $jt_oprice, 'bin_id' => $bin_id, 'dimension' => $dimension, 'weight' => $weight ];
  //     }
  //     $data[ 'items' ] = $item_list;
  //     $query = json_encode( $data );
  //     $url = "http://asia1.api.3dbinpacking.com/packer/packIntoMany";
  //     $prepared_query = 'query=' . $query;
  //     $ch = curl_init( $url );
  //     curl_setopt( $ch, CURLOPT_POST, true );
  //     curl_setopt( $ch, CURLOPT_POSTFIELDS, $prepared_query );
  //     curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
  //     curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
  //     curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
  //     $resp = curl_exec( $ch );
  //     if ( curl_errno( $ch ) ) {
  //       return [ 'error' => 'Error #' . curl_errno( $ch ) . ': ' . curl_error( $ch ) . '<br>' ];
  //     }
  //     curl_close( $ch );
  //     $response = json_decode( $resp, true );
  //     if ( isset( $response[ 'response' ][ 'errors' ] ) ) {
  //       if ( count( $response[ 'response' ][ 'errors' ] ) > 0 ) {
  //         $response = json_encode( $response );
  //         return [ 'error' => $response ];
  //       }
  //     }
  //     $b_packed = $response[ 'response' ][ 'bins_packed' ];
  //     if ( count( $b_packed ) == 1 ) {
  //       foreach ( $b_packed as $bin ) {
  //         $bin_id = $bin[ 'bin_data' ][ 'id' ];
  //         $dimension = $bin[ 'bin_data' ][ 'h' ] + $bin[ 'bin_data' ][ 'w' ] + $bin[ 'bin_data' ][ 'd' ];
  //         $x_d=((($bin[ 'bin_data' ][ 'w' ]*$bin[ 'bin_data' ][ 'd' ]*$bin[ 'bin_data' ][ 'h' ])/6000)*1000);
  //       }
  //       $bins = $data[ 'bins' ];
  //       foreach ( $bins as $bin ) {
  //         $latest = $bin[ 'id' ];
  //         if ( $bin[ 'id' ] == $bin_id ) {
  //           if ( $bin_id == '0,0' ) {
  //             $weight += 50;
  //           } else if ( $bin_id == '0+4' ) {
  //             $weight += 50;
  //           } else if ( $bin_id == 'A' ) {
  //             $weight += 80;
  //           } else if ( $bin_id == '2A' ) {
  //             $weight += 90;
  //           } else if ( $bin_id == 'B' ) {
  //             $weight += 130;
  //           } else if ( $bin_id == '2B' ) {
  //             $weight += 130;
  //           } else if ( $bin_id == 'C' ) {
  //             $weight += 190;
  //           } 
  //         }
  //       }
	// 	  $flash_price = $this->calculate_T_price( $dimension, $weight, $bin_id, ' กรุงเทพ ' );
	// 	  $flash_oprice = $this->calculate_T_price( $dimension, $weight, $bin_id, 'หี' );
	// 	  $kerry_price = $this->calculate_T_price_Kerry( $dimension, $weight, $bin_id, ' กรุงเทพ ' );
	// 	  $kerry_oprice = $this->calculate_T_price_Kerry( $dimension, $weight, $bin_id, 'หี' );
	// 	  $jt_price = $this->calculate_T_price_JT( $x_d, $weight, $bin_id, ' กรุงเทพ ' );
	// 	  $jt_oprice = $this->calculate_T_price_JT( $x_d, $weight, $bin_id, 'หี' );
	// 	  if ($sos[ 0 ][ 'product_line' ] != 0 && $sos[ 0 ][ 'product_line' ] != 1) {
	// 		  $flash_price +=10;
	// 		  $flash_oprice +=10;
	// 		  $kerry_price +=10;
	// 		  $kerry_oprice +=10;
	// 		  $jt_price +=10;
	// 		  $jt_oprice +=10;
	// 	  }
	// 	  return [ 'flash_price' => $flash_price, 'flash_oprice' => $flash_oprice,'kerry_price' => $kerry_price, 'kerry_oprice' => $kerry_oprice,'jt_price' => $jt_price, 'jt_oprice' => $jt_oprice, 'bin_id' => $bin_id, 'dimension' => $dimension, 'weight' => $weight ];
  //     } else {
  //       return [ 'error' => 'ของเยอะเกินจ้า มาคุยกับ IS', 'bin_id' => 'X', 'flash_price' => '99999999', 'flash_oprice' => '99999999','kerry_price' => '99999999', 'kerry_oprice' => '99999999','jt_price' => '99999999', 'jt_oprice' => '99999999' ];
  //     }
  //   }

  // }

  public function calculate($sos) {
		
    $sos = json_decode($sos, true);

    //config
    $bin_id = 'X';
    $remark = '';
    $error = '';

    $is_reg_20_ems_24 = true;

		foreach($sos as $so) {
		$is_reg_20_ems_24 = $is_reg_20_ems_24 && in_array($so['supplier_no'], ['619']);
		}
		
		if($is_reg_20_ems_24) {
				return json_encode([
					"shippings" => [
						[
							"url" => "https://faceticket.net/wp-content/uploads/2020/06/Thaipost-Logo.jpg",
							"name" => 'Thai Post (REG)',
							"price" => 20,
							"bin_id" => '-',
							"remark" => $remark
						],
						[
							"url" => "https://faceticket.net/wp-content/uploads/2020/06/Thaipost-Logo.jpg",
							"name" => 'Thai Post (EMS)',
							"price" => 24,
							"bin_id" => '-',
							"remark" => $remark
						]
					],
					"error" => $error
				]);
			}

		$item_list = [];
		$not_free_pk = false;

		foreach($sos as $so) {

			if ($so['width'] > 0 && $so['height'] > 0 && $so['length'] > 0) { 
				$item = [
					'w' => $so['width'],
					'h' => $so['height'],
					'd' => $so['length'],
					'q' => $so['quantity'],
					'vr' => '1',
					'id' => $so['product_no']
				];
				array_push($item_list, $item);
			}

			if ($so['product_line'] != 0 && $so['product_line'] != 1) {
				$not_free_pk = true;
			}

		}

		#1 Only 1 product and 1 EA (without API)
        if (count($sos) == 1 && $sos[0]['quantity'] == 1 && count($item_list) != 0) {

			$data = [
                'w' => $sos[0]['width'],
                'h' => $sos[0]['height'],
                'd' => $sos[0]['length']
            ];

			if ($data['w'] <= 14 && $data['h'] <= 6 && $data['d'] <= 20) {
				$bin_id = 'A';
				$weight = $this->calculate_total_weight($sos, 'A');
				$dimension = 14 + 6 + 20;
				$x_d = (((14 * 20 * 6) / 6000) * 1000);
			} else {
				$bin_id = 'NONE';
				$weight = $this->calculate_total_weight($sos, '');
				$dimension = $data['w'] + $data['h'] + $data['d'];
				$x_d = ((($data['w'] * $data['d'] * $data['h']) / 6000) * 1000);
			}
            
			$prices = $this->calculate_thai_post_price($weight);
			// $flash_price = $this->calculate_T_price($dimension, $weight, $bin_id, $sos);
            // $price = $flash_price + ($sos[0]['product_line'] != 0 && $sos[0]['product_line'] != 1 && $flash_price > 0 ? 10 : 0);

		} else {

			$data = [
                'username' => '6241153926@student.chula.ac.th', 
                'api_key' => '9df6d9456992fede5095c28587c8ab32',
                'bins' => [
                    ["w"=> 8.5, "h"=> 9, "d"=> 13, "id"=> "0,0"],
                    ["w"=> 11, "h"=> 10, "d"=> 17, "id"=> "0+4"],
                    ["w"=> 14, "h"=> 6, "d"=> 20, "id"=> "A"],
                    ["w"=> 14, "h"=> 12, "d"=> 20, "id"=> "2A"],
                    ["w"=> 17, "h"=> 9, "d"=> 25, "id"=> "B"],
                    ["w"=> 17, "h"=> 18, "d"=> 25, "id"=> "2B"],
                    ["w"=> 20, "h"=> 11, "d"=> 30, "id"=> "C"],
					          ["w"=> 20, "h"=> 22, "d"=> 30, "id"=> "2C"],
					          ["w"=> 22, "h"=> 14, "d"=> 35, "id"=> "D"],
					          ["w"=> 24, "h"=> 17, "d"=> 40, "id"=> "E"],
					          ["w"=> 31, "h"=> 13, "d"=> 36, "id"=> "F"],
					          ["w"=> 30, "h"=> 22, "d"=> 45, "id"=> "ฉ"]
                ]
            ];

			$item_list = [];
			$not_free_pk = false;

			foreach($sos as $so) {

				if ($so['width'] > 0 && $so['height'] > 0 && $so['length'] > 0) { 
                    $item = [
                        'w' => $so['width'],
                        'h' => $so['height'],
                        'd' => $so['length'],
                        'q' => $so['quantity'],
                        'vr' => '1',
                        'id' => $so['product_no']
                    ];
				    array_push($item_list, $item);
				}

				if ($so['product_line'] != 0 && $so['product_line'] != 1) {
					$not_free_pk = true;
				}

			}

			if (count($item_list) == 0) {

				return json_encode([
					"shippings" => [
						[
							"url" => "http://cdn.onlinewebfonts.com/svg/img_290643.png",
							"name" => '',
							"price" => 0,
							"bin_id" => 'NONE',
							"remark" => 'ถ้าสินค้าไม่ใช่บัตรเงินสด ติดต่อฝ่าย IS'
						]
					],
					"error" => $error
				]);

			} else if (count($item_list) == 1 && $item_list[0]['q'] == 1 ) {

				$data = [
                    'w' => $sos[0]['width'],
                    'h' => $sos[0]['height'],
                    'd' => $sos[0]['length']
                ];

				if ($data['w'] <= 14 && $data['h'] <= 6 && $data['d'] <= 20) {
					$bin_id = 'A';
					$weight = $this->calculate_total_weight($sos, 'A');
					$dimension = 14 + 6 + 20;
					$x_d = (((14 * 20 * 6) / 6000) * 1000);
				} else{
					$bin_id = 'NONE';
					$weight = $this->calculate_total_weight($sos, '');
					$dimension = $data['w'] + $data['h'] + $data['d'];
					$x_d = ((($data['w'] * $data['d'] * $data['h']) / 6000) * 1000);
				}

				$prices = $this->calculate_thai_post_price($weight);
				// $flash_price = $this->calculate_T_price($dimension, $weight, $bin_id, $sos);
                // $price = $flash_price + ($not_free_pk && $flash_price > 0 ? 10 : 0);

			} else {

                $data['items'] = $item_list;
                $query = json_encode($data);
                $url = "http://asia1.api.3dbinpacking.com/packer/packIntoMany";
                $prepared_query = 'query='.$query;
                $ch = curl_init($url);
                curl_setopt( $ch, CURLOPT_POST, true );
                curl_setopt( $ch, CURLOPT_POSTFIELDS, $prepared_query );
                curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
                curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
                curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
                $resp = curl_exec($ch);
                if (curl_errno($ch)) {
                    return ['error'=>'Error #' . curl_errno($ch) . ': ' . curl_error($ch).'<br>'];
                }
                curl_close($ch);

                $response = json_decode($resp,true);
                if(isset($response['response']['errors'])){
                    if(count($response['response']['errors'])>0){
                        // $response=json_encode($data);
                        // return ['error'=> 'รายงานIS'];
                        return json_encode([
                            "shippings" => [],
                            "error" => json_encode($response['response']['errors'])
                        ]);
                    }
                }

                $b_packed= $response['response']['bins_packed'];

                if (count($b_packed) == 1){

                    foreach ($b_packed as $bin){
                        $bin_id = $bin['bin_data']['id'];
                        $dimension = $bin['bin_data']['h'] + $bin['bin_data']['w'] + $bin['bin_data']['d'];
                        $x_d = ((($bin['bin_data']['w'] * $bin['bin_data']['d'] * $bin['bin_data']['h']) / 6000) * 1000);
                    }

                    $bins = $data['bins'];
                    foreach ($bins as $bin){
                        $latest = $bin['id'];
                        if ($bin['id'] == $bin_id){
							$weight = $this->calculate_total_weight($sos, $bin_id);
                        } 
                    }

					$prices = $this->calculate_thai_post_price($weight);
                    // $flash_price = $this->calculate_T_price($dimension, $weight, $bin_id, $sos);
                    // $price = $flash_price + ($not_free_pk && $flash_price > 0 ? 10 : 0);

                } else {

                    $error = 'สินค้าเยอะเกินหรือเกิดข้อผิดพลาด กรุณาติดต่อฝ่าย IS';

                }

            }

		}
    if($weight >= 1900) {
			return json_encode([
				"shippings" => [
					[
						"url" => "https://faceticket.net/wp-content/uploads/2020/06/Thaipost-Logo.jpg",
						"name" => 'Thai Post (EMS)',
						"price" => $prices['ems'],
						"bin_id" => $bin_id,
						"remark" => $remark
					]
				],
				"error" => $error
			]);
    } else {
		return json_encode([
			"shippings" => [
				[
					"url" => "https://faceticket.net/wp-content/uploads/2020/06/Thaipost-Logo.jpg",
					"name" => 'Thai Post (REG)',
					"price" => $prices['reg'],
					"bin_id" => $bin_id,
					"remark" => $remark
				],
				[
					"url" => "https://faceticket.net/wp-content/uploads/2020/06/Thaipost-Logo.jpg",
					"name" => 'Thai Post (EMS)',
					"price" => $prices['ems'],
					"bin_id" => $bin_id,
					"remark" => $remark
				]
			],
			"error" => $error
		]);
  }
}
    private function calculate_total_weight2($sos, $box_weight) {
        $weight = $box_weight;
        foreach($sos as $so) { $weight += $so['weight'] * $so['quantity']; }
        return $weight;
    }

    private function calculate_total_weight($sos, $bin_id) {

		$weight = 0;

		switch ($bin_id) {
        case '0,0':     $weight += 26; break;
        case '0+4':     $weight += 43; break;
        case 'A':       $weight += 51; break;
        case '2A':      $weight += 64; break;
        case 'B':       $weight += 81; break;
        case '2B':      $weight += 112; break;
			  case 'C':      $weight += 109; break;
			  case '2C':      $weight += 153; break;
			  case 'D':      $weight += 153; break;
			  case 'E':      $weight += 200; break;
			  case 'F':      $weight += 221; break;
			  case 'ฉ':      $weight += 295; break;
			
			
            default:
        }

        foreach($sos as $so) { $weight += $so['weight'] * $so['quantity']; }
        return $weight;

    }

	private function calculate_thai_post_price($weight) {

		$price_reg = 0;
		$price_ems = 0;

		if($weight <= 500) {
			$price_reg = 32;
			$price_ems = 42;
		} else if($weight <= 2500) {
			$price_reg = 42;
			$price_ems = 52;
		} else if($weight <= 5000) {
			$price_reg = 52;
			$price_ems = 62;
		} else if($weight <= 8000) {
			$price_reg = 62;
			$price_ems = 82;
		} else {
			$price_reg = 92;
			$price_ems = 112;
		}

		return [
			'reg' => $price_reg,
			'ems' => $price_ems
		];

	}


  public function addCommart() {

    $sono = $this->assignSo( '5' );
    $pono = $this->assignPo( '5' );

    // insert SO
    $sql = $this->prepare( "insert into SO (so_no, so_date, so_time, employee_id, approve_employee_no, product_line, product_type, vat_type, total_sales_no_vat, total_sales_vat, total_sales_price, point, commission, po_no, cancelled, discountso, done)
                                values (?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, 5, ?, 1, ?, ?, ?, 0, 0, ?, 0, 0, 1)" );
    $sql->execute( [
      $sono,
      'COMMA',
      json_decode( session::get( 'employee_detail' ), true )[ 'employee_id' ],
      'Install',
      ( double )input::post( 'totalSalesPrice' ) / 1.07,
      ( double )input::post( 'totalSalesPrice' ) / 107 * 7,
      ( double )input::post( 'totalSalesPrice' ),
      $pono
    ] );

    // print_r($sql->errorInfo());

    $check = $sql->errorInfo()[ 0 ];

    if ( $check == '00000' ) {

      // insert PO
      $sql = $this->prepare( "insert into PO (po_no, po_date, supplier_no, product_type, total_purchase_no_vat, total_purchase_vat, total_purchase_price, approved_employee, received, cancelled, product_line)
                                    values (?, CURRENT_TIMESTAMP, ?, ?, ?, ?, ?, ?, 0, 0, 5)" );
      $sql->execute( [
        $pono,
        '510',
        'Install',
        ( double )input::post( 'totalPurchasePrice' ) / 1.07,
        ( double )input::post( 'totalPurchasePrice' ) / 107 * 7,
        ( double )input::post( 'totalPurchasePrice' ),
        json_decode( session::get( 'employee_detail' ), true )[ 'employee_id' ]
      ] );

      // insert CustomerTransaction
      $sql = $this->prepare( "insert into CustomerTransaction (so_no, customer_tel) values (?, ?)" );
      $sql->execute( [ $sono, '0887912159' ] );

      $soxno = $this->assignSox();

      $sql = $this->prepare( "insert into SOX (sox_no, sox_datetime, employee_id, customer_tel, address, so_sales_no_vat, so_sales_vat, so_sales_price, so_total_discount,
                                    transportation_no_vat, transportation_vat, transportation_price, total_sales_no_vat, total_sales_vat, total_sales_price, cancelled, slip_uploaded, done, ird_no) 
                                    values (?, CURRENT_TIMESTAMP, ?, ?, ?, ?, ?, ?, 0, 0, 0, 0, ?, ?, ?, 0, 0, -1, '-')" );
      $sql->execute( [
        $soxno,
        'COMMA',
        '0887912159',
        '-',
        ( double )input::post( 'totalSalesPrice' ) / 1.07,
        ( double )input::post( 'totalSalesPrice' ) / 107 * 7,
        ( double )input::post( 'totalSalesPrice' ),
        ( double )input::post( 'totalSalesPrice' ) / 1.07,
        ( double )input::post( 'totalSalesPrice' ) / 107 * 7,
        ( double )input::post( 'totalSalesPrice' )
      ] );

      // print_r($sql->errorInfo());

      $sql = $this->prepare( "insert into SOXPrinting (sox_no, so_no, product_line, total_sales_no_vat, total_sales_vat, total_sales_price) values (?, ?, 5, ?, ?, ?)" );
      $sql->execute( [
        $soxno,
        $sono,
        ( double )input::post( 'totalSalesPrice' ) / 1.07,
        ( double )input::post( 'totalSalesPrice' ) / 107 * 7,
        ( double )input::post( 'totalSalesPrice' )
      ] );

      // echo $soxno;

      $items = json_decode( input::post( 'items' ), true );
      $items = json_decode( $items, true );

      foreach ( $items as $value ) {

        // insert SOPrinting
        $sql = $this->prepare( "insert into SOPrinting (so_no, product_no, sales_no_vat, sales_vat, sales_price, quantity, total_sales, point, total_point, commission, total_commission, cancelled, margin)
                                    values (?, ?, ?, ?, ?, ?, ?, 0, 0, 0, 0, 0, ?)" );
        $sql->execute( [
          $sono,
          $value[ 'product_no' ],
          ( double )$value[ 'sales_no_vat' ],
          ( double )$value[ 'sales_vat' ],
          ( double )$value[ 'sales_price' ],
          ( double )$value[ 'quantity' ],
          ( double )$value[ 'quantity' ] * $value[ 'sales_price' ],
          ( double )$value[ 'quantity' ] * $value[ 'margin' ]
        ] );

        // insert POPrinting
        $sql = $this->prepare( "insert into POPrinting (po_no, product_no, so_no, purchase_no_vat, purchase_vat, purchase_price, quantity, total_purchase_price, received, cancelled)
                                    values (?, ?, ?, ?, ?, ?, ?, ?, 0, 0)" );
        $sql->execute( [
          $pono,
          $value[ 'product_no' ],
          $sono,
          ( double )$value[ 'purchase_no_vat' ],
          ( double )$value[ 'purchase_vat' ],
          ( double )$value[ 'purchase_price' ],
          ( double )$value[ 'quantity' ],
          ( double )$value[ 'quantity' ] * $value[ 'purchase_price' ]
        ] );

      }

      echo $pono;

    } else {

      echo 'เกิดข้อผิดพลาด รบกวนออก SO ใหม่';

    }

  }

  // ==================================================================================================================================================================================================
  // COMMART 2020 - END
  // ==================================================================================================================================================================================================

  // SO Module
  public function getCustomerName() {
    $sql = $this->prepare( "select customer_name, customer_surname, customer_nickname, address from Customer where customer_tel = ?" );
    $sql->execute( [ input::postAngular( 'customerTel' ) ] );
    if ( $sql->rowCount() > 0 ) {
      return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
    }
    return json_encode( [] );
  }

  // SO Module
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

  public function osGetProducts() {
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
                                where Product.status = '1'" );
    $sql->execute( );
    if ( $sql->rowCount() > 0 ) {
      return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
    }
    return json_encode( [] );
  }
  // SO Module
  public function addSo() {

    $sono = $this->assignSo( json_decode( session::get( 'employee_detail' ), true )[ 'product_line' ] );
    $depositSox = '';
    
	
	  
	// Promotion Week 4 - Check Point Range
	/*$inPointRange = FALSE;
	$sql = $this->prepare("select ifnull(count(*),0) as count from SpecialQuestSP where employee_id = ?");
	$sql->execute([input::post('sellerNo')]);
	$count = $sql->fetchAll()[0]['count'];
	
	if ($count > 0) {
		$inPointRange = TRUE;
	}*/

    // insert SO
    $sql = $this->prepare( "insert into SO (so_no, so_date, so_time, employee_id, approve_employee_no, product_line, product_type,payment_type,payment , vat_type, total_sales_no_vat, total_sales_vat, total_sales_price, point, commission, cancelled, discountso, done)
                                values (?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ? ,? , ?, ?, ?, ?,?, ?,?, ?, ?, ?, 0, ?, 1)" );
    $sql->execute( [
      $sono,
      input::post( 'sellerNo' ),
      json_decode( session::get( 'employee_detail' ), true )[ 'employee_id' ],
      json_decode( session::get( 'employee_detail' ), true )[ 'product_line' ],
      input::post( 'productType' ), 
      input::post('paymentType'),
      input::post( 'payment' ),
     
      input::post( 'vatType' ),
      ( double )input::post( 'totalNoVat' ),
      ( double )input::post( 'realTotalVat' ),
      ( double )input::post( 'realTotalPrice' ),
      ( double )input::post( 'totalPoint' ),
      ( double )input::post( 'totalCommission' ),
      ( double )input::post( 'discount' )
    ] );
   

    $check = $sql->errorInfo()[ 0 ];
    echo $sql->errorInfo()[ 2 ];

    if ( $check == '00000' ) {

      echo 'บันทึก ';

      // insert CustomerTransaction
      $sql = $this->prepare( "insert into CustomerTransaction (so_no, customer_tel,address) values (?, ?, ?)" );
      $sql->execute( [ $sono, input::post( 'customerTel' ) , $_POST["address"]] );

      // insert PointLog
      $sql = $this->prepare( "insert into PointLog (date, time, employee_id, point, remark, note, type, cancelled) values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, 'SO', ?,'Sales', 0)" );
      $sql->execute( [ input::post( 'sellerNo' ), ( double )input::post( 'totalPoint' ), $sono ] );

      
      // // Xiaomi Promotion !!!
      // if(json_decode(session::get('employee_detail'), true)['product_line'] == 'X') {

      //     // $fono = $sono[0].'FO'.substr($sono, 3);

      //     $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note, cancelled) values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, 'Xiaomi', ?, 0)");
      //     $sql->execute([input::post('sellerNo'), (double) input::post('totalPoint') * 0.5, $sono]);

      //     $sql = $this->prepare("update SO set note = 'FO' where so_no = ?");
      //     $sql->execute([$sono]);

      // }

      // insert SOX and SOXPrinting (Install)


      if ( input::post( 'productType' ) == 'Install' ) {
        $check2 = '';
        while ( $check2 != '00000' ) {
          $soxno = $this->assignSox();


          $sql = $this->prepare( "insert into SOX (sox_no, sox_datetime, employee_id, customer_tel, address, so_sales_no_vat, so_sales_vat, so_sales_price, so_total_discount,
											transportation_no_vat, transportation_vat, transportation_price, total_sales_no_vat, total_sales_vat, total_sales_price, cancelled, slip_uploaded, done, ird_no,payment_option) 
											values (?, CURRENT_TIMESTAMP, ?, ?, ?, ?, ?, ?, ?, 0, 0, 0, ?, ?, ?, 0, 0, -1, '-',?)" );
          $success = $sql->execute( [
            $soxno,
            input::post( 'sellerNo' ),
            input::post( 'customerTel' ),
            $_POST["address"],
            ( double )input::post( 'totalNoVat' ),
            ( double )input::post( 'totalVat' ),
            ( double )input::post( 'totalPrice' ),
            ( double )input::post( 'discount' ),
            ( double )input::post( 'totalNoVat' ),
            ( double )input::post( 'totalVat' ),
            ( double )input::post( 'totalPrice' ),
            input::post('paymentType')
          ] );

          // if(!$success) {
          //   echo 'error on insert sox';
          //   echo 'เห็นตรงนี้ส่งให้ is ด่วน!!!';
          //   print_r($sql->errorInfo()) ;
          // }

          $sql = $this->prepare( "insert into SOXPrinting (sox_no, so_no, product_line, total_sales_no_vat, total_sales_vat, total_sales_price) values (?, ?, ?, ?, ?, ?)" );
          $sql->execute( [
            $soxno,
            $sono,
            json_decode( session::get( 'employee_detail' ), true )[ 'product_line' ],
            ( double )input::post( 'totalNoVat' ),
            ( double )input::post( 'totalVat' ),
            ( double )input::post( 'totalPrice' )
          ] );

          echo $soxno . ' : ';
          $check2 = $sql->errorInfo()[ 0 ];
        }
      }

      $soItemsArray = json_decode( input::post( 'soItems' ), true );
      $soItemsArray = json_decode( $soItemsArray, true );

      foreach ( $soItemsArray as $value ) {

        if ( !$value[ 'deposit' ] ) {

          // insert SOPrinting
          $sql = $this->prepare( "insert into SOPrinting (so_no, product_no, sales_no_vat, sales_vat, sales_price, quantity, total_sales, point, total_point, commission, total_commission, cancelled, margin)
                                        values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, ?)" );
          $sql->execute( [
            $sono,
            $value[ 'product_no' ],
            ( double )$value[ 'sales_no_vat' ],
            ( double )$value[ 'sales_vat' ],
            ( double )$value[ 'sales_price' ],
            ( double )$value[ 'quantity' ],
            ( double )$value[ 'quantity' ] * $value[ 'sales_price' ],
            ( double )$value[ 'point' ],
            ( double )$value[ 'quantity' ] * $value[ 'point' ],
            ( double )$value[ 'commission' ],
            ( double )$value[ 'quantity' ] * $value[ 'commission' ],
            ( double )$value[ 'quantity' ] * $value[ 'margin' ]
          ] );
          // insert StockOut (Stock)
          if ( input::post( 'productType' ) == 'Stock' ) {
            $sql = $this->prepare( "insert into StockOut (product_no, file_no, file_type, date, time, quantity_out, lot, rr_no)
                                                values (?, ?, 'SO', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, 0, ?)" );
            $sql->execute( [ $value[ 'product_no' ], $sono, $value[ 'quantity' ], $sono ] );
          }

          // insert StockOutXiaomi
          if ( $value[ 'product_line' ] == 'X' ) {
            $sql = $this->prepare( "insert into StockOutXiaomi (product_no, quantity_out, so_no, datetime, done)
                                                values (?, ?, ?, CURRENT_TIMESTAMP, 0)" );
            $sql->execute( [ $value[ 'product_no' ], $value[ 'quantity' ], $sono ] );
          }

        } else { // sd for install

          $sdno = $sono[ 0 ] . 'SD' . substr( $sono, 3 );

          // insert SD
          $sql = $this->prepare( "insert into SO (so_no, so_date, so_time, employee_id, approve_employee_no, product_line, product_type, vat_type, total_sales_no_vat, total_sales_vat, total_sales_price, point, commission, cancelled, discountso, done)
                                            values (?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, ?, 1)" );
          $sql->execute( [
            $sdno,
            input::post( 'sellerNo' ),
            json_decode( session::get( 'employee_detail' ), true )[ 'employee_id' ],
            json_decode( session::get( 'employee_detail' ), true )[ 'product_line' ],
            input::post( 'productType' ),
            input::post( 'vatType' ),
            ( double )$value[ 'sales_no_vat' ] * $value[ 'quantity' ],
            ( double )$value[ 'sales_vat' ] * $value[ 'quantity' ],
            ( double )$value[ 'sales_price' ] * $value[ 'quantity' ],
            0,
            0,
            0
          ] );

          // insert SDPrinting
          $sql = $this->prepare( "insert into SOPrinting (so_no, product_no, sales_no_vat, sales_vat, sales_price, quantity, total_sales, point, total_point, commission, total_commission, cancelled, margin)
                                        values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, ?)" );
          $sql->execute( [
            $sdno,
            substr( $value[ 'product_no' ], 0, 15 ),
            ( double )$value[ 'sales_no_vat' ],
            ( double )$value[ 'sales_vat' ],
            ( double )$value[ 'sales_price' ],
            ( double )$value[ 'quantity' ],
            ( double )$value[ 'quantity' ] * $value[ 'sales_price' ],
            0,
            0,
            0,
            0,
            0
          ] );

          // insert CustomerTransaction
          $sql = $this->prepare( "insert into CustomerTransaction (so_no, customer_tel) values (?, ?)" );
          $sql->execute( [ $sdno, input::post( 'customerTel' ) ] );

          // insert SDX
          $sdxno = $this->assignSox();

          $sql = $this->prepare( "insert into SOX (sox_no, sox_datetime, employee_id, customer_tel, address, so_sales_no_vat, so_sales_vat, so_sales_price, so_total_discount,
                                            transportation_no_vat, transportation_vat, transportation_price, total_sales_no_vat, total_sales_vat, total_sales_price, cancelled, slip_uploaded, done, ird_no) 
                                            values (?, CURRENT_TIMESTAMP, ?, ?, ?, ?, ?, ?, 0, 0, 0, 0, ?, ?, ?, 0, 0, -1, '-')" );
          $success = $sql->execute( [
            $sdxno,
            input::post( 'sellerNo' ),
            input::post( 'customerTel' ),
            input::post('address'),
            ( double )$value[ 'sales_no_vat' ] * $value[ 'quantity' ],
            ( double )$value[ 'sales_vat' ] * $value[ 'quantity' ],
            ( double )$value[ 'sales_price' ] * $value[ 'quantity' ],
            ( double )$value[ 'sales_no_vat' ] * $value[ 'quantity' ],
            ( double )$value[ 'sales_vat' ] * $value[ 'quantity' ],
            ( double )$value[ 'sales_price' ] * $value[ 'quantity' ]
          ] );

          // if(!$success) {
          //   echo 'error on insert sdx';
          //   echo 'เห็นตรงนี้ส่งให้ is ด่วน!!!';
          //   print_r($sql->errorInfo()) ;
          // }

          $sql = $this->prepare( "insert into SOXPrinting (sox_no, so_no, product_line, total_sales_no_vat, total_sales_vat, total_sales_price) values (?, ?, ?, ?, ?, ?)" );
          $sql->execute( [
            $sdxno,
            $sdno,
            json_decode( session::get( 'employee_detail' ), true )[ 'product_line' ],
            ( double )$value[ 'sales_no_vat' ] * $value[ 'quantity' ],
            ( double )$value[ 'sales_vat' ] * $value[ 'quantity' ],
            ( double )$value[ 'sales_price' ] * $value[ 'quantity' ]
          ] );

          $sql = $this->prepare( "update SOX set so_sales_no_vat = ?, so_sales_vat = ?, so_sales_price = ?, total_sales_no_vat = ?, total_sales_vat = ?, total_sales_price = ? where sox_no = ?" );
          $sql->execute( [
            ( double )input::post( 'totalNoVat' ) - ( $value[ 'sales_no_vat' ] * $value[ 'quantity' ] ),
            ( double )input::post( 'totalVat' ) - ( $value[ 'sales_vat' ] * $value[ 'quantity' ] ),
            ( double )input::post( 'totalPrice' ) - ( $value[ 'sales_price' ] * $value[ 'quantity' ] ),
            ( double )input::post( 'totalNoVat' ) - ( $value[ 'sales_no_vat' ] * $value[ 'quantity' ] ),
            ( double )input::post( 'totalVat' ) - ( $value[ 'sales_vat' ] * $value[ 'quantity' ] ),
            ( double )input::post( 'totalPrice' ) - ( $value[ 'sales_price' ] * $value[ 'quantity' ] ),
            $soxno
          ] );

          $sql = $this->prepare( "update SOXPrinting set total_sales_no_vat = ?, total_sales_vat = ?, total_sales_price = ? where sox_no = ?" );
          $sql->execute( [
            ( double )input::post( 'totalNoVat' ) - ( $value[ 'sales_no_vat' ] * $value[ 'quantity' ] ),
            ( double )input::post( 'totalVat' ) - ( $value[ 'sales_vat' ] * $value[ 'quantity' ] ),
            ( double )input::post( 'totalPrice' ) - ( $value[ 'sales_price' ] * $value[ 'quantity' ] ),
            $soxno
          ] );

          $sql = $this->prepare( "update SO set total_sales_no_vat = ?, total_sales_vat = ?, total_sales_price = ?, note = ? where so_no = ?" );
          $sql->execute( [
            ( double )input::post( 'totalNoVat' ) - ( $value[ 'sales_no_vat' ] * $value[ 'quantity' ] ),
            ( double )input::post( 'totalVat' ) - ( $value[ 'sales_vat' ] * $value[ 'quantity' ] ),
            ( double )input::post( 'totalPrice' ) - ( $value[ 'sales_price' ] * $value[ 'quantity' ] ),
            $sdno,
            $sono
          ] );

          $depositSox = ' ( มัดจำ ' . $sdxno . ' : ' . $sdno . ')';

        }

        // echo $sono.$depositSox.' สำเร็จ';

      }

      // $this->promotionWeek2($sono);
      // $this->updateTeams($sono);
      

      #if(json_decode(session::get('employee_detail'), true)['product_line'] == '3' || #json_decode(session::get('employee_detail'), true)['product_line'] == 'X') {
      #    $this->specialPromotionLine3($sono);
      #}

      // promotion week 9		
		// $sql = $this->promotionWeek10($sono);
    // $sql = $this->promotionTournamentWeek4($sono);
    $sql = $this->promotionWeek6($sono);
    
    

      //2022 week 3 tournament
		$score = $_POST["totalPrice"];
		$employee_id = input::post('sellerNo');
		$dt = new \DateTime('now', new \DateTimeZone('Asia/Bangkok'));
		$dt_timestamp=$dt->getTimestamp();
		$dayOfWeek = date("l", $dt_timestamp); //get day of week
		$dt_formatted=$dt->format('Hi'); //get time: hhmm
		

		// if(strcasecmp($dayOfWeek,'friday')==0){
		// 	// if($dt_formatted>='2300' && $dt_formatted<='2400'){
		// 		$score*=1.5;
		// 	// }
      
		// }

		$sql = $this->prepare("INSERT INTO WavePoint (sp_id,datetime_sold,point,so_no) VALUES (?,CURRENT_TIMESTAMP,?,?)");
		$sql->execute([$employee_id,$_POST["totalNoTransport"],$sono]);



    // if(strcasecmp($dayOfWeek,'wednesday')==0){
		// 	if($dt_formatted>='1000' && $dt_formatted<='1200'){
		// 		$score*=1.5;
		// 	}
      
		// }

		
		// $sql = $this->prepare("SELECT team_id FROM TeamMembers WHERE employee_id= ?");
		// $sql->execute([$employee_id]);
		// if ($sql->rowCount() > 0) {
		// 	$team_id = $sql->fetchAll()[0]['team_id'];
		// 	$sql = $this->prepare("INSERT INTO TeamTransactions (date,time,team_id,score,so_no,status)
		// 	VALUES (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP,?,?,?,0)");
		// 	$sql->execute([$team_id,$score,$sono]);
		// 	print_r($sql->errorInfo());
      
			// promotion team tour week 3
			// $sql = $this->promotionWeek3($sono);
		//}
    

		

      echo $sono . $depositSox . ' สำเร็จ';

    } else {

      echo 'เกิดข้อผิดพลาด รบกวนออก SO ใหม่';
      
    }

    

  }


  // SO Module
  private function assignSo( $productLine ) {
    $soPrefix = $this->getCompany( $productLine ) . 'SO-';
    $sql = $this->prepare( "select ifnull(max(so_no),0) as max from SO where so_no like ?" );
    $sql->execute( [ $this->getCompany( $productLine ) . '%O-%' ] );
    $maxSoNo = $sql->fetchAll()[ 0 ][ 'max' ];
    $runningNo = '';
    if ( $maxSoNo == '0' ) {
      $runningNo = '00001';
    } else {
      $latestRunningNo = ( int )substr( $maxSoNo, 4 ) + 1;
      if ( strlen( $latestRunningNo ) == 5 ) {
        $runningNo = $latestRunningNo;
      } else {
        for ( $x = 1; $x <= 5 - strlen( $latestRunningNo ); $x++ ) {
          $runningNo .= '0';
        }
        $runningNo .= $latestRunningNo;
      }
    }
    return $soPrefix . $runningNo;
  }

  // SO Module
  public function assignSox() {
    $sql = $this->prepare( "select ifnull(max(sox_no),0) as max from SOX" );
    $sql->execute();
    $maxSoxNo = $sql->fetchAll()[ 0 ][ 'max' ];
    $runningNo = '';
    if ( $maxSoxNo == '0' ) {
      $runningNo = '00001';
    } else {
      $latestRunningNo = ( int )substr( $maxSoxNo, 4 ) + 1;
      if ( strlen( $latestRunningNo ) == 5 ) {
        $runningNo = $latestRunningNo;
      } else {
        for ( $x = 1; $x <= 5 - strlen( $latestRunningNo ); $x++ ) {
          $runningNo .= '0';
        }
        $runningNo .= $latestRunningNo;
      }
    }
    return 'SOX-' . $runningNo;
  }
  // cancel_Sox Module
  public function getSOX() {
    $sql = $this->prepare( "SELECT SOX.sox_no, SOPrinting.so_no, SOPrinting.product_no, Product.product_name,SOPrinting.total_sales,SOX.done, SOX.slip_uploaded,SOX.cancelled
    FROM SOX
    INNER JOIN SOXPrinting on SOX.sox_no = SOXPrinting.sox_no
    INNER JOIN SOPrinting ON SOXPrinting.so_no = SOPrinting.so_no
    INNER JOIN Product ON SOPrinting.product_no = Product.product_no
    WHERE SOX.done = -1 and SOX.slip_uploaded = 0 and SOX.cancelled = 0 and SOX.sox_no = ?" );
    $sql->execute([ input::postAngular( 'sox_no' ) ] );
    if ( $sql->rowCount() > 0 ) {
      return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
    }
    return json_encode( [] );
  }
  // Change_Cancel SOX Module
  public function ChangeCancelSOX() {
    $soxItemsArray = json_decode(input::post('soxItems'), true); 
    $soxItemsArray = json_decode($soxItemsArray, true);
    
    $soxList = array();
    
    foreach($soxItemsArray as $soxItem) {
        
        if (!in_array($soxItem['sox_no'], $soxList)) {
              
            $soxList += [$soxItem['sox_no']];
            
            
            // update cancel in SOX SO SOPrinting PointLog
            $sql = $this->prepare("UPDATE SOX 
            LEFT JOIN SOXPrinting on SOX.sox_no = SOXPrinting.sox_no
            LEFT JOIN SO ON SOXPrinting.so_no = SO.so_no
            LEFT JOIN SOPrinting on SO.so_no = SOPrinting.so_no
            LEFT JOIN PointLog on SOXPrinting.so_no = PointLog.note
            SET
            SOX.cancelled = 1,
            SO.cancelled = 1,
            SOPrinting.cancelled = 1,
            PointLog.cancelled = 1
            WHERE SOX.sox_no = ? and SOX.done = -1 and SOX.slip_uploaded = 0;
            
            DELETE FROM StockOut
            WHERE file_no in (select so_no from SO WHERE cancelled = 1)");                                             
            $sql->execute([$soxItem['sox_no']]);
            
        }
        
    }
  }
  //cancel_Sox Select
  public function getsoxs(){
    $sql = $this->prepare("SELECT SOX.sox_no, SOX.employee_id, SOPrinting.so_no, SOPrinting.product_no,SOPrinting.quantity, Product.product_name,SOPrinting.total_sales,SOX.done, SOX.slip_uploaded,SOX.cancelled,SOXPrinting.product_line
    FROM SOX
    INNER JOIN SOXPrinting on SOX.sox_no = SOXPrinting.sox_no
    INNER JOIN SOPrinting ON SOXPrinting.so_no = SOPrinting.so_no
    INNER JOIN Product ON SOPrinting.product_no = Product.product_no
    WHERE SOX.done = -1 and SOX.slip_uploaded = 0 and SOX.cancelled = 0 
    ");
    $sql->execute();
    if ($sql->rowCount() > 0) {
      return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
    }
    return $sql->errorInfo()[0];
  }

  //cancel_onlyso Select
  public function getso(){
    $sql = $this->prepare("SELECT  SO.so_no,SOXPrinting.sox_no, SO.employee_id, Product.product_no,Product.product_name,SOPrinting.quantity,SOPrinting.total_sales,SO.done
    FROM  SO
    INNER JOIN SOPrinting ON SO.so_no = SOPrinting.so_no
    INNER JOIN Product ON SOPrinting.product_no = Product.product_no
    LEFT JOIN SOXPrinting on SO.so_no = SOXPrinting.so_no
    WHERE SO.cancelled = 0 AND SOXPrinting.sox_no IS null ;
    ");
     $sql->execute();
    if ($sql->rowCount() > 0) {
      return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
    }
    return json_encode([]);
  }
  // Change_Cancel SO Module
  public function ChangeCancelSO() {
    $soItemsArray = json_decode(input::post('soItems'), true); 
    $soItemsArray = json_decode($soItemsArray, true);
    
    $soList = array();
    
    foreach($soItemsArray as $soItem) {
        
        if (!in_array($soItem['so_no'], $soList)) {
              
            $soList += [$soItem['so_no']];
            
            
            // update cancel in SOX SO SOPrinting PointLog
            $sql = $this->prepare("update SO
            left join SOPrinting on SO.so_no = SOPrinting.so_no
            left join PointLog on SO.so_no = PointLog.note
            set SO.cancelled = 1,
            SOPrinting.cancelled = 1,
            PointLog.cancelled = 1
            where SO.so_no = ? ;
            
            DELETE FROM StockOut
            WHERE file_no in (select so_no from SO WHERE cancelled = 1)");                                             
            $sql->execute([$soItem['so_no']]);
            
        }
        
    }
  }
  // =====================================================================================================================================================================================
  // =====================================================================================================================================================================================

  // PO Module
  public function getSupplierList() {
    $sql = $this->prepare( "select * from Supplier where product_line = ?" );
    $sql->execute( [ json_decode( session::get( 'employee_detail' ), true )[ 'product_line' ] ] );
    if ( $sql->rowCount() > 0 ) {
      return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
    }
    return json_encode( [] );
  }

  // PO Module
  public function getStockProduct() {
    $sql = $this->prepare( "select * from Product 
                                inner join ProductCategory on (ProductCategory.category_no = Product.category_no and ProductCategory.product_line = Product.product_line)
                                inner join Supplier on Supplier.supplier_no = Product.supplier_no
                                where Product.product_line = ? and Product.product_type = 'Stock' and Product.status='1'" );
    $sql->execute( [ json_decode( session::get( 'employee_detail' ), true )[ 'product_line' ] ] );
    if ( $sql->rowCount() > 0 ) {
      return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
    }
    return json_encode( [] );
  }

  //PO Module
  public function getOrderInstallSo() {

    if (input::post('supplierNo') == 'B03') { 
        
      $sql = $this->prepare( "select
      SO.so_no,
        SO.so_date,
        SO.so_time,
        concat(orderer.employee_id, ' ', orderer.employee_nickname_thai) as orderer,
        concat(approved.employee_id, ' ', approved.employee_nickname_thai) as approved,
        SO.product_line,
        SO.product_type,
        SOPrinting.sales_price,
        SOPrinting.quantity,
        Product.*,
        Supplier.supplier_name
    from SOPrinting
    inner join SO on SO.so_no = SOPrinting.so_no
    left join SO as sd on sd.note = SO.so_no
    left join SOPrinting as sdPrinting on sdPrinting.so_no = sd.so_no
    inner join Employee as orderer on orderer.employee_id = SO.employee_id
    inner join Employee as approved on approved.employee_id = SO.approve_employee_no
    left join Product on Product.product_no = SOPrinting.product_no
    left join SOXPrinting on SOXPrinting.so_no = SO.so_no
    left join SOX on SOX.sox_no = SOXPrinting.sox_no
    inner join Supplier on Supplier.supplier_no = Product.supplier_no and Supplier.product_line = Product.product_line
    where SOPrinting.cancelled = 0 and not Product.product_type = 'Stock' and SO.po_no is null and Product.product_line = ? and Product.supplier_no = 'B03' and SO.note is null
    GROUP BY SO.so_no, Product.product_no
    order by SOPrinting.so_no desc" );

    $sql->execute( [ json_decode( session::get( 'employee_detail' ), true )[ 'product_line' ] ] );
    if ( $sql->rowCount() > 0 ) {
      return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
    }
    return json_encode( [] );

    }else {
    
    $sql = $this->prepare( "select
                                	SO.so_no,
                                    SO.so_date,
                                    SO.so_time,
                                    concat(orderer.employee_id, ' ', orderer.employee_nickname_thai) as orderer,
                                    concat(approved.employee_id, ' ', approved.employee_nickname_thai) as approved,
                                    SO.product_line,
                                    SO.product_type,
                                    SOPrinting.sales_price,
                                    SOPrinting.quantity,
                                    Product.*,
                                    Supplier.supplier_name
                                from SOPrinting
                                inner join SO on SO.so_no = SOPrinting.so_no
                                left join SO as sd on sd.note = SO.so_no
                                left join SOPrinting as sdPrinting on sdPrinting.so_no = sd.so_no
                                inner join Employee as orderer on orderer.employee_id = SO.employee_id
                                inner join Employee as approved on approved.employee_id = SO.approve_employee_no
                                left join Product on Product.product_no = SOPrinting.product_no
                                left join SOXPrinting on SOXPrinting.so_no = SO.so_no
                                left join SOX on SOX.sox_no = SOXPrinting.sox_no
                                inner join Supplier on Supplier.supplier_no = Product.supplier_no and Supplier.product_line = Product.product_line
                                where SOPrinting.cancelled = 0 and not Product.product_type = 'Stock' and SO.po_no is null and ((SOX.done = 0 and SO.payment is null) or (SOX.done = -1 and SO.payment=1)) and Product.product_line = ? and SO.note is null
                                GROUP BY SO.so_no, Product.product_no
                                order by SOPrinting.so_no desc" );
    $sql->execute( [ json_decode( session::get( 'employee_detail' ), true )[ 'product_line' ] ] );
    if ( $sql->rowCount() > 0 ) {
      return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
    }
    return json_encode( [] );
  }
}

  // PO Module
  public function addPo() {

    $pono = $this->assignPo( json_decode( session::get( 'employee_detail' ), true )[ 'product_line' ] );

    $received = ( input::post( 'productType' ) == 'Stock' ) ? -1 : 0;

    $sql = $this->prepare( "insert into PO (po_no, po_date, supplier_no, product_type, total_purchase_no_vat, total_purchase_vat, total_purchase_price, approved_employee, received, cancelled, product_line)
                                values (?, CURRENT_TIMESTAMP, ?, ?, ?, ?, ?, ?, ?, 0, ?)" );
    $sql->execute( [
      $pono,
      input::post( 'supplierNo' ),
      input::post( 'productType' ),
      ( double )input::post( 'totalNoVat' ),
      ( double )input::post( 'totalVat' ),
      ( double )input::post( 'totalPrice' ),
      json_decode( session::get( 'employee_detail' ), true )[ 'employee_id' ],
      $received,
      json_decode( session::get( 'employee_detail' ), true )[ 'product_line' ]
    ] );

    $poItemsArray = json_decode( input::post( 'poItems' ), true );
    $poItemsArray = json_decode( $poItemsArray, true );

    foreach ( $poItemsArray as $value ) {

      $sql = $this->prepare( "insert into POPrinting (po_no, product_no, so_no, purchase_no_vat, purchase_vat, purchase_price, quantity, total_purchase_price, received, cancelled)
                                values (?, ?, ?, ?, ?, ?, ?, ?, ?, 0)" );
      $sql->execute( [
        $pono,
        $value[ 'product_no' ],
        $value[ 'so_no' ],
        ( double )$value[ 'purchase_no_vat' ],
        ( double )$value[ 'purchase_vat' ],
        ( double )$value[ 'purchase_price' ],
        ( double )$value[ 'quantity' ],
        ( double )$value[ 'quantity' ] * $value[ 'purchase_price' ],
        $received
      ] );

      if ( input::post( 'productType' ) != 'Stock' ) {
        $sql = $this->prepare( "update SO set po_no = ? where so_no = ?" );
        $sql->execute( [ $pono, $value[ 'so_no' ] ] );
      }

    }

    echo $pono;

  }


  // PO Module
  private function assignPo( $productLine ) {
    $poPrefix = $this->getCompany( $productLine ) . 'PO-';
    $sql = $this->prepare( "select ifnull(max(po_no),0) as max from PO where po_no like ?" );
    $sql->execute( [ $poPrefix . '%' ] );
    $maxPoNo = $sql->fetchAll()[ 0 ][ 'max' ];
    $runningNo = '';
    if ( $maxPoNo == '0' ) {
      $runningNo = '00001';
    } else {
      $latestRunningNo = ( int )substr( $maxPoNo, 4 ) + 1;
      if ( strlen( $latestRunningNo ) == 5 ) {
        $runningNo = $latestRunningNo;
      } else {
        for ( $x = 1; $x <= 5 - strlen( $latestRunningNo ); $x++ ) {
          $runningNo .= '0';
        }
        $runningNo .= $latestRunningNo;
      }
    }
    return $poPrefix . $runningNo;
  }

  // =====================================================================================================================================================================================
  // =====================================================================================================================================================================================

  // CI Module
  public function getInstallPo() {
    $sql = $this->prepare( "select 
                                PO.po_no,
                                PO.po_date,
                                PO.supplier_no,
                                Supplier.supplier_name,
                                PO.product_type,
                                PO.total_purchase_no_vat,
                                PO.total_purchase_vat,  
                                PO.total_purchase_price,
                                PO.approved_employee,
                                PO.product_line,
                                POPrinting.quantity,
                                POPrinting.so_no,
                                POPrinting.purchase_no_vat as poprinting_purchase_no_vat,
                                POPrinting.purchase_vat as poprinting_purchase_vat,  
                                POPrinting.purchase_price as poprinting_purchase_price,
                                POPrinting.total_purchase_price as poprinting_total_purchase_price,
                                Product.product_no,
                                Product.product_name,
                                Invoice.commission,
                                Invoice.total_sales_no_vat,
                                Invoice.total_sales_vat,
                                Invoice.total_sales_price,
								SO.so_no,
								SO.payment_type,
                                SOXPrinting.total_sales_no_vat as sox_sales_no_vat,
                                SOXPrinting.total_sales_vat as sox_sales_vat,
                                SOXPrinting.total_sales_price as sox_sales_price
                            from PO
                            inner join POPrinting on POPrinting.po_no = PO.po_no
                            left join Product on Product.product_no = POPrinting.product_no
                            left join Invoice on Invoice.file_no = POPrinting.so_no
							inner join SO on POPrinting.so_no=SO.so_no
                            inner join Supplier on Supplier.supplier_no = PO.supplier_no and Supplier.product_line = PO.product_line
                            left join SOXPrinting on SO.so_no=SOXPrinting.so_no
                            where PO.product_type = 'Install' and POPrinting.received = 0 and POPrinting.cancelled = 0 and PO.approved_employee = ?" );
    $sql->execute( [ json_decode( session::get( 'employee_detail' ), true )[ 'employee_id' ] ] );
    if ( $sql->rowCount() > 0 ) {
      return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
    }
    return json_encode( [] );
  }
  public function debugCi(){
    $ciItemsArray = json_decode( input::post( 'ciItems' ), true );
    $ciItemsArray = json_decode( $ciItemsArray, true );

    $poList = array();

    foreach ( $ciItemsArray as $value ){
      print_r(( double )$value[ 'total_purchase_vat' ]." ");
      

  
    }
  }

  // CI Module
  public function addCi() {

    $ciItemsArray = json_decode( input::post( 'ciItems' ), true );
    $ciItemsArray = json_decode( $ciItemsArray, true );

    $poList = array();

    foreach ( $ciItemsArray as $value ) {

      if ( array_key_exists( $value[ 'po_no' ], $poList ) ) {

        $cino = $poList[ $value[ 'po_no' ] ];

      } else {

        $cino = $this->assignCi( $value[ 'po_no' ] );
        $poList += [ $value[ 'po_no' ] => $cino ];

        // insert CI

        $sql = $this->prepare( "insert into CI (ci_no, ci_date, approved_employee, supplier_no, invoice_no, confirm_subtotal, confirm_vat, confirm_total, cancelled, po_no)
                                        values (?, CURRENT_TIMESTAMP, ?, ?, ?, ?, ?, ?, ?, ?)" );
        $sql->execute( [
          $cino,
          json_decode( session::get( 'employee_detail' ), true )[ 'employee_id' ],
          $value[ 'supplier_no' ],
          '-',
          ( double )$value[ 'total_purchase_no_vat' ],
          ( double )$value[ 'total_purchase_vat' ],
          ( double )$value[ 'total_purchase_price' ],
          0,
          $value[ 'po_no' ]
        ] );

        // update done in SOX
        $sql = $this->prepare( "update SOX inner join SOXPrinting on SOXPrinting.sox_no = SOX.sox_no set done = 1 where SOXPrinting.so_no = ?" );
        $sql->execute( [ $value[ 'so_no' ] ] );

        // update received in PO & POPrinting
        $sql = $this->prepare( "update PO inner join POPrinting on POPrinting.po_no = PO.po_no
                                        set PO.received = 1, POPrinting.received = 1 
                                        where PO.po_no = ? and PO.cancelled = 0 and POPrinting.cancelled = 0" );
        $sql->execute( [ $value[ 'po_no' ] ] );


        // // insert AccountDetail sequence 1
        // // Dr
        // $sql=$this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
        //                         values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, 'CI')");                                             
        // $sql->execute([$cino, '1', '51-1000', (double) $value['total_purchase_no_vat'], 0]);

        // // insert AccountDetail sequence 2
        // // Cr
        // $sql=$this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
        //                         values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, 'CI')");                                             
        // $sql->execute([$cino, '2', '21-3'.$value['supplier_no'], 0, (double) $value['total_purchase_no_vat']]);

        // ============================================================================================================================================================
        // NEW CBA2021 ACC

        // insert AccountDetail sequence 1
        // Dr ซื้อ
        /*$sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
        $sql->execute([$cino, '1', '51-1'.$value['po_no'][0].'00', (double) $value['total_purchase_no_vat'], 0, 'CI']);

        // insert AccountDetail sequence 2
        // Cr เจ้าหนี้การค้ารอ Tax Invoice - Supplier XXX
        $sql = $this->prepare("insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
                                values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)"); 
        $sql->execute([$cino, '2', '21-2'.$value['supplier_no'], 0, (double) $value['total_purchase_no_vat'], 'CI']);
        */
        // ============================================================================================================================================================
        // NEW CBA2020 ACC

        // insert AccountDetail sequence 1
        // Dr รายได้รับล่วงหน้า - โครงการ X

        if ( $value[ 'payment_type' ] == "CC"||  $value[ 'payment_type' ] == "FB") {
         
          $sql = $this->prepare( "insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
											values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)" );
          $sql->execute( [ $cino, '1', '24-1' . $value[ 'po_no' ][ 0 ] . '00', ( double )$value[ 'total_sales_no_vat' ], 0, 'CI' ] );

         
          $sql = $this->prepare( "insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
											values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)" );
          $sql->execute( [ $cino, '2', '41-1' . $value[ 'po_no' ][ 0 ] . '00', 0, ( double )$value[ 'total_sales_no_vat' ], 'CI' ] );

      
          
          $sql = $this->prepare( "insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
											values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)" );
          $sql->execute( [ $cino, '3', '52-0'.$value[ 'po_no' ][ 0 ].'00', ( double )$value[ 'commission' ], 0, 'CI' ] );

          
          $sql = $this->prepare( "insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
											values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)" );
          $sql->execute( [ $cino, '4', '22-1'.$value[ 'po_no' ][ 0 ].'00', 0, ( double )$value[ 'commission' ], 'CI' ] );

          
          $sql = $this->prepare( "insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
											values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)" );
          $sql->execute( [ $cino, '5', '51-1' . $value[ 'po_no' ][ 0 ] . '00', ( double )$value[ 'total_purchase_no_vat' ], 0, 'CI' ] );

          
          $sql = $this->prepare( "insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
											values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)" );
          $sql->execute( [ $cino, '6', '21-2' . $value[ 'supplier_no' ], 0, ( double )$value[ 'total_purchase_no_vat' ], 'CI' ] );
          

        } else {
          // update received in InvoicePrinting
          // $sql = $this->prepare( "update InvoicePrinting
					// 						inner join Invoice on Invoice.invoice_no = InvoicePrinting.invoice_no
					// 						inner join SO on SO.so_no = Invoice.file_no
					// 						inner join PO on PO.po_no = SO.po_no
					// 						set rr_no = ?
					// 						where PO.po_no = ? and SO.so_no = ?" );
          // $sql->execute( [ $cino, $value[ 'po_no' ], $value[ 'so_no' ] ] );
          $sql = $this->prepare( "insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
											values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)" );
          $sql->execute( [ $cino, '1', '24-1' . $value[ 'po_no' ][ 0 ] . '00', ( double )$value[ 'total_sales_no_vat' ], 0, 'CI' ] );

          
          $sql = $this->prepare( "insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
											values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)" );
          $sql->execute( [ $cino, '2', '41-1'. $value[ 'po_no' ][ 0 ].'00', 0, ( double )$value[ 'total_sales_no_vat' ], 'CI' ] );

         
          $sql = $this->prepare( "insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
											values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)" );
          $sql->execute( [ $cino, '3', '52-0'.$value[ 'po_no' ][ 0 ].'00', ( double )$value[ 'commission' ], 0, 'CI' ] );

         
          $sql = $this->prepare( "insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
											values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)" );
          $sql->execute( [ $cino, '4', '22-1'.$value[ 'po_no' ][ 0 ].'00', 0, ( double )$value[ 'commission' ], 'CI' ] );

          
          $sql = $this->prepare( "insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
											values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)" );
          $sql->execute( [ $cino, '5', '51-1' . $value[ 'po_no' ][ 0 ] . '00', ( double )$value[ 'total_purchase_no_vat' ], 0, 'CI' ] );

         
          $sql = $this->prepare( "insert into AccountDetail (file_no, sequence, date, time, account_no, debit, credit, cancelled, note)
											values (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, 0, ?)" );
          $sql->execute( [ $cino, '6', '21-2' . $value[ 'supplier_no' ], 0, ( double )$value[ 'total_purchase_no_vat' ], 'CI' ] );
        }
        // ============================================================================================================================================================
        // END CBA2020 ACC


        echo $cino . ' (' . $value[ 'po_no' ] . ') ';
        echo 'Payment Type: '.$value[ 'payment_type' ];

      }

      // insert CIPrinting

      $sql = $this->prepare( "insert into CIPrinting (ci_no, so_no, product_no, purchase_no_vat, purchase_vat, purchase_price, quantity, total_purchase_price, cancelled)
                                    values (?, ?, ?, ?, ?, ?, ?, ?, 0)" );
      $sql->execute( [
        $cino,
        $value[ 'so_no' ],
        $value[ 'product_no' ],
        ( double )$value[ 'poprinting_purchase_no_vat' ],
        ( double )$value[ 'poprinting_purchase_vat' ],
        ( double )$value[ 'poprinting_purchase_price' ],
        ( double )$value[ 'quantity' ],
        ( double )$value[ 'poprinting_total_purchase_price' ]
      ] );

    }

  }

  // CI Module
  private function assignCi( $pono ) {
    $ciPrefix = $pono[ 0 ] . 'CI-';
    $sql = $this->prepare( "select ifnull(max(ci_no),0) as max from CI where ci_no like ?" );
    $sql->execute( [ $ciPrefix . '%' ] );
    $maxCiNo = $sql->fetchAll()[ 0 ][ 'max' ];
    $runningNo = '';
    if ( $maxCiNo == '0' ) {
      $runningNo = '00001';
    } else {
      $latestRunningNo = ( int )substr( $maxCiNo, 4 ) + 1;
      if ( strlen( $latestRunningNo ) == 5 ) {
        $runningNo = $latestRunningNo;
      } else {
        for ( $x = 1; $x <= 5 - strlen( $latestRunningNo ); $x++ ) {
          $runningNo .= '0';
        }
        $runningNo .= $latestRunningNo;
      }
    }
    return $ciPrefix . $runningNo;
  }

  // =====================================================================================================================================================================================
  // =====================================================================================================================================================================================

  // CS Module
  public function getLocation() {
    $sql = $this->prepare( "select * from CSLocation" );
    $sql->execute();
    if ( $sql->rowCount() > 0 ) {
      return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
    }
    return json_encode( [] );
  }

  // CS Module
  public function addCs() {

    $csno = $this->assignCs( json_decode( session::get( 'employee_detail' ), true )[ 'product_line' ] );

    $sql = $this->prepare( "insert into CS (cs_no, cs_date, employee_id, location_no, minimart, approved_employee, transaction_date, cancelled, note, confirmed)
                                values (?, ?, ?, ?, 0, ?, CURRENT_TIMESTAMP, 0, null, 0)" );
    $sql->execute( [
      $csno,
      input::post( 'cs_date' ),
      json_decode( session::get( 'employee_detail' ), true )[ 'employee_id' ],
      input::post( 'location_no' ),
      json_decode( session::get( 'employee_detail' ), true )[ 'employee_id' ]
    ] );

    $csItemsArray = json_decode( input::post( 'csItems' ), true );
    $csItemsArray = json_decode( $csItemsArray, true );

    foreach ( $csItemsArray as $value ) {

      $sql = $this->prepare( "insert into CSPrinting (cs_no, product_no, sales_no_vat, sales_vat, sales_price, quantity, total_sales_price, note)
                                values (?, ?, ?, ?, ?, ?, ?, null)" );
      $sql->execute( [
        $csno,
        $value[ 'product_no' ],
        ( double )$value[ 'sales_no_vat' ],
        ( double )$value[ 'sales_vat' ],
        ( double )$value[ 'sales_price' ],
        ( double )$value[ 'quantity' ],
        ( double )$value[ 'quantity' ] * $value[ 'sales_price' ]
      ] );

      $sql = $this->prepare( "insert into StockOut (product_no, file_no, file_type, date, time, quantity_out, lot, rr_no)
                                    values (?, ?, 'CS', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, 0, ?)" );
      $sql->execute( [ $value[ 'product_no' ], $csno, $value[ 'quantity' ], $csno ] );

    }

    echo $csno;

  }

  // CS Module
  private function assignCs( $productLine ) {
    $csPrefix = $this->getCompany( $productLine ) . 'CS-';
    $sql = $this->prepare( "select ifnull(max(cs_no),0) as max from CS where cs_no like ?" );
    $sql->execute( [ $csPrefix . '%' ] );
    $maxCsNo = $sql->fetchAll()[ 0 ][ 'max' ];
    $runningNo = '';
    if ( $maxCsNo == '0' ) {
      $runningNo = '00001';
    } else {
      $latestRunningNo = ( int )substr( $maxCsNo, 4 ) + 1;
      if ( strlen( $latestRunningNo ) == 5 ) {
        $runningNo = $latestRunningNo;
      } else {
        for ( $x = 1; $x <= 5 - strlen( $latestRunningNo ); $x++ ) {
          $runningNo .= '0';
        }
        $runningNo .= $latestRunningNo;
      }
    }
    return $csPrefix . $runningNo;
  }

  // =====================================================================================================================================================================================
  // =====================================================================================================================================================================================

  public function getXiaomiReport() {
    $sql = $this->prepare( "select
                                	SOPrinting.product_no,
                                    Product.product_description,
                                    Product.purchase_price,
                                    sum(SOPrinting.quantity) as quantity
                                from SO
                                left join SOPrinting on SOPrinting.so_no = SO.so_no
                                left join Product on Product.product_no = SOPrinting.product_no
                                where SO.note = 'FO' and SO.cancelled = 0 and Product.purchase_price > 0
                                group by 
                                	SOPrinting.product_no,
                                    Product.product_description,
                                    Product.purchase_price" );
    $sql->execute();
    if ( $sql->rowCount() > 0 ) {
      return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
    }
    return json_encode( [] );
  }

  public function getXiaomiReportDownload() {
    $sql = $this->prepare( "select
                                	SOPrinting.product_no,
                                    Product.product_description,
                                    Product.purchase_price,
                                    sum(SOPrinting.quantity) as quantity
                                from SO
                                left join SOPrinting on SOPrinting.so_no = SO.so_no
                                left join Product on Product.product_no = SOPrinting.product_no
                                where SO.note = CURRENT_DATE and SO.cancelled = 0 and Product.purchase_price > 0
                                group by 
                                	SOPrinting.product_no,
                                    Product.product_description,
                                    Product.purchase_price" );
    $sql->execute();
    if ( $sql->rowCount() > 0 ) {
      return $sql->fetchAll();
    }
    return [];
  }

  public function addXr() {
    $sql = $this->prepare( "update SO set note = CURRENT_DATE where note = 'FO'" );
    $sql->execute();
  }

  // =====================================================================================================================================================================================
  // =====================================================================================================================================================================================

  // Dashboard Module
  public function getDashboardSales() {
    $sql = $this->prepare( "select 
                                	sum(SOPrinting.total_sales) as total_sales,
                                    discount.discount,
                                    sum(SOPrinting.margin) as margin
                                from SOPrinting 
                                inner join SO on SO.so_no = SOPrinting.so_no
                                join (select sum(SO.discountso) as discount from SO where SO.product_line = 1 and SO.cancelled = 0) as discount
                                where SO.product_line = 1 and SOPrinting.cancelled = 0" );
    $sql->execute( [ json_decode( session::get( 'employee_detail' ), true )[ 'product_line' ] ] );
    if ( $sql->rowCount() > 0 ) {
      return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
    }
    return json_encode( [] );
  }

  // Dashboard Module
  public function getDashborad() {
    $sql = $this->prepare( "select * from PO where approved_employee = ? and not PO.received = -1 order by po_no desc" );
    $sql->execute( [ json_decode( session::get( 'employee_detail' ), true )[ 'employee_id' ] ] );
    if ( $sql->rowCount() > 0 ) {
      return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
    }
    return json_encode( [] );
  }

  // Dashboard Module
  public function getDashboradSo() {
    $sql = $this->prepare( "select 
                                	  SO.so_no,
                                    SO.so_date,
                                    SO.so_time,
                                    SO.cancelled,
                                    Invoice.invoice_no,
                                    Employee.employee_id,
                                    Employee.employee_nickname_thai,
                                    Employee.product_line,
                                    Product.product_name,
                                    SOPrinting.sales_price,
                                    SOPrinting.quantity,
                                    SOPrinting.total_sales,
                                    SO.total_sales_price,
                                    SO.discountso,
                                    SOXPrinting.sox_no,
                                    SOX.tracking_number,
                                    SOX.note,
                                    SOX.slip_name                                
                                from SOPrinting
                                inner join SO on SO.so_no = SOPrinting.so_no
                                inner join Product on Product.product_no = SOPrinting.product_no
                                inner join Employee on SO.employee_id = Employee.employee_id
                                left join Invoice on Invoice.file_no = SO.so_no
                                left join SOXPrinting on SO.so_no = SOXPrinting.so_no
                                left join SOX on SOXPrinting.sox_no = SOX.sox_no
                                where SO.approve_employee_no = ?
                                order by SO.so_no desc" );
    $sql->execute( [ json_decode( session::get( 'employee_detail' ), true )[ 'employee_id' ] ] );
    if ( $sql->rowCount() > 0 ) {
      return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
    }
    return json_encode( [] );
  }

  // Dashboard Module
  public function getDashboradPo() {
    $sql = $this->prepare( "select 
                                	PO.*,
                                    CI.ci_no,
                                    RR.rr_no,
                                    SO.so_no
                                from PO 
                                left join CI on CI.po_no = PO.po_no
                                left join RR on RR.po_no = PO.po_no
                                LEFT JOIN SO on SO.po_no = PO.po_no
                                where PO.approved_employee = ? and not PO.received = -1 order by po_no desc;" );
    $sql->execute( [ json_decode( session::get( 'employee_detail' ), true )[ 'employee_id' ] ] );
    if ( $sql->rowCount() > 0 ) {
      return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
    }
    return json_encode( [] );
  }

  // Dashboard Module
  	public function getDashboradCs() {
    $sql = $this->prepare( "select CS.cs_no, cs_date from CS where approved_employee = ? and cancelled = 0 order by cs_no desc" );
    $sql->execute( [ json_decode( session::get( 'employee_detail' ), true )[ 'employee_id' ] ] );
    if ( $sql->rowCount() > 0 ) {
      return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
    }
    return json_encode( [] );
  }

  //Tournament2
  private function updateTeams($sono){

      $sql = $this->prepare("select SO.so_no,
                              SO.so_sales_price,
                              SO.employee_id,
                              TeamMembers.employee_id,
                              TeamMembers.team_id,
                              Teams.team_id
                              from SO 
                              inner join TeamMembers on SO.employee_id = TeamMembers.employee_id
                              inner join Teams on TeamMembers.team_id = Teams.team_id
                              where so_no = ? and SO.cancelled = 0");
      $sql->execute([$sono]);
      $temp = $sql->fetchAll()[0];

      // insert TeamTransactions
      $sql = $this->prepare("insert into TeamTransactions (date,time,team_id,score,so_no,status) 
                                  values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP,?,?,?,0)");
      $team_id = $sql->fetchAll()[0]['team_id'];
      $sql->execute([$team_id, input::post('sellerNo'), $sono, (double) input::post('so_sales_price')]);

      // update Teams

      $sql = $this->prepare("update TeamTransactions inner join TeamMembers on TeamTransactions.team_id = TeamMembers.team_id	
                              inner join SO on SO.employee_id  = TeamMembers.employee_id
                              set TeamTransactions.score = (SO.total_sales_price * 1.5) where SO.so_no = ?");
      $sql->execute([$sono]);
      

      //update multi

  }

  	public function getSalesReport() {
    $sql = $this->prepare( "select
    SO.so_no,
    Week.week as so_week,
    SO.so_date,
    SO.so_time,
    Product.product_line,
    SOPrinting.product_no,
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
where SOPrinting.cancelled = 0 and not ProductCategory.category_name like 'ค่าจัดส่ง' and not ProductCategory.category_name like 'ค่าส่ง' and not ProductCategory.category_name like 'ค่าส่งสินค้า' and not ProductCategory.category_name like 'ค่าติดตั้ง'  
ORDER BY `SO`.`so_date` , `SO`.`so_time`" );
    $sql->execute();
    if ( $sql->rowCount() > 0 ) {
      return $sql->fetchAll();
    }
    return [];
  }
  public function getOSSalesReport() {
    $sql = $this->prepare( "select
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
                                where SOPrinting.cancelled = 0 and SO.employee_id REGEXP 'OS...'" );
    $sql->execute();
    if ( $sql->rowCount() > 0 ) {
      return $sql->fetchAll();
    }
    return [];
  }

  	public function getPointReport() {
    $sql = $this->prepare( "select
                                	PointLog.date,
                                    concat(sp.employee_id, ' ', sp.employee_nickname_thai) as sp,
                                    sp.product_line,
                                    concat(ce.employee_id, ' ', ce.employee_nickname_thai) as ce,
                                    PointLog.point,
                                    PointLog.remark,
                                    PointLog.note,
									PointLog.type,
									SO.total_sales_no_vat,
									SO.total_sales_vat,
                                    SO.total_sales_price
                                from PointLog 
                                inner join Employee as sp on sp.employee_id = PointLog.employee_id
                                inner join Employee as ce on ce.employee_id = sp.ce_id
                                left join SO on SO.so_no = PointLog.note
                                where PointLog.employee_id > 0 and PointLog.cancelled = 0" );
    $sql->execute();
    if ( $sql->rowCount() > 0 ) {
      return $sql->fetchAll();
    }
    return [];
  }
  public function OSGetPointReport() {
    $sql = $this->prepare( "select
                                	PointLog.date,
                                    concat(sp.employee_id, ' ', sp.employee_nickname_thai) as sp,
                                    sp.product_line,
                                    concat(ce.employee_id, ' ', ce.employee_nickname_thai) as ce,
                                    PointLog.point,
                                    PointLog.remark,
                                    PointLog.note,
									PointLog.type,
									SO.total_sales_no_vat,
									SO.total_sales_vat,
                                    SO.total_sales_price
                                from PointLog 
                                inner join Employee as sp on sp.employee_id = PointLog.employee_id
                                inner join Employee as ce on ce.employee_id = sp.ce_id
                                left join SO on SO.so_no = PointLog.note
                                where PointLog.employee_id > 0 and PointLog.cancelled = 0 and PointLog.employee_id REGEXP 'OS...'" );
    $sql->execute();
    if ( $sql->rowCount() > 0 ) {
      return $sql->fetchAll();
    }
    return [];
  }
  	public function getTournament() {
    $sql = $this->prepare("select substring(Teams.team_id,2,1) as product_line, Teams.team_name, Teams.ce_id from Teams" );
    $sql->execute();
    if ( $sql->rowCount() > 0 ) {
      return $sql->fetchAll();
    }
    return [];
  }
	
//   private function promotionWeek2($sono) {

//     $sql = $this->prepare("select * from 
//                             (select count(*) as countEasySell from PointLog where employee_id = ? and remark = 'Week 1-2 - Easy Sales' and cancelled = 0) as countEasySell,
//                             (select sum(total_sales_price) as totalSold from SO where employee_id = ?) as sumTotalSold");
                            
//     $sql->execute([input::post('sellerNo')]);
//     $temp1 = $sql->fetchAll()[0];
    
//     // $achieved = $temp['countProLine'];
//     $achieved2 = $temp1['countEasySell'];
//     $totalSold2 = $temp1['totalSold'];
    
//     $soItemsArray = json_decode(input::post('soItems'), true); 
//     $soItemsArray = json_decode($soItemsArray, true);
//     $count = 0;
//     $extraPoint = 0;
    
//     if(is_numeric(input::post('sellerNo'))) {
        
//         switch (json_decode(session::get('employee_detail'), true)['product_line']) {
            
//             case '1': // ขายกล้องฟิล์มตัวแรก && ขายสินค้าสาย 1 ครบ 1000 บาท

//                 $sql = $this->prepare("select * from 
//                 (select count(*) as countProLine1 from PointLog where employee_id = ? and remark = 'Week 2 - Line 1.1' and cancelled = 0) as countProLine1,
//                 (select count(*) as countProLine2 from PointLog where employee_id = ? and remark = 'Week 2 - Line 1.2' and cancelled = 0) as countProLine2");
//                 $sql->execute([input::post('sellerNo'),input::post('sellerNo')]);
//                 $temp = $sql->fetchAll()[0];

//                 if ($temp['countProLine1'] == 0) {

//                     $passed1 = false;

//                     foreach($soItemsArray as $value) {

//                         if ($value['sub_category'] == 'Film Camera' ) {
//                             $passed1 = true;
//                         }
//                     }
                    
//                     $extraPoint1 = ($passed1) ? 20 : 0;
                    
//                     if($extraPoint1 > 0) {
//                         $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
//                                                 values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//                         $sql->execute([input::post('sellerNo'), $extraPoint1, 'Week 2 - Line 1.1', $sono]);
//                         echo ' (ผ่านโปรสาย 1.1 ได้รับ '.$extraPoint1.' พ้อยท์!!!)';
//                     }
//                 }

//                 if ($temp['countProLine2'] == 0 ) {

//                     $sql = $this->prepare("select ifnull(sum(total_sales), 0) as totalSold from SO 
//                                             inner join SOPrinting on SOPrinting.so_no = SO.so_no
//                                             inner join Product on Product.product_no = SOPrinting.product_no
//                                             where employee_id = ? and Product.product_line = '1'
//                                             and SO.cancelled = 0 and ((so_date = '2022-06-06' AND so_time >= '12:00:00') OR so_date between '2022-06-06' AND '2022-06-11')");
//                     $sql->execute([input::post('sellerNo')]);
//                     $totalSold = $sql->fetchAll()[0]['totalSold'];
                    
//                     $extraPoint = ($totalSold >= 1000) ? 25: 0;
                    
//                     if($extraPoint > 0) {
//                         $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note, type, cancelled) values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?, 'Promotion', 0)");
//                         $sql->execute([input::post('sellerNo'), $extraPoint, 'Week 2 - Line 1.2', $sono]);
//                         echo ' (ผ่านโปรสาย 1.2 ได้รับ '.$extraPoint.' พ้อยท์!!!)';
//                     }

//                 }
//                 break;

//             case '2': // ขายแอร์

//                 $sql = $this->prepare("select * from 
//                 (select count(*) as countProLine1 from PointLog where employee_id = ? and remark = 'Week 2 - Line 2.1') as countProLine1,
//                 (select count(*) as countProLine2 from PointLog where employee_id = ? and remark = 'Week 2 - Line 2.2') as countProLine2,
//                 (select count(*) as countProLine3 from PointLog where employee_id = ? and remark = 'Week 2 - Line 2.3') as countProLine3");
                
//                 $sql->execute([input::post('sellerNo'), input::post('sellerNo'), input::post('sellerNo')]);
//                 $temp = $sql->fetchAll()[0];
            
//                 foreach($soItemsArray as $value) {
//                     if($value['category_no'] == '01') {
//                         $count = $count + $value['quantity'];
//                     }
//                 }
                
//                 if ($temp['countProLine1'] == 0 && $count == 1) {
                    
//                     $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note, type, cancelled) values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?, 'Promotion', 0)");
//                     $sql->execute([input::post('sellerNo'), 70, 'Week 2 - Line 2.1', $sono]);
//                     echo ' (ผ่านโปรสาย 2.1 ได้รับ 70 พ้อยท์!!!)';
                        
//                 } else if ($temp['countProLine1'] == 0 && $count == 2) {
                    
//                     $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note, type, cancelled) values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?, 'Promotion', 0)");
//                     $sql->execute([input::post('sellerNo'), 70, 'Week 2 - Line 2.1', $sono]);
//                     echo ' (ผ่านโปรสาย 2.1 ได้รับ 70 พ้อยท์!!!)';
                    
//                     $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note, type, cancelled) values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?, 'Promotion', 0)");
//                     $sql->execute([input::post('sellerNo'), 90, 'Week 2 - Line 2.2', $sono]);
//                     echo ' (ผ่านโปรสาย 2.2 ได้รับ 90 พ้อยท์!!!)';
                    
//                 } else if ($temp['countProLine1'] == 0 && $count > 2) {
                    
//                     $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note, type, cancelled) values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?, 'Promotion', 0)");
//                     $sql->execute([input::post('sellerNo'), 70, 'Week 2 - Line 2.1', $sono]);
//                     echo ' (ผ่านโปรสาย 2.1 ได้รับ 70 พ้อยท์!!!)';
                    
//                     $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note, type, cancelled) values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?, 'Promotion', 0)");
//                     $sql->execute([input::post('sellerNo'), 90, 'Week 2 - Line 2.2', $sono]);
//                     echo ' (ผ่านโปรสาย 2.2 ได้รับ 90 พ้อยท์!!!)';
                    
//                     $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note, type, cancelled) values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?, 'Promotion', 0)");
//                     $sql->execute([input::post('sellerNo'), 110, 'Week 2 - Line 2.3', $sono]);
//                     echo ' (ผ่านโปรสาย 2.3 ได้รับ 110 พ้อยท์!!!)';
                    
//                 } else if ($temp['countProLine1'] == 1 && $temp['countProLine2'] == 0 && $count == 1) {
                    
//                     $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note, type, cancelled) values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?, 'Promotion', 0)");
//                     $sql->execute([input::post('sellerNo'), 70, 'Week 2 - Line 2.1', $sono]);
//                     echo ' (ผ่านโปรสาย 2.1 ได้รับ 70 พ้อยท์!!!)';
                    
//                 } else if ($temp['countProLine1'] == 1 && $temp['countProLine2'] == 0 && $count > 1) {
                    
//                     $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note, type, cancelled) values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?, 'Promotion', 0)");
//                     $sql->execute([input::post('sellerNo'), 90, 'Week 2 - Line 2.2', $sono]);
//                     echo ' (ผ่านโปรสาย 2.2 ได้รับ 90 พ้อยท์!!!)';
                    
//                     $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note, type, cancelled) values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?, 'Promotion', 0)");
//                     $sql->execute([input::post('sellerNo'), 110, 'Week 2 - Line 2.3', $sono]);
//                     echo ' (ผ่านโปรสาย 2.3 ได้รับ 110 พ้อยท์!!!)';
                    
//                 } else if ($temp['countProLine1'] == 1 && $temp['countProLine2'] == 1 && $temp['countProLine3'] == 0 && $count > 0) {
                    
//                     $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note, type, cancelled) values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?, 'Promotion', 0)");
//                     $sql->execute([input::post('sellerNo'), 110, 'Week 2 - Line 2.3', $sono]);
//                     echo ' (ผ่านโปรสาย 2.3 ได้รับ 110 พ้อยท์!!!)';
                    
//                 }
                
//                 break;
            
//             // case '3': // 



//             // case '4': //

//             case '3': // 

//                 $sql = $this->prepare("select * from 
//                 (select count(*) as countProLine1 from PointLog where employee_id = ? and remark = 'Week 2 - Line 3.1') as countProLine1,
//                 (select count(*) as countProLine2 from PointLog where employee_id = ? and remark = 'Week 2 - Line 3.2') as countProLine2,
//                 (select count(*) as countProLine3 from PointLog where employee_id = ? and remark = 'Week 2 - Line 3.3') as countProLine3");

//                 $sql->execute([input::post('sellerNo'), input::post('sellerNo'), input::post('sellerNo')]);
//                 $temp = $sql->fetchAll()[0];

//                 if ($temp['countProLine1'] == 0) {

//                     foreach($soItemsArray as $value) {
//                         if($value['product_line'] == '3') {
//                             $count = $count + $value['quantity'];
//                         }
//                     }
                    
//                     if ($temp['countProLine1'] == 0 && $count == 1) {
                        
//                         $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note, type, cancelled) values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?, 'Promotion', 0)");
//                         $sql->execute([input::post('sellerNo'), 10, 'Week 2 - Line 3.1', $sono]);
//                         echo ' (ผ่านโปรสาย 3.1 ได้รับ 25 พ้อยท์!!!)';

//                 }
//             }
        
//                 if ($temp['countProLine2'] == 0) { // ครบทุก 800 บาท คือ พอได้ 800 ก็ได้แต้ม

//                     $sql = $this->prepare("select ifnull(sum(total_sales), 0) as totalSold from SO 
//                                             inner join SOPrinting on SOPrinting.so_no = SO.so_no
//                                             inner join Product on Product.product_no = SOPrinting.product_no
//                                             inner join ProductCategory on ProductCategory.category_no = Product.category_no and ProductCategory.product_line = Product.product_line
//                                             where SO.cancelled = 0 and ((so_date = '2022-06-05' AND so_time >= '12:00:00') OR so_date between '2022-06-05' AND '2022-06-11')
//                                             and Product.product_line = 3 and (Product.category_no = '04' OR Product.category_no = '08' OR Product.category_no = '09' OR Product.category_no = '10' OR Product.category_no = '11'");

//                     $sql->execute([input::post('sellerNo')]);
//                     $totalSold = $sql->fetchAll()[0]['totalSold'];                 
                    
//                     $extraPoint = ($totalSold >= 800) ? 10 : 0;

//                     if($extraPoint > 0) {
//                         $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note, type, cancelled) values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?, 'Promotion', 0)");
//                         $sql->execute([input::post('sellerNo'), $extraPoint, 'Week 2 - Line 3.2', $sono]);
//                         echo ' (ผ่านโปรสาย 3.2 ได้รับ '.$extraPoint.' พ้อยท์!!!)';
            
//                     }

//                 }

//                 if ($temp['countProLine3'] == 0) {

//                     // foreach($soItemsArray as $value) {
//                     //     if($value['category_no'] == '03' || $value['category_no'] == '17' || $value['category_no'] == '20') {
//                     //         $count = $count + $value['quantity'];
//                     //     }
//                     // }
                    
//                     // if ($temp['countProLine3'] == 0 && $count == 1) {
                        
                        
//                     $sql = $this->prepare("select ifnull(sum(total_sales), 0) as totalSold from SO 
//                                             inner join SOPrinting on SOPrinting.so_no = SO.so_no
//                                             inner join Product on Product.product_no = SOPrinting.product_no
//                                             inner join ProductCategory on ProductCategory.category_no = Product.category_no and ProductCategory.product_line = Product.product_line
//                                             where SO.cancelled = 0 and ((so_date = '2022-06-05' AND so_time >= '12:00:00') OR so_date between '2022-06-05' AND '2022-06-11')
//                                             and Product.product_line = 3 and (Product.category_no = '03' OR Product.category_no = '17' OR Product.category_no = '20')");
                    
//                     $sql->execute([input::post('sellerNo')]);
//                     $countSold = $sql->fetchAll()[0]['countSold'];

//                     if($countSold >= 1) {
                        
//                         $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
//                                                 values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//                         $sql->execute([input::post('sellerNo'), 30 , 'Week 2 - Line 3.3', $sono]);
//                         echo ' (ผ่านโปรสาย 3.3 ได้รับ 30 พ้อยท์!!!) ';
//                         }

                    
//                     }
                
//                 break;

//             case '4':
                
//                 $sql = $this->prepare("select * from 
//                 (select count(*) as countProLine1 from PointLog where employee_id = ? and remark = 'Week 2 - Line 4.1') as countProLine1,
//                 (select count(*) as countProLine2 from PointLog where employee_id = ? and remark = 'Week 2 - Line 4.2') as countProLine2,
//                 (select count(*) as countProLine3 from PointLog where employee_id = ? and remark = 'Week 2 - Line 4.3') as countProLine3");

//                 $sql->execute([input::post('sellerNo'), input::post('sellerNo')]);
//                 $temp = $sql->fetchAll()[0];

                
//                 if ($temp['countProLine1'] == 0) {
//                 $sql = $this->prepare("select ifnull(sum(total_sales), 0) as totalSold from SO 
//                                             inner join SOPrinting on SOPrinting.so_no = SO.so_no
//                                             inner join Product on Product.product_no = SOPrinting.product_no
//                                             inner join ProductCategory on ProductCategory.category_no = Product.category_no and ProductCategory.product_line = Product.product_line
//                                             where SO.cancelled = 0 and ((so_date = '2022-06-05' AND so_time >= '12:00:00') OR so_date between '2022-06-05' AND '2022-06-11')
//                                             and Product.product_line = 4 and (Product.category_no = '01' OR Product.category_no = '02' OR Product.category_no = '03' OR 
//                                             Product.category_no = '04' OR 
//                                             Product.category_no = '09' OR Product.category_no = '12' OR Product.category_no = '14' OR 
//                                             Product.category_no = '19' OR Product.category_no = '20' OR Product.category_no = '21')");

//                 $sql->execute([input::post('sellerNo')]);
//                 $countSold = $sql->fetchAll()[0]['countSold'];

//                 if($countSold >= 1) {
                        
//                     $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
//                                             values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//                     $sql->execute([input::post('sellerNo'), 5 , 'Week 2 - Line 4.1', $sono]);
//                     echo ' (ผ่านโปรสาย 4.1 ได้รับ 5 พ้อยท์!!!) ';
//                     }
//                 }

//                 if ($temp['countProLine2'] == 0) {

//                     $sql = $this->prepare("select ifnull(sum(total_sales), 0) as totalSold from SO 
//                                             inner join SOPrinting on SOPrinting.so_no = SO.so_no
//                                             inner join Product on Product.product_no = SOPrinting.product_no
//                                             inner join ProductCategory on ProductCategory.category_no = Product.category_no and ProductCategory.product_line = Product.product_line
//                                             where SO.cancelled = 0 and ((so_date = '2022-06-05' AND so_time >= '12:00:00') OR so_date between '2022-06-05' AND '2022-06-11')
//                                             and Product.product_line = 4 and (Product.category_no = '05')");
//                     $sql->execute([input::post('sellerNo')]);
//                     $totalSold = $sql->fetchAll()[0]['totalSold'];
                    
//                     if($totalSold >= 2500) {
//                         $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
//                                             values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//                         $sql->execute([input::post('sellerNo'), 15 , 'Week 2 - Line 4.2', $sono]);
//                         echo ' (ผ่านโปรสาย 4.2 ได้รับ 15 พ้อยท์!!!) ';
//                     }
                    
//                 }

//                 if ($temp['countProLine3'] == 0) {

//                     $sql = $this->prepare("select ifnull(sum(total_sales), 0) as totalSold from SO 
//                                             inner join SOPrinting on SOPrinting.so_no = SO.so_no
//                                             inner join Product on Product.product_no = SOPrinting.product_no
//                                             inner join ProductCategory on ProductCategory.category_no = Product.category_no and ProductCategory.product_line = Product.product_line
//                                             where SO.cancelled = 0 and ((so_date = '2022-06-05' AND so_time >= '12:00:00') OR so_date between '2022-06-05' AND '2022-06-11')
//                                             and Product.product_line = 4 and (Product.category_no = '06')");

//                     $sql->execute([input::post('sellerNo')]);
//                     $countSold = $sql->fetchAll()[0]['countSold'];

//                 if($countSold >= 2) {
                        
//                     $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
//                                             values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//                     $sql->execute([input::post('sellerNo'), 20 , 'Week 2 - Line 4.3', $sono]);
//                     echo ' (ผ่านโปรสาย 4.3 ได้รับ 20 พ้อยท์!!!) ';
//                     }
//                 }
//                 break;
                
//             case '5';

//             $sql = $this->prepare("select * from 
//                 (select count(*) as countProLine1 from PointLog where employee_id = ? and remark = 'Week 2 - Line 5.1') as countProLine1,
//                 (select count(*) as countProLine2 from PointLog where employee_id = ? and remark = 'Week 2 - Line 5.2') as countProLine2,
//                 (select count(*) as countProLine3 from PointLog where employee_id = ? and remark = 'Week 2 - Line 5.3') as countProLine3,
//                 (select count(*) as countProLine4 from PointLog where employee_id = ? and remark = 'Week 2 - Line 5.4') as countProLine4");

//                 $sql->execute([input::post('sellerNo'), input::post('sellerNo')]);
//                 $temp = $sql->fetchAll()[0];

//                 // if ($temp['countProLine1'] == 0) {

//                 //     $sql = $this->prepare("select ifnull(sum(total_sales), 0) as totalSold from SO 
//                 //                             inner join SOPrinting on SOPrinting.so_no = SO.so_no
//                 //                             inner join Product on Product.product_no = SOPrinting.product_no
//                 //                             inner join ProductCategory on ProductCategory.category_no = Product.category_no and ProductCategory.product_line = Product.product_line
//                 //                             where SO.cancelled = 0 and ((so_date = '2022-06-05' AND so_time >= '12:00:00') OR so_date between '2022-06-05' AND '2022-06-11')
//                 //                             and Product.product_line = 5 and (Product.category_no = '01' OR Product.category_no = '02')");

//                 //     $sql->execute([input::post('sellerNo')]);
//                 //     $countSold = $sql->fetchAll()[0]['countSold'];

//                 //     if ($countSold >=1) {

//                 //         $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
//                 //                                 values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//                 //         $sql->execute([input::post('sellerNo'), 50, 'Week 2 - Line 5.1', $sono]);

//                 //         echo ' (ผ่านโปรสาย 5 ได้รับ 50 พ้อยท์!!!) ';

//                 //     }
//                 // }

//                 // if ($temp['countProLine2'] == 0) {


//                 // }
//                 foreach($soItemsArray as $value) {
//                     if($value['category_no'] == '01') {
//                         $count = $count + $value['quantity'];
//                     }
//                 }
                
//                 if ($temp['countProLine1'] == 0 && $count == 1) {
                    
//                     $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
//                                                 values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//                     $sql->execute([input::post('sellerNo'), 50, 'Week 2 - Line 5.1', $sono]);
//                     echo ' (ผ่านโปรสาย 5.1 ได้รับ 50 พ้อยท์!!!)';
                        
//                 } else if ($temp['countProLine1'] == 0 && $count == 2) {
                    
//                     $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
//                                                 values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//                     $sql->execute([input::post('sellerNo'), 50, 'Week 2 - Line 5.1', $sono]);
//                     echo ' (ผ่านโปรสาย 5.1 ได้รับ 50 พ้อยท์!!!)';
                    
//                     $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
//                                                 values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//                     $sql->execute([input::post('sellerNo'), 70, 'Week 2 - Line 5.2', $sono]);
//                     echo ' (ผ่านโปรสาย 5.2 ได้รับ 70 พ้อยท์!!!)';
                    
//                 } else if ($temp['countProLine1'] == 0 && $count > 2) {
                    
//                     $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
//                                                 values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//                     $sql->execute([input::post('sellerNo'), 50, 'Week 2 - Line 5.1', $sono]);
//                     echo ' (ผ่านโปรสาย 5.1 ได้รับ 50 พ้อยท์!!!)';
                    
//                     $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
//                                                 values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//                     $sql->execute([input::post('sellerNo'), 70, 'Week 2 - Line 5.2)', $sono]);
//                     echo ' (ผ่านโปรสาย 5.2 ได้รับ 70 พ้อยท์!!!)';
                    
//                     $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
//                                                 values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//                     $sql->execute([input::post('sellerNo'), 100, 'Week 2 - Line 5.3', $sono]);
//                     echo ' (ผ่านโปรสาย 5.3 ได้รับ 100 พ้อยท์!!!)';
                    
//                 } else if ($temp['countProLine1'] == 1 && $temp['countProLine2'] == 0 && $count == 1) {
                    
//                     $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
//                                                 values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//                     $sql->execute([input::post('sellerNo'), 70, 'Week 2 - Line 5.2', $sono]);
//                     echo ' (ผ่านโปรสาย 5.2 ได้รับ 70 พ้อยท์!!!)';
                    
//                 } else if ($temp['countProLine1'] == 1 && $temp['countProLine2'] == 0 && $count > 1) {
                    
//                     $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
//                                                 values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//                     $sql->execute([input::post('sellerNo'), 70, 'Week 2 - Line 5.2', $sono]);
//                     echo ' (ผ่านโปรสาย 5.2 ได้รับ 70 พ้อยท์!!!)';
                    
//                     $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
//                                                 values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//                     $sql->execute([input::post('sellerNo'), 100, 'Week 2 - Line 5.3', $sono]);
//                     echo ' (ผ่านโปรสาย 5.3 ได้รับ 100 พ้อยท์!!!)';
                    
//                 } else if ($temp['countProLine1'] == 1 && $temp['countProLine2'] == 1 && $temp['countProLine3'] == 0 && $count > 0) {
                    
//                     $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
//                                                 values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//                     $sql->execute([input::post('sellerNo'), 100, 'Week 2 - Line 5.3', $sono]);
//                     echo ' (ผ่านโปรสาย 5.3 ได้รับ 100 พ้อยท์!!!)';


//                 }

//                 if ($temp['countProLine4'] == 0) {

//                     $sql = $this->prepare("select ifnull(sum(total_sales), 0) as totalSold from SO 
//                                             inner join SOPrinting on SOPrinting.so_no = SO.so_no
//                                             inner join Product on Product.product_no = SOPrinting.product_no
//                                             inner join ProductCategory on ProductCategory.category_no = Product.category_no and ProductCategory.product_line = Product.product_line
//                                             where SO.cancelled = 0 and ((so_date = '2022-06-05' AND so_time >= '12:00:00') OR so_date between '2022-06-05' AND '2022-06-11')
//                                             and Product.product_line = 5 and Product.sub_category = 'Accessories' or Product.sub_category = 'peripherals') ");

//                     $sql->execute([input::post('sellerNo'),$sono]);
//                     $countSold = $sql->fetchAll()[0]['countSold'];

//                     if($countSold >= 1) {
                        
//                         $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
//                                                 values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//                         $sql->execute([input::post('sellerNo'), 15, 'Week 2 - Line 5.4', $sono]);
//                         echo ' (ผ่านโปรสาย 5.4 ได้รับ 15 พ้อยท์!!!) ';
//                         }
//                 }
//                 break;
                
                

                

            
                

//             case '6': // ขายสินค้าหมวด snack ยกเว้น made by neen -> 10 points && ขายสินค้าสาย 6 ครบ 500 บาท -> 15 points

//                 $sql = $this->prepare("select * from 
//                 (select count(*) as countProLine1 from PointLog where employee_id = ? and remark = 'Week 2 - Line 6.1') as countProLine1,
//                 (select count(*) as countProLine2 from PointLog where employee_id = ? and remark = 'Week 2 - Line 6.2') as countProLine2");

//                 $sql->execute([input::post('sellerNo'), input::post('sellerNo')]);
//                 $temp = $sql->fetchAll()[0];

//                 if ($temp['countProLine1'] == 0) {

//                     $sql = $this->prepare("select ifnull(sum(total_sales), 0) as totalSold from SO 
//                                             inner join SOPrinting on SOPrinting.so_no = SO.so_no
//                                             inner join Product on Product.product_no = SOPrinting.product_no
//                                             inner join ProductCategory on ProductCategory.category_no = Product.category_no and ProductCategory.product_line = Product.product_line
//                                             where employee_id = ? and Product.product_line = '6' and not Product.supplier_no = '605'
//                                             SO.cancelled = 0 and ((so_date = '2022-06-06' AND so_time >= '12:00:00') OR so_date between '2022-06-06' AND '2022-06-11')");

                    
//                     $sql->execute([input::post('sellerNo')]);
//                     $totalSold = $sql->fetchAll()[0]['totalSold'];  
                                            
//                     if($totalSold >= 300) {
                    
//                         $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note, type, cancelled) values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//                         $sql->execute([input::post('sellerNo'), 10, 'Week 2 - Line 6.1', $sono]);
//                         echo ' (ผ่านโปรสาย 6.1 ได้รับ 10 พ้อยท์!!!)';
                    
//                         }        
                        
//                     }

//                 if ($temp['countProLine2'] == 0) {

//                     $sql = $this->prepare("select ifnull(sum(total_sales), 0) as totalSold from SO 
//                                             inner join SOPrinting on SOPrinting.so_no = SO.so_no
//                                             inner join Product on Product.product_no = SOPrinting.product_no
//                                             where employee_id = ? and Product.product_line = '6'
//                                             and SO.cancelled = 0 and ((so_date = '2022-06-06' AND so_time >= '12:00:00') OR so_date between '2022-06-06' AND '2022-06-11')");
//                     $sql->execute([input::post('sellerNo')]);
//                     $totalSold = $sql->fetchAll()[0]['totalSold'];
                    
//                     $extraPoint = ($totalSold >= 500) ? 15: 0;
                    
//                     if($extraPoint > 0) {
//                         $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note, type, cancelled) values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?, 'Promotion', 0)");
//                         $sql->execute([input::post('sellerNo'), $extraPoint, 'Week 2 - Line 6.2', $sono]);
//                         echo ' (ผ่านโปรสาย 6.2 ได้รับ '.$extraPoint.' พ้อยท์!!!)';

//                 }
                    
//                 }
//                 break;
            
//             case '7': // ขายเครื่องคิดเลข + สินค้าอะไรก็ได้ 

//                 $sql = $this->prepare("select * from 
//                 (select count(*) as countProLine1 from PointLog where employee_id = ? and remark = 'Week 2 - Line 7') as countProLine1");

//                 $sql->execute([input::post('sellerNo'), input::post('sellerNo')]);
//                 $temp = $sql->fetchAll()[0];

//                 if ($temp['countProLine1'] == 0) {

//                     $sql = $this->prepare("select ifnull(sum(total_sales), 0) as totalSold from SO 
//                                             inner join SOPrinting on SOPrinting.so_no = SO.so_no
//                                             inner join Product on Product.product_no = SOPrinting.product_no
//                                             inner join ProductCategory on ProductCategory.category_no = Product.category_no and ProductCategory.product_line = 1 Product.product_line
//                                             where SO.cancelled = 0 and ((so_date = '2022-06-05' AND so_time >= '12:00:00') OR so_date between '2022-06-05' AND '2022-06-11')
//                                             and Product.product_line = 7 and Product.category_no = '01' and (Product.category_no = '01' OR Product.category_no = '02' OR Product.category_no = '03' OR Product.category_no = '04' OR Product.category_no = '05' OR Product.category_no = '06' OR Product.category_no = '07' OR Product.category_no = '08' OR Product.category_no = '09' OR Product.category_no = '10')) ");

//                     $sql->execute([input::post('sellerNo')]);
//                     $countSold = $sql->fetchAll()[0]['countSold'];

//                     if($countSold >= 1) {
                        
//                         $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
//                                                 values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//                         $sql->execute([input::post('sellerNo'), 30, 'Week 2 - Line 7', $sono]);
//                         echo ' (ผ่านโปรสาย 7 ได้รับ 30 พ้อยท์!!!) ';
//                         }
//                 }
//                 break;
            
//             case '8': // ขายขาย bundle เครื่องประดับ 1 set -> 10 points && ขาย bundle ชุดนิสิต 1 set ->  20 points

//                 $sql = $this->prepare("select * from 
//                 (select count(*) as countProLine1 from PointLog where employee_id = ? and remark = 'Week 2 - Line 8.1') as countProLine1,
//                 (select count(*) as countProLine2 from PointLog where employee_id = ? and remark = 'Week 2 - Line 8.2') as countProLine2");

//                 $sql->execute([input::post('sellerNo'), input::post('sellerNo')]);
//                 $temp = $sql->fetchAll()[0];

//                 if ($temp['countProLine1'] == 0) {

//                     $sql = $this->prepare("select ifnull(sum(SOPrinting.quantity),0) as countSold from SO 
//                                             inner join SOPrinting on SOPrinting.so_no = SO.so_no
//                                             inner join Product on Product.product_no = SOPrinting.product_no
//                                             inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
//                                             where employee_id = ? and SO.so_no = ?  and Product.product_line = '8'
//                                             and ( Product.product_no  = '8-S2-07-807-014' or Product.product_no =  '8-S2-07-807-015' )
//                                             and SO.cancelled = 0 and ((so_date = '2022-06-06' AND so_time >= '12:00:00') OR so_date between '2022-06-06' AND '2022-06-11')");

//                     $sql->execute([input::post('sellerNo')]);
//                     $totalSold = $sql->fetchAll()[0]['totalSold'];

                    
                    
//                     if($totalSold >= 1) {
//                         $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note, type, cancelled) values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//                         $sql->execute([input::post('sellerNo'), 10, 'Week 2 - Line 8.1', $sono]);
//                         echo ' (ผ่านโปรสาย 8.1 ได้รับ 10 พ้อยท์!!!)';

//                 }

//                 }

//                 if ($temp['countProLine2'] == 0) {

//                     $sql = $this->prepare("select ifnull(sum(SOPrinting.quantity),0) as countSold from SO 
//                                             inner join SOPrinting on SOPrinting.so_no = SO.so_no
//                                             inner join Product on Product.product_no = SOPrinting.product_no
//                                             inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
//                                             where employee_id = ? and SO.so_no = ?  and Product.product_line = '8'
//                                             and ( Product.product_no  = '8-S2-07-807-017' or Product.product_no =  '8-S2-07-807-018' )
//                                             and SO.cancelled = 0 and ((so_date = '2022-06-06' AND so_time >= '12:00:00') OR so_date between '2022-06-06' AND '2022-06-11')");

//                     $sql->execute([input::post('sellerNo')]);
//                     $totalSold = $sql->fetchAll()[0]['totalSold'];

                    
                    
//                     if($totalSold >= 1) {
//                         $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note, type, cancelled) values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//                         $sql->execute([input::post('sellerNo'), 20, 'Week 2 - Line 8.2', $sono]);
//                         echo ' (ผ่านโปรสาย 8.1 ได้รับ 20 พ้อยท์!!!)';

//                 }

//                 }
//                 break;

//             case '9': // ขายเสื้อผ้าหรือภูฟ้าชิ้นแรก && ขายสินค้าสาย 9 ครบ 800 บาท

//                 $sql = $this->prepare("select * from 
//                 (select count(*) as countProLine1 from PointLog where employee_id = ? and remark = 'Week 2 - Line 9.1') as countProLine1,
//                 (select count(*) as countProLine2 from PointLog where employee_id = ? and remark = 'Week 2 - Line 9.2') as countProLine2");

//                 $sql->execute([input::post('sellerNo'), input::post('sellerNo')]);
//                 $temp = $sql->fetchAll()[0];
            
//                 if ($temp['countProLine1'] == 0) {

//                 $passed1 = false;
                    
//                     foreach($soItemsArray as $value) {
//                         if ($value['sub_category'] == 'Phufa' || $value['sub_category'] == 'Clothes' ) {
//                             $passed1 = true;
//                         }
//                     }
                    
//                     $extraPoint1 = ($passed1) ? 10 : 0;
                    
//                     if($extraPoint1 > 0) {
//                         $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note, type, cancelled) values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//                         $sql->execute([input::post('sellerNo'), $extraPoint1, 'Week 2 - Line 9.1', $sono]);
//                         echo ' (ผ่านโปรสาย 9.1 ได้รับ '.$extraPoint1.' พ้อยท์!!!)';
//                     }


//             }

//             if ($temp['countProLine2'] == 0) {

//                 $sql = $this->prepare("select ifnull(sum(total_sales), 0) as totalSold from SO 
//                                             inner join SOPrinting on SOPrinting.so_no = SO.so_no
//                                             inner join Product on Product.product_no = SOPrinting.product_no
//                                             where employee_id = ? and Product.product_line = '9'
//                                             and SO.cancelled = 0 and ((so_date = '2022-06-06' AND so_time >= '12:00:00') OR so_date between '2022-06-06' AND '2022-06-11')");
//                     $sql->execute([input::post('sellerNo')]);
//                     $totalSold = $sql->fetchAll()[0]['totalSold'];
                    
//                     $extraPoint = ($totalSold >= 800) ? 20: 0;
                    
//                     if($extraPoint > 0) {
//                         $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note, type, cancelled) values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//                         $sql->execute([input::post('sellerNo'), $extraPoint, 'Week 2 - Line 9.2', $sono]);
//                         echo ' (ผ่านโปรสาย 9.2 ได้รับ '.$extraPoint.' พ้อยท์!!!)';

//                 }

//             }
//             break;

//             case '0': // ขายสินค้าประเภทของกิน ครบ 500 บาท -> 15 points && ขายสินค้าสาย 10 ครบ 800 บาท -> 15 points

//                 $sql = $this->prepare("select * from 
//                 (select count(*) as countProLine1 from PointLog where employee_id = ? and remark = 'Week 2 - Line 10.1') as countProLine1,
//                 (select count(*) as countProLine2 from PointLog where employee_id = ? and remark = 'Week 2 - Line 10.2') as countProLine2");

//                 $sql->execute([input::post('sellerNo'), input::post('sellerNo')]);
//                 $temp = $sql->fetchAll()[0];

//                 if($temp['countProLine1'] == 0) {
                        
//                     $sql = $this->prepare("select ifnull(sum(total_sales), 0) as totalSold from SO 
//                                             inner join SOPrinting on SOPrinting.so_no = SO.so_no
//                                             inner join Product on Product.product_no = SOPrinting.product_no
//                                             inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
//                                             where employee_id = ? and Product.product_line = '0' and Product.category_no in ('03','04')
//                                             and SO.cancelled = 0 and ((so_date = '2022-06-06' AND so_time >= '12:00:00') OR so_date between '2022-06-06' AND '2022-06-11')");
//                     $sql->execute([input::post('sellerNo')]);
//                     $totalSold = $sql->fetchAll()[0]['totalSold'];
                    
//                     $extraPoint = ($totalSold >= 500) ? 15 : 0;
                    
//                     if($extraPoint > 0) {
//                         $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note, type, cancelled) values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//                         $sql->execute([input::post('sellerNo'), $extraPoint, 'Week 2 - Line 10.1', $sono]);
//                         echo ' (ผ่านโปรสาย 10.1 ได้รับ '.$extraPoint.' พ้อยท์!!!)';
//                     }

//                 if($temp['countProLine2'] == 0 ) {

//                     $sql = $this->prepare("select ifnull(sum(total_sales), 0) as totalSold from SO 
//                                             inner join SOPrinting on SOPrinting.so_no = SO.so_no
//                                             inner join Product on Product.product_no = SOPrinting.product_no
//                                             where employee_id = ? and Product.product_line = '0'
//                                             and SO.cancelled = 0 and ((so_date = '2022-06-06' AND so_time >= '12:00:00') OR so_date between '2022-06-06' AND '2022-06-11')");
//                     $sql->execute([input::post('sellerNo')]);
//                     $totalSold = $sql->fetchAll()[0]['totalSold'];
                    
//                     $extraPoint = ($totalSold >= 800) ? 15: 0;
                    
//                     if($extraPoint > 0) {
//                         $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note, type, cancelled) values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//                         $sql->execute([input::post('sellerNo'), $extraPoint, 'Week 2 - Line 10.2', $sono]);
//                         echo ' (ผ่านโปรสาย 10.2 ได้รับ '.$extraPoint.' พ้อยท์!!!)';

//                 }

//                 }


//             }
//             break;

//         if($achieved2 == 0) {
//             $extraPoint2 = ($totalSold2 >= 3500) ? 70 : 0;  
//             if($extraPoint2 > 0) {
//                 $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
//                                             values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//                 $sql->execute([input::post('sellerNo'), $extraPoint2, 'Week 1-2 - Easy Sales', $sono]);
//                 echo ' (ได้รับพ้อยท์พิเศษเพิ่มอีก '.$extraPoint2.' พ้อยท์!!!)';
//                 }
            



//         }
//     }

// }
// }


// 	private function promotionWeek8($sono) {
// 		$soItemsArray = json_decode(input::post('soItems'), true);
// 		$soItemsArray = json_decode($soItemsArray,true);
// 		$extraPoint = 0;
		
// 		if (is_numeric(input::post('sellerNo'))) {
			
// 			switch (json_decode(session::get('employee_detail'),true)['product_line']) {
					
// 				case '2':
					
// 					$sql = $this->prepare("select * from 
//                                         (select count(*) as countProLine1 from PointLog where employee_id = ? and remark = 'Week 8 - Line 2' and cancelled = 0) as countProLine1");

// 				  	$sql->execute( [input::post('sellerNo'),input::post('sellerNo')]);
// 				  	$temp = $sql->fetchAll(PDO::FETCH_ASSOC)[0];
					
					
// 					if($temp['countProLine1'] == 0) {
// 						$sql = $this->prepare("select ifnull(sum(quantity), 0) as countSold from SO 
//                                             inner join SOPrinting on SOPrinting.so_no = SO.so_no
//                                             inner join Product on Product.product_no = SOPrinting.product_no
//                                             inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
//                                             where employee_id = ? and Product.product_line = '2' 
//                                             and Product.category_no = '01'
//                                             and SO.cancelled = 0 and ((so_date = '2021-07-19' AND so_time >= '13:00:00') OR so_date between '2021-07-20' AND '2021-07-24')");
// 						$sql->execute([input::post('sellerNo')]);
// 						$countSold = $sql->fetchAll()[0]['countSold'];
                    
// 						if($countSold > 0) {
// 							$sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
// 													values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
// 							$sql->execute([input::post('sellerNo'), 300, 'Week 8 - Line 2', $sono]);
// 							//print_r($sql->errorInfo());
// 							echo ' (ผ่านโปรสาย 2 ได้รับ 300 พ้อยท์!!!) ';
// 							}
// 					}
					
// 					break;
					
// 			}
			
// 		}
		
// private function promotionTournamentWeek4 ($sono) {

//   $soItemsArray = json_decode(input::post('soItems'), true); 
//   $soItemsArray = json_decode($soItemsArray, true);
//   $extraPoint = 0;

//   if(is_numeric(input::post('sellerNo'))) {

//       switch (json_decode(session::get('employee_detail'), true)['product_line']) {

//           // ไม่เคยขายสายเรา ทีวี 1 เครื่อง 50 pts
//           case '1':
//               //เคยขายไหม
//               $sql = $this->prepare("SELECT COUNT(*) AS count FROM SO WHERE employee_id = ? AND product_line = 1 AND cancelled = 0 AND so_no != ?");
//               $sql->execute([input::post('sellerNo')]);
//               $count = $sql->fetchAll()[0]['count'];

//               $sql = $this->prepare("select * from 
//                                   (select count(*) as countProLine1 from PointLog where employee_id = ? and remark = 'Week 4 - Line 1.1' and cancelled = 0) as countProLine1");

//               $sql->execute([input::post('sellerNo')]);
//               $temp = $sql->fetchAll(PDO::FETCH_ASSOC)[0];

//               // ไม่เคยขาย

//               if($count == 0 && $temp['countProLine1'] == 0) {

//                   $sql = $this->prepare("select ifnull(sum(quantity), 0) as countSold from SO 
//                                       inner join SOPrinting on SOPrinting.so_no = SO.so_no
//                                       inner join Product on Product.product_no = SOPrinting.product_no
//                                       inner join ProductCategory on ProductCategory.category_no = Product.category_no and ProductCategory.product_line = Product.product_line
//                                       where employee_id = ? and Product.product_line = '1' and Product.category_no = '08' and SO.cancelled = 0 
//                                       and ((so_date = '2022-06-20' AND so_time >= '12:00:00') OR so_date between '2022-06-21' AND '2022-06-25')");

//                   $sql->execute([input::post('sellerNo')]);
//                   $countSold = $sql->fetchAll()[0]['countSold'];

//                   if($countSold > 0) {

//                       $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
//                     values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//         $sql->execute([input::post('sellerNo'), 50, 'Week 4 - Line 1.1', $sono]);
//         //print_r($sql->errorInfo());
//         echo ' (ผ่านโปรสาย 1.1 ได้รับ 50 พ้อยท์!!!) ';

//                   }

//               }

//               break;

//               // เคยขายสายเรา ขายแอร์ 1 เครื่อง 70 pts
//           case '2': 
//               // เคยขายไหม
//               $sql = $this->prepare("SELECT COUNT(*) AS count FROM SO WHERE employee_id = ? AND product_line = 2 AND cancelled = 0 AND so_no != ?");
//               $sql->execute([input::post('sellerNo')]);
//               $count = $sql->fetchAll()[0]['count'];

//               $sql = $this->prepare("select * from 
//                                   (select count(*) as countProLine1 from PointLog where employee_id = ? and remark = 'Week 4 - Line 2.1' and cancelled = 0) as countProLine1,
//                                   (select count(*) as countProLine2 from PointLog where employee_id = ? and remark = 'Week 4 - Line 2.2' and cancelled = 0) as countProLine2");

//               $sql->execute([input::post('sellerNo')]);
//               $temp = $sql->fetchAll(PDO::FETCH_ASSOC)[0];

//               // ไม่เคยขาย

//               if($count == 0 && $temp['countProLine1'] == 0) {
//       $sql = $this->prepare("select ifnull(sum(quantity), 0) as countSold from SO 
//                                       inner join SOPrinting on SOPrinting.so_no = SO.so_no
//                                       inner join Product on Product.product_no = SOPrinting.product_no
//                                       inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
//                                       where employee_id = ? and Product.product_line = '2' and Product.category_no = '01'
//                                       and SO.cancelled = 0 and ((so_date = '2022-06-20' AND so_time >= '12:00:00') OR so_date between '2022-06-21' AND '2022-06-25')");
//       $sql->execute([input::post('sellerNo')]);
//       $countSold = $sql->fetchAll()[0]['countSold'];

//                   if($countSold > 0) {
//         $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
//                     values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//         $sql->execute([input::post('sellerNo'), 70, 'Week 4 - Line 2.1', $sono]);
//         //print_r($sql->errorInfo());
//         echo ' (ผ่านโปรสาย 2.1 ได้รับ 70 พ้อยท์!!!) ';
//                       break;
//         }
              
    
//       $sql = $this->prepare("select ifnull(sum(quantity), 0) as countSold from SO 
//                                       inner join SOPrinting on SOPrinting.so_no = SO.so_no
//                                       inner join Product on Product.product_no = SOPrinting.product_no
//                                       inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
//                                       where employee_id = ? and Product.product_line = '2' and Product.category_no = '03'
//                                       and SO.cancelled = 0 and ((so_date = '2022-06-20' AND so_time >= '12:00:00') OR so_date between '2022-06-21' AND '2022-06-25')");
//       $sql->execute([input::post('sellerNo')]);
//       $countSold = $sql->fetchAll()[0]['countSold'];

//                   if($countSold > 0) {
//         $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
//                     values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//         $sql->execute([input::post('sellerNo'), 40, 'Week 4 - Line 2.1', $sono]);
//         //print_r($sql->errorInfo());
//         echo ' (ผ่านโปรสาย 2.1 ได้รับ 40 พ้อยท์!!!) ';
//         }
//               } if($count == 0 && $temp['countProLine1'] == 0) {
//       $sql = $this->prepare("select ifnull(sum(quantity), 0) as countSold from SO 
//                                       inner join SOPrinting on SOPrinting.so_no = SO.so_no
//                                       inner join Product on Product.product_no = SOPrinting.product_no
//                                       inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
//                                       where employee_id = ? and Product.product_line = '2' and Product.category_no = '02'
//                                       and SO.cancelled = 0 and ((so_date = '2022-06-20' AND so_time >= '12:00:00') OR so_date between '2022-06-21' AND '2022-06-25')");
//       $sql->execute([input::post('sellerNo')]);
//       $countSold = $sql->fetchAll()[0]['countSold'];

//                   if($countSold > 0) {
//         $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
//                     values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//         $sql->execute([input::post('sellerNo'), 30, 'Week 4 - Line 2.1', $sono]);
//         //print_r($sql->errorInfo());
//         echo ' (ผ่านโปรสาย 2.1 ได้รับ 30 พ้อยท์!!!) ';
//                   }
//               }


//               // เคยขาย

//               if($count > 0 && $temp['countProLine2'] == 0){
      
//       $sql = $this->prepare("select ifnull(sum(quantity), 0) as countSold from SO 
//                                       inner join SOPrinting on SOPrinting.so_no = SO.so_no
//                                       inner join Product on Product.product_no = SOPrinting.product_no
//                                       inner join ProductCategory on ProductCategory.category_no = Product.category_no and ProductCategory.product_line = Product.product_line
//                                       where employee_id = ? and Product.product_line = '2' and Product.category_no in ('01') and SO.cancelled = 0 
//                 and ((so_date = '2022-06-20' AND so_time >= '12:00:00') OR so_date between '2022-06-21' AND '2022-06-25')");

//       $sql->execute([input::post('sellerNo')]);
//       $countSold = $sql->fetchAll()[0]['countSold'];
      
//       if($countSold > 0) {
//         $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
//                   values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//         $sql->execute([input::post('sellerNo'), 70, 'Week 4 - Line 2.2', $sono]);
//         //print_r($sql->errorInfo());
//         echo ' (ผ่านโปรสาย 2.2 ได้รับ 70 พ้อยท์!!!) ';
//                 }
//     }

//     break;

//           case '3':
//               // เคยขายไหม
//               $sql = $this->prepare("SELECT COUNT(*) AS count FROM SO WHERE employee_id = ? AND product_line = 3 AND cancelled = 0 AND so_no != ?");
//               $sql->execute([input::post('sellerNo')]);
//               $count = $sql->fetchAll()[0]['count'];

//               $sql = $this->prepare("select * from 
//                                   (select count(*) as countProLine1 from PointLog where employee_id = ? and remark = 'Week 4 - Line 3.1' and cancelled = 0) as countProLine1,
//                                   (select count(*) as countProLine2 from PointLog where employee_id = ? and remark = 'Week 4 - Line 3.2' and cancelled = 0) as countProLine2");

//               $sql->execute([input::post('sellerNo')]);
//               $temp = $sql->fetchAll(PDO::FETCH_ASSOC)[0];

//               // ไม่เคยขาย

//               if($count == 0 && $temp['countProLine1'] == 0) { 
//                   // ต้นไม้ 20
//       $sql = $this->prepare("select ifnull(sum(quantity), 0) as countSold from SO 
//                                       inner join SOPrinting on SOPrinting.so_no = SO.so_no
//                                       inner join Product on Product.product_no = SOPrinting.product_no
//                                       inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
//                                       where employee_id = ? and Product.product_line = '3' and Product.category_no = '07'
//                                       and SO.cancelled = 0 and ((so_date = '2022-06-20' AND so_time >= '12:00:00') OR so_date between '2022-06-21' AND '2022-06-25')");
//       $sql->execute([input::post('sellerNo')]);
//       $countSold = $sql->fetchAll()[0]['countSold'];
              
//       if($countSold > 0) {
//         $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
//                     values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//         $sql->execute([input::post('sellerNo'), 20, 'Week 4 - Line 3.1', $sono]);
//         //print_r($sql->errorInfo());
//         echo ' (ผ่านโปรสาย 3.1 ได้รับ 20 พ้อยท์!!!) ';
//                       break;
//         }

//                   // เครื่องครัว
//                   $sql = $this->prepare("select ifnull(sum(quantity), 0) as countSold from SO 
//                                       inner join SOPrinting on SOPrinting.so_no = SO.so_no
//                                       inner join Product on Product.product_no = SOPrinting.product_no
//                                       inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
//                                       where employee_id = ? and Product.product_line = '3' and Product.category_no = '18'
//                                       and SO.cancelled = 0 and ((so_date = '2022-06-20' AND so_time >= '12:00:00') OR so_date between '2022-06-21' AND '2022-06-25')");
//       $sql->execute([input::post('sellerNo')]);
//       $countSold = $sql->fetchAll()[0]['countSold'];
              
//       if($countSold > 0) {
//         $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
//                     values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//         $sql->execute([input::post('sellerNo'), 20, 'Week 4 - Line 3.1', $sono]);
//         //print_r($sql->errorInfo());
//         echo ' (ผ่านโปรสาย 3.1 ได้รับ 20 พ้อยท์!!!) ';
//                       break;
//         }

//                   // เซตกระทะ
//                   $sql = $this->prepare("select ifnull(sum(quantity), 0) as countSold from SO 
//                                       inner join SOPrinting on SOPrinting.so_no = SO.so_no
//                                       inner join Product on Product.product_no = SOPrinting.product_no
//                                       inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
//                                       where employee_id = ? and Product.product_line = '3' and Product.category_no = '19'
//                                       and SO.cancelled = 0 and ((so_date = '2022-06-20' AND so_time >= '12:00:00') OR so_date between '2022-06-21' AND '2022-06-25')");
//       $sql->execute([input::post('sellerNo')]);
//       $countSold = $sql->fetchAll()[0]['countSold'];
              
//       if($countSold > 0) {
//         $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
//                     values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//         $sql->execute([input::post('sellerNo'), 20, 'Week 4 - Line 3.1', $sono]);
//         //print_r($sql->errorInfo());
//         echo ' (ผ่านโปรสาย 3.1 ได้รับ 20 พ้อยท์!!!) ';
//                       break;
//         }

//     }

//               // เคยขาย
//               if($count > 0 && $temp['countProLine2'] == 0) {
//                   // หม้อทอดไฟฟ้า 
//                   $sql = $this->prepare("select ifnull(sum(quantity), 0) as countSold from SO 
//                                           inner join SOPrinting on SOPrinting.so_no = SO.so_no
//                                           inner join Product on Product.product_no = SOPrinting.product_no
//                                           inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
//                                           where employee_id = ? and Product.product_line = '3' and Product.category_no = '03'
//                                           and SO.cancelled = 0 and ((so_date = '2022-06-20' AND so_time >= '12:00:00') OR so_date between '2022-06-21' AND '2022-06-25')");
//                   $sql->execute([input::post('sellerNo')]);
//                   $countSold = $sql->fetchAll()[0]['countSold'];

//                   if($countSold > 0) {
//                       $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
//                           values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//                       $sql->execute([input::post('sellerNo'), 30, 'Week 4 - Line 3.2', $sono]);
//                       //print_r($sql->errorInfo());
//                       echo ' (ผ่านโปรสาย 3.2 ได้รับ 30 พ้อยท์!!!) ';
//                       break;
//                       }

//                   // เก้าอี้ 
//                   $sql = $this->prepare("select ifnull(sum(quantity), 0) as countSold from SO 
//                                       inner join SOPrinting on SOPrinting.so_no = SO.so_no
//                                       inner join Product on Product.product_no = SOPrinting.product_no
//                                       inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
//                                       where employee_id = ? and Product.product_line = '3' and Product.category_no = '20'
//                                       and SO.cancelled = 0 and ((so_date = '2022-06-20' AND so_time >= '12:00:00') OR so_date between '2022-06-21' AND '2022-06-25')");
//                   $sql->execute([input::post('sellerNo')]);
//                   $countSold = $sql->fetchAll()[0]['countSold'];

//                   if($countSold > 0) {
//                       $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
//                               values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//                       $sql->execute([input::post('sellerNo'), 30, 'Week 4 - Line 3.2', $sono]);
//                       //print_r($sql->errorInfo());
//                       echo ' (ผ่านโปรสาย 3.2 ได้รับ 30 พ้อยท์!!!) ';
//                       break;
//                       }

//                   // โต๊ะ
//                   $sql = $this->prepare("select ifnull(sum(quantity), 0) as countSold from SO 
//                                       inner join SOPrinting on SOPrinting.so_no = SO.so_no
//                                       inner join Product on Product.product_no = SOPrinting.product_no
//                                       inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
//                                       where employee_id = ? and Product.product_line = '3' and Product.category_no = '21'
//                                       and SO.cancelled = 0 and ((so_date = '2022-06-20' AND so_time >= '12:00:00') OR so_date between '2022-06-21' AND '2022-06-25')");
//                   $sql->execute([input::post('sellerNo')]);
//                   $countSold = $sql->fetchAll()[0]['countSold'];

//                   if($countSold > 0) {
//                       $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
//                                               values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//                       $sql->execute([input::post('sellerNo'), 30, 'Week 4 - Line 3.2', $sono]);
//                       //print_r($sql->errorInfo());
//                       echo ' (ผ่านโปรสาย 3.2 ได้รับ 30 พ้อยท์!!!) ';
//                       break;
//                       }

//               }

//               break;

//               // ไม่เคยขายสายเรา Smartwatch 10 pts 
//               // เคยขายสายเรา iPad รุ่นไหนก็ได้ 2 เครื่อง 45 pts
//   case '4': 
//               // เคยขายไหม
//               $sql = $this->prepare("SELECT COUNT(*) AS count FROM SO WHERE employee_id = ? AND product_line = 4 AND cancelled = 0 AND so_no != ?");
//               $sql->execute([input::post('sellerNo')]);
//               $count = $sql->fetchAll()[0]['count'];

//               $sql = $this->prepare("select * from 
//                                   (select count(*) as countProLine1 from PointLog where employee_id = ? and remark = 'Week 4 - Line 4.1' and cancelled = 0) as countProLine1,
//                                   (select count(*) as countProLine2 from PointLog where employee_id = ? and remark = 'Week 4 - Line 4.2' and cancelled = 0) as countProLine2");

//               $sql->execute([input::post('sellerNo')]);
//               $temp = $sql->fetchAll(PDO::FETCH_ASSOC)[0];

//               // ไม่เคยขาย

//               if($count == 0 && $temp['countProLine1'] == 0) {
//               $sql = $this->prepare("select ifnull(sum(quantity), 0) as countSold from SO 
//                                       inner join SOPrinting on SOPrinting.so_no = SO.so_no
//                                       inner join Product on Product.product_no = SOPrinting.product_no
//                                       inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
//                                       where employee_id = ? and Product.product_line = '4' and (Product.sub_category = 'Smartwatch' or Product.product_no in ('4-I1-02-401-571','4-I1-02-401-573'))
//                                       and SO.cancelled = 0 and ((so_date = '2022-06-20' AND so_time >= '12:00:00') OR so_date between '2022-06-21' AND '2022-06-25')");
//                   $sql->execute([input::post('sellerNo')]);
//                   $countSold = $sql->fetchAll()[0]['countSold'];

//                   if($countSold > 0) {
//                       $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
//                                               values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//                       $sql->execute([input::post('sellerNo'), 10, 'Week 4 - Line 4.1', $sono]);
//                       //print_r($sql->errorInfo());
//                       echo ' (ผ่านโปรสาย 4.1 ได้รับ 10 พ้อยท์!!!) ';
//         }
//                   }
//               // เคยขาย

//               if($count > 0 && $temp['countProLine2'] == 0) {

//                   $sql = $this->prepare("select ifnull(sum(quantity), 0) as countSold from SO 
//                                       inner join SOPrinting on SOPrinting.so_no = SO.so_no
//                                       inner join Product on Product.product_no = SOPrinting.product_no
//                                       inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
//                                       where employee_id = ? and Product.product_line = '4' and Product.category_no = '01'
//                                       and SO.cancelled = 0 and ((so_date = '2022-06-20' AND so_time >= '12:00:00') OR so_date between '2022-06-21' AND '2022-06-25')");
//                   $sql->execute([input::post('sellerNo')]);
//                   $countSold = $sql->fetchAll()[0]['countSold'];

//                   if($countSold >= 2) {
//                       $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
//                                               values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//                       $sql->execute([input::post('sellerNo'), 45, 'Week 4 - Line 4.2', $sono]);
//                       //print_r($sql->errorInfo());
//                       echo ' (ผ่านโปรสาย 4.2 ได้รับ 45 พ้อยท์!!!) ';
//         }
//               }
//               break;

//           case '5': 

//                   // เคยขายไหม
//     $sql = $this->prepare("SELECT count(*) as count FROM SO WHERE employee_id = ? AND product_line = 5 AND cancelled = 0 and so_no != ?");
//     $sql->execute([input::post('sellerNo'), $sono]);
//     $count = $sql->fetchAll()[0]['count'];
    
//     $sql = $this->prepare("select * from 
//                                   (select count(*) as countProLine1 from PointLog where employee_id = ? and remark = 'Week 4 - Line 5.1' and cancelled = 0) as countProLine1,
//               (select count(*) as countProLine2 from PointLog where employee_id = ? and remark = 'Week 4 - Line 5.2' and cancelled = 0) as countProLine2");

//       $sql->execute( [input::post('sellerNo')]);
//       $temp = $sql->fetchAll(PDO::FETCH_ASSOC)[0];
                  
//               // ไม่เคยขาย
//     if($count == 0 && $temp['countProLine1'] == 0) {
//       $sql = $this->prepare("select ifnull(sum(quantity), 0) as countSold from SO 
//                                       inner join SOPrinting on SOPrinting.so_no = SO.so_no
//                                       inner join Product on Product.product_no = SOPrinting.product_no
//                                       inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
//                                       where employee_id = ? and Product.product_line = '5' and Product.category_no in ('01','02')
//                                       and SO.cancelled = 0 and ((so_date = '2022-06-20' AND so_time >= '12:00:00') OR so_date between '2022-06-21' AND '2022-06-25')");
//       $sql->execute([input::post('sellerNo')]);
//       $countSold = $sql->fetchAll()[0]['countSold'];
              
//       if($countSold > 0) {
//         $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
//                     values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//         $sql->execute([input::post('sellerNo'), 50, 'Week 4 - Line 5.1', $sono]);
//         //print_r($sql->errorInfo());
//         echo ' (ผ่านโปรสาย 5.1 ได้รับ 50 พ้อยท์!!!) ';
//         }
//     }
//               // เคยขาย
//               if($count > 0 && $temp['countProLine2'] == 0) {
//       $sql = $this->prepare("select ifnull(sum(quantity), 0) as countSold from SO 
//                                       inner join SOPrinting on SOPrinting.so_no = SO.so_no
//                                       inner join Product on Product.product_no = SOPrinting.product_no
//                                       inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
//                                       where employee_id = ? and Product.product_line = '5' and Product.category_no = '02'
//                                       and SO.cancelled = 0 and ((so_date = '2022-06-20' AND so_time >= '12:00:00') OR so_date between '2022-06-21' AND '2022-06-25')");
//       $sql->execute([input::post('sellerNo')]);
//       $countSold = $sql->fetchAll()[0]['countSold'];
              
//       if($countSold > 0) {
//         $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
//                     values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//         $sql->execute([input::post('sellerNo'), 60, 'Week 4 - Line 5.2', $sono]);
//         //print_r($sql->errorInfo());
//         echo ' (ผ่านโปรสาย 5.2 ได้รับ 60 พ้อยท์!!!) ';
//         }
//     }
//               break;

//           case '6':
//                   // เคยขายไหม
//                   $sql = $this->prepare("SELECT count(*) as count FROM SO WHERE employee_id = ? AND product_line = 6 AND cancelled = 0 and so_no != ?");
//                   $sql->execute([input::post('sellerNo'), $sono]);
//                   $count = $sql->fetchAll()[0]['count'];
                  
//                   $sql = $this->prepare("select * from 
//                                       (select count(*) as countProLine1 from PointLog where employee_id = ? and remark = 'Week 4 - Line 6.1' and cancelled = 0) as countProLine1,
//                                       (select count(*) as countProLine2 from PointLog where employee_id = ? and remark = 'Week 4 - Line 6.2' and cancelled = 0) as countProLine2");

//                   $sql->execute( [input::post('sellerNo')]);
//                   $temp = $sql->fetchAll(PDO::FETCH_ASSOC)[0];

//                   // ไม่เคยขาย

//                   if($count == 0 && $temp['countProLine1'] == 0) {
//                       $sql = $this->prepare("select ifnull(sum(quantity), 0) as countSold from SO 
//                                           inner join SOPrinting on SOPrinting.so_no = SO.so_no
//                                           inner join Product on Product.product_no = SOPrinting.product_no
//                                           inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
//                                           where employee_id = ? and Product.product_line = '6' and Product.product_no in ('6-I2-03-605-024','6-I2-03-605-025','6-I2-03-605-026','6-I2-03-605-034')
//                                           and SO.cancelled = 0 and ((so_date = '2022-06-20' AND so_time >= '12:00:00') OR so_date between '2022-06-21' AND '2022-06-25')");
//                       $sql->execute([input::post('sellerNo')]);
//                       $countSold = $sql->fetchAll()[0]['countSold'];
                  
//                       if($countSold > 0) {
//                           $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
//                                                   values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//                           $sql->execute([input::post('sellerNo'), 10, 'Week 4 - Line 6.1', $sono]);
//                           //print_r($sql->errorInfo());
//                           echo ' (ผ่านโปรสาย 6.1 ได้รับ 10 พ้อยท์!!!) ';
//                           }
//                   }

//                   // เคยขาย
//                   if($count > 0 && $temp['countProLine2'] == 0) {
//                       $sql = $this->prepare("select ifnull(sum(quantity), 0) as countSold from SO 
//                                           inner join SOPrinting on SOPrinting.so_no = SO.so_no
//                                           inner join Product on Product.product_no = SOPrinting.product_no
//                                           inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
//                                           where employee_id = ? and Product.product_line = '6' and Product.category_no = '02'
//                                           and SO.cancelled = 0 and ((so_date = '2022-06-20' AND so_time >= '12:00:00') OR so_date between '2022-06-21' AND '2022-06-25')");
//                       $sql->execute([input::post('sellerNo')]);
//                       $countSold = $sql->fetchAll()[0]['countSold'];
                  
//                       if($countSold > 0) {
//                           $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
//                                                   values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//                           $sql->execute([input::post('sellerNo'), 30, 'Week 4 - Line 6.2', $sono]);
//                           //print_r($sql->errorInfo());
//                           echo ' (ผ่านโปรสาย 6.2 ได้รับ 30 พ้อยท์!!!) ';
//                           }
//                   }
//                   break;

//           case '7':
//                       // เคยขายไหม
//                       $sql = $this->prepare("SELECT count(*) as count FROM SO WHERE employee_id = ? AND product_line = 7 AND cancelled = 0 and so_no != ?");
//                       $sql->execute([input::post('sellerNo'), $sono]);
//                       $count = $sql->fetchAll()[0]['count'];
                      
//                       $sql = $this->prepare("select * from 
//                                           (select count(*) as countProLine1 from PointLog where employee_id = ? and remark = 'Week 4 - Line 7.1' and cancelled = 0) as countProLine1");
  
//                       $sql->execute( [input::post('sellerNo')]);
//                       $temp = $sql->fetchAll(PDO::FETCH_ASSOC)[0];

//                       // ไม่เคยขาย
//                       if($count == 0 && $temp['countProLine1'] == 0) {
//                           $sql = $this->prepare("select ifnull(sum(total_sales), 0) as totalSold from SO 
//                                               inner join SOPrinting on SOPrinting.so_no = SO.so_no
//                                               inner join Product on Product.product_no = SOPrinting.product_no
//                                               inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
//                                               where employee_id = ? and Product.product_line = '7' and Product.category_no != '04'
//                                               and SO.cancelled = 0 and ((so_date = '2022-06-20' AND so_time >= '12:00:00') OR so_date between '2022-06-21' AND '2022-06-25')");
//                           $sql->execute([input::post('sellerNo')]);
//                           $totalSold = $sql->fetchAll()[0]['totalSold'];
                      
//                           if($totalSold >= 600) {
//                               $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
//                                                       values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//                               $sql->execute([input::post('sellerNo'), 20, 'Week 4 - Line 7.1', $sono]);
//                               //print_r($sql->errorInfo());
//                               echo ' (ผ่านโปรสาย 7.1 ได้รับ 20 พ้อยท์!!!) ';
//                               }
//                       }
//                       break;
          
//           case '8': 
//               // เคยขายไหม
//               $sql = $this->prepare("SELECT count(*) as count FROM SO WHERE employee_id = ? AND product_line = 8 AND cancelled = 0 and so_no != ?");
//               $sql->execute([input::post('sellerNo'), $sono]);
//               $count = $sql->fetchAll()[0]['count'];
              
//               $sql = $this->prepare("select * from 
//                                   (select count(*) as countProLine1 from PointLog where employee_id = ? and remark = 'Week 4 - Line 8.1' and cancelled = 0) as countProLine1,
//                                   (select count(*) as countProLine2 from PointLog where employee_id = ? and remark = 'Week 4 - Line 8.2' and cancelled = 0) as countProLine2");

//               $sql->execute( [input::post('sellerNo')]);
//               $temp = $sql->fetchAll(PDO::FETCH_ASSOC)[0];

//               // ไม่เคยขาย

//               if($count == 0 && $temp['countProLine1'] == 0) {
//                   $sql = $this->prepare("select ifnull(sum(total_sales), 0) as totalSold from SO 
//                                       inner join SOPrinting on SOPrinting.so_no = SO.so_no
//                                       inner join Product on Product.product_no = SOPrinting.product_no
//                                       inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
//                                       where employee_id = ? and Product.product_line = '8' and (Product.category_no != '06' or Product.product_name like '%ส่วนลดค่าจัดส่ง%')
//                                       and SO.cancelled = 0 and ((so_date = '2022-06-20' AND so_time >= '12:00:00') OR so_date between '2022-06-21' AND '2022-06-25')");
//                   $sql->execute([input::post('sellerNo')]);
//                   $totalSold = $sql->fetchAll()[0]['totalSold'];
              
//                   if($totalSold >= 1000) {
//                       $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
//                                               values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//                       $sql->execute([input::post('sellerNo'), 15, 'Week 4 - Line 8.1', $sono]);
//                       //print_r($sql->errorInfo());
//                       echo ' (ผ่านโปรสาย 8.1 ได้รับ 15 พ้อยท์!!!) ';
//                       }
//               }

//               // เคยขาย
//               if($count > 0 && $temp['countProLine2'] == 0) {
//                   $sql = $this->prepare("select ifnull(sum(total_sales), 0) as totalSold from SO 
//                                       inner join SOPrinting on SOPrinting.so_no = SO.so_no
//                                       inner join Product on Product.product_no = SOPrinting.product_no
//                                       inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
//                                       where employee_id = ? and Product.product_line = '8' and Product.category_no in ('01','03')
//                                       and SO.cancelled = 0 and ((so_date = '2022-06-20' AND so_time >= '12:00:00') OR so_date between '2022-06-21' AND '2022-06-25')");
//                   $sql->execute([input::post('sellerNo')]);
//                   $totalSold = $sql->fetchAll()[0]['totalSold'];
              
//                   if($totalSold >= 1500) {
//                       $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
//                                               values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//                       $sql->execute([input::post('sellerNo'), 20, 'Week 4 - Line 8.2', $sono]);
//                       //print_r($sql->errorInfo());
//                       echo ' (ผ่านโปรสาย 8.2 ได้รับ 20 พ้อยท์!!!) ';
//                       }
//               }
//               break;
          
//           case '9':
//               // เคยขายไหม
//               $sql = $this->prepare("SELECT count(*) as count FROM SO WHERE employee_id = ? AND product_line = 9 AND cancelled = 0 and so_no != ?");
//               $sql->execute([input::post('sellerNo'), $sono]);
//               $count = $sql->fetchAll()[0]['count'];
              
//               $sql = $this->prepare("select * from 
//                                   (select count(*) as countProLine1 from PointLog where employee_id = ? and remark = 'Week 4 - Line 9.1' and cancelled = 0) as countProLine1,
//                                   (select count(*) as countProLine2 from PointLog where employee_id = ? and remark = 'Week 4 - Line 9.2' and cancelled = 0) as countProLine2");

//               $sql->execute( [input::post('sellerNo')]);
//               $temp = $sql->fetchAll(PDO::FETCH_ASSOC)[0];

//               // ไม่เคยขาย

//               if($count == 0 && $temp['countProLine1'] == 0) {
//                   $sql = $this->prepare("select ifnull(sum(total_sales), 0) as totalSold from SO 
//                                       inner join SOPrinting on SOPrinting.so_no = SO.so_no
//                                       inner join Product on Product.product_no = SOPrinting.product_no
//                                       inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
//                                       where employee_id = ? and Product.product_line = '9' and Product.category_no != '04'
//                                       and SO.cancelled = 0 and ((so_date = '2022-06-20' AND so_time >= '12:00:00') OR so_date between '2022-06-21' AND '2022-06-25')");
//                   $sql->execute([input::post('sellerNo')]);
//                   $totalSold = $sql->fetchAll()[0]['totalSold'];
              
//                   if($totalSold >= 500) {
//                       $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
//                                               values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//                       $sql->execute([input::post('sellerNo'), 20, 'Week 4 - Line 9.1', $sono]);
//                       //print_r($sql->errorInfo());
//                       echo ' (ผ่านโปรสาย 9.1 ได้รับ 20 พ้อยท์!!!) ';
//                       }
//               }

//               // เคยขาย
//               if($count > 0 && $temp['countProLine2'] == 0) {
//                   $sql = $this->prepare("select ifnull(sum(quantity), 0) as countSold from SO 
//                                       inner join SOPrinting on SOPrinting.so_no = SO.so_no
//                                       inner join Product on Product.product_no = SOPrinting.product_no
//                                       inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
//                                       where employee_id = ? and Product.product_line = '9' and Product.sub_category = 'Bag' and Product.sub_category = 'Clothes'
//                                       and SO.cancelled = 0 and ((so_date = '2022-06-20' AND so_time >= '12:00:00') OR so_date between '2022-06-21' AND '2022-06-25')");
//                   $sql->execute([input::post('sellerNo')]);
//                   $countSold = $sql->fetchAll()[0]['countSold'];
              
//                   if($countSold > 0) {
//                       $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
//                                               values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//                       $sql->execute([input::post('sellerNo'), 25, 'Week 4 - Line 9.2', $sono]);
//                       //print_r($sql->errorInfo());
//                       echo ' (ผ่านโปรสาย 9.2 ได้รับ 25 พ้อยท์!!!) ';
//                       }
//               }
//               break;

//           case '0':
//               // เคยขายไหม
//               $sql = $this->prepare("SELECT count(*) as count FROM SO WHERE employee_id = ? AND product_line = 0 AND cancelled = 0 and so_no != ?");
//               $sql->execute([input::post('sellerNo'), $sono]);
//               $count = $sql->fetchAll()[0]['count'];
              
//               $sql = $this->prepare("select * from 
//                                   (select count(*) as countProLine1 from PointLog where employee_id = ? and remark = 'Week 4 - Line 10.1' and cancelled = 0) as countProLine1,
//                                   (select count(*) as countProLine2 from PointLog where employee_id = ? and remark = 'Week 4 - Line 10.2' and cancelled = 0) as countProLine2");

//               $sql->execute( [input::post('sellerNo')]);
//               $temp = $sql->fetchAll(PDO::FETCH_ASSOC)[0];

//               // ไม่เคยขาย

//               if($count == 0 && $temp['countProLine1'] == 0) {
//                   $sql = $this->prepare("select ifnull(sum(total_sales), 0) as totalSold from SO 
//                                       inner join SOPrinting on SOPrinting.so_no = SO.so_no
//                                       inner join Product on Product.product_no = SOPrinting.product_no
//                                       inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
//                                       where employee_id = ? and Product.product_line = '0' and Product.category_no in ('03','04')
//                                       and SO.cancelled = 0 and ((so_date = '2022-06-20' AND so_time >= '12:00:00') OR so_date between '2022-06-21' AND '2022-06-25')");
//                   $sql->execute([input::post('sellerNo')]);
//                   $totalSold = $sql->fetchAll()[0]['totalSold'];
              
//                   if($totalSold >= 300) {
//                       $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
//                                               values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//                       $sql->execute([input::post('sellerNo'), 15, 'Week 4 - Line 10.1', $sono]);
//                       //print_r($sql->errorInfo());
//                       echo ' (ผ่านโปรสาย 10.1 ได้รับ 15 พ้อยท์!!!) ';
//                       }
//               }

//               // เคยขาย
//               if($count > 0 && $temp['countProLine2'] == 0) {
//                   $sql = $this->prepare("select ifnull(sum(total_sales), 0) as totalSold from SO 
//                                       inner join SOPrinting on SOPrinting.so_no = SO.so_no
//                                       inner join Product on Product.product_no = SOPrinting.product_no
//                                       inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
//                                       where employee_id = ? and Product.product_line = '0' and Product.category_no in ('01','07')
//                                       and SO.cancelled = 0 and ((so_date = '2022-06-20' AND so_time >= '12:00:00') OR so_date between '2022-06-21' AND '2022-06-25')");
//                   $sql->execute([input::post('sellerNo')]);
//                   $totalSold = $sql->fetchAll()[0]['totalSold'];
              
//                   if($totalSold >= 350) {
//                       $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
//                                               values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//                       $sql->execute([input::post('sellerNo'), 25, 'Week 4 - Line 10.2', $sono]);
//                       //print_r($sql->errorInfo());
//                       echo ' (ผ่านโปรสาย 10.2 ได้รับ 25 พ้อยท์!!!) ';
//                       }
//               }
//               break;
                  
// }



//       }

//   }
    private function promotionWeek6 ($sono) {

      $soItemsArray = json_decode(input::post('soItems'), true); 
      $soItemsArray = json_decode($soItemsArray, true);
      $extraPoint = 0;

      if(is_numeric(input::post('sellerNo'))) {

          switch (json_decode(session::get('employee_detail'), true)['product_line']) {

              case '1': // ขาย polaroid 1 เครื่อง 25 แต้ม

                  $sql = $this->prepare( "select * from 
                  (select count(*) as countProLine1 from PointLog where employee_id = ? and remark = 'Week 6 - Line 1' and cancelled = 0) as countProLine1" );

                  $sql->execute( [ input::post( 'sellerNo' ) ] );

                  $temp = $sql->fetchAll()[ 0 ];

                  if ( $temp[ 'countProLine1' ] == 0 ) {
                      $sql = $this->prepare("select ifnull(sum(quantity), 0) as countSold from SO 
                                          inner join SOPrinting on SOPrinting.so_no = SO.so_no
                                          inner join Product on Product.product_no = SOPrinting.product_no
                                          inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
                                          where employee_id = ? and Product.product_line = '1' and Product.product_no between '1-I2-01-114-003' and '1-I2-01-114-008'
                                          and SO.cancelled = 0 and ((so_date = '2022-07-04' AND so_time >= '12:00:00') OR so_date between '2022-07-05' AND '2022-07-09')");
          $sql->execute([input::post('sellerNo')]);
          $countSold = $sql->fetchAll()[0]['countSold'];
                  
          if($countSold > 0) {
            $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
                        values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
            $sql->execute([input::post('sellerNo'), 25, 'Week 6 - Line 1', $sono]);
            //print_r($sql->errorInfo());
            echo ' (ผ่านโปรสาย 1 ได้รับ 25 พ้อยท์!!!) ';
                      }


                  }
                  break;
              
              case '2': // เครื่องฟอกอากาศ, เครื่องกระจายกลิ่นหอม 1 เครื่อง 50 pts

                  $sql = $this->prepare("select * from
                  (select count(*) as countProLine1 from PointLog where employee_id = ? and remark = 'Week 6 - Line 2.1' and cancelled = 0) as countProLine1,
                  (select count(*) as countProLine2 from PointLog where employee_id = ? and remark = 'Week 6 - Line 2.2' and cancelled = 0) as countProLine2");

                  $sql->execute([input::post('sellerNo'),input::post('sellerNo')]);
                  $temp = $sql->fetchAll()[0];

                  if ( $temp[ 'countProLine1' ] == 0 ) {

                      $sql = $this->prepare("select ifnull(sum(quantity), 0) as countSold from SO 
                                          inner join SOPrinting on SOPrinting.so_no = SO.so_no
                                          inner join Product on Product.product_no = SOPrinting.product_no
                                          inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
                                          where employee_id = ? and Product.product_line = '2' and Product.category_no in ('03','08')
                                          and SO.cancelled = 0 and ((so_date = '2022-07-04' AND so_time >= '12:00:00') OR so_date between '2022-07-05' AND '2022-07-09')");
          $sql->execute([input::post('sellerNo')]);
          $countSold = $sql->fetchAll()[0]['countSold'];
                  
          if($countSold > 0) {
            $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
                        values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
            $sql->execute([input::post('sellerNo'), 50, 'Week 6 - Line 2.1', $sono]);
            //print_r($sql->errorInfo());
            echo ' (ผ่านโปรสาย 2.1 ได้รับ 50 พ้อยท์!!!) ';
                      }

                  }
                  // ขายแอร์ 1 เครื่อง 70 pts
                  if ($temp['countProLine2'] == 0) {

                      $sql = $this->prepare("select ifnull(sum(quantity), 0) as countSold from SO 
                                          inner join SOPrinting on SOPrinting.so_no = SO.so_no
                                          inner join Product on Product.product_no = SOPrinting.product_no
                                          inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
                                          where employee_id = ? and Product.product_line = '2' and Product.category_no = '01'
                                          and SO.cancelled = 0 and ((so_date = '2022-07-04' AND so_time >= '12:00:00') OR so_date between '2022-07-05' AND '2022-07-09')");
          $sql->execute([input::post('sellerNo')]);
          $countSold = $sql->fetchAll()[0]['countSold'];
                  
          if($countSold > 0) {
            $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
                        values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
            $sql->execute([input::post('sellerNo'), 70, 'Week 6 - Line 2.2', $sono]);
            //print_r($sql->errorInfo());
            echo ' (ผ่านโปรสาย 2.2 ได้รับ 70 พ้อยท์!!!) ';
                          
                      }
                  }
                  break;

              case '3': 

                  $sql = $this->prepare("select * from
                  (select count(*) as countProLine1 from PointLog where employee_id = ? and remark = 'Week 6 - Line 3.1' and cancelled = 0) as countProLine1,
                  (select count(*) as countProLine2 from PointLog where employee_id = ? and remark = 'Week 6 - Line 3.2' and cancelled = 0) as countProLine2,
                  (select sum(point) as sumProLine1 from PointLog where employee_id = ? and remark = 'Week 6 - Line 3.1' and cancelled = 0) as sumProLine1");
                  
                  $sql->execute([input::post('sellerNo'),input::post('sellerNo'),input::post('sellerNo')]);
                  $temp = $sql->fetchAll()[0];
                  // ขายพัดลม เตารีด ครบทุก 800 บาท 25 pts
                  if ( $temp[ 'countProLine1' ] != -1 ) {

                      $sql = $this->prepare("select ifnull(sum(total_sales), 0) as totalSold from SO 
                                          inner join SOPrinting on SOPrinting.so_no = SO.so_no
                                          inner join Product on Product.product_no = SOPrinting.product_no
                                          inner join ProductCategory on ProductCategory.category_no = Product.category_no and ProductCategory.product_line = Product.product_line
                                          where employee_id = ? and Product.product_line = '3' and Product.category_no in ('09','10')
                                          and SO.cancelled = 0 and ((so_date = '2022-07-04' AND so_time >= '12:00:00') OR so_date between '2022-07-05' AND '2022-07-09')");
                      $sql->execute([input::post('sellerNo')]);
                      $totalSold = $sql->fetchAll()[0]['totalSold'];
                      
                      $times=intdiv($temp['sumProLine1'],25);
                      $sold=$times*800;
                      $new_total=$totalSold-$sold;
                      $p_times=intdiv($new_total,800);
                      $extraPoint = ($p_times >= 1) ? $p_times*25: 0;
                      
                      if($extraPoint > 0) {
                          $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note, type, cancelled) values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?, 'Promotion', 0)");
                          $sql->execute([input::post('sellerNo'), $extraPoint, 'Week 6 - Line 3.1', $sono]);
                          echo ' (ผ่านโปรสาย 3.1 ได้รับ '.$extraPoint.' พ้อยท์!!!)';
                      }
                  }
                  // ขายไมโครเวฟ เตาอบ หม้อทอดไฟฟ้า 35 pts 
                  if ($temp['countProLine2'] == 0) {

                      $sql = $this->prepare("select ifnull(sum(quantity), 0) as countSold from SO 
                                          inner join SOPrinting on SOPrinting.so_no = SO.so_no
                                          inner join Product on Product.product_no = SOPrinting.product_no
                                          inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
                                          where employee_id = ? and Product.product_line = '3' and Product.category_no in ('03','05','06')
                                          and SO.cancelled = 0 and ((so_date = '2022-07-04' AND so_time >= '12:00:00') OR so_date between '2022-07-05' AND '2022-07-09')");
          $sql->execute([input::post('sellerNo')]);
          $countSold = $sql->fetchAll()[0]['countSold'];
                  
          if($countSold > 0) {
            $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
                        values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
            $sql->execute([input::post('sellerNo'), 35, 'Week 6 - Line 3.2', $sono]);
            //print_r($sql->errorInfo());
            echo ' (ผ่านโปรสาย 3.2 ได้รับ 35 พ้อยท์!!!) ';
                          
                      }
                  }
                  break;

              case '4':

                  $sql = $this->prepare("select * from
                  (select count(*) as countProLine1 from PointLog where employee_id = ? and remark = 'Week 6 - Line 4.1' and cancelled = 0) as countProLine1,
                  (select count(*) as countProLine2 from PointLog where employee_id = ? and remark = 'Week 6 - Line 4.2' and cancelled = 0) as countProLine2");

                  $sql->execute([input::post('sellerNo'),input::post('sellerNo')]);
                  $temp = $sql->fetchAll()[0];

                  // ขาย ipad gen9 1 เครื่อง 25 pts
                  if ( $temp[ 'countProLine1' ] == 0 ) {

                      $sql = $this->prepare("select ifnull(sum(quantity), 0) as countSold from SO 
                                          inner join SOPrinting on SOPrinting.so_no = SO.so_no
                                          inner join Product on Product.product_no = SOPrinting.product_no
                                          inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
                                          where employee_id = ? and Product.product_line = '4' and Product.product_no in ('4-I1-01-B02-117','4-I1-01-B02-118','4-I1-01-B02-119','4-I1-01-B02-120',
                                          '4-I1-01-B02-121','4-I1-01-B02-122','4-I1-01-B02-123','4-I1-01-B02-124','4-I1-01-B03-117','4-I1-01-B03-118','4-I1-01-B03-119','4-I1-01-B03-120','4-I1-01-B03-121',
                                          '4-I1-01-B03-122','4-I1-01-B03-123','4-I1-01-B03-124','4-I1-01-B04-085','4-I1-01-B04-086','4-I1-01-B04-087','4-I1-01-B04-088','4-I1-01-B04-089','4-I1-01-B04-090',
                                          '4-I1-01-B04-091','4-I1-01-B04-092','4-I1-01-B06-073','4-I1-01-B06-074','4-I1-01-B06-075','4-I1-01-B06-076','4-I1-01-B06-077','4-I1-01-B06-078','4-I1-01-B06-079','4-I1-01-B06-080')
                                          and SO.cancelled = 0 and ((so_date = '2022-07-04' AND so_time >= '12:00:00') OR so_date between '2022-07-05' AND '2022-07-09')");
          $sql->execute([input::post('sellerNo')]);
          $countSold = $sql->fetchAll()[0]['countSold'];
                  
          if($countSold > 0) {
            $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
                        values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
            $sql->execute([input::post('sellerNo'), 25, 'Week 6 - Line 4.1', $sono]);
            //print_r($sql->errorInfo());
            echo ' (ผ่านโปรสาย 4.1 ได้รับ 25 พ้อยท์!!!) ';
                      }

                  }
                  // ขายโทรศัพท์ 1 เครื่อง 35 pts 
                  if ($temp['countProLine2'] == 0) {

                      $sql = $this->prepare("select ifnull(sum(quantity), 0) as countSold from SO 
                                          inner join SOPrinting on SOPrinting.so_no = SO.so_no
                                          inner join Product on Product.product_no = SOPrinting.product_no
                                          inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
                                          where employee_id = ? and Product.product_line = '4' and Product.category_no = '03'
                                          and SO.cancelled = 0 and ((so_date = '2022-07-04' AND so_time >= '12:00:00') OR so_date between '2022-07-05' AND '2022-07-09')");
          $sql->execute([input::post('sellerNo')]);
          $countSold = $sql->fetchAll()[0]['countSold'];
                  
          if($countSold > 0) {
            $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
                        values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
            $sql->execute([input::post('sellerNo'), 35, 'Week 6 - Line 4.2', $sono]);
            //print_r($sql->errorInfo());
            echo ' (ผ่านโปรสาย 4.2 ได้รับ 35 พ้อยท์!!!) ';
                          
                      }
                  }
                  break;

              case '5':

                  $sql = $this->prepare("select * from
                  (select count(*) as countProLine1 from PointLog where employee_id = ? and remark = 'Week 6 - Line 5.1' and cancelled = 0) as countProLine1,
                  (select count(*) as countProLine2 from PointLog where employee_id = ? and remark = 'Week 6 - Line 5.2' and cancelled = 0) as countProLine2");

                  $sql->execute([input::post('sellerNo'),input::post('sellerNo')]);
                  $temp = $sql->fetchAll()[0];
                  // ขาย Accessories, Peripherals 1 ชิ้น 20 pts
                  if ( $temp[ 'countProLine1' ] == 0 ) {

                      $sql = $this->prepare("select ifnull(sum(quantity), 0) as countSold from SO 
                                          inner join SOPrinting on SOPrinting.so_no = SO.so_no
                                          inner join Product on Product.product_no = SOPrinting.product_no
                                          inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
                                          where employee_id = ? and Product.product_line = '5' and Product.sub_category in ('Accessories','peripherals')
                                          and SO.cancelled = 0 and ((so_date = '2022-07-04' AND so_time >= '12:00:00') OR so_date between '2022-07-05' AND '2022-07-09')");
          $sql->execute([input::post('sellerNo')]);
          $countSold = $sql->fetchAll()[0]['countSold'];
                  
          if($countSold > 0) {
            $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
                        values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
            $sql->execute([input::post('sellerNo'), 20, 'Week 6 - Line 5.1', $sono]);
            //print_r($sql->errorInfo());
            echo ' (ผ่านโปรสาย 5.1 ได้รับ 20 พ้อยท์!!!) ';
                      }

                  }
                  // macbook, notebook 1 เครื่อง 70 pts 
                  if ($temp['countProLine2'] == 0) {

                      $sql = $this->prepare("select ifnull(sum(quantity), 0) as countSold from SO 
                                          inner join SOPrinting on SOPrinting.so_no = SO.so_no
                                          inner join Product on Product.product_no = SOPrinting.product_no
                                          inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
                                          where employee_id = ? and Product.product_line = '5' and Product.sub_category in ('Macbook','notebook')
                                          and SO.cancelled = 0 and ((so_date = '2022-07-04' AND so_time >= '12:00:00') OR so_date between '2022-07-05' AND '2022-07-09')");
          $sql->execute([input::post('sellerNo')]);
          $countSold = $sql->fetchAll()[0]['countSold'];
                  
          if($countSold > 0) {
            $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
                        values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
            $sql->execute([input::post('sellerNo'), 70, 'Week 6 - Line 5.2', $sono]);
            //print_r($sql->errorInfo());
            echo ' (ผ่านโปรสาย 5.2 ได้รับ 70 พ้อยท์!!!) ';
                          
                      }
                  }
                  break;
              
              case '6':

                  $sql = $this->prepare("select * from
                  (select count(*) as countProLine1 from PointLog where employee_id = ? and remark = 'Week 6 - Line 6' and cancelled = 0) as countProLine1");

                  $sql->execute([input::post('sellerNo')]);
                  $temp = $sql->fetchAll()[0];

                  // ขายสินค้าดอยคำ แพร์รี่ วุ้นเส้นชอช้างครบ 300 บาท 15 pts
                  if ( $temp[ 'countProLine1' ] == 0 ) {
                      $sql = $this->prepare("select ifnull(sum(total_sales), 0) as totalSold from SO 
                                              inner join SOPrinting on SOPrinting.so_no = SO.so_no
                                              inner join Product on Product.product_no = SOPrinting.product_no
                                              inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
                                              where employee_id = ? and Product.product_line = '6' and Product.supplier_no in ('606','613','614') and Product.category_no != '05'
                                              and SO.cancelled = 0 and ((so_date = '2022-07-04' AND so_time >= '12:00:00') OR so_date between '2022-07-05' AND '2022-07-09')");
                      $sql->execute([input::post('sellerNo')]);
                      $totalSold = $sql->fetchAll()[0]['totalSold'];

                      if($totalSold >= 300) {
                          $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
                                                  values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
                          $sql->execute([input::post('sellerNo'), 15, 'Week 6 - Line 6', $sono]);
                          //print_r($sql->errorInfo());
                          echo ' (ผ่านโปรสาย 6 ได้รับ 15 พ้อยท์!!!) ';
                      }
                  }
                  break;
              
              case '7':

                  $sql = $this->prepare("select * from
                  (select count(*) as countProLine1 from PointLog where employee_id = ? and remark = 'Week 6 - Line 7' and cancelled = 0) as countProLine1");

                  $sql->execute([input::post('sellerNo')]);
                  $temp = $sql->fetchAll()[0];
                  // ขายหนังสือ 3 เล่มขึ้นไป 25 pts
                  if ( $temp[ 'countProLine1' ] == 0 ) {

                      $sql = $this->prepare("select ifnull(sum(quantity), 0) as countSold from SO 
                                          inner join SOPrinting on SOPrinting.so_no = SO.so_no
                                          inner join Product on Product.product_no = SOPrinting.product_no
                                          inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
                                          where employee_id = ? and Product.product_line = '7' and Product.category_no = '07'
                                          and SO.cancelled = 0 and ((so_date = '2022-07-04' AND so_time >= '12:00:00') OR so_date between '2022-07-05' AND '2022-07-09')");
          $sql->execute([input::post('sellerNo')]);
          $countSold = $sql->fetchAll()[0]['countSold'];
                  
          if($countSold >= 3) {
            $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
                        values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
            $sql->execute([input::post('sellerNo'), 25, 'Week 6 - Line 7', $sono]);
            //print_r($sql->errorInfo());
            echo ' (ผ่านโปรสาย 7 ได้รับ 25 พ้อยท์!!!) ';
                      }

                  }
                  break;

              case '8':

                  $sql = $this->prepare("select * from
                  (select count(*) as countProLine1 from PointLog where employee_id = ? and remark = 'Week 6 - Line 8' and cancelled = 0) as countProLine1");

                  $sql->execute([input::post('sellerNo')]);
                  $temp = $sql->fetchAll()[0];
                  // ขายสินค้าหมวด health wellness อะไรก็ได้ครบ 1000 บาท 25 pts
                  if ( $temp[ 'countProLine1' ] == 0 ) {

                      $sql = $this->prepare("select ifnull(sum(total_sales), 0) as totalSold from SO 
                                          inner join SOPrinting on SOPrinting.so_no = SO.so_no
                                          inner join Product on Product.product_no = SOPrinting.product_no
                                          inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
                                          where employee_id = ? and Product.product_line = '8' and Product.category_no in ('01','03')
                                          and SO.cancelled = 0 and ((so_date = '2022-07-04' AND so_time >= '12:00:00') OR so_date between '2022-07-05' AND '2022-07-09')");
          $sql->execute([input::post('sellerNo')]);
          $totalSold = $sql->fetchAll()[0]['totalSold'];
                  
          if($totalSold >= 1000) {
            $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
                        values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
            $sql->execute([input::post('sellerNo'), 25, 'Week 6 - Line 8', $sono]);
            //print_r($sql->errorInfo());
            echo ' (ผ่านโปรสาย 8 ได้รับ 25 พ้อยท์!!!) ';
                      }

                  }
                  break;
              
              case '9':

                  $sql = $this->prepare("select * from
                  (select count(*) as countProLine1 from PointLog where employee_id = ? and remark = 'Week 6 - Line 9.1' and cancelled = 0) as countProLine1,
                  (select count(*) as countProLine2 from PointLog where employee_id = ? and remark = 'Week 6 - Line 9.2' and cancelled = 0) as countProLine2");

                  $sql->execute([input::post('sellerNo'),input::post('sellerNo')]);
                  $temp = $sql->fetchAll()[0];
                  // ขายเครื่องประดับหรือกระเป๋ารวมเกิน 500 บาท 20 pts
                  if ( $temp[ 'countProLine1' ] == 0 ) {

                      $sql = $this->prepare("select ifnull(sum(total_sales), 0) as totalSold from SO 
                                          inner join SOPrinting on SOPrinting.so_no = SO.so_no
                                          inner join Product on Product.product_no = SOPrinting.product_no
                                          inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
                                          where employee_id = ? and Product.product_line = '9' and Product.sub_category in ('Accessory','Bag')
                                          and SO.cancelled = 0 and ((so_date = '2022-07-04' AND so_time >= '12:00:00') OR so_date between '2022-07-05' AND '2022-07-09')");
          $sql->execute([input::post('sellerNo')]);
          $totalSold = $sql->fetchAll()[0]['totalSold'];
                  
          if($totalSold >= 500) {
            $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
                        values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
            $sql->execute([input::post('sellerNo'), 20, 'Week 6 - Line 9.1', $sono]);
            //print_r($sql->errorInfo());
            echo ' (ผ่านโปรสาย 9.1 ได้รับ 20 พ้อยท์!!!) ';
                      }

                  }
                  // ขายสินค้าสาย 9 อะไรก็ได้ยอดเกิน 1,000 บาท 30 pts
                  if ($temp['countProLine2'] == 0) {

                      $sql = $this->prepare("select ifnull(sum(total_sales), 0) as totalSold from SO 
                                          inner join SOPrinting on SOPrinting.so_no = SO.so_no
                                          inner join Product on Product.product_no = SOPrinting.product_no
                                          inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
                                          where employee_id = ? and Product.product_line = '9' and Product.category_no != '04'
                                          and SO.cancelled = 0 and ((so_date = '2022-07-04' AND so_time >= '12:00:00') OR so_date between '2022-07-05' AND '2022-07-09')");
          $sql->execute([input::post('sellerNo')]);
          $totalSold = $sql->fetchAll()[0]['totalSold'];
                  
          if($totalSold >= 1000) {
            $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
                        values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
            $sql->execute([input::post('sellerNo'), 30, 'Week 6 - Line 9.2', $sono]);
            //print_r($sql->errorInfo());
            echo ' (ผ่านโปรสาย 9.2 ได้รับ 30 พ้อยท์!!!) ';
                      }
                  }
                  break;

              case '0':

                  $sql = $this->prepare("select * from
                  (select count(*) as countProLine1 from PointLog where employee_id = ? and remark = 'Week 6 - Line 10.1' and cancelled = 0) as countProLine1,
                  (select count(*) as countProLine2 from PointLog where employee_id = ? and remark = 'Week 6 - Line 10.2' and cancelled = 0) as countProLine2,
                  (select count(*) as countProLine3 from PointLog where employee_id = ? and remark = 'Week 6 - Line 10.3' and cancelled = 0) as countProLine3");

                  $sql->execute([input::post('sellerNo'),input::post('sellerNo'),input::post('sellerNo')]);
                  $temp = $sql->fetchAll()[0];
                  // ขายสินค้ากลุ่ม Accessories ให้ได้ 1 ชิ้น 20 pts 

                  if ( $temp[ 'countProLine1' ] == 0 ) {

                      $sql = $this->prepare("select ifnull(sum(quantity), 0) as countSold from SO 
                                          inner join SOPrinting on SOPrinting.so_no = SO.so_no
                                          inner join Product on Product.product_no = SOPrinting.product_no
                                          inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
                                          where employee_id = ? and Product.product_line = '0' and Product.category_no = '07'
                                          and SO.cancelled = 0 and ((so_date = '2022-07-04' AND so_time >= '12:00:00') OR so_date between '2022-07-05' AND '2022-07-09')");
          $sql->execute([input::post('sellerNo')]);
          $countSold = $sql->fetchAll()[0]['countSold'];
                  
          if($countSold > 0) {
            $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
                        values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
            $sql->execute([input::post('sellerNo'), 20, 'Week 6 - Line 10.1', $sono]);
            //print_r($sql->errorInfo());
            echo ' (ผ่านโปรสาย 10.1 ได้รับ 20 พ้อยท์!!!) ';
                      }

                  }
                  // ขายสินค้าอะไรก็ได้ในสาย 10 ให้ได้ครบ 500/ 1,000 บาท 20/30 pts 
                  $sql = $this->prepare("select ifnull(sum(total_sales), 0) as totalSold from SO 
                                          inner join SOPrinting on SOPrinting.so_no = SO.so_no
                                          inner join Product on Product.product_no = SOPrinting.product_no
                                          inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
                                          where employee_id = ? and Product.product_line = '0' and Product.category_no != '05'
                                          and SO.cancelled = 0 and ((so_date = '2022-07-04' AND so_time >= '12:00:00') OR so_date between '2022-07-05' AND '2022-07-09')");
                  $sql->execute([input::post('sellerNo')]);
        $totalSold = $sql->fetchAll()[0]['totalSold'];

                  if ($temp['countProLine2'] == 0 && $totalSold < 1000 && $totalSold >= 500) {
                  
                      $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note, type, cancelled) values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?, 'Promotion', 0)");
                      $sql->execute([input::post('sellerNo'), 20, 'Week 6 - Line 10.2', $sono]);
                      echo ' (ผ่านโปรสาย 10.2 ได้รับ 20 พ้อยท์!!!)';

                  } else if ($temp['countProLine2'] == 0 && $totalSold >= 1000) {

                      $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note, type, cancelled) values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?, 'Promotion', 0)");
                      $sql->execute([input::post('sellerNo'), 20, 'Week 6 - Line 10.2', $sono]);
                      echo ' (ผ่านโปรสาย 10.2 ได้รับ 20 พ้อยท์!!!)';
                      
                      $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note, type, cancelled) values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?, 'Promotion', 0)");
                      $sql->execute([input::post('sellerNo'), 30, 'Week 6 - Line 10.3', $sono]);
                      echo ' (ผ่านโปรสาย 10.3 ได้รับ 30 พ้อยท์!!!)';

                  } else if ($temp['countProLine2'] != 0 && $temp['countProLine3'] == 0 && $totalSold >= 1000) {

                      $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note, type, cancelled) values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?, 'Promotion', 0)");
                      $sql->execute([input::post('sellerNo'), 30, 'Week 6 - Line 10.3', $sono]);
                      echo ' (ผ่านโปรสาย 10.3 ได้รับ 30 พ้อยท์!!!)';

                  }
                  break;


          }
      }
    }
			
// 	}
// private function promotionWeek3($sono) {

//   $soItemsArray = json_decode(input::post('soItems'), true); 
//   $soItemsArray = json_decode($soItemsArray, true);
//   $extraPoint = 0;
    
//   if(is_numeric(input::post('sellerNo'))) {
      
//       switch (json_decode(session::get('employee_detail'), true)['product_line']) {

//           case '6': // ขายสินค้าหมวด food&beverage ครบ 300 บาท 5 points

//               $sql = $this->prepare("select * from 

//                                   (select count(*) as countProLine1 from PointLog where employee_id = ? and remark = 'Week 3 - Line 6' and cancelled = 0) as countProLine1");

//               $sql->execute( [input::post('sellerNo')]);
//               $temp = $sql->fetchAll()[ 0 ];
 
//               if ( $temp[ 'countProLine1' ] == 0 ) {

//                   $sql = $this->prepare( "select ifnull(sum(total_sales), 0) as totalSold from SO 
//                                       inner join SOPrinting on SOPrinting.so_no = SO.so_no
//                                       inner join Product on Product.product_no = SOPrinting.product_no
//                                       inner join ProductCategory on ProductCategory.category_no = Product.category_no and ProductCategory.product_line = Product.product_line
//                                       where employee_id = ? and Product.product_line = '6' and Product.category_no = '01' and SO.cancelled = 0 and SO.so_date between '2022-06-13' and '2022-06-18'");

//                   $sql->execute([input::post('sellerNo')]);

//                   $totalSold = $sql->fetchAll()[0]['totalSold'];


//                   $extraPoint = ( $totalSold >= 300 ) ? 5 : 0;


//                   if ( $extraPoint > 0 ) {

//                       $sql = $this->prepare( "insert into PointLog (date, time, employee_id, point, remark, note, type, cancelled) 
//                 values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?, 'Promotion', 0)" );

//                       $sql->execute( [ input::post( 'sellerNo' ), $extraPoint, 'Week 3 - Line 6', $sono ] );

//                       echo ' (ผ่านโปรสาย 6 ได้รับ 5 พ้อยท์!!!)';
//                   }
//               }
//               break;

//           case '7': // ขายสินค้าอะไรก็ได้ในสาย 7 ครบ 600 บาท 10 points

//               $sql = $this->prepare( "select * from 
//                                   (select count(*) as countProLine1 from PointLog where employee_id = ? and remark = 'Week 3 - Line 7' and cancelled = 0) as countProLine1" );

//               $sql->execute( [ input::post( 'sellerNo' ) ] );

//               $temp = $sql->fetchAll()[ 0 ];


//               if ( $temp[ 'countProLine1' ] == 0 ) {


//                   $sql = $this->prepare( "select ifnull(sum(total_sales), 0) as totalSold from SO 

//                                       inner join SOPrinting on SOPrinting.so_no = SO.so_no

//                                       inner join Product on Product.product_no = SOPrinting.product_no

//                                       inner join ProductCategory on ProductCategory.category_no = Product.category_no and ProductCategory.product_line = Product.product_line

//                                       where employee_id = ? and Product.product_line = '7' and Product.category_no in ('01','02','03','06','07','08','09','10')

//                                       and SO.cancelled = 0 and SO.so_date between '2022-06-13' and '2022-06-18'" );

//                   $sql->execute( [ input::post( 'sellerNo' ) ] );

//                   $totalSold = $sql->fetchAll()[ 0 ][ 'totalSold' ];
                  


//                   $extraPoint = ( $totalSold >= 600 ) ? 10 : 0;
                  


//                   if ( $extraPoint > 0 ) {

//                       $sql = $this->prepare( "insert into PointLog (date, time, employee_id, point, remark, note, type, cancelled) values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?, 'Promotion', 0)" );

//                       $sql->execute( [ input::post( 'sellerNo' ), $extraPoint, 'Week 3 - Line 7', $sono ] );

//                       echo ' (ผ่านโปรสาย 7 ได้รับ 10 พ้อยท์!!!)';
                  
//                   }
//               }
//               break;
          
//           case '8': // ขายสินค้าอะไรก็ได้ในสาย 8 ครบ 1000 บาท 5 points

//               $sql = $this->prepare( "select * from 
//                                   (select count(*) as countProLine1 from PointLog where employee_id = ? and remark = 'Week 3 - Line 8' and cancelled = 0) as countProLine1" );

//               $sql->execute( [ input::post( 'sellerNo' ) ] );

//               $temp = $sql->fetchAll()[ 0 ];


//               if ( $temp[ 'countProLine1' ] == 0 ) {


//                   $sql = $this->prepare( "select ifnull(sum(total_sales), 0) as totalSold from SO 

//                                       inner join SOPrinting on SOPrinting.so_no = SO.so_no

//                                       inner join Product on Product.product_no = SOPrinting.product_no

//                                       inner join ProductCategory on ProductCategory.category_no = Product.category_no and ProductCategory.product_line = Product.product_line

//                                       where employee_id = ? and Product.product_line = '8' and Product.category_no in ('01','02','03','04','05') 

//                                       and SO.cancelled = 0 and SO.so_date between '2022-06-13' and '2022-06-18'" );

//                   $sql->execute( [ input::post( 'sellerNo' ) ] );

//                   $totalSold = $sql->fetchAll()[ 0 ][ 'totalSold' ];


//                   $extraPoint = ( $totalSold >= 1000 ) ? 5 : 0;


//                   if ( $extraPoint > 0 ) {

//                       $sql = $this->prepare( "insert into PointLog (date, time, employee_id, point, remark, note, type, cancelled) values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?, 'Promotion', 0)" );

//                       $sql->execute( [ input::post( 'sellerNo' ), $extraPoint, 'Week 3 - Line 8', $sono ] );

//                       echo ' (ผ่านโปรสาย 8 ได้รับ 5 พ้อยท์!!!)';
                  
//                   }
//               }
//               break;
//           case '9': // ขายรองเท้า1 คู่ หรือสินค้าอะไรก็ได้ในครบ 600 บาท 10 points

//               $sql = $this->prepare( "select * from 
//               (select count(*) as countProLine1 from PointLog where employee_id = ? and remark = 'Week 3 - Line 9' and cancelled = 0) as countProLine1" );

//               $sql->execute( [ input::post( 'sellerNo' ) ] );

//               $temp = $sql->fetchAll()[ 0 ];


//               if ( $temp[ 'countProLine1' ] == 0 ) {

//                   $sql = $this->prepare("select ifnull(sum(total_sales), 0) as totalSold from SO 
//                                       inner join SOPrinting on SOPrinting.so_no = SO.so_no
//                                       inner join Product on Product.product_no = SOPrinting.product_no
//                                       inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
//                                       where employee_id = ? and Product.product_line = '9' and Product.category_no in ('01','02')
//                                       and SO.cancelled = 0 and SO.so_date between '2022-06-13' AND '2022-06-18')");
//       $sql->execute([input::post('sellerNo')]);
//       $totalSold = $sql->fetchAll()[0]['totalSold'];
              
//       if($totalSold >= 600) {
//         $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
//                     values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//         $sql->execute([input::post('sellerNo'), 10, 'Week 3 - Line 9', $sono]);
//         //print_r($sql->errorInfo());
//         echo ' (ผ่านโปรสาย 9 ได้รับ 10 พ้อยท์!!!) ';
//         break;
//         }
//     }

//     if($temp['countProLine1'] == 0) {
//       $sql = $this->prepare("select ifnull(sum(quantity), 0) as countSold from SO 
//                                       inner join SOPrinting on SOPrinting.so_no = SO.so_no
//                                       inner join Product on Product.product_no = SOPrinting.product_no
//                                       inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
//                                       where employee_id = ? and Product.product_line = '9' and Product.sub_category = 'Shoes'
//                                       and SO.cancelled = 0 and SO.so_date between '2022-06-13' and '2022-06-18'");
//       $sql->execute([input::post('sellerNo')]);
//       $countSold = $sql->fetchAll()[0]['countSold'];
              
//       if($countSold > 0) {
//         $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
//                     values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
//         $sql->execute([input::post('sellerNo'), 10, 'Week 3 - Line 9', $sono]);
//         //print_r($sql->errorInfo());
//         echo ' (ผ่านโปรสาย 9 ได้รับ 10 พ้อยท์!!!) ';
//         break;
                  
//                   }
//               }
//               break;

//           case '0': // ขายสินค้าอะไรก็ได้ในสาย 10 ครบ 300 บาท 5 points

//               $sql = $this->prepare( "select * from 
//                                   (select count(*) as countProLine1 from PointLog where employee_id = ? and remark = 'Week 3 - Line 10' and cancelled = 0) as countProLine1" );

//               $sql->execute( [ input::post( 'sellerNo' ) ] );

//               $temp = $sql->fetchAll()[ 0 ];


//               if ( $temp[ 'countProLine1' ] == 0 ) {


//                   $sql = $this->prepare( "select ifnull(sum(total_sales), 0) as totalSold from SO 

//                                       inner join SOPrinting on SOPrinting.so_no = SO.so_no

//                                       inner join Product on Product.product_no = SOPrinting.product_no

//                                       inner join ProductCategory on ProductCategory.category_no = Product.category_no and ProductCategory.product_line = Product.product_line

//                                       where employee_id = ? and Product.product_line = '0' and Product.category_no in ('01','02','03','04','07')

//                                       and SO.cancelled = 0 and SO.so_date between '2022-06-13' and '2022-06-18'" );

//                   $sql->execute( [ input::post( 'sellerNo' ) ] );

//                   $totalSold = $sql->fetchAll()[ 0 ][ 'totalSold' ];


//                   $extraPoint = ( $totalSold >= 300) ? 5 : 0;


//                   if ( $extraPoint > 0 ) {

//                       $sql = $this->prepare( "insert into PointLog (date, time, employee_id, point, remark, note, type, cancelled) values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?, 'Promotion', 0)" );

//                       $sql->execute( [ input::post( 'sellerNo' ), $extraPoint, 'Week 3 - Line 10', $sono ] );

//                       echo ' (ผ่านโปรสาย 10 ได้รับ 5 พ้อยท์!!!)';
                  
//                   }
//               }
//               break;
          
//           }
          
//       }
//   }
	


	// //2022 is001
	// private function promotionTeamWeek3($team_id) {
		
	// 	$soItemsArray = json_decode(input::post('soItems'), true);
	// 	$soItemsArray = json_decode($soItemsArray,true);
	// 	$extraPoint = 0;
		
		
	// 	$product_line = json_decode(session::get('employee_detail'), true)['product_line'];
  //       $pro = 'pro'.$product_line;
        
  //       $sql = $this->prepare("select count(*) as count from ProTour where team_id = ? and ".$pro." = 1");
  //       $sql->execute([$team_id]);
  //       $temp = $sql->fetchAll()[0];
        
  //       if($temp['count'] == 0) {
            
  //           switch ($product_line) {
				
					
	// 			case '1': # Promotion Week 3 - Line 1 ขายทีวี 1 เครื่อง หรือ เครื่องเล่น Nintendo Switch 1 เครื่อง				
                
  //                   $sql = $this->prepare("select ifnull(sum(SOPrinting.quantity),0) as count from SO
  //                                           inner join SOPrinting on SOPrinting.so_no = SO.so_no
  //                                           inner join TeamMembers on TeamMembers.employee_id = SO.employee_id
  //                                           left join Product on Product.product_no = SOPrinting.product_no
  //                                           left join ProductCategory on ProductCategory.category_no = Product.category_no and ProductCategory.product_line = Product.product_line
  //                                           where SO.cancelled = 0 and ((so_date = '2021-06-13' AND so_time >= '12:00:00') OR so_date between '2021-06-12' AND '2021-06-18')
	// 										and Product.product_line = 1 and (Product.category_no = '03' and Product.sub_category = 'TV')  and TeamMembers.team_id = ?");
  //                   $sql->execute([$team_id]);
  //                   $temp = $sql->fetchAll()[0];
                    
  //                   if($temp['count'] > 0) {
  //                       $sql = $this->prepare("update ProTour set pro1 = 1 where team_id = ?");
  //                       $sql->execute([$team_id]);    
  //                   }
                    
  //                   break;
					
	// 			case '2': # Promotion Week 3 - Line 2 ขายเครื่องฟอกอากาศครบทีมละ 2 เครื่อง/ทีม 						
                
  //                   $sql = $this->prepare("select ifnull(sum(SOPrinting.quantity),0) as count from SO
  //                                           inner join SOPrinting on SOPrinting.so_no = SO.so_no
  //                                           inner join TeamMembers on TeamMembers.employee_id = SO.employee_id
  //                                           left join Product on Product.product_no = SOPrinting.product_no
  //                                           left join ProductCategory on ProductCategory.category_no = Product.category_no and ProductCategory.product_line = Product.product_line
  //                                           where SO.cancelled = 0 and ((so_date = '2021-06-13' AND so_time >= '12:00:00') OR so_date between '2021-06-12' AND '2021-06-18')
	// 										and Product.product_line = 2 and Product.category_no = '01' and TeamMembers.team_id = ?");
  //                   $sql->execute([$team_id]);
  //                   $temp = $sql->fetchAll()[0];
                    
  //                   if($temp['count'] >= 2) {
  //                       $sql = $this->prepare("update ProTour set pro2 = 1 where team_id = ?");
  //                       $sql->execute([$team_id]);    
  //                   }
                    
  //                   break;
				
	// 			case '3': # Promotion Week 3 - Line 3 ขายอะไรก็ได้ครบ 3500 บาท 								
                
  //                   $sql = $this->prepare("select ifnull(sum(SOPrinting.total_sales),0) as sum from SO
  //                                           inner join SOPrinting on SOPrinting.so_no = SO.so_no
  //                                           inner join TeamMembers on TeamMembers.employee_id = SO.employee_id
  //                                           left join Product on Product.product_no = SOPrinting.product_no
  //                                           left join ProductCategory on ProductCategory.category_no = Product.category_no and ProductCategory.product_line = Product.product_line
  //                                           where SO.cancelled = 0 and ((so_date = '2021-06-13' AND so_time >= '12:00:00') OR so_date between '2021-06-12' AND '2021-06-18')
	// 										and Product.product_line = 3 and TeamMembers.team_id = ?");
  //                   $sql->execute([$team_id]);
  //                   $temp = $sql->fetchAll()[0];
                    
  //                   if($temp['sum'] >= 3500) {
  //                       $sql = $this->prepare("update ProTour set pro3 = 1 where team_id = ?");
  //                       $sql->execute([$team_id]);    
  //                   }
                    
  //                   break;
					
	// 			case '4': # Promotion Week 3 - Line 4 ขาย iPad หรือ iPad Bundle ได้ครบทีมละ 4 เครื่อง 								
                
  //                   $sql = $this->prepare("select ifnull(sum(SOPrinting.quantity),0) as count from SO
  //                                           inner join SOPrinting on SOPrinting.so_no = SO.so_no
  //                                           inner join TeamMembers on TeamMembers.employee_id = SO.employee_id
  //                                           left join Product on Product.product_no = SOPrinting.product_no
  //                                           left join ProductCategory on ProductCategory.category_no = Product.category_no and ProductCategory.product_line = Product.product_line
  //                                           where SO.cancelled = 0 and ((so_date = '2021-06-13' AND so_time >= '12:00:00') OR so_date between '2021-06-12' AND '2021-06-18')
	// 										and Product.product_line = 4 and Product.category_no = '01' and TeamMembers.team_id = ?");
  //                   $sql->execute([$team_id]);
  //                   $temp = $sql->fetchAll()[0];
                    
  //                   if($temp['count'] >= 4) {
  //                       $sql = $this->prepare("update ProTour set pro4 = 1 where team_id = ?");
  //                       $sql->execute([$team_id]);    
  //                   }
                    
  //                   break;
					
	// 			case '5': # Promotion Week 8 - Line 5 ขายสินค้าMacbook Proหรืออะไรก็ได้ในcate Windows				
	// 					// 	ขายสินค้าในsubcate Acsyหรือcate Peripheralsครบ3000/ทีม											
                
  //                   $sql = $this->prepare("select ifnull(sum(SOPrinting.quantity),0) as count from SO
  //                                           inner join SOPrinting on SOPrinting.so_no = SO.so_no
  //                                           inner join TeamMembers on TeamMembers.employee_id = SO.employee_id
  //                                           left join Product on Product.product_no = SOPrinting.product_no
  //                                           left join ProductCategory on ProductCategory.category_no = Product.category_no and ProductCategory.product_line = Product.product_line
  //                                           where SO.cancelled = 0 and ((so_date = '2021-06-13' AND so_time >= '12:00:00') OR so_date between '2021-06-12' AND '2021-06-18')
	// 										and Product.product_line = 5 and ((Product.category_no = '01' AND (product_name LIKE '%macbook pro%' or product_name LIKE '%mbp%')) OR (Product,category_no = '02'))  and TeamMembers.team_id = ?");
  //                   $sql->execute([$team_id]);
  //                   $temp = $sql->fetchAll()[0];
                    
  //                   if($temp['count'] > 0) {
  //                       $sql = $this->prepare("update ProTour set pro5_1 = 1 where team_id = ?");
  //                       $sql->execute([$team_id]);    
  //                   }
					
	// 				$sql = $this->prepare("select ifnull(sum(SOPrinting.total_sales),0) as sum from SO
  //                                           inner join SOPrinting on SOPrinting.so_no = SO.so_no
  //                                           inner join TeamMembers on TeamMembers.employee_id = SO.employee_id
  //                                           left join Product on Product.product_no = SOPrinting.product_no
  //                                           left join ProductCategory on ProductCategory.category_no = Product.category_no and ProductCategory.product_line = Product.product_line
  //                                           where SO.cancelled = 0 and ((so_date = '2021-07-19' AND so_time >= '13:00:00') OR so_date between '2021-07-20' AND '2021-07-24')
	// 										and Product.product_line = 5 and ((Product.category_no = '01' and Product.sub_category = 'Accessories') OR (Product.category_no = '03') OR Product.category_no = '05')  and TeamMembers.team_id = ?");
  //                   $sql->execute([$team_id]);
  //                   $temp = $sql->fetchAll()[0];
                    
  //                   if($temp['sum'] >= 3000) {
  //                       $sql = $this->prepare("update ProTour set pro5_2 = 1 where team_id = ?");
  //                       $sql->execute([$team_id]);    
  //                   }
					
                    
  //                   break;
					
	// 			case '6': # Promotion Week 8 - Line 6 ขาย the doop(602) / ดอยคำ(613) / แพรี่(614) / ยูออนไลน์(622) / VC(609,616) ครบ 2000 บาท/ทีม											
                
  //                   $sql = $this->prepare("select ifnull(sum(SOPrinting.total_sales),0) as sum from SO
  //                                           inner join SOPrinting on SOPrinting.so_no = SO.so_no
  //                                           inner join TeamMembers on TeamMembers.employee_id = SO.employee_id
  //                                           left join Product on Product.product_no = SOPrinting.product_no
  //                                           left join ProductCategory on ProductCategory.category_no = Product.category_no and ProductCategory.product_line = Product.product_line
  //                                           where SO.cancelled = 0 and ((so_date = '2021-07-19' AND so_time >= '13:00:00') OR so_date between '2021-07-20' AND '2021-07-24')
	// 										and Product.product_line = 6 and Product.category_no not in ('04','07') and Product.supplier_no in 
	// 										('602','613','614','622','609','616') and TeamMembers.team_id = ?");
  //                   $sql->execute([$team_id]);
  //                   $temp = $sql->fetchAll()[0];
                    
  //                   if($temp['sum'] >= 2000) {
  //                       $sql = $this->prepare("update ProTour set pro6 = 1 where team_id = ?");
  //                       $sql->execute([$team_id]);    
  //                   }
                    
  //                   break;
					
	// 			case '7': # Promotion Week 8 - Line 7 ขายเครื่องคิดเลข / เวย์ / ทอย ครบ 3,000 บาท/ทีม														
                
  //                   $sql = $this->prepare("select ifnull(sum(SOPrinting.total_sales),0) as sum from SO
  //                                           inner join SOPrinting on SOPrinting.so_no = SO.so_no
  //                                           inner join TeamMembers on TeamMembers.employee_id = SO.employee_id
  //                                           left join Product on Product.product_no = SOPrinting.product_no
  //                                           left join ProductCategory on ProductCategory.category_no = Product.category_no and ProductCategory.product_line = Product.product_line
  //                                           where SO.cancelled = 0 and ((so_date = '2021-07-19' AND so_time >= '13:00:00') OR so_date between '2021-07-20' AND '2021-07-24')
	// 										and Product.product_line = 7 and Product.category_no in ('01','06','08','05') and TeamMembers.team_id = ?");
  //                   $sql->execute([$team_id]);
  //                   $temp = $sql->fetchAll()[0];
                    
  //                   if($temp['sum'] >= 3000) {
  //                       $sql = $this->prepare("update ProTour set pro7 = 1 where team_id = ?");
  //                       $sql->execute([$team_id]);    
  //                   }
                    
  //                   break;
					
	// 			case '8': # Promotion Week 8 - Line 8 ขายสินค้าอะไรก็ได้ในสาย 8 เกิน 5,000 บาท																		
                
  //                   $sql = $this->prepare("select ifnull(sum(SOPrinting.total_sales),0) as sum from SO
  //                                           inner join SOPrinting on SOPrinting.so_no = SO.so_no
  //                                           inner join TeamMembers on TeamMembers.employee_id = SO.employee_id
  //                                           left join Product on Product.product_no = SOPrinting.product_no
  //                                           left join ProductCategory on ProductCategory.category_no = Product.category_no and ProductCategory.product_line = Product.product_line
  //                                           where SO.cancelled = 0 and ((so_date = '2021-07-19' AND so_time >= '13:00:00') OR so_date between '2021-07-20' AND '2021-07-24')
	// 										and Product.product_line = 8 and Product.category_no not in ('06') and TeamMembers.team_id = ?");
  //                   $sql->execute([$team_id]);
  //                   $temp = $sql->fetchAll()[0];
                    
  //                   if($temp['sum'] >= 5000) {
  //                       $sql = $this->prepare("update ProTour set pro8 = 1 where team_id = ?");
  //                       $sql->execute([$team_id]);    
  //                   }
                    
  //                   break;
					
	// 			case '9': # Promotion Week 8 - Line 9 ขายอะไรก็ได้ในสาย 9 ครบ 3000																		
                
  //                   $sql = $this->prepare("select ifnull(sum(SOPrinting.total_sales),0) as sum from SO
  //                                           inner join SOPrinting on SOPrinting.so_no = SO.so_no
  //                                           inner join TeamMembers on TeamMembers.employee_id = SO.employee_id
  //                                           left join Product on Product.product_no = SOPrinting.product_no
  //                                           left join ProductCategory on ProductCategory.category_no = Product.category_no and ProductCategory.product_line = Product.product_line
  //                                           where SO.cancelled = 0 and ((so_date = '2021-07-19' AND so_time >= '13:00:00') OR so_date between '2021-07-20' AND '2021-07-24')
	// 										and Product.product_line = 9 and Product.category_no not in ('03') and TeamMembers.team_id = ?");
  //                   $sql->execute([$team_id]);
  //                   $temp = $sql->fetchAll()[0];
                    
  //                   if($temp['sum'] >= 3000) {
  //                       $sql = $this->prepare("update ProTour set pro9 = 1 where team_id = ?");
  //                       $sql->execute([$team_id]);    
  //                   }
                    
  //                   break;
					
	// 			case '0': # Promotion Week 8 - Line 10 ขายทองม้วนกล่อง 150 กรัม 6 กล่องและปั้นสิบ 8 ถุง/ทีม																		
                
  //                   $sql = $this->prepare("select ifnull(sum(SOPrinting.quantity),0) as count from SO
  //                                           inner join SOPrinting on SOPrinting.so_no = SO.so_no
  //                                           inner join TeamMembers on TeamMembers.employee_id = SO.employee_id
  //                                           left join Product on Product.product_no = SOPrinting.product_no
  //                                           left join ProductCategory on ProductCategory.category_no = Product.category_no and ProductCategory.product_line = Product.product_line
  //                                           where SO.cancelled = 0 and ((so_date = '2021-07-19' AND so_time >= '13:00:00') OR so_date between '2021-07-20' AND '2021-07-24')
	// 										and Product.product_line = 0 and Product.product_no in ('0-S2-04-007-005','0-S2-04-007-014','0-S2-04-007-023') and TeamMembers.team_id = ?");
  //                   $sql->execute([$team_id]);
  //                   $temp = $sql->fetchAll()[0];
					
	// 				$sql = $this->prepare("select ifnull(sum(SOPrinting.quantity),0) as count from SO
  //                                           inner join SOPrinting on SOPrinting.so_no = SO.so_no
  //                                           inner join TeamMembers on TeamMembers.employee_id = SO.employee_id
  //                                           left join Product on Product.product_no = SOPrinting.product_no
  //                                           left join ProductCategory on ProductCategory.category_no = Product.category_no and ProductCategory.product_line = Product.product_line
  //                                           where SO.cancelled = 0 and ((so_date = '2021-07-19' AND so_time >= '13:00:00') OR so_date between '2021-07-20' AND '2021-07-24')
	// 										and Product.product_line = 0 and Product.supplier_no = '006' and TeamMembers.team_id = ?");
  //                   $sql->execute([$team_id]);
  //                   $temp2 = $sql->fetchAll()[0];
					
                    
  //                   if($temp['count'] >= 6 && $temp2['count'] >= 8) {
  //                       $sql = $this->prepare("update ProTour set pro0 = 1 where team_id = ?");
  //                       $sql->execute([$team_id]);    
  //                   }
                    
  //                   break;

                
                                        
  //           }
	// 	}

		  

// 	}
	
// /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
// 	private function promotionWeek9($sono) {
		
// 		$soItemsArray = json_decode(input::post('soItems'), true);
// 		$soItemsArray = json_decode($soItemsArray,true);
// 		$extraPoint = 0;

// 		if (is_numeric(input::post('sellerNo'))) {
// 			$sql = $this->prepare("select point_rank, q1, q2, q3 from PointRank where employee_id = ?");
//    			$sql->execute([input::post('sellerNo')]);
//    			$temp = $sql->fetchAll(PDO::FETCH_ASSOC)[0];
   
//    		if($temp['q1'] == 0){
//    			 $sql = $this->prepare("select ifnull(sum(SOPrinting.total_sales),0) as totalSold from SO 
// 			 						inner join SOPrinting on SOPrinting.so_no = SO.so_no
// 									  inner join Product on Product.product_no = SOPrinting.product_no
// 									  inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
// 									  where employee_id = ? and Product.product_name not like '%ค่า%ส่ง%' and Product.sales_price > 0
// 									  and SO.cancelled = 0 and ((so_date = '2021-07-26' AND so_time >= '13:00:00') OR so_date between '2021-07-27' AND '2021-08-07')");
// 		$sql->execute([input::post('sellerNo')]);
// 		$totalSold = $sql->fetchAll()[0]['totalSold'];

// 		if($temp['point_rank'] == 'novice') {
// 		 $targetedSales = 12000;
// 		}
// 		else if($temp['point_rank'] == 'junior') {
// 		 $targetedSales = 30000;
// 		}
// 		else if($temp['point_rank'] == 'senior') {
// 		 $targetedSales = 60000;
// 		}
// 		else if($temp['point_rank'] == 'semi-pro') {
// 		 $targetedSales = 90000;
// 		}
// 		else if($temp['point_rank'] == 'pro') {
// 		 $targetedSales = 120000;
// 		}
// 		else {
// 		 $targetedSales = 0;
// 		}

// 		if($targetedSales > 0 && $totalSold >= $targetedSales){
// 		 $sql = $this->prepare("update PointRank set q1 = 1 where employee_id = ?");
// 		 $sql->execute([input::post('sellerNo')]);
// 		}
// 	   }

// 	   if($temp['q2'] == 0){
// 		$sql = $this->prepare("select ifnull(sum(SOPrinting.total_sales), 0) as totalSold from SO 
// 							  inner join SOPrinting on SOPrinting.so_no = SO.so_no
// 							  inner join Product on Product.product_no = SOPrinting.product_no
// 							  inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
// 							  where employee_id = ? and Product.product_line = '0' and Product.category_no in ('01','03','04','06')
// 							  and SO.cancelled = 0 and ((so_date = '2021-07-26' AND so_time >= '13:00:00') OR so_date between '2021-07-27' AND '2021-08-07')");
// 		$sql->execute([input::post('sellerNo')]);
// 		$totalSold = $sql->fetchAll()[0]['totalSold'];

// 		if($totalSold >= 150) {
// 		 $sql = $this->prepare("update PointRank set q2 = 1 where employee_id = ?");
// 		 $sql->execute([input::post('sellerNo')]); 
// 		}

// 	   }
			
			
// 			switch (json_decode(session::get('employee_detail'),true)['product_line']) {
// 					//่ผ่านโปรสาย  ทีวี 1 เครื่อง 500 / กล้องติดรถยนต์ 1 เครื่อง 100 
// 				case '1': 
// 					$sql = $this->prepare("select * from 
//                                         (select count(*) as countProLine1 from PointLog where employee_id = ? and remark = 'Week 9 - Line 1(1)' and cancelled = 0) as countProLine1,
// 										(select count(*) as countProLine2 from PointLog where employee_id = ? and remark = 'Week 9 - Line 1(2)' and cancelled = 0) as countProLine2");

// 				  	$sql->execute( [input::post('sellerNo'),input::post('sellerNo')]);
// 				  	$temp = $sql->fetchAll(PDO::FETCH_ASSOC)[0];
					
					
// 					if($temp['countProLine1'] >= 0) {
// 						$sql = $this->prepare("select ifnull(sum(SOPrinting.quantity),0) as countSold from SO 
//                                             inner join SOPrinting on SOPrinting.so_no = SO.so_no
//                                             inner join Product on Product.product_no = SOPrinting.product_no
//                                             inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
//                                             where employee_id = ? and SO.so_no = ? and Product.product_line = '1' 
//                                             and Product.category_no = '02' AND Product.sub_category = 'TV' 
//                                             and SO.cancelled = 0 and ((so_date = '2021-07-26' AND so_time >= '13:00:00') OR so_date between '2021-07-27' AND '2021-07-31')");
// 						$sql->execute([input::post('sellerNo'),$sono]);
// 						$countSold = $sql->fetchAll()[0]['countSold'];
                    
// 						if($countSold >= 1) {
// 							$point = 500*$countSold;
// 							$sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
// 													values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
// 							$sql->execute([input::post('sellerNo'),$point, 'Week 9 - Line 1(1)', $sono]);
// 							//print_r($sql->errorInfo());
// 							echo ' (ผ่านโปรสาย 1(1) ได้รับ '.$point.' พ้อยท์!!!) ';
// 							}
// 					}
					
// 					if($temp['countProLine2'] >= 0) {
// 						$sql = $this->prepare("select ifnull(sum(SOPrinting.quantity),0) as countSold from SO 
//                                             inner join SOPrinting on SOPrinting.so_no = SO.so_no
//                                             inner join Product on Product.product_no = SOPrinting.product_no
//                                             inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
//                                             where employee_id = ? and SO.so_no = ? and Product.product_line = '1' 
//                                             and Product.category_no = '01' AND Product.sub_category = 'Car Security'  
//                                             and SO.cancelled = 0 and ((so_date = '2021-07-26' AND so_time >= '13:00:00') OR so_date between '2021-07-27' AND '2021-07-31')");
// 						$sql->execute([input::post('sellerNo'),$sono]);
// 						$countSold = $sql->fetchAll()[0]['countSold'];
                    
// 						if($countSold >= 1) {
// 							$point = 100*$countSold;
// 							$sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
// 													values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
// 							$sql->execute([input::post('sellerNo'), $point, 'Week 9 - Line 1(2)', $sono]);
// 							//print_r($sql->errorInfo());
// 							echo ' (ผ่านโปรสาย 1(2) ได้รับ '.$point.' พ้อยท์!!!) ';
// 							}
// 					}
					
// 					break;
// 				//ผ่านโปรสาย 2 แอร์ 1 เครื่อง 500 /เครื่องฟอกอากาศ, เครื่องกรองน้ำ 1 เครื่อง 150 
// 				case '2': 
// 						$sql = $this->prepare("select * from 
// 											(select count(*) as countProLine1 from PointLog where employee_id = ? and remark = 'Week 9 - Line 2(1)' and cancelled = 0) as countProLine1,
// 											(select count(*) as countProLine2 from PointLog where employee_id = ? and remark = 'Week 9 - Line 2(2)' and cancelled = 0) as countProLine2");

// 						$sql->execute( [input::post('sellerNo'),input::post('sellerNo')]);
// 						$temp = $sql->fetchAll(PDO::FETCH_ASSOC)[0];


// 						if($temp['countProLine1'] >= 0) {
// 							$sql = $this->prepare("select ifnull(sum(SOPrinting.quantity),0) as countSold from SO 
// 												inner join SOPrinting on SOPrinting.so_no = SO.so_no
// 												inner join Product on Product.product_no = SOPrinting.product_no
// 												inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
// 												where employee_id = ? and SO.so_no = ? and Product.product_line = '2' 
// 												and Product.category_no = '01' 
// 												and SO.cancelled = 0 and ((so_date = '2021-07-26' AND so_time >= '13:00:00') OR so_date between '2021-07-27' AND '2021-07-31')");
// 							$sql->execute([input::post('sellerNo'),$sono]);
// 							$countSold = $sql->fetchAll()[0]['countSold'];

// 							if($countSold >= 1) {
// 								$point = 500*$countSold;
// 								$sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
// 														values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
// 								$sql->execute([input::post('sellerNo'), $point, 'Week 9 - Line 2(1)', $sono]);
// 								//print_r($sql->errorInfo());
// 								echo ' (ผ่านโปรสาย 2(1) ได้รับ '.$point.' พ้อยท์!!!) ';
// 								}
// 						}

// 						if($temp['countProLine2'] >= 0) {
// 							$sql = $this->prepare("select ifnull(sum(SOPrinting.quantity),0) as countSold from SO 
// 												inner join SOPrinting on SOPrinting.so_no = SO.so_no
// 												inner join Product on Product.product_no = SOPrinting.product_no
// 												inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
// 												where employee_id = ?  and SO.so_no = ? and Product.category_no IN ('02','03') AND Product.product_line = '2' AND NOT Product.sub_category IN ('ไส้กรองอากาศ','Catridges')
// 												and SO.cancelled = 0 and ((so_date = '2021-07-26' AND so_time >= '13:00:00') OR so_date between '2021-07-27' AND '2021-07-31')");
// 							$sql->execute([input::post('sellerNo'),$sono]);
// 							$countSold = $sql->fetchAll()[0]['countSold'];

// 							if($countSold >= 1) {
// 								$point = 150*$countSold;
// 								$sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
// 														values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
// 								$sql->execute([input::post('sellerNo'), $point, 'Week 9 - Line 2(2)', $sono]);
// 								//print_r($sql->errorInfo());
// 								echo ' (ผ่านโปรสาย 2(2) ได้รับ '.$point.' พ้อยท์!!!) ';
// 								}
// 						}

// 						break;	
// 					//ผ่านโปรสาย 3 ตู้เย็น/เครื่องซักผ้าง 500 /หม้อทอด/เครื่องดูดฝุ่น แบรนด์ autobot 150 
// 				case '3': 
// 						$sql = $this->prepare("SELECT countProLine1.* , countProLine2.*
// 												FROM (select count(*) as countProLine1 from PointLog where remark = 'Week 9 - Line 3(1)' and cancelled = 0 AND employee_id = ? ) as countProLine1 ,
// 												(select COUNT(PointLog.remark) AS countProLine2, ifnull(SUM(PointLog.point),0)  AS gainedPoint 
// 												FROM PointLog where PointLog.remark = 'Week 9 - Line 3(2)' and PointLog.cancelled = 0 AND PointLog.employee_id = ?) as countProLine2;");

// 						$sql->execute([input::post('sellerNo'),input::post('sellerNo')]);
// 						$temp = $sql->fetchAll(PDO::FETCH_ASSOC)[0];


// 						if($temp['countProLine1'] >= 0) {
// 							$sql = $this->prepare("select ifnull(sum(SOPrinting.quantity),0) as countSold from SO 
// 												inner join SOPrinting on SOPrinting.so_no = SO.so_no
// 												inner join Product on Product.product_no = SOPrinting.product_no
// 												inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
// 												where employee_id = ? and SO.so_no = ?  and Product.product_line = '3' 
// 												and Product.category_no IN ('01','02') 
// 												and SO.cancelled = 0 and ((so_date = '2021-07-26' AND so_time >= '13:00:00') OR so_date between '2021-07-27' AND '2021-07-31')");
// 							$sql->execute([input::post('sellerNo'),$sono]);
// 							$countSold = $sql->fetchAll()[0]['countSold'];

// 							if($countSold >= 1) {
// 								$point = 500*$countSold;
// 								$sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
// 														values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
// 								$sql->execute([input::post('sellerNo'), $point, 'Week 9 - Line 3(1)', $sono]);
// 								//print_r($sql->errorInfo());
// 								echo ' (ผ่านโปรสาย 3(1) ได้รับ '.$point.' พ้อยท์!!!) ';
// 								}
// 						}

// 						if($temp['countProLine2'] >= 0) {
// 							$sql = $this->prepare("select ifnull(sum(SOPrinting.total_sales),0) as totalSold from SO 
// 												inner join SOPrinting on SOPrinting.so_no = SO.so_no
// 												inner join Product on Product.product_no = SOPrinting.product_no
// 												inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
// 												where employee_id = ? and Product.product_line = '3' and (Product.category_no = '03' OR (Product.category_no = '08' AND Product.brand = 'Autobot') OR Product.category_no = '16')
// 												and SO.cancelled = 0 and ((so_date = '2021-07-26' AND so_time >= '13:00:00') OR so_date between '2021-07-27' AND '2021-07-31')
//                                                 AND SO.so_no IN (SELECT SOPrinting.so_no FROM `SOPrinting` LEFT JOIN SO ON SO.so_no= SOPrinting.so_no LEFT JOIN Product ON SOPrinting.product_no = Product.product_no WHERE SO.employee_id= '26064' AND substring(SOPrinting.product_no,1,1)='3' AND (substring(SOPrinting.product_no,6,2)='03' OR (substring(SOPrinting.product_no,6,2)='08' AND Product.brand='Autobot')) AND SO.cancelled='0')");
// 							$sql->execute([input::post('sellerNo')]);
// 							$totalSold = $sql->fetchAll()[0][totalSold];
					
							
// 							if($totalSold >= 2000) {
// 								$sold = $temp['gainedPoint']*2000;
// 								$new_total = $totalSold - $sold;
// 								$multiplier = intdiv($new_total,2000);
// 								$point = 300 * $multiplier;

// 								if ($multiplier >= 1){
// 									$sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
// 														values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
// 									$sql->execute([input::post('sellerNo'), $point, 'Week 9 - Line 3(2)', $sono]);
// 									//print_r($sql->errorInfo());
// 									echo ' (ผ่านโปรสาย 3(2) ได้รับ '.$point.' พ้อยท์!!!) ';
		
// 								} 					
// 							}									
// 						}

// 						break;
					
// 					//ผ่านโปรสาย 4  ขาย iPad 1 เครื่องกับ Accessories อะไรก็ได้ 1 ชิ้น 200 /ขายโทรศัพท์ 1 เครื่อง 100 
// 				case '4': 
// 						$sql = $this->prepare("select * from 
// 											(select count(*) as countProLine1 from PointLog where employee_id = ? and remark = 'Week 9 - Line 4(1)' and cancelled = 0) as countProLine1,
// 											(select count(*) as countProLine2 from PointLog where employee_id = ? and remark = 'Week 9 - Line 4(2)' and cancelled = 0) as countProLine2");

// 						$sql->execute( [input::post('sellerNo'),input::post('sellerNo')]);
// 						$temp = $sql->fetchAll(PDO::FETCH_ASSOC)[0];


						

// 						if($temp['countProLine2'] >= 0) {
// 							$sql = $this->prepare("select ifnull(sum(SOPrinting.quantity), 0) as countSold from SO 
// 												inner join SOPrinting on SOPrinting.so_no = SO.so_no
// 												inner join Product on Product.product_no = SOPrinting.product_no
// 												inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
// 												where employee_id = ? and SO.so_no = ?  and Product.product_line = '4' 
// 												and Product.category_no = '03'
// 												and SO.cancelled = 0 and ((so_date = '2021-07-26' AND so_time >= '13:00:00') OR so_date between '2021-07-27' AND '2021-07-31')");
// 							$sql->execute([input::post('sellerNo'),$sono]);
// 							$countSold = $sql->fetchAll()[0]['countSold'];

// 							if($countSold >= 1) {
// 								$point = 100*$countSold;
// 								$sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
// 														values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
// 								$sql->execute([input::post('sellerNo'), $point, 'Week 9 - Line 4(2)', $sono]);
// 								//print_r($sql->errorInfo());
// 								echo ' (ผ่านโปรสาย 4(2) ได้รับ '.$point.' พ้อยท์!!!) ';
// 								}
// 						}

// 						break;	
					
// 					//ผ่านโปรสาย 5 PeripheralsหรือacsyกับMacOSหรือWindows 300 / Peripheralsหรือacsy 100
// 				case '5' :
// 						$sql = $this->prepare("select * from 
// 											(select count(*) as countProLine1 from PointLog where employee_id = ? and remark = 'Week 9 - Line 5(1)' and cancelled = 0) as countProLine1,
// 											(select count(*) as countProLine2 from PointLog where employee_id = ? and remark = 'Week 9 - Line 5(2)' and cancelled = 0) as countProLine2");

// 						$sql->execute( [input::post('sellerNo'),input::post('sellerNo')]);
// 						$temp = $sql->fetchAll(PDO::FETCH_ASSOC)[0];


						
// 						if($temp['countProLine2'] >= 0) {
// 							$sql = $this->prepare("select ifnull(sum(SOPrinting.quantity), 0) as countSold from SO 
// 												inner join SOPrinting on SOPrinting.so_no = SO.so_no
// 												inner join Product on Product.product_no = SOPrinting.product_no
// 												inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
// 												where employee_id = ? and SO.so_no = ? and Product.product_line = '5' 
// 												and (Product.category_no = '03' or (Product.category_no = '01' and Product.sub_category = 'Accessories')) 
// 												and SO.cancelled = 0 and ((so_date = '2021-07-26' AND so_time >= '13:00:00') OR so_date between '2021-07-27' AND '2021-07-31')");
// 							$sql->execute([input::post('sellerNo'),$sono]);
// 							//echo print_r($sql->errorInfo());
// 							$countSold = $sql->fetchAll()[0]['countSold'];

// 							if($countSold >= 1) {
// 								$point = 100*$countSold;
// 								$sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
// 														values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
// 								$sql->execute([input::post('sellerNo'), $point, 'Week 9 - Line 5(2)', $sono]);
// 								//echo print_r($sql->errorInfo());
// 								echo ' (ผ่านโปรสาย 5(2) ได้รับ '.$point.' พ้อยท์!!!) ';
// 								}
// 						}

// 						break;	
					
// 					// โปรสาย 6 ขาย granobite หรือ ดอยคำ ครบ 500 ได้ 50 / ขายอะไรก็ได้	ครบ 1200 150
// 				case '6': 
// 						$sql = $this->prepare("SELECT countProLine1.* , countProLine2.* 
// 												FROM ((select COUNT(PointLog.remark) AS countProLine1, ifnull(SUM(PointLog.point),0)  AS gainedPoint1
// 												FROM PointLog where PointLog.remark = 'Week 9 - Line 6(1)' and PointLog.cancelled = 0 AND PointLog.employee_id = ?)  as countProLine1 ,
// 												(select COUNT(PointLog.remark) AS countProLine2, ifnull(SUM(PointLog.point),0)  AS gainedPoint2 
// 												FROM PointLog where PointLog.remark = 'Week 9 - Line 6(2)' and PointLog.cancelled = 0 AND PointLog.employee_id = ?) 
// 												AS countProLine2) ;");

// 						$sql->execute( [input::post('sellerNo'),input::post('sellerNo')]);
// 						$temp = $sql->fetchAll(PDO::FETCH_ASSOC)[0];
                        

// 						if($temp['countProLine1'] >= 0) {
// 							$sql = $this->prepare("select ifnull(sum(SOPrinting.total_sales), 0) as totalSold from SO 
// 												inner join SOPrinting on SOPrinting.so_no = SO.so_no
// 												inner join Product on Product.product_no = SOPrinting.product_no
// 												inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
// 												where employee_id = ? and Product.product_line = '6' 
// 												and Product.category_no IN ('01','02','05','08') 
// 												AND Product.supplier_no IN('607','613')
// 												and SO.cancelled = 0 and ((so_date = '2021-07-26' AND so_time >= '13:00:00') OR so_date between '2021-07-27' AND '2021-07-31')");
// 							$sql->execute([input::post('sellerNo')]);
// 							$totalSold = $sql->fetchAll()[0]['totalSold'];
                            

// 							if($totalSold >= 500) {
// 								$sold = $temp['gainedPoint1']*500;
// 								$new_total = $totalSold - $sold;
// 								$multiplier = intdiv($new_total,500);
// 								$point = 50 * $multiplier;

// 								if ($multiplier >= 1){
// 									$sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
// 														values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
// 									$sql->execute([input::post('sellerNo'), $point, 'Week 9 - Line 6(1)', $sono]);
									
// 									echo ' (ผ่านโปรสาย 6(1) ได้รับ '.$point.' พ้อยท์!!!) ';
		
// 								} 					
// 							}									
					
// 						}
					
// 						if($temp['countProLine2'] >= 0) {
// 							$sql = $this->prepare("select ifnull(sum(SOPrinting.total_sales), 0) as totalSold from SO 
// 												inner join SOPrinting on SOPrinting.so_no = SO.so_no
// 												inner join Product on Product.product_no = SOPrinting.product_no
// 												inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
// 												where employee_id = ? and Product.product_line = '6' 
// 												and Product.category_no IN ('01','02','03','04','05','06','08') 
// 												and SO.cancelled = 0 and ((so_date = '2021-07-26' 
// 												AND so_time >= '13:00:00') OR so_date between '2021-07-27' 
// 												AND '2021-07-31')");
// 							$sql->execute([input::post('sellerNo')]);
// 							$totalSold = $sql->fetchAll()[0]['totalSold'];
                            
							
// 							if($totalSold >= 1200) {
// 								$sold = $temp['gainedPoint2']*1200;
// 								$new_total = $totalSold - $sold;
// 								$multiplier = intdiv($new_total,1200);
// 								$point = 150 * $multiplier;

// 								if ($multiplier >= 1){
// 									$sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
// 														values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
// 									$sql->execute([input::post('sellerNo'), $point, 'Week 9 - Line 6(2)', $sono]);
									
// 									echo ' (ผ่านโปรสาย 6(2) ได้รับ '.$point.' พ้อยท์!!!) ';
		
// 								}				
// 							}
// 						}
					
// 						break;		
					
// 					//โปรสาย 7 sport	1เครื่อง	500 /อะไรก็ได้ไม่ใช่ sport  1000 ได้ 100
// 				case '7': 
// 						$sql = $this->prepare("SELECT countProLine1.* , countProLine2.* 
// 												FROM (select count(*) as countProLine1 from PointLog where remark = 'Week 9 - Line 7(1)' and cancelled = 0 AND employee_id = ? ) as countProLine1 ,
// 												(select COUNT(PointLog.remark) AS countProLine2, 
// 												ifnull(SUM(PointLog.point),0) AS gainedPoint 
// 												FROM PointLog where PointLog.remark = 'Week 9 - Line 7(2)' and PointLog.cancelled = 0 AND PointLog.employee_id = ?) 
// 												AS countProLine2 ;");

// 						$sql->execute( [input::post('sellerNo'),input::post('sellerNo')]);
// 						$temp = $sql->fetchAll(PDO::FETCH_ASSOC)[0];


// 						if($temp['countProLine1'] >= 0) {
// 							$sql = $this->prepare("select ifnull(sum(SOPrinting.quantity), 0) as countSold from SO 
// 												inner join SOPrinting on SOPrinting.so_no = SO.so_no
// 												inner join Product on Product.product_no = SOPrinting.product_no
// 												inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
// 												where employee_id = ? and SO.so_no = ? and Product.product_line = '7' 
// 												and Product.category_no = '03'
// 												and SO.cancelled = 0 and ((so_date = '2021-07-26' AND so_time >= '13:00:00') OR so_date between '2021-07-27' AND '2021-07-31')");
// 							$sql->execute([input::post('sellerNo'),$sono]);
// 							$countSold = $sql->fetchAll()[0]['countSold'];

// 							if($countSold >= 1) {
// 								$point = 500*$countSold;
// 								$sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
// 														values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
// 								$sql->execute([input::post('sellerNo'),$point , 'Week 9 - Line 7(1)', $sono]);
// 								//print_r($sql->errorInfo());
// 								echo ' (ผ่านโปรสาย 7(1) ได้รับ '.$point.' พ้อยท์!!!) ';
// 								}
// 						}
// 					// ต้องมาแก้ อะไรก็ได้ที่ไม่ใช่ sport	1000	100 **
// 						if($temp['countProLine2'] >= 0) {
// 							$sql = $this->prepare("select ifnull(sum(SOPrinting.total_sales), 0) as totalSold from SO 
// 												inner join SOPrinting on SOPrinting.so_no = SO.so_no
// 												inner join Product on Product.product_no = SOPrinting.product_no
// 												inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
// 												where employee_id = ? and Product.product_line = '7' 
// 												and Product.category_no not IN ('03','04')
// 												and SO.cancelled = 0 and ((so_date = '2021-07-26' AND so_time >= '13:00:00') OR so_date between '2021-07-27' AND '2021-07-31')");
// 							$sql->execute([input::post('sellerNo')]);
// 							$totalSold = $sql->fetchAll()[0]['totalSold'];

// 							if($totalSold >= 1000) {
// 								$sold = $temp['gainedPoint']*1000;
// 								$new_total = $totalSold - $sold;
// 								$multiplier = intdiv($new_total,1000);
// 								$point = 100 * $multiplier;

// 								if ($multiplier >= 1){
// 									$sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
// 														values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
// 									$sql->execute([input::post('sellerNo'), $point, 'Week 9 - Line 7(2)', $sono]);
// 									//print_r($sql->errorInfo());
// 									echo ' (ผ่านโปรสาย 7(2) ได้รับ '.$point.' พ้อยท์!!!) ';
		
// 								} 					
// 							}									
// 						}

// 						break;	
// 					//โปรสาย 8 ขาย cosmetic อะไรก้ได้ 2000 ได้ 200 / vistra 1 ขวด 150
			
// 				case '8': 
// 						$sql = $this->prepare("SELECT countProLine1.* , countProLine2.* 
// 												FROM (select COUNT(PointLog.remark) AS countProLine1, ifnull(SUM(PointLog.point),0)  AS gainedPoint 
// 												FROM PointLog where PointLog.remark = 'Week 9 - Line 8(1)' and PointLog.cancelled = 0 AND PointLog.employee_id = ?) 
// 												AS countProLine1,
// 												(select count(*) as countProLine2 from PointLog where remark = 'Week 9 - Line 8(2)' and cancelled = 0 AND employee_id = ? ) 
// 												as countProLine2 ;");

// 						$sql->execute( [input::post('sellerNo'),input::post('sellerNo')]);
// 						$temp = $sql->fetchAll(PDO::FETCH_ASSOC)[0];

// 						//ต้องมาแก้
// 						if($temp['countProLine1'] >= 0) {
// 							$sql = $this->prepare("select ifnull(sum(SOPrinting.total_sales), 0) as totalSold from SO 
// 												inner join SOPrinting on SOPrinting.so_no = SO.so_no
// 												inner join Product on Product.product_no = SOPrinting.product_no
// 												inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
// 												where employee_id = ? and Product.product_line = '8' 
// 												and Product.category_no IN ('04','07')
// 												and SO.cancelled = 0 and ((so_date = '2021-07-26' AND so_time >= '13:00:00') OR so_date between '2021-07-27' AND '2021-07-31')");
// 							$sql->execute([input::post('sellerNo')]);
// 							$totalSold = $sql->fetchAll()[0]['totalSold'];

// 							if($totalSold >= 2000) {
// 								$sold = $temp['gainedPoint']*2000;
// 								$new_total = $totalSold - $sold;
// 								$multiplier = intdiv($new_total,2000);
// 								$point = 200 * $multiplier;

// 								if ($multiplier >= 1){
// 									$sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
// 														values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
// 									$sql->execute([input::post('sellerNo'), $point, 'Week 9 - Line 8(1)', $sono]);
// 									//print_r($sql->errorInfo());
// 									echo ' (ผ่านโปรสาย 8(1) ได้รับ '.$point.' พ้อยท์!!!) ';
		
// 								} 					
// 							}									
// 						}


// 						if($temp['countProLine2'] >= 0) {
// 							$sql = $this->prepare("select ifnull(sum(SOPrinting.quantity), 0) as countSold from SO 
// 												inner join SOPrinting on SOPrinting.so_no = SO.so_no
// 												inner join Product on Product.product_no = SOPrinting.product_no
// 												inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
// 												where employee_id = ? and SO.so_no = ? and Product.product_line = '8' 
// 												and Product.category_no = '02'
// 												and SO.cancelled = 0 and ((so_date = '2021-07-26' AND so_time >= '13:00:00') OR so_date between '2021-07-27' AND '2021-07-31')");
// 							$sql->execute([input::post('sellerNo'),$sono]);
// 							$countSold = $sql->fetchAll()[0]['countSold'];

// 							if($countSold >= 1) {
// 								$point = 150*$countSold;
// 								$sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
// 														values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
// 								$sql->execute([input::post('sellerNo'), $point , 'Week 9 - Line 8(2)', $sono]);
// 								//print_r($sql->errorInfo());
// 								echo ' (ผ่านโปรสาย 8(2) ได้รับ '.$point.' พ้อยท์!!!) ';
// 								}
// 						}

// 						break;
					
// 						 //อะไรก็ได้ 1000 บาท ได้ 200 
// 				case '9':
// 						$sql = $this->prepare("SELECT * 
// 												FROM select COUNT(PointLog.remark) AS countProLine2, ifnull(SUM(PointLog.point),0)  AS gainedPoint 
// 												FROM PointLog where PointLog.remark = 'Week 9 - Line 9(2)' and PointLog.cancelled = 0 AND PointLog.employee_id = ?;");

// 						$sql->execute( [input::post('sellerNo'),input::post('sellerNo')]);
// 						$temp = $sql->fetchAll(PDO::FETCH_ASSOC)[0];

						
// 						if($temp['countProLine1'] >= 0) {
// 							$sql = $this->prepare("select ifnull(sum(SOPrinting.total_sales), 0) as totalSold from SO 
// 												inner join SOPrinting on SOPrinting.so_no = SO.so_no
// 												inner join Product on Product.product_no = SOPrinting.product_no
// 												inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
// 												where employee_id = ? and Product.product_line = '9' 
// 												AND not Product.category_no = '04'
// 												and SO.cancelled = 0 and ((so_date = '2021-07-26' AND so_time >= '13:00:00') OR so_date between '2021-07-27' AND '2021-07-31')");
// 							$sql->execute([input::post('sellerNo')]);
// 							$totalSold = $sql->fetchAll()[0]['totalSold'];

// 							if($totalSold >= 1000) {
// 								$sold = $temp['gainedPoint']*1000;
// 								$new_total = $totalSold - $sold;
// 								$multiplier = intdiv($new_total,1000);
// 								$point = 200 * $multiplier;

// 								if ($multiplier >= 1){
// 									$sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
// 														values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
// 									$sql->execute([input::post('sellerNo'), $point, 'Week 9 - Line 9(2)', $sono]);
// 									//print_r($sql->errorInfo());
// 									echo ' (ผ่านโปรสาย 9(2) ได้รับ '.$point.' พ้อยท์!!!) ';
		
// 								} 					
// 							}									
// 						}

// 						break;
					
// 					//โปรสาย 10 อะไรก็ได้ใน Food & beverage	ครบ 300	ได้ 60 / อะไรก็ได้ใน snack & dessert ครบ 200ได้ 40
// 					case '0': 
// 						$sql = $this->prepare("SELECT countProLine1.* , countProLine2.* 
// 												FROM (select COUNT(PointLog.remark) AS countProLine2, ifnull(SUM(PointLog.point),0)  AS gainedPoint1 
// 												FROM PointLog where PointLog.remark = 'Week 9 - Line 10(1)' and PointLog.cancelled = 0 AND PointLog.employee_id = ?) as countProLine1 ,
// 												(select COUNT(PointLog.remark) AS countProLine2, ifnull(SUM(PointLog.point),0)  AS gainedPoint2 
// 												FROM PointLog where PointLog.remark = 'Week 9 - Line 10(2)' and PointLog.cancelled = 0 AND PointLog.employee_id = ?) 
// 												AS countProLine2 ;");

// 						$sql->execute( [input::post('sellerNo'),input::post('sellerNo')]);
// 						$temp = $sql->fetchAll(PDO::FETCH_ASSOC)[0];


// 						if($temp['countProLine1'] >= 0) {
// 							$sql = $this->prepare("select ifnull(sum(SOPrinting.total_sales), 0) as totalSold from SO 
// 												inner join SOPrinting on SOPrinting.so_no = SO.so_no
// 												inner join Product on Product.product_no = SOPrinting.product_no
// 												inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
// 												where employee_id = ? and Product.product_line = '0' 
// 												and Product.category_no IN ('03','06') 
// 												and SO.cancelled = 0 and ((so_date = '2021-07-26' AND so_time >= '13:00:00') OR so_date between '2021-07-27' AND '2021-07-31')");
// 							$sql->execute([input::post('sellerNo')]);
// 							$totalSold = $sql->fetchAll()[0]['totalSold'];

// 							if($totalSold >= 300) {
// 								$sold = $temp['gainedPoint1']*300;
// 								$new_total = $totalSold - $sold;
// 								$multiplier = intdiv($new_total,300);
// 								$point = 60 * $multiplier;

// 								if ($multiplier >= 1){
// 									$sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
// 														values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
// 									$sql->execute([input::post('sellerNo'), $point, 'Week 9 - Line 10(1)', $sono]);
// 									//print_r($sql->errorInfo());
// 									echo ' (ผ่านโปรสาย 10(1) ได้รับ '.$point.' พ้อยท์!!!) ';
		
// 								} 					
// 							}									
					
// 						}
					
// 						if($temp['countProLine2'] >= 0) {
// 							$sql = $this->prepare("select ifnull(sum(SOPrinting.total_sales), 0) as totalSold from SO 
// 												inner join SOPrinting on SOPrinting.so_no = SO.so_no
// 												inner join Product on Product.product_no = SOPrinting.product_no
// 												inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
// 												where employee_id = ? and Product.product_line = '0' 
// 												and Product.category_no IN ('04','06') and SO.cancelled = 0 and ((so_date = '2021-07-26' AND so_time >= '13:00:00') OR so_date between '2021-07-27' AND '2021-07-31');");
// 							$sql->execute([input::post('sellerNo')]);
// 							$totalSold = $sql->fetchAll()[0]['totalSold'];
							
// 							if($totalSold >= 200) {
// 								$sold = $temp['gainedPoint2']*200;
// 								$new_total = $totalSold - $sold;
// 								$multiplier = intdiv($new_total,200);
// 								$point = 40 * $multiplier;

// 								if ($multiplier >= 1){
// 									$sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
// 														values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
// 									$sql->execute([input::post('sellerNo'), $point, 'Week 9 - Line 10(2)', $sono]);
// 									//print_r($sql->errorInfo());
// 									echo ' (ผ่านโปรสาย 10(2) ได้รับ '.$point.' พ้อยท์!!!) ';
		
// 								}				
// 							}
// 						}
					
// 						break;
// 			}
// 		}
// 	}
    
    // private function promotionWeek10($sono){
    //     $soItemsArray = json_decode(input::post('soItems'), true);
		// $soItemsArray = json_decode($soItemsArray,true);
		// $extraPoint = 0;

		// if (is_numeric(input::post('sellerNo'))) {
		// 	$sql = $this->prepare("select point_rank, q1, q2, q3 from PointRank where employee_id = ?");
   	// 		$sql->execute([input::post('sellerNo')]);
   	// 		$temp = $sql->fetchAll(PDO::FETCH_ASSOC)[0];
   
   	// 	if($temp['q1'] == 0){
   	// 		 $sql = $this->prepare("select ifnull(sum(SOPrinting.total_sales),0) as totalSold from SO 
		// 	 						inner join SOPrinting on SOPrinting.so_no = SO.so_no
		// 							  inner join Product on Product.product_no = SOPrinting.product_no
		// 							  inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
		// 							  where employee_id = ? and Product.product_name not like '%ค่า%ส่ง%' and Product.sales_price > 0
		// 							  and SO.cancelled = 0 and ((so_date = '2021-07-26' AND so_time >= '13:00:00') OR so_date between '2021-07-27' AND '2021-08-07')");
		// $sql->execute([input::post('sellerNo')]);
		// $totalSold = $sql->fetchAll()[0]['totalSold'];

		// if($temp['point_rank'] == 'novice') {
		//  $targetedSales = 12000;
		// }
		// else if($temp['point_rank'] == 'junior') {
		//  $targetedSales = 30000;
		// }
		// else if($temp['point_rank'] == 'senior') {
		//  $targetedSales = 60000;
		// }
		// else if($temp['point_rank'] == 'semi-pro') {
		//  $targetedSales = 90000;
		// }
		// else if($temp['point_rank'] == 'pro') {
		//  $targetedSales = 120000;
		// }
		// else {
		//  $targetedSales = 0;
		// }

		// if($targetedSales > 0 && $totalSold >= $targetedSales){
		//  $sql = $this->prepare("update PointRank set q1 = 1 where employee_id = ?");
		//  $sql->execute([input::post('sellerNo')]);
		// }
	  //  }

	  //  if($temp['q2'] == 0){
		// $sql = $this->prepare("select ifnull(sum(SOPrinting.total_sales), 0) as totalSold from SO 
		// 					  inner join SOPrinting on SOPrinting.so_no = SO.so_no
		// 					  inner join Product on Product.product_no = SOPrinting.product_no
		// 					  inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
		// 					  where employee_id = ? and Product.product_line = '0' and Product.category_no in ('01','03','04','06')
		// 					  and SO.cancelled = 0 and ((so_date = '2021-07-26' AND so_time >= '13:00:00') OR so_date between '2021-07-27' AND '2021-08-07')");
		// $sql->execute([input::post('sellerNo')]);
		// $totalSold = $sql->fetchAll()[0]['totalSold'];

		// if($totalSold >= 150) {
		//  $sql = $this->prepare("update PointRank set q2 = 1 where employee_id = ?");
		//  $sql->execute([input::post('sellerNo')]); 
		// }

	  //  }
    //  }
    //     switch (json_decode(session::get('employee_detail'),true)['product_line']){
    //         case '1': 
		// 			$sql = $this->prepare("select * from 
    //                                     (select count(*) as countProLine1 from PointLog where employee_id = ? and remark = 'Week 10 - Line 1(1)' and cancelled = 0) as countProLine1,
		// 								(select count(*) as countProLine2 from PointLog where employee_id = ? and remark = 'Week 10 - Line 1(2)' and cancelled = 0) as countProLine2");

		// 		  	$sql->execute( [input::post('sellerNo'),input::post('sellerNo')]);
		// 		  	$temp = $sql->fetchAll(PDO::FETCH_ASSOC)[0];
					
					
		// 			if($temp['countProLine1'] >= 0) {
		// 				$sql = $this->prepare("select ifnull(sum(SOPrinting.quantity),0) as countSold from SO 
    //                                         inner join SOPrinting on SOPrinting.so_no = SO.so_no
    //                                         inner join Product on Product.product_no = SOPrinting.product_no
    //                                         inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
    //                                         where employee_id = ? and SO.so_no = ? and Product.product_line = '1' 
    //                                         and Product.category_no = '02' AND Product.sub_category = 'TV' 
    //                                         and SO.cancelled = 0 and ((so_date = '2021-08-02' AND so_time >= '13:00:00') OR so_date between '2021-08-03' AND '2021-08-07')");
		// 				$sql->execute([input::post('sellerNo'),$sono]);
		// 				$countSold = $sql->fetchAll()[0]['countSold'];
                    
		// 				if($countSold >= 1) {
		// 					$point = 500*$countSold;
		// 					$sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
		// 											values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
		// 					$sql->execute([input::post('sellerNo'),$point, 'Week 10 - Line 1(1)', $sono]);
		// 					//print_r($sql->errorInfo());
		// 					echo ' (ผ่านโปรสาย 1(1) ได้รับ ' . $point . ' พ้อยท์!!!) ';

		// 					}
		// 			}
					
		// 			if($temp['countProLine2'] >= 0) {
		// 				$sql = $this->prepare("select ifnull(sum(SOPrinting.quantity),0) as countSold from SO 
    //                                         inner join SOPrinting on SOPrinting.so_no = SO.so_no
    //                                         inner join Product on Product.product_no = SOPrinting.product_no
    //                                         inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
    //                                         where employee_id = ? and SO.so_no = ? and Product.product_line = '1' 
    //                                         and ((Product.category_no = '01' AND Product.sub_category = 'Car Security')  
    //                                         OR Product.product_no = '1-O1-02-109-014'
    //                                         and SO.cancelled = 0 
    //                                         and ((so_date = '2021-08-02' AND so_time >= '13:00:00') OR so_date between '2021-08-03' AND '2021-08-07'))");
		// 				$sql->execute([input::post('sellerNo'),$sono]);
		// 				$countSold = $sql->fetchAll()[0]['countSold'];
                    
		// 				if($countSold >= 1) {
		// 					$point = 100*$countSold;
		// 					$sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
		// 											values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
		// 					$sql->execute([input::post('sellerNo'), $point, 'Week 10 - Line 1(2)', $sono]);
		// 					//print_r($sql->errorInfo());
		// 					echo ' (ผ่านโปรสาย 1(2) ได้รับ ' . $point . ' พ้อยท์!!!) ';

		// 					}
		// 			}
					
		// 	break;

    //         case '2': 
                    
    //             // proline(1)
    //             $sql = $this->prepare("select ifnull(sum(SOPrinting.quantity),0) as countSold from SO 
    //                                 inner join SOPrinting on SOPrinting.so_no = SO.so_no
    //                                 inner join Product on Product.product_no = SOPrinting.product_no
    //                                 inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
    //                                 where employee_id = ? and SO.so_no = ? and Product.product_line = '2' 
    //                                 and Product.category_no = '01' 
    //                                 and SO.cancelled = 0 and ((so_date = '2021-08-02' AND so_time >= '13:00:00') OR so_date between '2021-08-03' AND '2021-08-07')");
    //             $sql->execute([input::post('sellerNo'),$sono]);
    //             $countSold = $sql->fetchAll()[0]['countSold'];

    //             if($countSold >= 1) {
    //                 $point = 500*$countSold;
    //                 $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
    //                                         values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
    //                 $sql->execute([input::post('sellerNo'), $point, 'Week 10 - Line 2(1)', $sono]);
    //                 //print_r($sql->errorInfo());
    //                 echo ' (ผ่านโปรสาย 2(1) ได้รับ '.$point.' พ้อยท์!!!) ';
    //             }
                

    //              // proline(2)
    //             $sql = $this->prepare("select ifnull(sum(SOPrinting.quantity),0) as countSold from SO 
    //                                 inner join SOPrinting on SOPrinting.so_no = SO.so_no
    //                                 inner join Product on Product.product_no = SOPrinting.product_no
    //                                 inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
    //                                 where employee_id = ?  and SO.so_no = ? and Product.category_no IN ('02','03') AND Product.product_line = '2' AND NOT Product.sub_category IN ('ไส้กรองอากาศ','Catridges')
    //                                 and SO.cancelled = 0 and ((so_date = '2021-08-02' AND so_time >= '13:00:00') OR so_date between '2021-08-03' AND '2021-08-07')");
    //             $sql->execute([input::post('sellerNo'),$sono]);
    //             $countSold = $sql->fetchAll()[0]['countSold'];

    //             if($countSold >= 1) {
    //                 $point = 150*$countSold;
    //                 $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
    //                                         values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
    //                 $sql->execute([input::post('sellerNo'), $point, 'Week 10 - Line 2(2)', $sono]);
    //                 //print_r($sql->errorInfo());
    //                 echo ' (ผ่านโปรสาย 2(2) ได้รับ '.$point.' พ้อยท์!!!) ';
    //             }
                    

    //         break; 

    //         case '3':
    //             $sql = $this->prepare("SELECT countProLine1.* , countProLine2.*
		// 										FROM (select count(*) as countProLine1 from PointLog where remark = 'Week 10 - Line 3(1)' and cancelled = 0 AND employee_id = ? ) as countProLine1 ,
		// 										(select COUNT(PointLog.remark) AS countProLine2, ifnull(SUM(PointLog.point),0)  AS gainedPoint
		// 										FROM PointLog where PointLog.remark = 'Week 10 - Line 3(2)' and PointLog.cancelled = 0 AND PointLog.employee_id = ?) as countProLine2;");

    //             $sql->execute([input::post('sellerNo'), input::post('sellerNo')]);
    //             $temp = $sql->fetchAll(PDO::FETCH_ASSOC)[0];

    //             // proline(1)
    //             $sql = $this->prepare("select ifnull(sum(SOPrinting.quantity),0) as countSold from SO
    //                                                         inner join SOPrinting on SOPrinting.so_no = SO.so_no
    //                                                         inner join Product on Product.product_no = SOPrinting.product_no
    //                                                         inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
    //                                                         where employee_id = ? and SO.so_no = ?  and Product.product_line = '3'
    //                                                         and Product.category_no IN ('01','02')
    //                                                         and SO.cancelled = 0 and ((so_date = '2021-08-02' AND so_time >= '13:00:00') OR so_date between '2021-08-03' AND '2021-08-07')");
    //             $sql->execute([input::post('sellerNo'), $sono]);
    //             $countSold = $sql->fetchAll()[0]['countSold'];

    //             if ($countSold >= 1) {
    //                 $point = 500 * $countSold;
    //                 $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled)
    //                                                                 values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
    //                 $sql->execute([input::post('sellerNo'), $point, 'Week 10 - Line 3(1)', $sono]);
    //                 //print_r($sql->errorInfo());
    //                 echo ' (ผ่านโปรสาย 3(1) ได้รับ ' . $point . ' พ้อยท์!!!) ';
    //             }
                
    //             //proline(2)
    //             if($temp['countProLine2'] >= 0) {
    //                 $sql = $this->prepare("select ifnull(sum(SOPrinting.total_sales),0) as totalSold from SO 
    //                                     inner join SOPrinting on SOPrinting.so_no = SO.so_no
    //                                     inner join Product on Product.product_no = SOPrinting.product_no
    //                                     inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
    //                                     where employee_id = ? and Product.product_line = '3' and (Product.category_no = '03' OR (Product.category_no = '08' AND Product.brand = 'Autobot') OR Product.category_no = '16')
    //                                     and SO.cancelled = 0 and ((so_date = '2021-08-02' AND so_time >= '13:00:00') OR so_date between '2021-08-03' AND '2021-08-07')
    //                                     AND SO.so_no IN 
    //                                     (SELECT SOPrinting.so_no FROM `SOPrinting` LEFT JOIN SO ON SO.so_no= SOPrinting.so_no 
    //                                     LEFT JOIN Product ON SOPrinting.product_no = Product.product_no WHERE SO.employee_id= ? 
    //                                     AND substring(SOPrinting.product_no,1,1)='3' AND (substring(SOPrinting.product_no,6,2)='03' OR (substring(SOPrinting.product_no,6,2)='08' AND Product.brand='Autobot')) AND SO.cancelled='0')");

    //                 $sql->execute([input::post('sellerNo'), input::post('sellerNo')]);
    //                 $totalSold = $sql->fetchAll()[0]['totalSold'];
            
                    
    //                 if($totalSold >= 2000) {
    //                     $sold = $temp['gainedPoint']/300*2000;
    //                     $new_total = $totalSold - $sold;
    //                     $multiplier = intdiv($new_total,2000);
    //                     $point = 300 * $multiplier;

    //                     if ($multiplier >= 1){
    //                         $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
    //                                             values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
    //                         $sql->execute([input::post('sellerNo'), $point, 'Week 10 - Line 3(2)', $sono]);
                         
    //                         echo ' (ผ่านโปรสาย 3(2) ได้รับ '.$point.' พ้อยท์!!!) ';

    //                     } 					
    //                 }									
    //             }

    //         break;
            
    //         case '4':

    //             // proline(1)
    //             $sql = $this->prepare("select ifnull(sum(SOPrinting.quantity),0) as countSold from SO
    //                                                     inner join SOPrinting on SOPrinting.so_no = SO.so_no
    //                                                     inner join Product on Product.product_no = SOPrinting.product_no
    //                                                     inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
    //                                                     where employee_id = ? and SO.so_no = ? and Product.product_line = '4'
    //                                                     and Product.category_no = '01'
    //                                                     and SO.cancelled = 0 and ((so_date = '2021-08-02' AND so_time >= '13:00:00') OR so_date between '2021-08-03' AND '2021-08-07')");
    //             $sql->execute([input::post('sellerNo'), $sono]);
    //             $countSold = $sql->fetchAll()[0]['countSold'];

    //             if ($countSold >= 1) {
    //                 $point = 250 * $countSold;
    //                 $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled)
    //                                                             values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
    //                 $sql->execute([input::post('sellerNo'), $point, 'Week 10 - Line 4(1)', $sono]);
    //                 //print_r($sql->errorInfo());
    //                 echo ' (ผ่านโปรสาย 4(1) ได้รับ ' . $point . ' พ้อยท์!!!) ';
    //             }
            
    //             // proline(2)
    //             $sql = $this->prepare("select ifnull(sum(SOPrinting.quantity),0) as countSold from SO
    //                                                     inner join SOPrinting on SOPrinting.so_no = SO.so_no
    //                                                     inner join Product on Product.product_no = SOPrinting.product_no
    //                                                     inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
    //                                                     where employee_id = ? and SO.so_no = ? and Product.product_line = '4'
    //                                                     and Product.category_no = '02'
    //                                                     and SO.cancelled = 0 and ((so_date = '2021-08-02' AND so_time >= '13:00:00') OR so_date between '2021-08-03' AND '2021-08-07')");

    //             $sql->execute([input::post('sellerNo'), $sono]);
    //             $countSold = $sql->fetchAll()[0]['countSold'];

    //             if ($countSold >= 1) {
    //                 $point = 100 * $countSold;
    //                 $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled)
    //                                                             values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
    //                 $sql->execute([input::post('sellerNo'), $point, 'Week 10 - Line 4(2)', $sono]);
    //                 //print_r($sql->errorInfo());
    //                 echo ' (ผ่านโปรสาย 4(2) ได้รับ ' . $point . ' พ้อยท์!!!) ';
    //             }
  
    //         break;

    //         case '5':
                
    //             // proline(1)
    //             $sql = $this->prepare("select ifnull(sum(SOPrinting.quantity),0) as countSold from SO
    //                                                     inner join SOPrinting on SOPrinting.so_no = SO.so_no
    //                                                     inner join Product on Product.product_no = SOPrinting.product_no
    //                                                     inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
    //                                                     where employee_id = ? and SO.so_no = ? and Product.product_line = '5'
    //                                                     and Product.category_no = '02'
    //                                                     and SO.cancelled = 0 and ((so_date = '2021-08-02' AND so_time >= '13:00:00') OR so_date between '2021-08-03' AND '2021-08-07')");

    //             $sql->execute([input::post('sellerNo'), $sono]);
    //             $countSold = $sql->fetchAll()[0]['countSold'];

    //             if ($countSold >= 1) {
    //                 $point = 400 * $countSold;
    //                 $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled)
    //                                                                             values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
    //                 $sql->execute([input::post('sellerNo'), $point, 'Week 10 - Line 5(1)', $sono]);
    //                 //print_r($sql->errorInfo());
    //                 echo ' (ผ่านโปรสาย 5(1) ได้รับ ' . $point . ' พ้อยท์!!!) ';
    //             }

    //             // proline(2)
    //             $sql = $this->prepare("select ifnull(sum(SOPrinting.quantity), 0) as countSold from SO
		// 										inner join SOPrinting on SOPrinting.so_no = SO.so_no
		// 										inner join Product on Product.product_no = SOPrinting.product_no
		// 										inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
		// 										where employee_id = ? and SO.so_no = ? and Product.product_line = '5'
		// 										and (Product.category_no = '03' or (Product.category_no = '01' and Product.sub_category = 'Accessories'))
		// 										and SO.cancelled = 0 and ((so_date = '2021-08-02' AND so_time >= '13:00:00') OR so_date between '2021-08-03' AND '2021-08-07')");
    //             $sql->execute([input::post('sellerNo'), $sono]);
    //             //echo print_r($sql->errorInfo());
    //             $countSold = $sql->fetchAll()[0]['countSold'];

    //             if ($countSold >= 1) {
    //                 $point = 100 * $countSold;
    //                 $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled)
    //                                                                     values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
    //                 $sql->execute([input::post('sellerNo'), $point, 'Week 10 - Line 5(2)', $sono]);
    //                 //echo print_r($sql->errorInfo());
    //                 echo ' (ผ่านโปรสาย 5(2) ได้รับ ' . $point . ' พ้อยท์!!!) ';
    //             }


    //         break;  

    //         case '6': 
		// 				$sql = $this->prepare("SELECT countProLine1.* , countProLine2.* 
		// 										FROM (select COUNT(PointLog.remark) AS countProLine1, ifnull(SUM(PointLog.point),0)  AS gainedPoint1
		// 										FROM PointLog where PointLog.remark = 'Week 10 - Line 6(1)' and PointLog.cancelled = 0 AND PointLog.employee_id = ?)  as countProLine1 ,
		// 										(select COUNT(PointLog.remark) AS countProLine2, ifnull(SUM(PointLog.point),0)  AS gainedPoint2 
		// 										FROM PointLog where PointLog.remark = 'Week 10 - Line 6(2)' and PointLog.cancelled = 0 AND PointLog.employee_id = ?) 
		// 										AS countProLine2 ;");

		// 				$sql->execute( [input::post('sellerNo'),input::post('sellerNo')]);
		// 				$temp = $sql->fetchAll(PDO::FETCH_ASSOC)[0];
 
		// 				if($temp['countProLine1'] >= 0) {
		// 					$sql = $this->prepare("select ifnull(sum(SOPrinting.total_sales),0) as totalSold from SO 
		// 										inner join SOPrinting on SOPrinting.so_no = SO.so_no
		// 										inner join Product on Product.product_no = SOPrinting.product_no
		// 										inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
		// 										where employee_id = ? and Product.product_line = '6' and Product.category_no IN ('01','02','05','08') AND Product.supplier_no IN ('607','613')
		// 										and SO.cancelled = 0 and ((so_date = '2021-08-02' AND so_time >= '13:00:00') OR so_date between '2021-08-03' AND '2021-08-07')
    //                                             AND SO.so_no IN 
    //                                             (SELECT SOPrinting.so_no FROM `SOPrinting` LEFT JOIN SO ON SO.so_no= SOPrinting.so_no 
    //                                             LEFT JOIN Product ON SOPrinting.product_no = Product.product_no WHERE SO.employee_id= ? 
    //                                             AND substring(SOPrinting.product_no,1,1)='6' 
    //                                             AND substring(SOPrinting.product_no,6,2) IN ('01','02','05') 
    //                                             AND Product.supplier_no IN ('607','613')
    //                                             AND SO.cancelled='0')");
		// 					$sql->execute([input::post('sellerNo'),input::post('sellerNo')]);
		// 					$totalSold = $sql->fetchAll()[0]['totalSold'];
                            

		// 					if($totalSold >= 500) {
		// 						$count = $temp['gainedPoint1']/100;
		// 						$sold = $count*500;
		// 						$new_total = $totalSold - $sold;
		// 						$multiplier = intdiv($new_total,500);
		// 						$point = 100 * $multiplier;

		// 						if ($multiplier >= 1){
		// 							$sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
		// 												values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
		// 							$sql->execute([input::post('sellerNo'), $point, 'Week 10 - Line 6(1)', $sono]);
									
		// 							echo ' (ผ่านโปรสาย 6(1) ได้รับ ' . $point . ' พ้อยท์!!!) ';

		
		// 						} 					
		// 					}									
					
		// 				}

		// 				if($temp['countProLine2'] >= 0) {
		// 					$sql = $this->prepare("select ifnull(sum(SOPrinting.total_sales),0) as totalSold from SO 
		// 										inner join SOPrinting on SOPrinting.so_no = SO.so_no
		// 										inner join Product on Product.product_no = SOPrinting.product_no
		// 										inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
		// 										where employee_id = ? and Product.product_line = '6' 
    //                                             and Product.category_no IN ('01','02','03','04','05','06','08') 
    //                                             and SO.cancelled = 0 
    //                                             and ((so_date = '2021-08-02' AND so_time >= '13:00:00') OR so_date between '2021-08-03' AND '2021-08-07')
    //                                             AND SO.so_no IN 
    //                                             (SELECT SOPrinting.so_no FROM `SOPrinting` LEFT JOIN SO ON SO.so_no= SOPrinting.so_no 
    //                                             LEFT JOIN Product ON SOPrinting.product_no = Product.product_no WHERE SO.employee_id= ? 
    //                                             AND substring(SOPrinting.product_no,1,1)='6' 
    //                                             AND substring(SOPrinting.product_no,6,2) IN ('01','02','03','04','05','06')  
    //                                             AND SO.cancelled='0')");
    
		// 					$sql->execute([input::post('sellerNo'),input::post('sellerNo')]);
		// 					$totalSold = $sql->fetchAll()[0]['totalSold'];
                            
							
		// 					if($totalSold >= 1000) {
		// 						$count = $temp['gainedPoint2']/200;
		// 						$sold = $count*1000;
		// 						$new_total = $totalSold - $sold;
		// 						$multiplier = intdiv($new_total,1000);
		// 						$point = 200 * $multiplier;

		// 						if ($multiplier >= 1){
		// 							$sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
		// 												values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
		// 							$sql->execute([input::post('sellerNo'), $point, 'Week 10 - Line 6(2)', $sono]);
									
		// 							echo ' (ผ่านโปรสาย 6(2) ได้รับ ' . $point . ' พ้อยท์!!!) ';

		
		// 						}				
		// 					}
		// 				}
					
		// 	break;

    //         case '7': 
    //             $sql = $this->prepare("SELECT countProLine1.* , countProLine2.* 
    //                                     FROM (select count(*) as countProLine1 from PointLog where remark = 'Week 10 - Line 7(1)' and cancelled = 0 AND employee_id = ? ) as countProLine1 ,
    //                                     (select COUNT(PointLog.remark) AS countProLine2, 
    //                                     ifnull(SUM(PointLog.point),0) AS gainedPoint 
    //                                     FROM PointLog where PointLog.remark = 'Week 10 - Line 7(2)' and PointLog.cancelled = 0 AND PointLog.employee_id = ?) 
    //                                     AS countProLine2 ;");

    //             $sql->execute( [input::post('sellerNo'),input::post('sellerNo')]);
    //             $temp = $sql->fetchAll(PDO::FETCH_ASSOC)[0];


    //             if($temp['countProLine1'] >= 0) {
    //                 $sql = $this->prepare("select ifnull(sum(SOPrinting.quantity), 0) as countSold from SO 
    //                                     inner join SOPrinting on SOPrinting.so_no = SO.so_no
    //                                     inner join Product on Product.product_no = SOPrinting.product_no
    //                                     inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
    //                                     where employee_id = ? and SO.so_no = ? and Product.product_line = '7' 
    //                                     and Product.category_no = '03'
    //                                     and SO.cancelled = 0 and ((so_date = '2021-08-02' AND so_time >= '13:00:00') OR so_date between '2021-08-03' AND '2021-08-07')");
    //                 $sql->execute([input::post('sellerNo'),$sono]);
    //                 $countSold = $sql->fetchAll()[0]['countSold'];

    //                 if($countSold >= 1) {
    //                     $point = 600*$countSold;
    //                     $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
    //                                             values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
    //                     $sql->execute([input::post('sellerNo'),$point , 'Week 10 - Line 7(1)', $sono]);
    //                     //print_r($sql->errorInfo());
    //                     echo ' (ผ่านโปรสาย 7(1) ได้รับ ' . $point . ' พ้อยท์!!!) ';

    //                     }
    //             }
            
    //             if($temp['countProLine2'] >= 0) {
    //                 $sql = $this->prepare("select ifnull(sum(SOPrinting.total_sales),0) as totalSold from SO 
    //                                     inner join SOPrinting on SOPrinting.so_no = SO.so_no
    //                                     inner join Product on Product.product_no = SOPrinting.product_no
    //                                     inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
    //                                     where employee_id = ? and Product.product_line = '7' 
    //                                     and Product.category_no IN ('01','02','05','06','07','08','09','10') 
    //                                     and SO.cancelled = 0 and ((so_date = '2021-08-02' AND so_time >= '13:00:00') OR so_date between '2021-08-03' AND '2021-08-07')
    //                                     AND SO.so_no IN 
    //                                     (SELECT SOPrinting.so_no FROM `SOPrinting` LEFT JOIN SO ON SO.so_no= SOPrinting.so_no 
    //                                     LEFT JOIN Product ON SOPrinting.product_no = Product.product_no WHERE SO.employee_id= ? 
    //                                     AND substring(SOPrinting.product_no,1,1)='7' 
    //                                     AND (substring(SOPrinting.product_no,6,2) IN ('01','02','06','07','08','09','10')
    //                                     AND SO.cancelled='0')");
    //                 $sql->execute([input::post('sellerNo'),input::post('sellerNo')]);
    //                 $totalSold = $sql->fetchAll()[0]['totalSold'];

    //                 if($totalSold >= 1000) {
    //                     $count = $temp['gainedPoint']/100;
    //                     $sold = $count*1000;
    //                     $new_total = $totalSold - $sold;
    //                     $multiplier = intdiv($new_total,1000);
    //                     $point = 100 * $multiplier;

    //                     if ($multiplier >= 1){
    //                         $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
    //                                             values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
    //                         $sql->execute([input::post('sellerNo'), $point, 'Week 10 - Line 7(2)', $sono]);
    //                         //print_r($sql->errorInfo());
    //                         echo ' (ผ่านโปรสาย 7(2) ได้รับ ' . $point . ' พ้อยท์!!!) ';


    //                     } 					
    //                 }									
    //             }

    //         break;
            
    //         case '8':     
    //             $sql = $this->prepare("SELECT countProLine1.* , countProLine2.* 
    //                                     FROM (select COUNT(PointLog.remark) AS countProLine1, ifnull(SUM(PointLog.point),0)  AS gainedPoint 
    //                                     FROM PointLog where PointLog.remark = 'Week 10 - Line 8(1)' and PointLog.cancelled = 0 AND PointLog.employee_id = ?) 
    //                                     AS countProLine1,
    //                                     (select count(*) as countProLine2 from PointLog where remark = 'Week 10 - Line 8(2)' and cancelled = 0 AND employee_id = ? ) 
    //                                     as countProLine2 ;");

    //             $sql->execute( [input::post('sellerNo'),input::post('sellerNo')]);
    //             $temp = $sql->fetchAll(PDO::FETCH_ASSOC)[0];



    //             //µÈÕß¡“·°È
    //             if($temp['countProLine1'] >= 0) {
    //                 $sql = $this->prepare("select ifnull(sum(SOPrinting.total_sales),0) as totalSold from SO 
    //                                     inner join SOPrinting on SOPrinting.so_no = SO.so_no
    //                                     inner join Product on Product.product_no = SOPrinting.product_no
    //                                     inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
    //                                     where employee_id = ? and Product.product_line = '8' 
    //                                     and Product.category_no IN ('03','07') and SO.cancelled = 0 
    //                                     and ((so_date = '2021-08-02' AND so_time >= '13:00:00') OR so_date between '2021-08-03' AND '2021-08-07')
    //                                     AND SO.so_no IN 
    //                                     (SELECT SOPrinting.so_no FROM `SOPrinting` LEFT JOIN SO ON SO.so_no= SOPrinting.so_no 
    //                                     LEFT JOIN Product ON SOPrinting.product_no = Product.product_no WHERE SO.employee_id= ? 
    //                                     AND substring(SOPrinting.product_no,1,1)='8' AND substring(SOPrinting.product_no,6,2)='03'
    //                                     AND SO.cancelled='0')");
    //                 $sql->execute([input::post('sellerNo'),input::post('sellerNo')]);
    //                 $totalSold = $sql->fetchAll()[0]['totalSold'];

    //                 if($totalSold >= 500) {
    //                     $sold = $temp['gainedPoint']/200*500;
    //                     $new_total = $totalSold - $sold;
    //                     $multiplier = intdiv($new_total,500);
    //                     $point = 200 * $multiplier;

    //                     if ($multiplier >= 1){
    //                         $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
    //                                             values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
    //                         $sql->execute([input::post('sellerNo'), $point, 'Week 10 - Line 8(1)', $sono]);
    //                         //print_r($sql->errorInfo());
    //                         echo ' (ผ่านโปรสาย 8(1) ได้รับ ' . $point . ' พ้อยท์!!!) ';


    //                     } 					
    //                 }									
    //             }


    //             if($temp['countProLine2'] >= 0) {
    //                 $sql = $this->prepare("select ifnull(sum(SOPrinting.quantity), 0) as countSold from SO 
    //                                     inner join SOPrinting on SOPrinting.so_no = SO.so_no
    //                                     inner join Product on Product.product_no = SOPrinting.product_no
    //                                     inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
    //                                     where employee_id = ? and SO.so_no = ? and Product.product_line = '8' 
    //                                     and (Product.category_no = '02' 
    //                                     OR (Product.category_no = '04' AND Product.supplier_no = '811'))
    //                                     and SO.cancelled = 0 and ((so_date = '2021-08-02' AND so_time >= '13:00:00') OR so_date between '2021-08-03' AND '2021-08-07')");
    //                 $sql->execute([input::post('sellerNo'),$sono]);
    //                 $countSold = $sql->fetchAll()[0]['countSold'];

    //                 if($countSold >= 1) {
    //                     $point = 150*$countSold;
    //                     $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
    //                                             values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
    //                     $sql->execute([input::post('sellerNo'), $point , 'Week 10 - Line 8(2)', $sono]);
    //                     //print_r($sql->errorInfo());
    //                     echo ' (ผ่านโปรสาย 8(2) ได้รับ ' . $point . ' พ้อยท์!!!) ';

    //                     }
    //             }

		// 	break;

    //         case '9':
    //             $sql = $this->prepare("SELECT countProLine1.* , countProLine2.*
		// 										FROM (select count(*) as countProLine1 from PointLog where remark = 'Week 10 - Line 9(1)' and cancelled = 0 AND employee_id = ? ) as countProLine1 ,
		// 										(select COUNT(PointLog.remark) AS countProLine2, ifnull(SUM(PointLog.point),0)  AS gainedPoint
		// 										FROM PointLog where PointLog.remark = 'Week 10 - Line 9(2)' and PointLog.cancelled = 0 AND PointLog.employee_id = ?) as countProLine2;");

    //             $sql->execute([input::post('sellerNo'), input::post('sellerNo')]);
    //             $temp = $sql->fetchAll(PDO::FETCH_ASSOC)[0];

    //             // proline(1)
    //             $sql = $this->prepare("select ifnull(sum(SOPrinting.quantity),0) as countSold from SO
    //                                                     inner join SOPrinting on SOPrinting.so_no = SO.so_no
    //                                                     inner join Product on Product.product_no = SOPrinting.product_no
    //                                                     inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
    //                                                     where employee_id = ? and SO.so_no = ? and Product.product_line = '9'
    //                                                     and Product.category_no = '02'
    //                                                     and SO.cancelled = 0 and ((so_date = '2021-08-02' AND so_time >= '13:00:00') OR so_date between '2021-08-03' AND '2021-08-07')");

    //             $sql->execute([input::post('sellerNo'), $sono]);
    //             $countSold = $sql->fetchAll()[0]['countSold'];

    //             if ($countSold >= 1) {
    //                 $point = 70 * $countSold;
    //                 $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled)
    //                                                                                             values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
    //                 $sql->execute([input::post('sellerNo'), $point, 'Week 10 - Line 9(1)', $sono]);
    //                 //print_r($sql->errorInfo());
    //                 echo ' (ผ่านโปรสาย 9(1) ได้รับ ' . $point . ' พ้อยท์!!!) ';
    //             }

    //             // proline(2)
    //             if ($temp['countProLine2'] >= 0) {
    //                 $sql = $this->prepare("select ifnull(sum(SOPrinting.total_sales),0) as totalSold from SO
    //                                                     inner join SOPrinting on SOPrinting.so_no = SO.so_no
    //                                                     inner join Product on Product.product_no = SOPrinting.product_no
    //                                                     inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
    //                                                     where employee_id = ? and Product.product_line = '9' and (Product.category_no = '02' OR Product.category_no = '03')
    //                                                     and SO.cancelled = 0 and ((so_date = '2021-08-02' AND so_time >= '13:00:00') OR so_date between '2021-08-03' AND '2021-08-07')
    //                                                     AND SO.so_no IN 
    //                                                     (SELECT SOPrinting.so_no FROM `SOPrinting` LEFT JOIN SO ON SO.so_no= SOPrinting.so_no 
    //                                                     LEFT JOIN Product ON SOPrinting.product_no = Product.product_no WHERE SO.employee_id= ? 
    //                                                     AND substring(SOPrinting.product_no,1,1)='9' AND substring(SOPrinting.product_no,6,2)='02' AND SO.cancelled='0')");
    //                 $sql->execute([input::post('sellerNo'),input::post('sellerNo')]);
    //                 $totalSold = $sql->fetchAll()[0]['totalSold'];

    //                 if ($totalSold >= 1000) {
    //                     $sold = $temp['gainedPoint'] / 200 * 1000;
    //                     $new_total = $totalSold - $sold;
    //                     $multiplier = intdiv($new_total, 1000);
    //                     $point = 200 * $multiplier;

    //                     if ($multiplier >= 1) {
    //                         $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled)
    //                                                             values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
    //                         $sql->execute([input::post('sellerNo'), $point, 'Week 10 - Line 9(2)', $sono]);

    //                         echo ' (ผ่านโปรสาย 9(2) ได้รับ ' . $point . ' พ้อยท์!!!) ';

    //                     }
    //                 }
    //             }


    //         break;

    //         case'0':

    //             $sql = $this->prepare("SELECT countProLine1.* , countProLine2.* 
    //                                     FROM (select count(*) as countProLine1 , ifnull(SUM(PointLog.point),0) AS gainedPoint1 
    //                                     from PointLog where remark = 'Week 10 - Line 10(1)' and cancelled = 0 AND employee_id = ? ) as countProLine1 , 
    //                                     (select COUNT(*) AS countProLine2, ifnull(SUM(PointLog.point),0) AS gainedPoint2 
    //                                     FROM PointLog where PointLog.remark = 'Week 10 - Line 10(2)' and PointLog.cancelled = 0 AND PointLog.employee_id = ?) as countProLine2;");

    //             $sql->execute([input::post('sellerNo'), input::post('sellerNo')]);
    //             $temp = $sql->fetchAll(PDO::FETCH_ASSOC)[0];

    //             // proline(1)
    //             $sql = $this->prepare("select ifnull(sum(SOPrinting.quantity),0) as totalQuantity from SO
    //                                     inner join SOPrinting on SOPrinting.so_no = SO.so_no
    //                                     inner join Product on Product.product_no = SOPrinting.product_no
    //                                     inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
    //                                     where employee_id = ? and Product.product_line = '0' AND Product.category_no = '04' AND Product.product_name LIKE '%ปั้นสิบ%'
    //                                     and SO.cancelled = 0 and ((so_date = '2021-08-02' AND so_time >= '13:00:00') OR so_date between '2021-08-03' AND '2021-08-07')");
    //             $sql->execute([input::post('sellerNo')]);
    //             $totalQuantity = $sql->fetchAll()[0]['totalQuantity'];

    //             if($totalQuantity>=3){
    //                 $proQuantity = $temp['gainedPoint1']/100 * 3;
    //                 $multiplier = intdiv(($totalQuantity - $proQuantity),3);
    //                 $point = 100 * $multiplier;

    //                 if($multiplier >=1){
    //                     $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled)
    //                                             values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
    //                     $sql->execute([input::post('sellerNo'), $point, 'Week 10 - Line 10(1)', $sono]);

    //                     echo ' (ผ่านโปรสาย 10(1) ได้รับ ' . $point . ' พ้อยท์!!!) ';

    //                 }
    //             }

    //             // proline(2)
    //             $sql = $this->prepare("select ifnull(sum(SOPrinting.quantity),0) as totalQuantity from SO
    //                                     inner join SOPrinting on SOPrinting.so_no = SO.so_no
    //                                     inner join Product on Product.product_no = SOPrinting.product_no
    //                                     inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
    //                                     where employee_id = ? and Product.product_no IN ('0-S2-04-007-005', '0-S2-04-007-014' ,'0-S2-04-007-023')
    //                                     and SO.cancelled = 0 and ((so_date = '2021-08-02' AND so_time >= '13:00:00') OR so_date between '2021-08-03' AND '2021-08-07')");
    //             $sql->execute([input::post('sellerNo')]);
    //             $totalQuantity = $sql->fetchAll()[0]['totalQuantity'];

    //             if ($totalQuantity >= 3) {
    //                 $proQuantity = $temp['gainedPoint2'] / 100 * 3;
    //                 $multiplier = intdiv(($totalQuantity - $proQuantity), 3);
    //                 $point = 100 * $multiplier;

    //                 if ($multiplier >= 1) {
    //                     $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled)
    //                                                             values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
    //                     $sql->execute([input::post('sellerNo'), $point, 'Week 10 - Line 10(2)', $sono]);

    //                     echo ' (ผ่านโปรสาย 10(2) ได้รับ ' . $point . ' พ้อยท์!!!) ';

    //                 }
    //             }

    //         break;
    //     }
    
    // }
    
  // 	private function specialPromotionLine3( $sono ) {

  //   $sql = $this->prepare( "select count(distinct SOPrinting.so_no) as count from SOPrinting join SO on SO.so_no = SOPrinting.so_no where employee_id = ? and substring(SOPrinting.product_no, 1, 1) = 3 and SO.so_date > '2020-07-29'" );
  //   $sql->execute( [ input::post( 'sellerNo' ) ]);
  //   $count = $sql->fetchAll()[ 0 ][ 'count' ];

  //   if ( $count == 1 ) {
  //     $sql = $this->prepare( "insert into PointLog (date, time, employee_id, point, remark, note, cancelled) values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, 'Special Promotion - Line 3(1)', ?, 0)" );
  //     $sql->execute( [ input::post( 'sellerNo' ), 10, $sono ] );
  //   } else if ( $count == 2 ) {
  //     $sql = $this->prepare( "insert into PointLog (date, time, employee_id, point, remark, note, cancelled) values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, 'Special Promotion - Line 3(2)', ?, 0)" );
  //     $sql->execute( [ input::post( 'sellerNo' ), 20, $sono ] );
  //   } else if ( $count == 3 ) {
  //     $sql = $this->prepare( "insert into PointLog (date, time, employee_id, point, remark, note, cancelled) values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, 'Special Promotion - Line 3(3)', ?, 0)" );
  //     $sql->execute( [ input::post( 'sellerNo' ), 30, $sono ] );
  //   } else if ( $count == 4 ) {
  //     $sql = $this->prepare( "insert into PointLog (date, time, employee_id, point, remark, note, cancelled) values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, 'Special Promotion - Line 3(4)', ?, 0)" );
  //     $sql->execute( [ input::post( 'sellerNo' ), 40, $sono ] );
  //   }

  //   $sql = $this->prepare( "select count(*) as count from SOPrinting left join Product on Product.product_no = SOPrinting.product_no where Product.category_no in ('02', '11') and Product.product_line = '3' and SOPrinting.so_no = ?" );
  //   $sql->execute( [ $sono ] );
  //   $count = $sql->fetchAll()[ 0 ][ 'count' ];

  //   if ( $count > 0 ) {
  //     $sql = $this->prepare( "insert into PointLog (date, time, employee_id, point, remark, note, cancelled) values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, 'Special Promotion - Line 3(S)', ?, 0)" );
  //     $sql->execute( [ input::post( 'sellerNo' ), 300, $sono ] );
  //   }

  // }
	
	public function get_top10_so(){
		$sql = $this->prepare("SELECT count(so_no) AS count, Product.product_name AS product_name FROM SOPrinting
								LEFT JOIN Product ON Product.product_no=SOPrinting.product_no
								WHERE substring(SOPrinting.product_no,1,1)=? 
								AND Product.product_name NOT LIKE '%ค่าส่ง%'
                                AND Product.product_name NOT LIKE '%ค่าจัดส่ง%'
                                AND Product.product_name NOT LIKE '%ค่าติดตั้ง%'
                                AND Product.product_name NOT LIKE '%ค่าขนส่ง%'
								AND Product.product_name NOT LIKE '%ส่วนลด%'
								GROUP BY Product.product_name
								ORDER BY count(so_no) DESC
								LIMIT 10;");
		$sql->execute([json_decode(session::get('employee_detail'), true)['product_line']]);
		return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
	}
	
	public function get_top10_margin(){
		$sql = $this->prepare("SELECT sum(SOPrinting.margin) AS sum_margin, Product.product_name AS product_name FROM 	SOPrinting
								LEFT JOIN Product ON Product.product_no=SOPrinting.product_no
								WHERE substring(SOPrinting.product_no,1,1)=? 
								AND Product.product_name NOT LIKE '%ค่าส่ง%'
                                AND Product.product_name NOT LIKE '%ค่าจัดส่ง%'
                                AND Product.product_name NOT LIKE '%ค่าติดตั้ง%'
                                AND Product.product_name NOT LIKE '%ค่าขนส่ง%'
								AND Product.product_name NOT LIKE '%ส่วนลด%'
								GROUP BY Product.product_name
								ORDER BY sum_margin DESC
								LIMIT 10;");
		$sql->execute([json_decode(session::get('employee_detail'), true)['product_line']]);
		return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
	}
	public function get_fa_sales_total(){
		$sql = $this->prepare("SELECT t1.*,SUM(Forecast.sales_forecast) AS forecast_sales, 		actual_sales/(SUM(Forecast.sales_forecast))*100 AS percent_sales
						FROM Forecast RIGHT JOIN (select SO.product_line, sum(SOPrinting.sales_no_vat *SOPrinting.quantity) AS actual_sales 
					  from SOPrinting inner join SO on SO.so_no = SOPrinting.so_no 
							inner join Employee on Employee.employee_id = SO.employee_id 
							left join Product on Product.product_no = SOPrinting.product_no 
							left join ProductCategory on ProductCategory.category_no = Product.category_no And ProductCategory.product_line = Product.product_line 
                                                  INNER JOIN Week ON SO.so_date = Week.date
							

							WHERE SOPrinting.cancelled = 0 AND SO.cancelled = 0 AND not Product.product_name like '%ค่า%ส่ง%' and not Product.product_name like '%ติดตั้ง%' AND SO.product_line =? AND Week.week IN ('1','2','3','4','5','6','7','8','9','10') 

							GROUP BY SO.product_line) as t1 ON Forecast.product_line =  t1.product_line 

						group BY t1.product_line 
						ORDER BY t1.product_line;");
		$sql->execute([json_decode(session::get('employee_detail'), true)['product_line']]);
		return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
	}
	public function get_fa_margin_total(){
		$sql =$this->prepare("SELECT t1.*,SUM(Forecast.margin_forecast) AS forecast_margin, (actual_margin/SUM(Forecast.margin_forecast))*100 AS percent_margin
			 FROM Forecast RIGHT JOIN (select SO.product_line, sum(SOPrinting.margin) AS actual_margin
			  from SOPrinting inner join SO on SO.so_no = SOPrinting.so_no 
					inner join Employee on Employee.employee_id = SO.employee_id 
					left join Product on Product.product_no = SOPrinting.product_no 
					inner join Week on Week.date = SO.so_date

					 WHERE SOPrinting.cancelled = 0 AND SO.cancelled = 0 AND not Product.product_name like '%ค่า%ส่ง%' and not Product.product_name like '%ติดตั้ง%' AND Week.week > 0 AND SO.product_line=? AND Week.week IN ('1','2','3','4','5','6','7','8','9','10')

					GROUP BY SO.product_line) as t1 ON Forecast.product_line =  t1.product_line 

				group BY t1.product_line 
				ORDER BY t1.product_line;");
		$sql->execute([json_decode(session::get('employee_detail'), true)['product_line']]);
		return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
	}
	
	public function get_fa_sales_weeks(){
		$sql = $this->prepare("SELECT  t1.*,Forecast.sales_forecast AS forecast_sales
					FROM Forecast RIGHT JOIN (select SO.product_line, Week.week, sum(SOPrinting.sales_no_vat *SOPrinting.quantity) AS actual_sales 
						from SOPrinting inner join SO on SO.so_no = SOPrinting.so_no 
        				inner join Employee on Employee.employee_id = SO.employee_id 
        				left join Product on Product.product_no = SOPrinting.product_no 
        				left join ProductCategory on ProductCategory.category_no = Product.category_no And ProductCategory.product_line = Product.product_line 
    
        				inner join Week on Week.date = SO.so_date 
        				WHERE SOPrinting.cancelled = 0 AND SO.cancelled = 0 AND not Product.product_name like '%ค่า%ส่ง%' and not Product.product_name like '%ติดตั้ง%'
        				AND Week.week IN ('1','2','3','4','5','6','7','8','9','10') 
        				GROUP BY SO.product_line, Week.week) as t1 ON Forecast.product_line = 	t1.product_line AND Forecast.week = t1.week
					WHERE t1.product_line = ?
    				group BY t1.product_line, t1.week
    				ORDER BY t1.product_line ASC, t1.week;");
		$sql->execute([json_decode(session::get('employee_detail'), true)['product_line']]);
		return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
	}
	public function get_fa_margin_weeks(){
		$sql = $this->prepare("SELECT  t1.*,Forecast.margin_forecast AS forecast_margin
					FROM Forecast RIGHT JOIN (select SO.product_line, Week.week, sum(SOPrinting.margin ) AS actual_margin 
						from SOPrinting inner join SO on SO.so_no = SOPrinting.so_no 
        				inner join Employee on Employee.employee_id = SO.employee_id 
        				left join Product on Product.product_no = SOPrinting.product_no 
        				left join ProductCategory on ProductCategory.category_no = Product.category_no And ProductCategory.product_line = Product.product_line 
        				inner join Supplier on Supplier.supplier_no = Product.supplier_no AND 	Supplier.product_line = Product.product_line 
        				inner join Week on Week.date = SO.so_date 
        				WHERE SOPrinting.cancelled = 0 AND SO.cancelled = 0 AND not Product.product_name like '%ค่า%ส่ง%' and not Product.product_name like '%ติดตั้ง%'
        				AND Week.week IN ('1','2','3','4','5','6','7','8','9','10') 
        				GROUP BY SO.product_line, Week.week) as t1 ON Forecast.product_line = 	t1.product_line AND Forecast.week = t1.week
					WHERE t1.product_line = ?
    				group BY t1.product_line, t1.week
    				ORDER BY t1.product_line ASC, t1.week;");
		$sql->execute([json_decode(session::get('employee_detail'), true)['product_line']]);
		return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
	}
	public function get_fa_sales_cat(){
		$sql = $this->prepare("SELECT  t1.*,forecast_category.sales_forecast AS forecast_sales
							 FROM forecast_category LEFT JOIN (select ProductCategory.category_name,SO.product_line, Week.week, sum(SOPrinting.sales_no_vat *SOPrinting.quantity) AS actual_sales 
							  from SOPrinting INNER join SO on SO.so_no = SOPrinting.so_no 
									LEFT join Employee on Employee.employee_id = SO.employee_id 
									left join Product on Product.product_no = SOPrinting.product_no 
									RIGHT join ProductCategory on ProductCategory.category_no = Product.category_no And ProductCategory.product_line = Product.product_line 
									inner join Week on Week.date = SO.so_date 
									WHERE SOPrinting.cancelled = 0 AND SO.cancelled = 0 AND not Product.product_name like '%ค่า%ส่ง%' and not Product.product_name like '%ติดตั้ง%' AND not ProductCategory.category_name like '%ส่วนลด%'
									AND Week.week IN ('8','9','10') 
									GROUP BY SO.product_line, Week.week, ProductCategory.category_name  
						ORDER BY `Week`.`week`  DESC ) as t1 ON forecast_category.product_line = t1.product_line AND forecast_category.week = t1.week AND forecast_category.category_name =  t1.category_name
							 WHERE t1.product_line = ?
								ORDER BY t1.product_line ASC, t1.week;");
		$sql->execute([json_decode(session::get('employee_detail'), true)['product_line']]);
		return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
	}
	public function get_fa_margin_cat(){
		$sql = $this->prepare("SELECT  t1.*,forecast_category.margin_forecast AS forecast_margin
							 FROM forecast_category LEFT JOIN (select ProductCategory.category_name,SO.product_line, Week.week, sum(SOPrinting.margin) AS actual_margin 
							  from SOPrinting INNER join SO on SO.so_no = SOPrinting.so_no 
									LEFT join Employee on Employee.employee_id = SO.employee_id 
									left join Product on Product.product_no = SOPrinting.product_no 
									RIGHT join ProductCategory on ProductCategory.category_no = Product.category_no And ProductCategory.product_line = Product.product_line 

									inner join Week on Week.date = SO.so_date 
									WHERE SOPrinting.cancelled = 0 AND SO.cancelled = 0 AND not Product.product_name like '%ค่า%ส่ง%' and not Product.product_name like '%ติดตั้ง%' 
									AND Week.week IN ('8','9','10') 
									GROUP BY SO.product_line, Week.week, ProductCategory.category_name  
						ORDER BY `Week`.`week`  DESC ) as t1 ON forecast_category.product_line = t1.product_line AND forecast_category.week = t1.week AND forecast_category.category_name =  t1.category_name
							 WHERE t1.product_line = ?

								ORDER BY t1.product_line ASC, t1.week;");
		$sql->execute([json_decode(session::get('employee_detail'), true)['product_line']]);
		return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
		
	} 
	
	public function get_fa_sales_cat_all(){
		$sql = $this->prepare("SELECT t3.*,forecast_category_all.sales_forecast AS forecast_sales
        					 FROM forecast_category_all LEFT JOIN (SELECT  t1.product_line, t1.category_no, t1.category_name, ifnull((t2.actual_sales),0) as actual_sales
							 		FROM (SELECT DISTINCT  ProductCategory.product_line, ProductCategory.category_no, ProductCategory.category_name
               						FROM ProductCategory, Week
    								WHERE Week BETWEEN 1 AND 10 AND NOT ProductCategory.category_name like '%ค่า%ส่ง%' and not  ProductCategory.category_name like 'ค่าติดตั้ง') as t1
							 LEFT JOIN (SELECT Product.product_line, Product.category_no, sum(SOPrinting.sales_no_vat * SOPrinting.quantity) as actual_sales 
           							FROM SOPrinting
									INNER JOIN SO on SO.so_no = SOPrinting.so_no
            						INNER JOIN Product on Product.product_no = SOPrinting.product_no
            						RIGHT JOIN Week on Week.date = SO.so_date
									WHERE SO.cancelled = 0 AND Week.week between 1 AND 10 
              						GROUP BY  product_line, category_no) AS t2 ON  t1.product_line = t2.product_line AND t1.category_no = t2.category_no  
           							ORDER BY  t1.product_line asc, t1.category_no asc) as t3 
							ON forecast_category_all.product_line = t3.product_line AND  forecast_category_all.category_name =  t3.category_name
							WHERE t3.product_line = ?
      						GROUP BY category_name  
							ORDER BY t3.product_line ASC;");
		$sql->execute([json_decode(session::get('employee_detail'), true)['product_line']]);
		return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
		
	} 
	public function get_fa_margin_cat_all(){
		$sql = $this->prepare("SELECT t3.*,forecast_category_all.margin_forecast AS forecast_margin
        					 FROM forecast_category_all LEFT JOIN (SELECT  t1.product_line, t1.category_no, t1.category_name, ifnull((t2.actual_margin),0) as actual_margin
							 		FROM (SELECT DISTINCT  ProductCategory.product_line, ProductCategory.category_no, ProductCategory.category_name
               						FROM ProductCategory, Week
    								WHERE Week BETWEEN 1 AND 10 AND NOT ProductCategory.category_name like '%ค่า%ส่ง%' and not  ProductCategory.category_name like 'ค่าติดตั้ง') as t1
							 LEFT JOIN (SELECT Product.product_line, Product.category_no, sum(SOPrinting.margin) as actual_margin 
           							FROM SOPrinting
									INNER JOIN SO on SO.so_no = SOPrinting.so_no
            						INNER JOIN Product on Product.product_no = SOPrinting.product_no
            						RIGHT JOIN Week on Week.date = SO.so_date
									WHERE SO.cancelled = 0 AND Week.week between 1 AND 10 
              						GROUP BY  product_line, category_no) AS t2 ON  t1.product_line = t2.product_line AND t1.category_no = t2.category_no  
           							ORDER BY  t1.product_line asc, t1.category_no asc) as t3 
							ON forecast_category_all.product_line = t3.product_line AND  forecast_category_all.category_name =  t3.category_name
                            WHERE t3.product_line = ?
      						GROUP BY category_name  
                            
							ORDER BY t3.product_line ASC;");	
		$sql->execute([json_decode(session::get('employee_detail'), true)['product_line']]);
		return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
		
	}
	
	public function get_stack(){
		$sql = $this->prepare("SELECT t1.week, t1.product_line, t1.category_no, t1.category_name, ifnull(t2.actual_sales,0) as actual_sales
				FROM (SELECT DISTINCT Week.week, ProductCategory.product_line, ProductCategory.category_no, ProductCategory.category_name
							   FROM ProductCategory, Week
					WHERE Week BETWEEN 1 AND 10 AND NOT ProductCategory.category_name like '%ค่า%ส่ง%' and not  ProductCategory.category_name like 'ค่าติดตั้ง'and not  ProductCategory.category_name like 'ส่วนลด') as t1
				LEFT JOIN (SELECT Week.week, Product.product_line, Product.category_no, sum(SOPrinting.sales_no_vat * SOPrinting.quantity) as actual_sales 
						   FROM SOPrinting
							INNER JOIN SO on SO.so_no = SOPrinting.so_no
							INNER JOIN Product on Product.product_no = SOPrinting.product_no
							RIGHT JOIN Week on Week.date = SO.so_date
							WHERE SO.cancelled = 0 AND Week.week between 1 AND 10 
							  AND (Product.product_name not LIKE '%ค่า%ส่ง%' OR Product.product_name not LIKE '%ติดตั้ง%')
							GROUP BY week, product_line, category_no) as t2 on t1.week = t2.week AND t1.product_line = t2.product_line 
							AND t1.category_no = t2.category_no  
				WHERE t1.product_line = ? ORDER BY `t1`.`week`  ASC, t1.product_line asc, t1.category_no asc;");
		$sql->execute([json_decode(session::get('employee_detail'), true)['product_line']]);
		return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
	}
	public function get_cat_for_stack(){
		$sql = $this->prepare("SELECT DISTINCT(category_name)
FROM (SELECT DISTINCT Week.week, ProductCategory.product_line, ProductCategory.category_no, ProductCategory.category_name
					   FROM ProductCategory, Week
			WHERE Week BETWEEN 1 AND 10 AND NOT ProductCategory.category_name like '%ค่า%ส่ง%' and not  ProductCategory.category_name like 'ค่าติดตั้ง' and not  ProductCategory.category_name like 'ส่วนลด') as t1
		LEFT JOIN (SELECT Week.week, Product.product_line, Product.category_no, sum(SOPrinting.sales_no_vat * SOPrinting.quantity) as actual_sales 
				   FROM SOPrinting
					INNER JOIN SO on SO.so_no = SOPrinting.so_no
					INNER JOIN Product on Product.product_no = SOPrinting.product_no
					RIGHT JOIN Week on Week.date = SO.so_date
					WHERE SO.cancelled = 0 AND Week.week between 1 AND 10 
					  AND (Product.product_name not LIKE '%ค่า%ส่ง%' OR Product.product_name not LIKE '%ติดตั้ง%' OR Product.product_name not LIKE '%ส่วนลด%')
					GROUP BY week, product_line, category_no) as t2 on t1.week = t2.week AND t1.product_line = t2.product_line 
					AND t1.category_no = t2.category_no 
		WHERE t1.product_line=? ORDER BY `t1`.`week`  ASC, t1.product_line asc, t1.category_no asc;");
		$sql->execute([json_decode(session::get('employee_detail'), true)['product_line']]);
		return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );

	}

  public function os_get_top10_so($productLine){
    if($productLine == 'a') {
      $sql = $this->prepare("SELECT count(so_no) AS count, Product.product_name AS product_name FROM SOPrinting
      LEFT JOIN Product ON Product.product_no=SOPrinting.product_no
      AND Product.product_name NOT LIKE '%ค่าส่ง%'
                      AND Product.product_name NOT LIKE '%ค่าจัดส่ง%'
                      AND Product.product_name NOT LIKE '%ค่าติดตั้ง%'
                      AND Product.product_name NOT LIKE '%ค่าขนส่ง%'
      AND Product.product_name NOT LIKE '%ส่วนลด%'
      GROUP BY Product.product_name
      ORDER BY count(so_no) DESC
      LIMIT 10;");
    $sql->execute();
  } else {
		$sql = $this->prepare("SELECT count(so_no) AS count, Product.product_name AS product_name FROM SOPrinting
								LEFT JOIN Product ON Product.product_no=SOPrinting.product_no
								WHERE substring(SOPrinting.product_no,1,1)=? 
								AND Product.product_name NOT LIKE '%ค่าส่ง%'
                                AND Product.product_name NOT LIKE '%ค่าจัดส่ง%'
                                AND Product.product_name NOT LIKE '%ค่าติดตั้ง%'
                                AND Product.product_name NOT LIKE '%ค่าขนส่ง%'
								AND Product.product_name NOT LIKE '%ส่วนลด%'
								GROUP BY Product.product_name
								ORDER BY count(so_no) DESC
								LIMIT 10;");
		$sql->execute([$productLine]);
  }
		return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
	}
	
	public function os_get_top10_margin($productLine){
    if($productLine == 'a') {
		$sql = $this->prepare("SELECT sum(SOPrinting.margin) AS sum_margin, Product.product_name AS product_name FROM 	SOPrinting
								LEFT JOIN Product ON Product.product_no=SOPrinting.product_no
								AND Product.product_name NOT LIKE '%ค่าส่ง%'
                                AND Product.product_name NOT LIKE '%ค่าจัดส่ง%'
                                AND Product.product_name NOT LIKE '%ค่าติดตั้ง%'
                                AND Product.product_name NOT LIKE '%ค่าขนส่ง%'
								AND Product.product_name NOT LIKE '%ส่วนลด%'
								GROUP BY Product.product_name
								ORDER BY sum_margin DESC
								LIMIT 10;");
		$sql->execute();
    } else { 		$sql = $this->prepare("SELECT sum(SOPrinting.margin) AS sum_margin, Product.product_name AS product_name FROM 	SOPrinting
      LEFT JOIN Product ON Product.product_no=SOPrinting.product_no
      WHERE substring(SOPrinting.product_no,1,1)=? 
      AND Product.product_name NOT LIKE '%ค่าส่ง%'
                      AND Product.product_name NOT LIKE '%ค่าจัดส่ง%'
                      AND Product.product_name NOT LIKE '%ค่าติดตั้ง%'
                      AND Product.product_name NOT LIKE '%ค่าขนส่ง%'
      AND Product.product_name NOT LIKE '%ส่วนลด%'
      GROUP BY Product.product_name
      ORDER BY sum_margin DESC
      LIMIT 10;");
$sql->execute([$productLine]);}
		return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
	}
	public function os_get_fa_sales_total($productLine){
    if($productLine == 'a') {
		$sql = $this->prepare("SELECT t1.*,SUM(Forecast.sales_forecast) AS forecast_sales, 		actual_sales/(SUM(Forecast.sales_forecast))*100 AS percent_sales
						FROM Forecast RIGHT JOIN (select SO.product_line, sum(SOPrinting.sales_no_vat *SOPrinting.quantity) AS actual_sales 
					  from SOPrinting inner join SO on SO.so_no = SOPrinting.so_no 
							inner join Employee on Employee.employee_id = SO.employee_id 
							left join Product on Product.product_no = SOPrinting.product_no 
							left join ProductCategory on ProductCategory.category_no = Product.category_no And ProductCategory.product_line = Product.product_line 
                                                  INNER JOIN Week ON SO.so_date = Week.date
							

							WHERE SOPrinting.cancelled = 0 AND SO.cancelled = 0 AND not Product.product_name like '%ค่า%ส่ง%' and not Product.product_name like '%ติดตั้ง%' AND Week.week IN ('1','2','3','4','5','6','7','8','9','10') 

							GROUP BY SO.product_line) as t1 ON Forecast.product_line =  t1.product_line 

						group BY t1.product_line 
						ORDER BY t1.product_line;");
		$sql->execute();
    } else {
      $sql = $this->prepare("SELECT t1.*,SUM(Forecast.sales_forecast) AS forecast_sales, 		actual_sales/(SUM(Forecast.sales_forecast))*100 AS percent_sales
      FROM Forecast RIGHT JOIN (select SO.product_line, sum(SOPrinting.sales_no_vat *SOPrinting.quantity) AS actual_sales 
      from SOPrinting inner join SO on SO.so_no = SOPrinting.so_no 
        inner join Employee on Employee.employee_id = SO.employee_id 
        left join Product on Product.product_no = SOPrinting.product_no 
        left join ProductCategory on ProductCategory.category_no = Product.category_no And ProductCategory.product_line = Product.product_line 
                                            INNER JOIN Week ON SO.so_date = Week.date
        

        WHERE SOPrinting.cancelled = 0 AND SO.cancelled = 0 AND not Product.product_name like '%ค่า%ส่ง%' and not Product.product_name like '%ติดตั้ง%' AND SO.product_line =? AND Week.week IN ('1','2','3','4','5','6','7','8','9','10') 

        GROUP BY SO.product_line) as t1 ON Forecast.product_line =  t1.product_line 

      group BY t1.product_line 
      ORDER BY t1.product_line;");
$sql->execute([$productLine]);
    }
		return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
	}
	public function os_get_fa_margin_total($productLine){
    if($productLine == 'a') {
		$sql =$this->prepare("SELECT t1.*,SUM(Forecast.margin_forecast) AS forecast_margin, (actual_margin/SUM(Forecast.margin_forecast))*100 AS percent_margin
			 FROM Forecast RIGHT JOIN (select SO.product_line, sum(SOPrinting.margin) AS actual_margin
			  from SOPrinting inner join SO on SO.so_no = SOPrinting.so_no 
					inner join Employee on Employee.employee_id = SO.employee_id 
					left join Product on Product.product_no = SOPrinting.product_no 
					inner join Week on Week.date = SO.so_date

					 WHERE SOPrinting.cancelled = 0 AND SO.cancelled = 0 AND not Product.product_name like '%ค่า%ส่ง%' and not Product.product_name like '%ติดตั้ง%' AND Week.week > 0 AND Week.week IN ('1','2','3','4','5','6','7','8','9','10')

					GROUP BY SO.product_line) as t1 ON Forecast.product_line =  t1.product_line 

				group BY t1.product_line 
				ORDER BY t1.product_line;");
		$sql->execute([$productLine]);
    } else {
      $sql =$this->prepare("SELECT t1.*,SUM(Forecast.margin_forecast) AS forecast_margin, (actual_margin/SUM(Forecast.margin_forecast))*100 AS percent_margin
      FROM Forecast RIGHT JOIN (select SO.product_line, sum(SOPrinting.margin) AS actual_margin
       from SOPrinting inner join SO on SO.so_no = SOPrinting.so_no 
         inner join Employee on Employee.employee_id = SO.employee_id 
         left join Product on Product.product_no = SOPrinting.product_no 
         inner join Week on Week.date = SO.so_date

          WHERE SOPrinting.cancelled = 0 AND SO.cancelled = 0 AND not Product.product_name like '%ค่า%ส่ง%' and not Product.product_name like '%ติดตั้ง%' AND Week.week > 0 AND SO.product_line=? AND Week.week IN ('1','2','3','4','5','6','7','8','9','10')

         GROUP BY SO.product_line) as t1 ON Forecast.product_line =  t1.product_line 

       group BY t1.product_line 
       ORDER BY t1.product_line;");
   $sql->execute([$productLine]);
    }
		return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
	}
	
	public function os_get_fa_sales_weeks($productLine){
    if($productLine == 'a') {
		$sql = $this->prepare("SELECT  t1.*,Forecast.sales_forecast AS forecast_sales
					FROM Forecast RIGHT JOIN (select SO.product_line, Week.week, sum(SOPrinting.sales_no_vat *SOPrinting.quantity) AS actual_sales 
						from SOPrinting inner join SO on SO.so_no = SOPrinting.so_no 
        				inner join Employee on Employee.employee_id = SO.employee_id 
        				left join Product on Product.product_no = SOPrinting.product_no 
        				left join ProductCategory on ProductCategory.category_no = Product.category_no And ProductCategory.product_line = Product.product_line 
    
        				inner join Week on Week.date = SO.so_date 
        				WHERE SOPrinting.cancelled = 0 AND SO.cancelled = 0 AND not Product.product_name like '%ค่า%ส่ง%' and not Product.product_name like '%ติดตั้ง%'
        				AND Week.week IN ('1','2','3','4','5','6','7','8','9','10') 
        				GROUP BY SO.product_line, Week.week) as t1 ON Forecast.product_line = 	t1.product_line AND Forecast.week = t1.week
    				group BY t1.product_line, t1.week
    				ORDER BY t1.product_line ASC, t1.week;");
		$sql->execute();
    } else {
      $sql = $this->prepare("SELECT  t1.*,Forecast.sales_forecast AS forecast_sales
      FROM Forecast RIGHT JOIN (select SO.product_line, Week.week, sum(SOPrinting.sales_no_vat *SOPrinting.quantity) AS actual_sales 
        from SOPrinting inner join SO on SO.so_no = SOPrinting.so_no 
            inner join Employee on Employee.employee_id = SO.employee_id 
            left join Product on Product.product_no = SOPrinting.product_no 
            left join ProductCategory on ProductCategory.category_no = Product.category_no And ProductCategory.product_line = Product.product_line 

            inner join Week on Week.date = SO.so_date 
            WHERE SOPrinting.cancelled = 0 AND SO.cancelled = 0 AND not Product.product_name like '%ค่า%ส่ง%' and not Product.product_name like '%ติดตั้ง%'
            AND Week.week IN ('1','2','3','4','5','6','7','8','9','10') 
            GROUP BY SO.product_line, Week.week) as t1 ON Forecast.product_line = 	t1.product_line AND Forecast.week = t1.week
      WHERE t1.product_line = ?
        group BY t1.product_line, t1.week
        ORDER BY t1.product_line ASC, t1.week;");
$sql->execute([$productLine]);
    }
		return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
	}
	public function os_get_fa_margin_weeks($productLine){
    if($productLine == 'a') {
		$sql = $this->prepare("SELECT  t1.*,Forecast.margin_forecast AS forecast_margin
					FROM Forecast RIGHT JOIN (select SO.product_line, Week.week, sum(SOPrinting.margin ) AS actual_margin 
						from SOPrinting inner join SO on SO.so_no = SOPrinting.so_no 
        				inner join Employee on Employee.employee_id = SO.employee_id 
        				left join Product on Product.product_no = SOPrinting.product_no 
        				left join ProductCategory on ProductCategory.category_no = Product.category_no And ProductCategory.product_line = Product.product_line 
        				inner join Supplier on Supplier.supplier_no = Product.supplier_no AND 	Supplier.product_line = Product.product_line 
        				inner join Week on Week.date = SO.so_date 
        				WHERE SOPrinting.cancelled = 0 AND SO.cancelled = 0 AND not Product.product_name like '%ค่า%ส่ง%' and not Product.product_name like '%ติดตั้ง%'
        				AND Week.week IN ('1','2','3','4','5','6','7','8','9','10') 
        				GROUP BY SO.product_line, Week.week) as t1 ON Forecast.product_line = 	t1.product_line AND Forecast.week = t1.week
    				group BY t1.product_line, t1.week
    				ORDER BY t1.product_line ASC, t1.week;");
		$sql->execute();
    } else {
      $sql = $this->prepare("SELECT  t1.*,Forecast.margin_forecast AS forecast_margin
      FROM Forecast RIGHT JOIN (select SO.product_line, Week.week, sum(SOPrinting.margin ) AS actual_margin 
        from SOPrinting inner join SO on SO.so_no = SOPrinting.so_no 
            inner join Employee on Employee.employee_id = SO.employee_id 
            left join Product on Product.product_no = SOPrinting.product_no 
            left join ProductCategory on ProductCategory.category_no = Product.category_no And ProductCategory.product_line = Product.product_line 
            inner join Supplier on Supplier.supplier_no = Product.supplier_no AND 	Supplier.product_line = Product.product_line 
            inner join Week on Week.date = SO.so_date 
            WHERE SOPrinting.cancelled = 0 AND SO.cancelled = 0 AND not Product.product_name like '%ค่า%ส่ง%' and not Product.product_name like '%ติดตั้ง%'
            AND Week.week IN ('1','2','3','4','5','6','7','8','9','10') 
            GROUP BY SO.product_line, Week.week) as t1 ON Forecast.product_line = 	t1.product_line AND Forecast.week = t1.week
      WHERE t1.product_line = ?
        group BY t1.product_line, t1.week
        ORDER BY t1.product_line ASC, t1.week;");
$sql->execute([$productLine]);
    }
		return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
	}
	public function os_get_fa_sales_cat($productLine){
    if($productLine == 'a') {
		$sql = $this->prepare("SELECT  t1.*,forecast_category.sales_forecast AS forecast_sales
							 FROM forecast_category LEFT JOIN (select ProductCategory.category_name,SO.product_line, Week.week, sum(SOPrinting.sales_no_vat *SOPrinting.quantity) AS actual_sales 
							  from SOPrinting INNER join SO on SO.so_no = SOPrinting.so_no 
									LEFT join Employee on Employee.employee_id = SO.employee_id 
									left join Product on Product.product_no = SOPrinting.product_no 
									RIGHT join ProductCategory on ProductCategory.category_no = Product.category_no And ProductCategory.product_line = Product.product_line 
									inner join Week on Week.date = SO.so_date 
									WHERE SOPrinting.cancelled = 0 AND SO.cancelled = 0 AND not Product.product_name like '%ค่า%ส่ง%' and not Product.product_name like '%ติดตั้ง%' AND not ProductCategory.category_name like '%ส่วนลด%'
									AND Week.week IN ('8','9','10') 
									GROUP BY SO.product_line, Week.week, ProductCategory.category_name  
						ORDER BY `Week`.`week`  DESC ) as t1 ON forecast_category.product_line = t1.product_line AND forecast_category.week = t1.week AND forecast_category.category_name =  t1.category_name
								ORDER BY t1.product_line ASC, t1.week;");
		$sql->execute();
    } else {
      $sql = $this->prepare("SELECT  t1.*,forecast_category.sales_forecast AS forecast_sales
      FROM forecast_category LEFT JOIN (select ProductCategory.category_name,SO.product_line, Week.week, sum(SOPrinting.sales_no_vat *SOPrinting.quantity) AS actual_sales 
       from SOPrinting INNER join SO on SO.so_no = SOPrinting.so_no 
         LEFT join Employee on Employee.employee_id = SO.employee_id 
         left join Product on Product.product_no = SOPrinting.product_no 
         RIGHT join ProductCategory on ProductCategory.category_no = Product.category_no And ProductCategory.product_line = Product.product_line 
         inner join Week on Week.date = SO.so_date 
         WHERE SOPrinting.cancelled = 0 AND SO.cancelled = 0 AND not Product.product_name like '%ค่า%ส่ง%' and not Product.product_name like '%ติดตั้ง%' AND not ProductCategory.category_name like '%ส่วนลด%'
         AND Week.week IN ('8','9','10') 
         GROUP BY SO.product_line, Week.week, ProductCategory.category_name  
   ORDER BY `Week`.`week`  DESC ) as t1 ON forecast_category.product_line = t1.product_line AND forecast_category.week = t1.week AND forecast_category.category_name =  t1.category_name
      WHERE t1.product_line = ?
       ORDER BY t1.product_line ASC, t1.week;");
$sql->execute([$productLine]);
    }
		return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
	}
	public function os_get_fa_margin_cat($productLine){
    if($productLine == 'a') {
		$sql = $this->prepare("SELECT  t1.*,forecast_category.margin_forecast AS forecast_margin
							 FROM forecast_category LEFT JOIN (select ProductCategory.category_name,SO.product_line, Week.week, sum(SOPrinting.margin) AS actual_margin 
							  from SOPrinting INNER join SO on SO.so_no = SOPrinting.so_no 
									LEFT join Employee on Employee.employee_id = SO.employee_id 
									left join Product on Product.product_no = SOPrinting.product_no 
									RIGHT join ProductCategory on ProductCategory.category_no = Product.category_no And ProductCategory.product_line = Product.product_line 

									inner join Week on Week.date = SO.so_date 
									WHERE SOPrinting.cancelled = 0 AND SO.cancelled = 0 AND not Product.product_name like '%ค่า%ส่ง%' and not Product.product_name like '%ติดตั้ง%' 
									AND Week.week IN ('8','9','10') 
									GROUP BY SO.product_line, Week.week, ProductCategory.category_name  
						ORDER BY `Week`.`week`  DESC ) as t1 ON forecast_category.product_line = t1.product_line AND forecast_category.week = t1.week AND forecast_category.category_name =  t1.category_name

								ORDER BY t1.product_line ASC, t1.week;");
		$sql->execute();
    } else { 		$sql = $this->prepare("SELECT  t1.*,forecast_category.margin_forecast AS forecast_margin
      FROM forecast_category LEFT JOIN (select ProductCategory.category_name,SO.product_line, Week.week, sum(SOPrinting.margin) AS actual_margin 
       from SOPrinting INNER join SO on SO.so_no = SOPrinting.so_no 
         LEFT join Employee on Employee.employee_id = SO.employee_id 
         left join Product on Product.product_no = SOPrinting.product_no 
         RIGHT join ProductCategory on ProductCategory.category_no = Product.category_no And ProductCategory.product_line = Product.product_line 

         inner join Week on Week.date = SO.so_date 
         WHERE SOPrinting.cancelled = 0 AND SO.cancelled = 0 AND not Product.product_name like '%ค่า%ส่ง%' and not Product.product_name like '%ติดตั้ง%' 
         AND Week.week IN ('8','9','10') 
         GROUP BY SO.product_line, Week.week, ProductCategory.category_name  
   ORDER BY `Week`.`week`  DESC ) as t1 ON forecast_category.product_line = t1.product_line AND forecast_category.week = t1.week AND forecast_category.category_name =  t1.category_name
      WHERE t1.product_line = ?

       ORDER BY t1.product_line ASC, t1.week;");
$sql->execute([$productLine]);
}
		return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
		
	} 
	
	public function os_get_fa_sales_cat_all($productLine){
    if($productLine == 'a') {
		$sql = $this->prepare("SELECT t3.*,forecast_category_all.sales_forecast AS forecast_sales
        					 FROM forecast_category_all LEFT JOIN (SELECT  t1.product_line, t1.category_no, t1.category_name, ifnull((t2.actual_sales),0) as actual_sales
							 		FROM (SELECT DISTINCT  ProductCategory.product_line, ProductCategory.category_no, ProductCategory.category_name
               						FROM ProductCategory, Week
    								WHERE Week BETWEEN 1 AND 10 AND NOT ProductCategory.category_name like '%ค่า%ส่ง%' and not  ProductCategory.category_name like 'ค่าติดตั้ง') as t1
							 LEFT JOIN (SELECT Product.product_line, Product.category_no, sum(SOPrinting.sales_no_vat * SOPrinting.quantity) as actual_sales 
           							FROM SOPrinting
									INNER JOIN SO on SO.so_no = SOPrinting.so_no
            						INNER JOIN Product on Product.product_no = SOPrinting.product_no
            						RIGHT JOIN Week on Week.date = SO.so_date
									WHERE SO.cancelled = 0 AND Week.week between 1 AND 10 
              						GROUP BY  product_line, category_no) AS t2 ON  t1.product_line = t2.product_line AND t1.category_no = t2.category_no  
           							ORDER BY  t1.product_line asc, t1.category_no asc) as t3 
							ON forecast_category_all.product_line = t3.product_line AND  forecast_category_all.category_name =  t3.category_name
      						GROUP BY category_name  
							ORDER BY t3.product_line ASC;");
		$sql->execute();
    } else {
      $sql = $this->prepare("SELECT t3.*,forecast_category_all.sales_forecast AS forecast_sales
      FROM forecast_category_all LEFT JOIN (SELECT  t1.product_line, t1.category_no, t1.category_name, ifnull((t2.actual_sales),0) as actual_sales
      FROM (SELECT DISTINCT  ProductCategory.product_line, ProductCategory.category_no, ProductCategory.category_name
              FROM ProductCategory, Week
       WHERE Week BETWEEN 1 AND 10 AND NOT ProductCategory.category_name like '%ค่า%ส่ง%' and not  ProductCategory.category_name like 'ค่าติดตั้ง') as t1
  LEFT JOIN (SELECT Product.product_line, Product.category_no, sum(SOPrinting.sales_no_vat * SOPrinting.quantity) as actual_sales 
            FROM SOPrinting
     INNER JOIN SO on SO.so_no = SOPrinting.so_no
           INNER JOIN Product on Product.product_no = SOPrinting.product_no
           RIGHT JOIN Week on Week.date = SO.so_date
     WHERE SO.cancelled = 0 AND Week.week between 1 AND 10 
             GROUP BY  product_line, category_no) AS t2 ON  t1.product_line = t2.product_line AND t1.category_no = t2.category_no  
            ORDER BY  t1.product_line asc, t1.category_no asc) as t3 
 ON forecast_category_all.product_line = t3.product_line AND  forecast_category_all.category_name =  t3.category_name
 WHERE t3.product_line = ?
     GROUP BY category_name  
 ORDER BY t3.product_line ASC;");
$sql->execute([$productLine]);
    }
		return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
		
	} 
	public function os_get_fa_margin_cat_all($productLine){
    if($productLine == 'a') {
		$sql = $this->prepare("SELECT t3.*,forecast_category_all.margin_forecast AS forecast_margin
        					 FROM forecast_category_all LEFT JOIN (SELECT  t1.product_line, t1.category_no, t1.category_name, ifnull((t2.actual_margin),0) as actual_margin
							 		FROM (SELECT DISTINCT  ProductCategory.product_line, ProductCategory.category_no, ProductCategory.category_name
               						FROM ProductCategory, Week
    								WHERE Week BETWEEN 1 AND 10 AND NOT ProductCategory.category_name like '%ค่า%ส่ง%' and not  ProductCategory.category_name like 'ค่าติดตั้ง') as t1
							 LEFT JOIN (SELECT Product.product_line, Product.category_no, sum(SOPrinting.margin) as actual_margin 
           							FROM SOPrinting
									INNER JOIN SO on SO.so_no = SOPrinting.so_no
            						INNER JOIN Product on Product.product_no = SOPrinting.product_no
            						RIGHT JOIN Week on Week.date = SO.so_date
									WHERE SO.cancelled = 0 AND Week.week between 1 AND 10 
              						GROUP BY  product_line, category_no) AS t2 ON  t1.product_line = t2.product_line AND t1.category_no = t2.category_no  
           							ORDER BY  t1.product_line asc, t1.category_no asc) as t3 
							ON forecast_category_all.product_line = t3.product_line AND  forecast_category_all.category_name =  t3.category_name
      						GROUP BY category_name  
                            
							ORDER BY t3.product_line ASC;");	
		$sql->execute();
    } else {
      $sql = $this->prepare("SELECT t3.*,forecast_category_all.margin_forecast AS forecast_margin
      FROM forecast_category_all LEFT JOIN (SELECT  t1.product_line, t1.category_no, t1.category_name, ifnull((t2.actual_margin),0) as actual_margin
      FROM (SELECT DISTINCT  ProductCategory.product_line, ProductCategory.category_no, ProductCategory.category_name
              FROM ProductCategory, Week
       WHERE Week BETWEEN 1 AND 10 AND NOT ProductCategory.category_name like '%ค่า%ส่ง%' and not  ProductCategory.category_name like 'ค่าติดตั้ง') as t1
  LEFT JOIN (SELECT Product.product_line, Product.category_no, sum(SOPrinting.margin) as actual_margin 
            FROM SOPrinting
     INNER JOIN SO on SO.so_no = SOPrinting.so_no
           INNER JOIN Product on Product.product_no = SOPrinting.product_no
           RIGHT JOIN Week on Week.date = SO.so_date
     WHERE SO.cancelled = 0 AND Week.week between 1 AND 10 
             GROUP BY  product_line, category_no) AS t2 ON  t1.product_line = t2.product_line AND t1.category_no = t2.category_no  
            ORDER BY  t1.product_line asc, t1.category_no asc) as t3 
 ON forecast_category_all.product_line = t3.product_line AND  forecast_category_all.category_name =  t3.category_name
               WHERE t3.product_line = ?
     GROUP BY category_name  
               
 ORDER BY t3.product_line ASC;");	
$sql->execute([$productLine]);
    }
		return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
		
	}
	
	public function os_get_stack($productLine){
    if($productLine == 'a') {
		$sql = $this->prepare("SELECT t1.week, t1.product_line, t1.category_no, t1.category_name, ifnull(t2.actual_sales,0) as actual_sales
				FROM (SELECT DISTINCT Week.week, ProductCategory.product_line, ProductCategory.category_no, ProductCategory.category_name
							   FROM ProductCategory, Week
					WHERE Week BETWEEN 1 AND 10 AND NOT ProductCategory.category_name like '%ค่า%ส่ง%' and not  ProductCategory.category_name like 'ค่าติดตั้ง'and not  ProductCategory.category_name like 'ส่วนลด') as t1
				LEFT JOIN (SELECT Week.week, Product.product_line, Product.category_no, sum(SOPrinting.sales_no_vat * SOPrinting.quantity) as actual_sales 
						   FROM SOPrinting
							INNER JOIN SO on SO.so_no = SOPrinting.so_no
							INNER JOIN Product on Product.product_no = SOPrinting.product_no
							RIGHT JOIN Week on Week.date = SO.so_date
							WHERE SO.cancelled = 0 AND Week.week between 1 AND 10 
							  AND (Product.product_name not LIKE '%ค่า%ส่ง%' OR Product.product_name not LIKE '%ติดตั้ง%')
							GROUP BY week, product_line, category_no) as t2 on t1.week = t2.week AND t1.product_line = t2.product_line 
							AND t1.category_no = t2.category_no  
				 ORDER BY `t1`.`week`  ASC, t1.product_line asc, t1.category_no asc;");
		$sql->execute();
    } else {
      $sql = $this->prepare("SELECT t1.week, t1.product_line, t1.category_no, t1.category_name, ifnull(t2.actual_sales,0) as actual_sales
      FROM (SELECT DISTINCT Week.week, ProductCategory.product_line, ProductCategory.category_no, ProductCategory.category_name
               FROM ProductCategory, Week
        WHERE Week BETWEEN 1 AND 10 AND NOT ProductCategory.category_name like '%ค่า%ส่ง%' and not  ProductCategory.category_name like 'ค่าติดตั้ง'and not  ProductCategory.category_name like 'ส่วนลด') as t1
      LEFT JOIN (SELECT Week.week, Product.product_line, Product.category_no, sum(SOPrinting.sales_no_vat * SOPrinting.quantity) as actual_sales 
             FROM SOPrinting
            INNER JOIN SO on SO.so_no = SOPrinting.so_no
            INNER JOIN Product on Product.product_no = SOPrinting.product_no
            RIGHT JOIN Week on Week.date = SO.so_date
            WHERE SO.cancelled = 0 AND Week.week between 1 AND 10 
              AND (Product.product_name not LIKE '%ค่า%ส่ง%' OR Product.product_name not LIKE '%ติดตั้ง%')
            GROUP BY week, product_line, category_no) as t2 on t1.week = t2.week AND t1.product_line = t2.product_line 
            AND t1.category_no = t2.category_no  
      WHERE t1.product_line = ? ORDER BY `t1`.`week`  ASC, t1.product_line asc, t1.category_no asc;");
  $sql->execute([$productLine]);
    }
		return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
	}
	public function os_get_cat_for_stack($productLine){
    if($productLine == 'a') {
		$sql = $this->prepare("SELECT DISTINCT(category_name)
FROM (SELECT DISTINCT Week.week, ProductCategory.product_line, ProductCategory.category_no, ProductCategory.category_name
					   FROM ProductCategory, Week
			WHERE Week BETWEEN 1 AND 10 AND NOT ProductCategory.category_name like '%ค่า%ส่ง%' and not  ProductCategory.category_name like 'ค่าติดตั้ง' and not  ProductCategory.category_name like 'ส่วนลด') as t1
		LEFT JOIN (SELECT Week.week, Product.product_line, Product.category_no, sum(SOPrinting.sales_no_vat * SOPrinting.quantity) as actual_sales 
				   FROM SOPrinting
					INNER JOIN SO on SO.so_no = SOPrinting.so_no
					INNER JOIN Product on Product.product_no = SOPrinting.product_no
					RIGHT JOIN Week on Week.date = SO.so_date
					WHERE SO.cancelled = 0 AND Week.week between 1 AND 10 
					  AND (Product.product_name not LIKE '%ค่า%ส่ง%' OR Product.product_name not LIKE '%ติดตั้ง%' OR Product.product_name not LIKE '%ส่วนลด%')
					GROUP BY week, product_line, category_no) as t2 on t1.week = t2.week AND t1.product_line = t2.product_line 
					AND t1.category_no = t2.category_no 
		 ORDER BY `t1`.`week`  ASC, t1.product_line asc, t1.category_no asc;");
		$sql->execute();
    } else {
      $sql = $this->prepare("SELECT DISTINCT(category_name)
      FROM (SELECT DISTINCT Week.week, ProductCategory.product_line, ProductCategory.category_no, ProductCategory.category_name
                   FROM ProductCategory, Week
            WHERE Week BETWEEN 1 AND 10 AND NOT ProductCategory.category_name like '%ค่า%ส่ง%' and not  ProductCategory.category_name like 'ค่าติดตั้ง' and not  ProductCategory.category_name like 'ส่วนลด') as t1
          LEFT JOIN (SELECT Week.week, Product.product_line, Product.category_no, sum(SOPrinting.sales_no_vat * SOPrinting.quantity) as actual_sales 
                 FROM SOPrinting
                INNER JOIN SO on SO.so_no = SOPrinting.so_no
                INNER JOIN Product on Product.product_no = SOPrinting.product_no
                RIGHT JOIN Week on Week.date = SO.so_date
                WHERE SO.cancelled = 0 AND Week.week between 1 AND 10 
                  AND (Product.product_name not LIKE '%ค่า%ส่ง%' OR Product.product_name not LIKE '%ติดตั้ง%' OR Product.product_name not LIKE '%ส่วนลด%')
                GROUP BY week, product_line, category_no) as t2 on t1.week = t2.week AND t1.product_line = t2.product_line 
                AND t1.category_no = t2.category_no 
          WHERE t1.product_line=? ORDER BY `t1`.`week`  ASC, t1.product_line asc, t1.category_no asc;");
          $sql->execute([$productLine]);
    }
		return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );

	}
	
	public function get_sp_data(){
		$sql = $this->prepare("SELECT Employee.ce_id, 
										Employee.employee_id,  
										Employee.employee_nickname_thai, 
										ifnull(SSOO.Total_Sales,0) AS total_sales, 
										IFnull(SSOO.Count_SO,0) AS count_so, 
										t1.remark, 		
										PPLL.total_point
								FROM Employee 
								LEFT JOIN (SELECT Employee.employee_id, ifnull(t.total_sales,0) as total_sales, ifnull(t.count_so,0) as count_so 
											FROM Employee
                                            LEFT JOIN (SELECT Employee.employee_id, ifnull(sum(SO.total_sales_no_vat),0) as total_sales, count(*) as count_so
                                                       FROM Employee 
                                                       LEFT JOIN SO on SO.employee_id = Employee.employee_id
                                                       WHERE position = 'sp' AND SO.cancelled = 0
                                                       group BY SO.employee_id) as t on t.employee_id = Employee.employee_id
                                            WHERE Employee.position = 'sp') as SSOO on SSOO.employee_id=Employee.employee_id
								left join (select PointLog.employee_id, sum(PointLog.point) as total_point 
                                           from PointLog 
                                           INNER JOIN Employee on Employee.employee_id = PointLog.employee_id
                                           where cancelled = 0 AND position = 'sp'
											group by PointLog.employee_id) as PPLL 
                                            on PPLL.employee_id=Employee.employee_id
                                LEFT JOIN (SELECT Employee.employee_id, ifnull(t.remark,'-') as remark 
                                           FROM Employee
                                           LEFT JOIN (SELECT employee_id, GROUP_CONCAT(remark) as remark 
                                                      FROM PointLog
                                                      WHERE cancelled = 0 AND remark IN ('Handling Objection and Effective Negotiation', 'Service Mindset', 'Basic Selling Skill', 'Week 3 - Fun Quest 1',
													  									'Week 3 - Fun Quest 2', 'Week 3 - Fun Quest 3', 'Week 5-6 - Fun Quest #1', 'Week 5-6 - Fun Quest #2', 'Week 5-6 - Fun Quest #3','Digital Marketing')
                                                      GROUP BY employee_id) as t on t.employee_id = Employee.employee_id
                                           WHERE Employee.position = 'sp') as t1 on t1.employee_id = Employee.employee_id
								WHERE Employee.product_line = ?  AND Employee.ce_id IS NOT null AND position = 'sp'
								group by Employee.employee_id
                                Order BY Employee.ce_id, Employee.employee_id ASC");
		$sql->execute([json_decode(session::get('employee_detail'), true)['product_line']]);
		return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
	}
	public function get_top10_sp(){
		$sql = $this->prepare("SELECT Employee.employee_id, employee_nickname_thai,
								ifnull ((SSOO.total_sales),0) AS total_sales, 
								IFnull(SSOO.count_so,0) AS count_so, 
												IFnull((SSOO.margin),0) AS sum_margin,
												IFnull((MAX(Week.week)),0) as week,
												IFnull((MAX(SSOO.latest_date)),0) AS latest_date,
												IFnull((MAX(SO.so_date)),0) AS latest_date_all
				FROM Employee 
				LEFT JOIN (select SO.employee_id, sum(SOPrinting.sales_no_vat * SOPrinting.quantity) as total_sales, count(DISTINCT SO.so_no) as count_so, MAX(so_date) AS latest_date, SUM(SOPrinting.margin) AS margin from SO INNER JOIN SOPrinting ON SO.so_no = SOPrinting.so_no
							where SOPrinting.cancelled= 0 AND SO.cancelled =0 AND SO.product_line = ? 
							group by SO.employee_id) as SSOO on SSOO.employee_id=Employee.employee_id 
				LEFT JOIN SO ON SSOO.employee_id = SO.employee_id 
				LEFT  join (SELECT SO.so_no AS so_no, MAX(SO.so_date) FROM SO) AS dl 
				ON SO.so_no = dl.so_no 
				INNER JOIN Week ON SO.so_date = Week.date
							 WHERE Employee.position = 'sp'
							 group by Employee.employee_id  
					   ORDER BY sum_margin  DESC;");
		$sql->execute([json_decode(session::get('employee_detail'), true)['product_line']]);
		return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
	}
	
	/*public function get_act_sp(){
		$sql = $this->prepare("SELECT count(PointLog.employee_id) as Count_sp,PointLog.remark,sp.*, 						 (count(PointLog.employee_id)/total_sp) *100 AS percent
						From (select count(employee_id) as total_sp from Employee where product_line=? and Employee.position='SP') as sp,PointLog
							left join Employee on Employee.employee_id=PointLog.employee_id
							where PointLog.remark in ('Handling OBJ' ,'Service Mindset' ,'Basic Selling Skill' , 'Week 3 - Fun Quest 1','Week 3 - Fun Quest 2','Week 3 - Fun Quest 3','Week 5-6 - Fun Quest #1' , 'Week 5-6 - Fun Quest #2' , 'Week 5-6 - Fun Quest #3') and Employee.product_line=? and Employee.position='SP'
							group by PointLog.remark;");
		$sql->execute([json_decode(session::get('employee_detail'), true)['product_line']]);
		return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
	}*/
	
			
			
	public function get_accumsales_forecast_diff(){
		$sql = $this->prepare("SELECT t1.* , 
							  SUM(t2.actual_sales) AS accum_sales, 
							  Forecast3.sales_forecast AS sales_forecast, 
							  Forecast3.accum_forecast, 
							  t3.actual_sales_company - Forecast4.accum_forecast_company AS accum_diff ,
                              t1.actual_sales - Forecast3.sales_forecast AS actual_diff,
							  t3.actual_sales_company AS accum_sales_company, 
							  Forecast4.accum_forecast_company  AS accum_forecast_company ,
							  SUM(t2.actual_gp) AS accum_actual_gp , SUM(t2.actual_gpm) AS accum_actual_gpm ,t5.line_actual_gp, t5.line_forecast_gp , t5.percent_actual_gp
					 FROM (select SO.product_line, Week.week, 
					 		  sum(SOPrinting.sales_no_vat *SOPrinting.quantity) AS actual_sales ,
							  SUM(SOPrinting.margin) AS actual_gp ,
							  SUM(SOPrinting.margin)*100/ SUM(SOPrinting.sales_no_vat) AS actual_gpm
							  from SOPrinting 
							  inner join SO on SO.so_no = SOPrinting.so_no 
							  inner join Employee on Employee.employee_id = SO.employee_id 
							  left join Product on Product.product_no = SOPrinting.product_no 
							  inner join Week on Week.date = SO.so_date 
							  WHERE SOPrinting.cancelled = 0 AND SO.cancelled = 0 AND not Product.product_name like '%ค่าส่ง%' AND not Product.product_name like '%ติดตั้ง%'
							  AND Week.week IN ('1','2','3','4','5','6','7','8','9','10') 
							  GROUP BY SO.product_line, Week.week) as t1
							INNER JOIN (SELECT Forecast1.product_line, Forecast1.week,Forecast1.sales_forecast,
                                        SUM(Forecast2.sales_forecast) AS accum_forecast 
							   FROM Forecast Forecast1 
							   INNER JOIN Forecast Forecast2 ON Forecast1.product_line = Forecast2.product_line 
								  AND Forecast1.week >= Forecast2.week
							   GROUP BY Forecast1.product_line, Forecast1.week) AS Forecast3 ON t1.product_line = Forecast3.product_line AND t1.week = Forecast3.week
							INNER JOIN (select SO.product_line, Week.week, sum(SOPrinting.sales_no_vat *SOPrinting.quantity) AS actual_sales ,SUM(SOPrinting.margin) AS actual_gp ,
							SUM(SOPrinting.margin)*100/ SUM(SOPrinting.sales_no_vat) AS actual_gpm
							   from SOPrinting 
							   inner join SO on SO.so_no = SOPrinting.so_no 
							   inner join Employee on Employee.employee_id = SO.employee_id 
							   left join Product on Product.product_no = SOPrinting.product_no 
							   inner join Week on Week.date = SO.so_date
							   WHERE SOPrinting.cancelled = 0 AND SO.cancelled = 0 AND not Product.product_name like '%ค่าส่ง%'  AND not Product.product_name like '%ติดตั้ง%'
								 AND Week.week IN ('1','2','3','4','5','6','7','8','9','10') 
							   GROUP BY SO.product_line, Week.week) as t2 on t1.product_line = t2.product_line AND t1.week >= t2.week
							INNER JOIN (select Week.week, 
							   sum(SOPrinting.sales_no_vat *SOPrinting.quantity) AS actual_sales_company
							   from SOPrinting 
							   inner join SO on SO.so_no = SOPrinting.so_no 
							   inner join Employee on Employee.employee_id = SO.employee_id 
							   left join Product on Product.product_no = SOPrinting.product_no 
							   inner join Week on Week.date = SO.so_date 
							   WHERE SOPrinting.cancelled = 0 AND SO.cancelled = 0 
							   AND not Product.product_name like '%ค่าส่ง%' 
							   AND Week.week IN ('1','2','3','4','5','6','7','8','9','10') 
							   GROUP BY Week.week) as t3 on t3.week = t1.week
							INNER JOIN (SELECT Forecast.week,SUM(Forecast.sales_forecast) AS accum_forecast_company 
								FROM Forecast
								GROUP BY Forecast.week) AS Forecast4 ON t1.week = Forecast4.week
							LEFT JOIN (SELECT t4.* ,SUM(Forecast.margin_forecast) AS line_forecast_gp, 
								(line_actual_gp/SUM(Forecast.margin_forecast))*100 AS percent_actual_gp
								FROM Forecast RIGHT JOIN (select SO.product_line, sum(SOPrinting.margin) AS line_actual_gp
								FROM SOPrinting inner join SO on SO.so_no = SOPrinting.so_no 
								inner join Employee on Employee.employee_id = SO.employee_id 
								left join Product on Product.product_no = SOPrinting.product_no 
								inner join Week on Week.date = SO.so_date
								WHERE SOPrinting.cancelled = 0 AND SO.cancelled = 0 AND not Product.product_name like '%ค่าส่ง%' AND NOT Product.product_name like '%ติดตั้ง%' AND Week.week > 0
								GROUP BY SO.product_line) as t4 ON Forecast.product_line =  t4.product_line 
								GROUP BY t4.product_line ORDER BY t4.product_line) AS t5 ON t1.product_line=t5.product_line

							GROUP BY t1.product_line, t1.week  
							ORDER BY `t1`.`week` ASC, t1.product_line ASC;");
		$sql->execute([]);
		return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
	}
	public function get_sp_contributing() {
		$sql = $this->prepare("SELECT PointLog.employee_id,sum(PointLog.point),CASE
                                    WHEN sum(PointLog.point) between 0 and 100   THEN '0-100'
                                    WHEN sum(PointLog.point) between 100.01 and 200   THEN '100-200'
                                    WHEN sum(PointLog.point) between 200.01 and 300   THEN '200-300'
                                    WHEN sum(PointLog.point) between 300.01 and 400   THEN '300-400'
                                    WHEN sum(PointLog.point) between 400.01 and 500   THEN '400-500'
                                    WHEN sum(PointLog.point) between 500.01 and 600   THEN '500-600'
                                    WHEN sum(PointLog.point) between 600.01 and 700   THEN '600-700'
                                    WHEN sum(PointLog.point) between 700.01 and 800   THEN '700-800'
                                    WHEN sum(PointLog.point) between 800.01 and 900   THEN '800-900'
                                    WHEN sum(PointLog.point) between 900.01 and 1000   THEN '900-1000'
                                    WHEN sum(PointLog.point) >= 1000.01   THEN '>1000'
                                    END AS sp_range,PointLog.date AS point_date,Week.week
									FROM PointLog
									left join Employee on Employee.employee_id=PointLog.employee_id
									INNER JOIN Week ON PointLog.date = Week.date
									where PointLog.cancelled=0 and Employee.position='SP' AND PointLog.remark = 'SO'
									group by PointLog.employee_id  ,Week.date
									ORDER BY Week.date  ASC;");
		$sql->execute([]);
		return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
	}
	public function get_sp_engagement() {
		$sql = $this->prepare("Select a.week, a.date, ifnull(count_sp,0) as count_sp, a.typename 
									from (select * from Week,Point_type WHERE Week.week not in (-6,-5,-4,-3,-2,-1) and Week.week <= 10) as a
									Left join (select PointLog.date, count(PointLog.point) as count_sp, type 
           							from PointLog 
           							INNER JOIN Employee on Employee.employee_id = PointLog.employee_id
           							where cancelled=0 AND position = 'sp'
           							group by date,type) as b on b.date=a.date AND a.typename = b.type;");
		$sql->execute([]);
		return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
	}
	public function get_sp_engagement_week() {
		$sql = $this->prepare("SELECT COUNT(PointLog.employee_id),Week.week,PointLog.type
									FROM PointLog JOIN Week ON PointLog.date = Week.date
									JOIN Employee ON Employee.employee_id = PointLog.employee_id
									WHERE PointLog.cancelled = 0 AND Employee.position = 'sp'
									GROUP BY Week.week,PointLog.type
									HAVING NOT Week.week IN (-6,-5,-4,-3,-2,-1);");
		$sql->execute([]);
		return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
	}
	
	public function get_sp_data_all(){
		$sql = $this->prepare("SELECT Employee.ce_id, 
										Employee.employee_id,  
										Employee.employee_nickname_thai, 
										ifnull(SSOO.Total_Sales,0) AS total_sales, 
										IFnull(SSOO.Count_SO,0) AS count_so, 
										t1.remark, 		
										PPLL.total_point
								FROM Employee 
								LEFT JOIN (SELECT Employee.employee_id, ifnull(t.total_sales,0) as total_sales, ifnull(t.count_so,0) as count_so 
											FROM Employee
                                            LEFT JOIN (SELECT Employee.employee_id, ifnull(sum(SO.total_sales_no_vat),0) as total_sales, count(*) as count_so
                                                       FROM Employee 
                                                       LEFT JOIN SO on SO.employee_id = Employee.employee_id
                                                       WHERE position = 'sp' AND SO.cancelled = 0
                                                       group BY SO.employee_id) as t on t.employee_id = Employee.employee_id
                                            WHERE Employee.position = 'sp') as SSOO on SSOO.employee_id=Employee.employee_id
								left join (select PointLog.employee_id, sum(PointLog.point) as total_point 
                                           from PointLog 
                                           INNER JOIN Employee on Employee.employee_id = PointLog.employee_id
                                           where cancelled = 0 AND position = 'sp'
											group by PointLog.employee_id) as PPLL 
                                            on PPLL.employee_id=Employee.employee_id
                                LEFT JOIN (SELECT Employee.employee_id, ifnull(t.remark,'-') as remark 
                                           FROM Employee
                                           LEFT JOIN (SELECT employee_id, GROUP_CONCAT(remark) as remark 
                                                      FROM PointLog
                                                      WHERE cancelled = 0 AND remark IN ('Handling Objection and Effective Negotiation', 'Service Mindset', 'Basic Selling Skill', 'Week 3 - Fun Quest 1',
													  									'Week 3 - Fun Quest 2', 'Week 3 - Fun Quest 3', 'Week 5-6 - Fun Quest #1', 'Week 5-6 - Fun Quest #2', 'Week 5-6 - Fun Quest #3','Digital Marketing')
                                                      GROUP BY employee_id) as t on t.employee_id = Employee.employee_id
                                           WHERE Employee.position = 'sp') as t1 on t1.employee_id = Employee.employee_id
								WHERE Employee.ce_id IS NOT null AND position = 'sp'
								group by Employee.employee_id
                                Order BY Employee.ce_id, Employee.employee_id ASC;");
		$sql->execute([]);
		return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );	
	}
	
	public function get_fa_sales_total_all(){
		$sql = $this->prepare("SELECT t1.*,SUM(Forecast.sales_forecast) AS forecast_sales, 	
								actual_sales/(SUM(Forecast.sales_forecast))*100 AS percent_sales
									FROM Forecast RIGHT JOIN (select SO.product_line, sum(SOPrinting.sales_no_vat *SOPrinting.quantity) AS actual_sales 
					  				from SOPrinting inner join SO on SO.so_no = SOPrinting.so_no 
										inner join Employee on Employee.employee_id = SO.employee_id 
										left join Product on Product.product_no = SOPrinting.product_no 
										left join ProductCategory on ProductCategory.category_no = Product.category_no And ProductCategory.product_line = Product.product_line 
     									INNER JOIN Week ON SO.so_date = Week.date
							
									WHERE SOPrinting.cancelled = 0 AND SO.cancelled = 0 AND not Product.product_name like '%ค่า%ส่ง%' and not Product.product_name like '%ติดตั้ง%' AND Week.week IN ('1','2','3','4','5','6','7','8','9','10') 

									GROUP BY SO.product_line) as t1 ON Forecast.product_line =  t1.product_line 

									group BY t1.product_line 
									ORDER BY t1.product_line;");
		$sql->execute([]);
		return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
	}
	public function get_fa_margin_total_all(){
		$sql =$this->prepare("SELECT t1.*,SUM(Forecast.margin_forecast) AS forecast_margin,
							(actual_margin/SUM(Forecast.margin_forecast))*100 AS percent_margin
									 FROM Forecast RIGHT JOIN (select SO.product_line, 
									 sum(SOPrinting.margin) AS actual_margin
									 from SOPrinting inner join SO on SO.so_no = SOPrinting.so_no 
										inner join Employee on Employee.employee_id = SO.employee_id 
										left join Product on Product.product_no = SOPrinting.product_no 
										inner join Week on Week.date = SO.so_date

									WHERE SOPrinting.cancelled = 0 AND SO.cancelled = 0 AND not Product.product_name like '%ค่า%ส่ง%' and not Product.product_name like '%ติดตั้ง%' AND Week.week > 0 AND Week.week IN ('1','2','3','4','5','6','7','8','9','10')

									GROUP BY SO.product_line) as t1 ON Forecast.product_line =  t1.product_line 

									group BY t1.product_line 
									ORDER BY t1.product_line;");
		$sql->execute([]);
		return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
	}



  public function assignInternalPVANo() {
    $rqPrefix = 'EXA-';
    $sql = $this->prepare( "select ifnull(max(internal_pva_no),0) as max from PVA where internal_pva_no like ?" );
    $sql->execute( [ 'EXA-%' ] );
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

  public function addRequestPettyMoney() { 

    $internal_pva_no = $this->assignInternalPVANo();

    $ivrc_file_name = $_FILES['invoice/receipt']['name'];
    $ivrc_file_data = base64_encode(file_get_contents($_FILES['invoice/receipt']['tmp_name']));
    $ivrc_file_type = $_FILES['invoice/receipt']['type'];

    $slip_file_name = $_FILES['slip']['name'];
    $slip_file_data = base64_encode(file_get_contents($_FILES['slip']['tmp_name']));
    $slip_file_type = $_FILES['slip']['type'];

    $sql = $this->prepare("INSERT INTO PVA (internal_pva_no, pv_date, pv_time, employee_id, employee_name, line_id, total_paid, product_names,bank_name,bank_no,ivrc_name, ivrc_type, ivrc_data, slip_name, slip_type, slip_data, pv_status	) 
                            VALUE (?,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,?,?,?,?,?,?,?,?,?,?,?,?,?,0)");
    $success = $sql->execute([
      $internal_pva_no,
      input::post( 'employee_id' ),
      input::post( 'employee_name' ),
      input::post( 'lineId' ),
      ( double )input::post( 'cost' ),
      input::post( 'product_name' ),
      input::post( 'bank_name' ),
      input::post( 'bank_no' ),
      $ivrc_file_name,
      $ivrc_file_type,
      $ivrc_file_data,
      $slip_file_name,
      $slip_file_type,
      $slip_file_data,
    ]);
    
    if ($success) {
      echo 'success';
    } else {
      echo $internal_pva_no  . ' error'.'<br>';
      print_r($sql->errorInfo());
    }

  }

  public function assign_pvc_no() {

    $rqPrefix = 'PVC-';
    $sql = $this->prepare( "select ifnull(max(PVC_No),0) as max from PVC_Demo where PVC_No like ?" );
    $sql->execute( [ 'PVC-%' ] );
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
  
  public function assign_re_req_no() {

    $rqPrefix = 'EXC-';
    $sql = $this->prepare(  "select ifnull(max(re_req_no),0) as max from Reimbursement_Request where re_req_no like 'EXC-%'" );
    $sql->execute();
    $maxRqNo = $sql->fetchAll()[ 0 ][ 'max' ];
  
    $runningNo = '';
    if ( $maxRqNo == '0' ) {
      $runningNo = '00001';
    } else {
      $latestRunningNo = ( int )substr( $maxRqNo, 6 ) + 1;
     
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
  public function addReReqDetails(){
    
    $sql = $this->prepare("update Reimbursement_Request SET withdraw_date=?, withdraw_name=?, employee_id=?, 
    line_id=?, bank_name=?, tax_number=?, bank_book_name=?, bank_book_number=? , 
    details=?,due_date=? WHERE re_req_no  = ?");
    $sql->execute([
      
      input::post( 'withdrawDate' ),
      input::post( 'withdrawName' ),
      input::post( 'employeeId' ),
      input::post( 'employeeLine' ),
      input::post( 'bankName' ),
      input::post( 'taxNumber' ),
      input::post( 'bankBookName' ),
      input::post( 'bankBookNumber' ),
      input::post( 'table' ),
      input::post( 'dueDate' ),
      input::post( 're_req_no' )
      
      
    ]);
    print_r($sql->errorInfo());
    
    return input::post( 're_req_no' );
    
    
  }
  
  
  public function uploadImgForReReq() {
    $Quotation_pic = $_FILES['quotation_pic']; 
    
    
   
    $rq_no = $this->assign_re_req_no();
     
     
       $file1 = file_get_contents($Quotation_pic['tmp_name']);
       $file1 = base64_encode($file1);
       $file1Name = $Quotation_pic['name'];
       $file1Type = $Quotation_pic['type'];
     
      
  
      $sql = $this->prepare("insert into Reimbursement_Request (re_req_no, quotation_name, quotation_type, quotation_data)
                               values( ?, ?, ?, ?)" );
      $success = $sql->execute([
         $rq_no,
         $file1Name,
         $file1Type,
         $file1
       
      ]);
      return $rq_no;
      
  
      
    
 
  
  }

public function getStockPo() {
  $sql = $this->prepare( "SELECT
  PO.po_no,
  PO.po_date,
  PO.supplier_no,
  Supplier.supplier_name,
  PO.product_type,
  PO.total_purchase_no_vat,
  PO.total_purchase_vat,
  PO.total_purchase_price,
  PO.approved_employee,
  PO.product_line,
  POPrinting.quantity,
  POPrinting.so_no,
  POPrinting.purchase_no_vat AS poprinting_purchase_no_vat,
  POPrinting.purchase_vat AS poprinting_purchase_vat,
  POPrinting.purchase_price AS poprinting_purchase_price,
  POPrinting.total_purchase_price AS poprinting_total_purchase_price,
  Product.product_no,
  Product.product_name,
  Invoice.commission,
  Invoice.total_sales_no_vat,
  Invoice.total_sales_vat,
  Invoice.total_sales_price
FROM
  PO
INNER JOIN POPrinting ON POPrinting.po_no = PO.po_no
LEFT JOIN Product ON Product.product_no = POPrinting.product_no
LEFT JOIN Invoice ON Invoice.file_no = POPrinting.so_no
INNER JOIN Supplier ON Supplier.supplier_no = PO.supplier_no AND Supplier.product_line = PO.product_line
WHERE
(PO.product_type = 'Stock' OR PO.product_type = 'install') AND POPrinting.received = 0 AND POPrinting.cancelled = 0 AND PO.approved_employee = ?" );
  $sql->execute( [ json_decode( session::get( 'employee_detail' ), true )[ 'employee_id' ] ] );
  if ( $sql->rowCount() > 0 ) {
    return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
  }
  return json_encode( [] );
}

public function confirmPO() {

  $ciItemsArray = json_decode( input::post( 'ciItems' ), true );
  $ciItemsArray = json_decode( $ciItemsArray, true );

  $poList = array();
  //echo print_r($ciItemsArray);
  foreach ( $ciItemsArray as $value ) {

    if ( array_key_exists( $value[ 'po_no' ], $poList ) ) {

    } else {

      // $poList += [ $value[ 'po_no' ] => 'ha' ];

      $sql = $this->prepare( "update PO set received = 2 where po_no = ?");
      $success1 = $sql->execute([$value[ 'po_no' ]]);
      //echo $value[ 'po_no' ];
      //echo print_r($sql->errorInfo());
      $sql = $this->prepare( "update POPrinting set received = 2 where po_no = ?");
      $success2 = $sql->execute([$value[ 'po_no' ]]);
      //echo print_r($sql->errorInfo());
    }
  }

  //if($sucess1 && $success2) {
    echo $cino . ' (' . $value[ 'po_no' ] . ') ';
  // } else {
  //   echo $success1;
  //   echo '                ';
  //   echo $success2;
  //   echo 'fail';
  // }
}
public function addPVC(){
    
  $sql = $this->prepare("update  PVC_Demo SET Withdraw_Date=?, Withdraw_Name=?, Employee_ID=?, 
  Employee_Line=?, Bank_Name=?, Tax_Number=?, Bank_Book_Name=?, Bank_Book_Number=? ,Authorize_Name=?, 
  Table_Of_Details=? WHERE PVC_No = ?");
  $success = $sql->execute([
    
    input::post( 'withdrawDate' ),
    input::post( 'withdrawName' ),
    input::post( 'employeeId' ),
    input::post( 'employeeLine' ),
    input::post( 'bankName' ),
    input::post( 'taxNumber' ),
    input::post( 'bankBookName' ),
    input::post( 'bankNumber' ),
    input::post( 'authorizerName' ),
    input::post( 'table' ),
    input::post( 'PVC_No' )
    
    
  ]);
  if ($success){ echo ' help สำเร็จ';
  echo input::post( 'PVC_No' ).'<br>';
    print_r($sql->errorInfo());}
  else {
       ' error'.'<br>';
    echo input::post( 'PVC_No' ).'<br>';
    print_r($sql->errorInfo());
  }
  
}


public function uploadImgForPVC() {
  $Quotation_pic = $_FILES['Quotation_pic'];
  if(filesize($Quotation_pic['tmp_name']) > 50000) {
    $success = false;
    $re->cause = 'File size too big!! Max file size is 50 kb.';
  } else if(!@is_array(getimagesize($Quotation_pic['tmp_name']))){
    $success = false;
    $re->cause = 'File is not an image.';
  } else {
   $rq_no = $this->assign_pvc_no();
   
   if(isset($Quotation_pic)) {
     $file1 = file_get_contents($Quotation_pic['tmp_name']);
     $file1 = base64_encode($file1);
     $file1Name = $Quotation_pic['name'];
     $file1Type = $Quotation_pic['type'];
   }
    

    $sql = $this->prepare("insert into PVC_Demo (PVC_No, quotation_name, quotation_type, quotation_image)
                             values( ?, ?, ?, ?)" );
    $success = $sql->execute([
       $rq_no,
       $file1Name,
       $file1Type,
       $file1
     
    ]);

    $re->rq_no = $rq_no;
  }
  if ($success) {
    $re->success = true;
    echo json_encode($re);
  } else {
    $re->success = false;
    $re->errorlog = print_r($sql->errorInfo()); //have to change dataType to text to check sql error
    echo json_encode($re);
  }
}


  public function requestWSD(){
    $sql = $this->prepare("SELECT
                            Invoice.invoice_no
                            FROM
                                SOX
                            LEFT JOIN SOXPrinting ON SOX.sox_no = SOXPrinting.sox_no
                            LEFT JOIN Invoice ON Invoice.file_no = SOXPrinting.so_no
                            where SOX.sox_no = ?
                          ");
    $sql->execute([input::post('sox_no')]);
    if ($sql->rowCount() > 0) {             
      $iv_no = $sql->fetchAll(PDO::FETCH_ASSOC); 
    }

    $sql = $this->prepare("SELECT
                            Invoice.id_no
                            FROM
                                SOX
                            LEFT JOIN SOXPrinting ON SOX.sox_no = SOXPrinting.sox_no
                            LEFT JOIN Invoice ON Invoice.file_no = SOXPrinting.so_no
                            where SOX.sox_no = ?
                          ");
    $sql->execute([input::post('sox_no')]);
    if ($sql->rowCount() > 0) {             
      $vat_id = $sql->fetchAll(PDO::FETCH_ASSOC)[0]['id_no']; 
    }


    foreach ( $iv_no as $value ) {
      $wsdno = $this->assignWSD( ); 
      $sql = $this->prepare("INSERT into WSD(wsd_no, wsd_date, wsd_time, employee_id, total_amount, vat_id, sox_no, invoice_no, note, bank, bank_no, recipient, recipient_address, wsd_status)
                            values (?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, 0)");
      $sql->execute([
        $wsdno,
        json_decode(session::get('employee_detail'), true)['employee_id'],
        input::post('totalAmount'),
        $vat_id,
        input::post('sox_no'),  
        $value[ 'invoice_no' ],
        input::post('note'),
        input::post('bank'),
        input::post('bank_no'),
        input::post('recipient')
      ]);
    }


  }

/////////pvd/////////
private function assignWSD() {
  $wsdPrefix = 'EXD-';
  $sql = $this->prepare( "select ifnull(max(wsd_no),0) as max from WSD where wsd_no like ?" );
  $sql->execute( [ 'EXD-%' ] );
  $maxwsdNo = $sql->fetchAll()[ 0 ][ 'max' ];
  $runningNo = '';
  if ( $maxwsdNo == '0' ) {
    $runningNo = '00001';
  } else {
    $latestRunningNo = ( int )substr( $maxwsdNo, 4 ) + 1;
    if ( strlen( $latestRunningNo ) == 5 ) {
      $runningNo = $latestRunningNo;
    } else {
      for ( $x = 1; $x <= 5 - strlen( $latestRunningNo ); $x++ ) {
        $runningNo .= '0';
      }
      $runningNo .= $latestRunningNo;
    }
  }
  return $wsdPrefix . $runningNo;
}
public function getOSDashboardSox($line){
  $sql = $this->prepare("SELECT SOXPrinting.sox_no,SO.so_no,SO.product_line,SOX.employee_id
  FROM SOXPrinting 
  INNER JOIN SO ON SOXPrinting.so_no = SO.so_no 
  INNER JOIN SOX ON SOXPrinting.sox_no = SOX.sox_no 
  WHERE SO.product_line=$line AND SOX.employee_id=?;");
  $sql->execute( [ json_decode( session::get( 'employee_detail' ), true )[ 'employee_id' ] ] );
   if ($sql->rowCount() > 0) {             
    return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
  }
}
public function getAllOSDashboardSox(){
  $sql = $this->prepare("SELECT SOXPrinting.sox_no,SO.so_no,SO.product_line,SOX.employee_id
  FROM SOXPrinting 
  INNER JOIN SO ON SOXPrinting.so_no = SO.so_no 
  INNER JOIN SOX ON SOXPrinting.sox_no = SOX.sox_no 
  WHERE SOX.employee_id=?;");
  $sql->execute( [ json_decode( session::get( 'employee_detail' ), true )[ 'employee_id' ] ] );
   if ($sql->rowCount() > 0) {             
    return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
  }
}
public function getOSDashboardSoxAll($line){
  $sql = $this->prepare("SELECT SOXPrinting.sox_no,SO.so_no,SO.product_line,SOX.employee_id
  FROM SOXPrinting 
  INNER JOIN SO ON SOXPrinting.so_no = SO.so_no 
  INNER JOIN SOX ON SOXPrinting.sox_no = SOX.sox_no 
  WHERE  SO.product_line=$line;");
  $sql->execute( );
   if ($sql->rowCount() > 0) {             
    return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
  }
}
public function getAllAllOSDashboardSox(){
  $sql = $this->prepare("SELECT SOXPrinting.sox_no,SO.so_no,SO.product_line,SOX.employee_id
  FROM SOXPrinting 
  INNER JOIN SO ON SOXPrinting.so_no = SO.so_no 
  INNER JOIN SOX ON SOXPrinting.sox_no = SOX.sox_no 
  WHERE 1;");
  $sql->execute( );
   if ($sql->rowCount() > 0) {             
    return json_encode( $sql->fetchAll( PDO::FETCH_ASSOC ), JSON_UNESCAPED_UNICODE );
  }
}

  

}