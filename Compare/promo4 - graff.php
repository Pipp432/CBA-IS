<?php

namespace model;

use _core\model;
use _core\helper\session;
use _core\helper\input;
use _core\helper\uri;
use _core\helper\thaiNum;
use PDO;

class mktModel extends model {

    private function promotionTournamentWeek4 ($sono) {

        $soItemsArray = json_decode(input::post('soItems'), true); 
        $soItemsArray = json_decode($soItemsArray, true);
        $extraPoint = 0;

        if(is_numeric(input::post('sellerNo'))) {

            switch (json_decode(session::get('employee_detail'), true)['product_line']) {

                // ไม่เคยขายสายเรา ทีวี 1 เครื่อง 50 pts
                case '1':
                    //เคยขายไหม
                    $sql = $this->prepare("SELECT COUNT(*) AS count FROM SO WHERE employee_id = ? AND product_line = 1 AND cancelled = 0 AND so_no != ?");
                    $sql->execute([input::post('sellerNo')]);
                    $count = $sql->fetchAll()[0]['count'];

                    $sql = $this->prepare("select * from 
                                        (select count(*) as countProLine1 from PointLog where employee_id = ? and remark = 'Week 4 - Line 1.1' and cancelled = 0) as countProLine1");

                    $sql->execute([input::post('sellerNo')]);
                    $temp = $sql->fetchAll(PDO::FETCH_ASSOC)[0];

                    // ไม่เคยขาย

                    if($count == 0 && $temp['countProLine1'] == 0) {

                        $sql = $this->prepare("select ifnull(sum(quantity), 0) as countSold from SO 
                                            inner join SOPrinting on SOPrinting.so_no = SO.so_no
                                            inner join Product on Product.product_no = SOPrinting.product_no
                                            inner join ProductCategory on ProductCategory.category_no = Product.category_no and ProductCategory.product_line = Product.product_line
                                            where employee_id = ? and Product.product_line = '1' and Product.category_no = '08' and SO.cancelled = 0 
                                            and ((so_date = '2022-06-20' AND so_time >= '12:00:00') OR so_date between '2022-06-21' AND '2022-06-25')");

                        $sql->execute([input::post('sellerNo')]);
                        $countSold = $sql->fetchAll()[0]['countSold'];

                        if($countSold > 0) {

                            $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
													values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
							$sql->execute([input::post('sellerNo'), 50, 'Week 4 - Line 1.1', $sono]);
							//print_r($sql->errorInfo());
							echo ' (ผ่านโปรสาย 1.1 ได้รับ 50 พ้อยท์!!!) ';

                        }

                    }

                    break;

                    // เคยขายสายเรา ขายแอร์ 1 เครื่อง 70 pts
                case '2': 
                    // เคยขายไหม
                    $sql = $this->prepare("SELECT COUNT(*) AS count FROM SO WHERE employee_id = ? AND product_line = 2 AND cancelled = 0 AND so_no != ?");
                    $sql->execute([input::post('sellerNo')]);
                    $count = $sql->fetchAll()[0]['count'];

                    $sql = $this->prepare("select * from 
                                        (select count(*) as countProLine1 from PointLog where employee_id = ? and remark = 'Week 4 - Line 2.1' and cancelled = 0) as countProLine1,
                                        (select count(*) as countProLine2 from PointLog where employee_id = ? and remark = 'Week 4 - Line 2.2' and cancelled = 0) as countProLine2");

                    $sql->execute([input::post('sellerNo')]);
                    $temp = $sql->fetchAll(PDO::FETCH_ASSOC)[0];

                    // ไม่เคยขาย

                    if($count == 0 && $temp['countProLine1'] == 0) {
						$sql = $this->prepare("select ifnull(sum(total_sales), 0) as totalSold from SO 
                                            inner join SOPrinting on SOPrinting.so_no = SO.so_no
                                            inner join Product on Product.product_no = SOPrinting.product_no
                                            inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
                                            where employee_id = ? and Product.product_line = '2' and Product.category_no in ('02','03')
                                            and SO.cancelled = 0 and ((so_date = '2022-06-20' AND so_time >= '12:00:00') OR so_date between '2022-06-21' AND '2022-06-25')");
						$sql->execute([input::post('sellerNo')]);
						$totalSold = $sql->fetchAll()[0]['totalSold'];
                    
						if($totalSold >= 1000) {
							$sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
													values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
							$sql->execute([input::post('sellerNo'), 30, 'Week 4 - Line 2(1)', $sono]);
							//print_r($sql->errorInfo());
							echo ' (ผ่านโปรสาย 2.1 ได้รับ 30 พ้อยท์!!!) ';
							}
					}

                    // เคยขาย

                    if($count > 0 && $temp['countProLine2'] == 0){
						
						$sql = $this->prepare("select ifnull(sum(quantity), 0) as countSold from SO 
                                            inner join SOPrinting on SOPrinting.so_no = SO.so_no
                                            inner join Product on Product.product_no = SOPrinting.product_no
                                            inner join ProductCategory on ProductCategory.category_no = Product.category_no and ProductCategory.product_line = Product.product_line
                                            where employee_id = ? and Product.product_line = '2' and Product.category_no in ('01') and SO.cancelled = 0 
											and ((so_date = '2022-06-20' AND so_time >= '12:00:00') OR so_date between '2022-06-21' AND '2022-06-25')");

						$sql->execute([input::post('sellerNo')]);
						$countSold = $sql->fetchAll()[0]['countSold'];
						
						if($countSold > 0) {
							$sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
												values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
							$sql->execute([input::post('sellerNo'), 70, 'Week 4 - Line 2(2)', $sono]);
							//print_r($sql->errorInfo());
							echo ' (ผ่านโปรสาย 2.2 ได้รับ 70 พ้อยท์!!!) ';
                    	}
					}
			
					break;

                case '3':
                    // เคยขายไหม
                    $sql = $this->prepare("SELECT COUNT(*) AS count FROM SO WHERE employee_id = ? AND product_line = 3 AND cancelled = 0 AND so_no != ?");
                    $sql->execute([input::post('sellerNo')]);
                    $count = $sql->fetchAll()[0]['count'];

                    $sql = $this->prepare("select * from 
                                        (select count(*) as countProLine1 from PointLog where employee_id = ? and remark = 'Week 4 - Line 3.1' and cancelled = 0) as countProLine1,
                                        (select count(*) as countProLine2 from PointLog where employee_id = ? and remark = 'Week 4 - Line 3.2' and cancelled = 0) as countProLine2");

                    $sql->execute([input::post('sellerNo')]);
                    $temp = $sql->fetchAll(PDO::FETCH_ASSOC)[0];

                    // ไม่เคยขาย

                    if($count == 0 && $temp['countProLine1'] == 0) { 
                        // ต้นไม้ 20
						$sql = $this->prepare("select ifnull(sum(quantity), 0) as countSold from SO 
                                            inner join SOPrinting on SOPrinting.so_no = SO.so_no
                                            inner join Product on Product.product_no = SOPrinting.product_no
                                            inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
                                            where employee_id = ? and Product.product_line = '3' and Product.category_no = '07'
                                            and SO.cancelled = 0 and ((so_date = '2022-06-20' AND so_time >= '12:00:00') OR so_date between '2022-06-21' AND '2022-06-25')");
						$sql->execute([input::post('sellerNo')]);
						$countSold = $sql->fetchAll()[0]['countSold'];
                    
						if($countSold > 0) {
							$sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
													values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
							$sql->execute([input::post('sellerNo'), 20, 'Week 4 - Line 3.1', $sono]);
							//print_r($sql->errorInfo());
							echo ' (ผ่านโปรสาย 3.1 ได้รับ 20 พ้อยท์!!!) ';
                            break;
							}

                        // เครื่องครัว
                        $sql = $this->prepare("select ifnull(sum(quantity), 0) as countSold from SO 
                                            inner join SOPrinting on SOPrinting.so_no = SO.so_no
                                            inner join Product on Product.product_no = SOPrinting.product_no
                                            inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
                                            where employee_id = ? and Product.product_line = '3' and Product.category_no = '18'
                                            and SO.cancelled = 0 and ((so_date = '2022-06-20' AND so_time >= '12:00:00') OR so_date between '2022-06-21' AND '2022-06-25')");
						$sql->execute([input::post('sellerNo')]);
						$countSold = $sql->fetchAll()[0]['countSold'];
                    
						if($countSold > 0) {
							$sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
													values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
							$sql->execute([input::post('sellerNo'), 20, 'Week 4 - Line 3.1', $sono]);
							//print_r($sql->errorInfo());
							echo ' (ผ่านโปรสาย 3.1 ได้รับ 20 พ้อยท์!!!) ';
                            break;
							}

                        // เซตกระทะ
                        $sql = $this->prepare("select ifnull(sum(quantity), 0) as countSold from SO 
                                            inner join SOPrinting on SOPrinting.so_no = SO.so_no
                                            inner join Product on Product.product_no = SOPrinting.product_no
                                            inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
                                            where employee_id = ? and Product.product_line = '3' and Product.category_no = '19'
                                            and SO.cancelled = 0 and ((so_date = '2022-06-20' AND so_time >= '12:00:00') OR so_date between '2022-06-21' AND '2022-06-25')");
						$sql->execute([input::post('sellerNo')]);
						$countSold = $sql->fetchAll()[0]['countSold'];
                    
						if($countSold > 0) {
							$sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
													values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
							$sql->execute([input::post('sellerNo'), 20, 'Week 4 - Line 3.1', $sono]);
							//print_r($sql->errorInfo());
							echo ' (ผ่านโปรสาย 3.1 ได้รับ 20 พ้อยท์!!!) ';
                            break;
							}

					}

                    // เคยขาย
                    if($count > 0 && $temp['countProLine2'] == 0) {
                        // หม้อทอดไฟฟ้า 
                        $sql = $this->prepare("select ifnull(sum(quantity), 0) as countSold from SO 
                                                inner join SOPrinting on SOPrinting.so_no = SO.so_no
                                                inner join Product on Product.product_no = SOPrinting.product_no
                                                inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
                                                where employee_id = ? and Product.product_line = '3' and Product.category_no = '03'
                                                and SO.cancelled = 0 and ((so_date = '2022-06-20' AND so_time >= '12:00:00') OR so_date between '2022-06-21' AND '2022-06-25')");
                        $sql->execute([input::post('sellerNo')]);
                        $countSold = $sql->fetchAll()[0]['countSold'];

                        if($countSold > 0) {
                            $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
                                values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
                            $sql->execute([input::post('sellerNo'), 30, 'Week 4 - Line 3.2', $sono]);
                            //print_r($sql->errorInfo());
                            echo ' (ผ่านโปรสาย 3.2 ได้รับ 30 พ้อยท์!!!) ';
                            break;
                            }

                        // เก้าอี้ 
                        $sql = $this->prepare("select ifnull(sum(quantity), 0) as countSold from SO 
                                            inner join SOPrinting on SOPrinting.so_no = SO.so_no
                                            inner join Product on Product.product_no = SOPrinting.product_no
                                            inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
                                            where employee_id = ? and Product.product_line = '3' and Product.category_no = '20'
                                            and SO.cancelled = 0 and ((so_date = '2022-06-20' AND so_time >= '12:00:00') OR so_date between '2022-06-21' AND '2022-06-25')");
                        $sql->execute([input::post('sellerNo')]);
                        $countSold = $sql->fetchAll()[0]['countSold'];

                        if($countSold > 0) {
                            $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
                                    values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
                            $sql->execute([input::post('sellerNo'), 30, 'Week 4 - Line 3.2', $sono]);
                            //print_r($sql->errorInfo());
                            echo ' (ผ่านโปรสาย 3.2 ได้รับ 30 พ้อยท์!!!) ';
                            break;
                            }

                        // โต๊ะ
                        $sql = $this->prepare("select ifnull(sum(quantity), 0) as countSold from SO 
                                            inner join SOPrinting on SOPrinting.so_no = SO.so_no
                                            inner join Product on Product.product_no = SOPrinting.product_no
                                            inner join ProductCategory on ProductCategory.product_line = Product.product_line and ProductCategory.category_no = Product.category_no
                                            where employee_id = ? and Product.product_line = '3' and Product.category_no = '21'
                                            and SO.cancelled = 0 and ((so_date = '2022-06-20' AND so_time >= '12:00:00') OR so_date between '2022-06-21' AND '2022-06-25')");
                        $sql->execute([input::post('sellerNo')]);
                        $countSold = $sql->fetchAll()[0]['countSold'];

                        if($countSold > 0) {
                            $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note,type, cancelled) 
                                                    values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?,'Promotion', 0)");
                            $sql->execute([input::post('sellerNo'), 30, 'Week 4 - Line 3.2', $sono]);
                            //print_r($sql->errorInfo());
                            echo ' (ผ่านโปรสาย 3.2 ได้รับ 30 พ้อยท์!!!) ';
                            break;
                            }

                    }

                    break;

					






            }

        }
    }
}