<?php

namespace model;

use _core\model;
use _core\helper\session;
use _core\helper\input;
use _core\helper\uri;
use _core\helper\thaiNum;
use PDO;

class mktModel extends model {

    private function promotionWeek3 ($sono) {

        $soItemsArray = json_decode(input::post('soItems'), true); 
        $soItemsArray = json_decode($soItemsArray, true);
        $extrapoint = 0;
            
        if(is_numeric(input::post('sellerNo'))) {
            
            switch (json_decode(session::get('employee_detail'), true)['product_line']) {

                case '6': // ขายสินค้าหมวด food&beverage ครบ 300 บาท 5 points

                    $sql = $this->prepare("select * from 

                                        (select count(*) as countProLine1 from PointLog where employee_id = ? and remark = 'Week 3 - Line 6' and cancelled = 0) as countProLine1");

                    $sql->execute( [input::post('sellerNo')]);
                    $temp = $sql->fetchAll()[ 0 ];
			 
                    if ( $temp[ 'countProLine1' ] == 0 ) {

                        $sql = $this->prepare( "select ifnull(sum(total_sales), 0) as totalSold from SO 
                                            inner join SOPrinting on SOPrinting.so_no = SO.so_no
                                            inner join Product on Product.product_no = SOPrinting.product_no
                                            inner join ProductCategory on ProductCategory.category_no = Product.category_no and ProductCategory.product_line = Product.product_line
                                            where employee_id = ? and Product.product_line = '6' and Product.category_no = '01' and SO.cancelled = 0 and SO.so_date between '2022-06-13' and '2022-06-18'");

                        $sql->execute([input::post('sellerNo')]);

                        $totalSold = $sql->fetchAll()[0]['totalSold'];


                        $extraPoint = ( $totalSold >= 300 ) ? 5 : 0;


                        if ( $extraPoint > 0 ) {

                            $sql = $this->prepare( "insert into PointLog (date, time, employee_id, point, remark, note, type, cancelled) 
			  							values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?, 'Promotion', 0)" );

                            $sql->execute( [ input::post( 'sellerNo' ), $extraPoint, 'Week 3 - Line 6', $sono ] );

                            echo ' (ผ่านโปรสาย 6 ได้รับ 5 พ้อยท์!!!)';
                        }
                    }
                    break;

                case '7': // ขายสินค้าอะไรก็ได้ในสาย 7 ครบ 600 บาท 10 points

                    $sql = $this->prepare( "select * from 
                                        (select count(*) as countProLine1 from PointLog where employee_id = ? and remark = 'Week 3 - Line 7' and cancelled = 0) as countProLine1" );

                    $sql->execute( [ input::post( 'sellerNo' ) ] );

                    $temp = $sql->fetchAll()[ 0 ];


                    if ( $temp[ 'countProLine1' ] == 0 ) {


                        $sql = $this->prepare( "select ifnull(sum(total_sales), 0) as totalSold from SO 

                                            inner join SOPrinting on SOPrinting.so_no = SO.so_no

                                            inner join Product on Product.product_no = SOPrinting.product_no

                                            inner join ProductCategory on ProductCategory.category_no = Product.category_no and ProductCategory.product_line = Product.product_line

                                            where employee_id = ? and Product.product_line = '7' and Product.category_no in ('01','02','03','06','07','08','09','10')

                                            and SO.cancelled = 0 and SO.so_date between '2022-06-13' and '2022-06-18'" );

                        $sql->execute( [ input::post( 'sellerNo' ) ] );

                        $totalSold = $sql->fetchAll()[ 0 ][ 'totalSold' ];


                        $extraPoint = ( $totalSold >= 600 ) ? 10 : 0;


                        if ( $extraPoint > 0 ) {

                            $sql = $this->prepare( "insert into PointLog (date, time, employee_id, point, remark, note, type, cancelled) values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?, 'Promotion', 0)" );

                            $sql->execute( [ input::post( 'sellerNo' ), $extrapoint, 'Week 3 - Line 7', $sono ] );

                            echo ' (ผ่านโปรสาย 7 ได้รับ 10 พ้อยท์!!!)';
                        
                        }
                    }
                    break;
                
                case '8': // ขายสินค้าอะไรก็ได้ในสาย 8 ครบ 1000 บาท 5 points

                    $sql = $this->prepare( "select * from 
                                        (select count(*) as countProLine1 from PointLog where employee_id = ? and remark = 'Week 3 - Line 8' and cancelled = 0) as countProLine1" );

                    $sql->execute( [ input::post( 'sellerNo' ) ] );

                    $temp = $sql->fetchAll()[ 0 ];


                    if ( $temp[ 'countProLine1' ] == 0 ) {


                        $sql = $this->prepare( "select ifnull(sum(total_sales), 0) as totalSold from SO 

                                            inner join SOPrinting on SOPrinting.so_no = SO.so_no

                                            inner join Product on Product.product_no = SOPrinting.product_no

                                            inner join ProductCategory on ProductCategory.category_no = Product.category_no and ProductCategory.product_line = Product.product_line

                                            where employee_id = ? and Product.product_line = '8' and Product.category_no in ('01','02','03','04','05') 

                                            and SO.cancelled = 0 and SO.so_date between '2022-06-13' and '2022-06-18'" );

                        $sql->execute( [ input::post( 'sellerNo' ) ] );

                        $totalSold = $sql->fetchAll()[ 0 ][ 'totalSold' ];


                        $extraPoint = ( $totalSold >= 1000 ) ? 5 : 0;


                        if ( $extraPoint > 0 ) {

                            $sql = $this->prepare( "insert into PointLog (date, time, employee_id, point, remark, note, type, cancelled) values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?, 'Promotion', 0)" );

                            $sql->execute( [ input::post( 'sellerNo' ), $extrapoint, 'Week 3 - Line 8', $sono ] );

                            echo ' (ผ่านโปรสาย 8 ได้รับ 5 พ้อยท์!!!)';
                        
                        }
                    }
                    break;
                case '9': // ขายรองเท้า1 คู่ หรือสินค้าอะไรก็ได้ในครบ 600 บาท 10 points

                    $sql = $this->prepare( "select * from 
                    (select count(*) as countProLine1 from PointLog where employee_id = ? and remark = 'Week 3 - Line 9' and cancelled = 0) as countProLine1" );

                    $sql->execute( [ input::post( 'sellerNo' ) ] );

                    $temp = $sql->fetchAll()[ 0 ];


                    if ( $temp[ 'countProLine1' ] == 0 ) {

                        $sql = $this->prepare("select ifnull(sum(total_sales), 0) as totalSold from SO 
                                            inner join SOPrinting on SOPrinting.so_no = SO.so_no
                                            inner join Product on Product.product_no = SOPrinting.product_no
                                            inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
                                            where employee_id = ? and Product.product_line = '9' and Product.category_no in ('01','02')
                                            and SO.cancelled = 0 and SO.so_date between '2022-06-13' AND '2022-06-18')");
						$sql->execute([input::post('sellerNo')]);
						$totalSold = $sql->fetchAll()[0]['totalSold'];
                    
						if($totalSold >= 600) {
							$sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
													values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
							$sql->execute([input::post('sellerNo'), 10, 'Week 3 - Line 9', $sono]);
							//print_r($sql->errorInfo());
							echo ' (ผ่านโปรสาย 9 ได้รับ 10 พ้อยท์!!!) ';
							break;
							}
					}

					if($temp['countProLine1'] == 0) {
						$sql = $this->prepare("select ifnull(sum(quantity), 0) as countSold from SO 
                                            inner join SOPrinting on SOPrinting.so_no = SO.so_no
                                            inner join Product on Product.product_no = SOPrinting.product_no
                                            inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
                                            where employee_id = ? and Product.product_line = '9' and Product.sub_category = 'Shoes'
                                            and SO.cancelled = 0 and SO.so_date between '2022-06-13' and '2022-06-18'");
						$sql->execute([input::post('sellerNo')]);
						$countSold = $sql->fetchAll()[0]['countSold'];
                    
						if($countSold >= 1) {
							$sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
													values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
							$sql->execute([input::post('sellerNo'), 10, 'Week 3 - Line 9', $sono]);
							//print_r($sql->errorInfo());
							echo ' (ผ่านโปรสาย 9 ได้รับ 10 พ้อยท์!!!) ';
							break;
                        
                        }
                    }
                    break;

                case '0': // ขายสินค้าอะไรก็ได้ในสาย 10 ครบ 300 บาท 5 points

                    $sql = $this->prepare( "select * from 
                                        (select count(*) as countProLine1 from PointLog where employee_id = ? and remark = 'Week 3 - Line 10' and cancelled = 0) as countProLine1" );

                    $sql->execute( [ input::post( 'sellerNo' ) ] );

                    $temp = $sql->fetchAll()[ 0 ];


                    if ( $temp[ 'countProLine1' ] == 0 ) {


                        $sql = $this->prepare( "select ifnull(sum(total_sales), 0) as totalSold from SO 

                                            inner join SOPrinting on SOPrinting.so_no = SO.so_no

                                            inner join Product on Product.product_no = SOPrinting.product_no

                                            inner join ProductCategory on ProductCategory.category_no = Product.category_no and ProductCategory.product_line = Product.product_line

                                            where employee_id = ? and Product.product_line = '0' and Product.category_no in ('01','02','03','04','07')

                                            and SO.cancelled = 0 and SO.so_date between '2022-06-13' and '2022-06-18'" );

                        $sql->execute( [ input::post( 'sellerNo' ) ] );

                        $totalSold = $sql->fetchAll()[ 0 ][ 'totalSold' ];


                        $extraPoint = ( $totalSold >= 300) ? 5 : 0;


                        if ( $extraPoint > 0 ) {

                            $sql = $this->prepare( "insert into PointLog (date, time, employee_id, point, remark, note, type, cancelled) values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?, 'Promotion', 0)" );

                            $sql->execute( [ input::post( 'sellerNo' ), $extrapoint, 'Week 3 - Line 10', $sono ] );

                            echo ' (ผ่านโปรสาย 10 ได้รับ 5 พ้อยท์!!!)';
                        
                        }
                    }
                    break;
                
                }
            }
        }
}