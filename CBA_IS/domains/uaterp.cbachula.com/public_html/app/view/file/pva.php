<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController" ng-init="getDetail()">

        <div class="row px-2 mt-2">
            <div class="col-4 pl-0 pr-3">
                <img src="/public/img/cba-logo.png" alt="cba-logo" style="width: 60%;">
            </div>
            <div class="col-4 px-0">
                <h3 style="text-align: center;"><b>ใบสั่งเติมเงินรองจ่าย<br>Payment Voucher</b></h3>
            </div>
            <div class="col-4 px-0">
                <h5 style="text-align: right;">ต้นฉบับ</h5>
                <h5 style="text-align: right;"><b>เลขที่ {{detail[0].pv_no}}</b></h5>
                <h6 style="text-align: right;">วันที่ {{detail[0].pv_date}}</h6>
            </div>
        </div>
        
        <div class="row px-2 mt-2">
            <div class="col-12 px-0">
                <p class="my-0">
                    <b>ห้างหุ้นส่วนสามัญ บริษัทจำลองจุฬาลงกรณ์มหาวิทยาลัย 2565 (โครงการ 3)</b><br>
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
                        <td><b>สั่งจ่าย</b> พนักงานรองจ่าย </td>
                    </tr>
                    <tr>
                        <td><b>จ่ายเพื่อ</b> เติมเงินรองจ่าย </td>
                    </tr>
                </table>
            </div>
        </div>  

        <div class="row px-2 mt-2">
            <table style="border-collapse: collapse; width: 100%;">
                <tr>
                    <th>ใบเบิกเงินรองจ่ายลงวันที่</th>
                    <th>เลขที่ใบเบิกเงินรองจ่าย</th>
                    <th>จำนวน</th>
                </tr>
                <tr ng-repeat = "child in pvaChilds">
                    <td style="text-align: left;">{{child.pv_date}}</td>
                    <td style="text-align: left;">{{child.internal_pva_no}}</td>
                    <td style="text-align: right;">{{child.total_paid | number:2}}</td>
                </tr>
            
                <tr>
                    <td style="text-align: left;">จำนวนที่ต้องการเติมเพิ่ม</td>
                    <td style="text-align: left;">{{detail[0].additional_cash_reason}}</td>
                    <td style="text-align: right;">{{detail[0].additional_cash | number:2}}</td>
                </tr>

                <tr>
                    <th style="text-align: left;">จำนวนรวม </td>
                    <th style="text-align: left;">{{detail[1]}}</td>
                    <th style="text-align: right;">{{netTotal | number:2}}</td>
                </tr>
            </table>
        </div>  

        <div class="row px-2 mt-2">
            หมายเหตุ : {{detail[0].notes}}
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
            //$scope.company = $scope.detail[0].pv_no.substring(0,1);
            $scope.pvaChilds = <?php echo $this->pvaChilds; ?>;
            $scope.netTotal = parseFloat($scope.detail[0]["total_paid"]) + parseFloat($scope.detail[0]["additional_cash"]);
        }
    });
    
</script>