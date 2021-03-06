<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController" ng-init="getDetail()">

        <div class="row px-2 mt-2">
            <div class="col-4 pl-0 pr-3">
                <img src="/public/img/cba-logo.png" alt="cba-logo" style="width: 60%;">
            </div>
            <div class="col-4 px-0">
                <h3 style="text-align: center;"><b>ใบสำคัญสั่งจ่าย<br>Payment Voucher</b></h3>
            </div>
            <div class="col-4 px-0">
                <h5 style="text-align: right;">ต้นฉบับ</h5>
                <h5 style="text-align: right;"><b>เลขที่ P</b></h5>
                <h6 style="text-align: right;">วันที่ 2022-0</h6>
            </div>
        </div>
        
        <div class="row px-2 mt-2">
            <div class="col-12 px-0">
                <p class="my-0">
                    <b>ห้างหุ้นส่วนสามัญ บริษัทจำลองจุฬาลงกรณ์มหาวิทยาลัย 2565 (โครงการ P)</b><br>
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
                        <td><b>สั่งจ่าย</b> กรมสรรพากร</td>
                        <td><b>จ่ายเพื่อ</b> ภาษีมูลค่าเพิ่ม</td>
                        <td><b>วันครบกำหนดจ่ายเงิน</b> 2022-0</td>
                    </tr>
                </table>
            </div>
        </div>  

        <div class="row px-2 mt-2">
            <table style="border-collapse: collapse; width: 100%;">
                <tr>
                    <th>เอกสารอ้างอิงลงวันที่</th>
                    <th>หมายเลขอ้างอิง<br>ใบกำกับภาษี/ใบเบิกค่าใช้จ่าย</th>
                    <th>รายละเอียด</th>
                    <!-- <th>จำนวน</th> -->
                    <th>หมายเหตุ</th>
                </tr>
                <tr ng-repeat="item in detail">
                    <td style="text-align: left;">P</td>
                    <td style="text-align: left;">PE</td>
                    <td style="text-align: left;">P</td>
                    <!-- <td style="text-align: right;">P</td> -->
                    <td style="text-align: left;">P</td>
                </tr>
                <tr>
                    <th colspan="1" style="text-align: center;">P</th>
                    <th colspan="1" style="text-align: left;">P</th>
                    <th colspan="1" style="text-align: center;">P</th>
                    <th colspan="1" style="text-align: right;">P</th>
                </tr>
                <tr>
                    <th colspan="1" style="text-align: center;">จำนวนเงิน</th>
                    <th colspan="1" style="text-align: left;">0</th>
                    <th colspan="1" style="text-align: center;">รวม</th>
                    <th colspan="1" style="text-align: right;">0</th>
                </tr>
            </table>
        </div>  
        
        <div class="row px-2 mt-3">
            <!-- <p><i>หมายเหตุ : จ่าย {{detail[0].bank}}</i></p> -->
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
            $scope.detail = <?php echo $this->pv; ?>;
            $scope.company = $scope.detail[0].pv_no.substring(0,1);
        }
    });
    
</script>