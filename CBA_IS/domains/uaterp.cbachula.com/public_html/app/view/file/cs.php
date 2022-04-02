<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController" ng-init="getDetail()">

        <div class="row px-2 mt-2">
            <div class="col-4 pl-0 pr-3">
                <img src="/public/img/cba-logo.png" alt="cba-logo" style="width: 60%;">
            </div>
            <div class="col-4 px-0">
                <h3 style="text-align: center;"><b>Counter Sales</b></h3>
            </div>
            <div class="col-4 px-0">
                <h5 style="text-align: right;">สำเนา - <?php echo $this->type; ?></h5>
                <h5 style="text-align: right;"><b>เลขที่ {{detail[0].cs_no}}</b></h5>
            </div>
        </div>
        
        <div class="row px-2 mt-2">
            <div class="col-9 px-0">
                <p class="my-0">
                    <b>ห้างหุ้นส่วนสามัญ บริษัทจำลองจุฬาลงกรณ์มหาวิทยาลัย 2565 (โครงการ {{company}})</b><br>
                    อาคารไชยยศสมบัติ 1 ห้องเลขที่ 315 ชั้นที่ 3 เลขที่ 254 ถนนพญาไท แขวงวังใหม่ เขตปทุมวัน กรุงเทพมหานคร 10330<br>
                    โทร. 0-2218-5762 โทรสาร. 0-2218-5762<br>
                </p>
            </div>
            <div class="col-3 p-2" style="border: 1px solid black;">
                <p class="my-0">
                    <span style="font-size:20px;">▢</span> นับสินค้า<br>
                    <span style="font-size:20px;">▢</span> อัพ Tracking Sheet<br>
                    <span style="font-size:20px;">▢</span> กรอก Stock In<br>
                </p>
            </div>
        </div>  
        
        <hr>

        <div class="row px-2 mt-2">
            <div class="col-12 px-0">
                <table style="border-collapse: collapse; width: 100%;">
                    <tr>
                        <td><b>รหัส CE</b> {{detail[0].employee_name}}</td>
                        <td><b>สถานที่ออก CS</b> {{detail[0].location_name}}</td>
                    </tr>
                    <tr>
                        <td><b>วันที่เบิกสินค้า</b> {{detail[0].cs_date}}</td>
						<td><b>วันที่ส่งคืนสินค้า</b></td>
                    </tr>
                </table>
            </div>
        </div>  

        <div class="row px-2 mt-2">
            <table style="border-collapse: collapse; width: 100%;">
                <tr>
                    <th>ลำดับ</th>
                    <th>รหัสสินค้า</th>
                    <th>รายการ</th>
                    <th>ราคา/หน่วย<br>รวมภาษีมูลค่าเพิ่ม</th>
                    <th>หน่วย</th>
                    <th>ออก<br>(หน่วย)</th>
                    <th>เข้า<br>(หน่วย)</th>
					<th>ขาย<br>(หน่วย)</th>
                </tr>
                <tr ng-repeat="item in detail">
                    <td style="text-align: center;">{{$index+1}}</td>
                    <td style="text-align: center;">{{item.product_no}}</td>
                    <td style="text-align: left;">{{item.product_name}}</td>
                    <td style="text-align: right;">{{item.sales_price}}</td>
                    <td style="text-align: left;">{{item.unit}}</td>
                    <td style="text-align: right;">{{item.quantity}}</td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </div> 
        
        <div class="row px-2 mt-3">
            
            <div class="col-4 pl-0 pr-1">
                <table style="border-collapse: collapse; width: 100%;">
                    <tr>
                        <th colspan="2">พนักงานขาย</th>
                    </tr>
                    <tr>
                        <td style="text-align: center;"><br>________________________<br>(ชื่อเล่น _________________)<br>เบิกสินค้า</td>
                        <td style="text-align: center;"><br>________________________<br>(ชื่อเล่น _________________)<br>คืนสินค้า</td>
                    </tr>
                </table>
            </div>
            
            <div class="col-4 pl-1 pr-1">
                <table style="border-collapse: collapse; width: 100%;">
                    <tr>
                        <th colspan="2">พนักงานฝ่าย SCM</th>
                    </tr>
                    <tr>
                        <td style="text-align: center;"><br>________________________<br>(ชื่อเล่น _________________)<br>เบิกสินค้า</td>
                        <td style="text-align: center;"><br>________________________<br>(ชื่อเล่น _________________)<br>คืนสินค้า</td>
                    </tr>
                </table>
            </div>
            
            <div class="col-4 pl-1 pr-0">
                <table style="border-collapse: collapse; width: 100%;">
                    <tr>
                        <th colspan="2">ฝ่ายการเงิน</th>
                    </tr>
                    <tr>
                        <td style="text-align: center;"><br>________________________<br>(ชื่อเล่น _________________)<br>ผู้รับผิดชอบเงิน</td>
                        <td style="text-align: center;"><br>________________________<br>(ชื่อเล่น _________________)<br>ผู้รับเงิน</td>
                    </tr>
                </table>
            </div>
            
        </div> 
    
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
            $scope.detail = <?php echo $this->cs; ?>;
            $scope.company = $scope.detail[0].cs_no.substring(0,1);
        }
    });
    
</script>