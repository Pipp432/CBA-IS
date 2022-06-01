<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController" ng-init="getDetail()">

        <div class="row px-2 mt-2">
            <div class="col-4 pl-0 pr-3">
                <img src="/public/img/cba-logo.png" alt="cba-logo" style="width: 60%;">
            </div>
            <div class="col-4 px-0">
                <h3 style="text-align: center;"><b>ใบรับสินค้า<br>Receiving Report</b></h3>
            </div>
            <div class="col-4 px-0">
                <h5 style="text-align: right;">สำเนา</h5>
                <h5 style="text-align: right;"><b>เลขที่ {{detail[0].rr_no}}</b></h5>
                <h6 style="text-align: right;">วันที่ {{detail[0].rr_date}}</h6>
            </div>
        </div>
        
        <div class="row px-2 mt-2">
            <div class="col-12 px-0">
                <p class="my-0">
                    <b>ห้างหุ้นส่วนสามัญ บริษัทจำลองจุฬาลงกรณ์มหาวิทยาลัย 2565 (โครงการ {{company}})</b><br>
                    อาคารไชยยศสมบัติ 1 ห้องเลขที่ 315 ชั้นที่ 3 เลขที่ 254 ถนนพญาไท แขวงวังใหม่ เขตปทุมวัน กรุงเทพมหานคร 10330<br>
                    โทร. 0-2218-5762 โทรสาร. 0-2218-5762<br>
                </p>
            </div>
        </div>  
        
        <hr>

        <div class="row px-2 mt-2">
            <div class="col-12 px-0">
                <table style="border-collapse: collapse; width: 100%;">
                    <tr>
                        <td><b>ผู้ขาย</b> {{detail[0].supplier_name}}</td>
                        <td><b>รหัสผู้ขาย</b> {{detail[0].supplier_no}}</td>
                    </tr>
                    <tr>
                        <td colspan="2"><b>ที่อยู่</b> {{detail[0].address}}</td>
                    </tr>
                </table>
            </div>
        </div>  

        <div class="row px-2 mt-2">
            <table style="border-collapse: collapse; width: 100%;">
                <tr>
                    <th>รหัสสินค้า</th>
                    <th>รายการ</th>
                    <th>จำนวน</th>
                    <th>หน่วย</th>
                    <th>ราคา/หน่วย</th>
                    <th>จำนวนเงิน</th>
                </tr>
                <tr ng-repeat="item in detail" ng-show="item.total_purchase_price != 0">
                    <td style="text-align: center;">{{item.product_no}}</td>
                    <td style="text-align: left;">{{item.product_description}}</td>
                    <td style="text-align: right;">{{item.quantity}}</td>
                    <td style="text-align: left;">{{item.unit}}</td>
                    <td style="text-align: right;">{{item.purchase_price | number:2}}</td>
                    <td style="text-align: right;">{{item.total_purchase_price | number:2}}</td>
                </tr>
                <tr>
                    <th colspan="3" rowspan="3">
                    <div class="row">
                        <div class="col-4" style="text-align:left;">
                                    หมายเหตุ:
                        </div>
                        <div class="col-4">
                                <div class="row">
									<div class="col">
										<img ng-src="/public/img/scm_sign.jpg" style="width: 40%; " />
									</div>
								</div>
								<div class="row" style="margin-top: -7%">
                                    <div class="col">
                                     __________________________<br>ฝ่าย SCM<br>วันที่ {{day}}/{{month}}/{{year}} 
                                    </div>
                                </div>
                            </div>
                        <div class="col-4">
                        <br> __________________________<br>ผู้ส่งสินค้า<br>วันที่ _____/_____/_____ 
                        </div>
                    </div>
                
                <!--    </th>
                    <th colspan="2" style="text-align: right;">ราคาสินค้า</th>
                    <th colspan="1" style="text-align: right;">{{detail[0].total_purchase_no_vat | number:2}}</th>
                </tr>
                <tr>
                    <th colspan="2" style="text-align: right;">ภาษีมูลค่าเพิ่ม 7%</th>
                    <th colspan="1" style="text-align: right;">{{detail[0].total_purchase_vat | number:2}}</th>
                </tr>
                <tr>
                    <th colspan="2" style="text-align: right;">ราคารวมทั้งสิ้น</th>
                    <th colspan="1" style="text-align: right;">{{detail[0].total_price | number:2}}</th>
                </tr> -->
            </table>
        </div> 
        
        <br>
        
        
    
    </div>

</body>

</html>

<style>
    body, h1, h2, h3, h4, h5, h6, p { font-family: 'Sarabun', sans-serif; }
    table, th, td { border: 1px solid black; padding: 5px; }
    th { text-align: center; }
</style>

<script>
    
    app.controller('moduleAppController', function($scope) {
        $scope.getDetail = function() {
            $scope.detail = <?php echo $this->rr; ?>;
            $scope.company = $scope.detail[0].rr_no.substring(0,1);
            $scope.year = $scope.detail[0].rr_date.substring(0,4);
            $scope.month = $scope.detail[0].rr_date.substring(5,7);
			$scope.day = $scope.detail[0].rr_date.substring(8,10);
            switch($scope.company) {
                case '1': $scope.company_id = '0-9920-04240-25-5'; break;
                case '2': $scope.company_id = '0-9920-04240-26-3'; break;
                case '3': $scope.company_id = '0-9920-04240-24-7'; break;
                default: $scope.company_id = 'XXX'; break;
            }
        }
    });
    
</script>