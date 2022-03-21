<!DOCTYPE html>
<html>

<body>

    <script>
        app.controller('app_controller', function($scope) {
            $scope.get_detail = function() {
                $scope.detail = <?php echo $this->rv; ?>[0];
                $scope.project_no = $scope.detail.dv_no.substring(0, 1);
                $scope.total_amount_text = num_to_thai(Number($scope.detail.total_amount));
            }
        });
    </script>

    <div class="container mt-3" ng-controller="app_controller" ng-init="get_detail()">

        <div class="row px-2 mt-2">
            <div class="col-9 pl-0 pr-3">
                <div class="row">
                    <div class="col-2 pr-0">
                        <img src="/public/img/logo_icon2.png" alt="bizcube-logo" style="width:100%;">
                    </div>
                    <div class="col-10">
                        <p class="my-0">
                            <b>ห้างหุ้นส่วนสามัญ บริษัทจำลองจุฬาลงกรณ์มหาวิทยาลัย 2563 (โครงการพิเศษ {{project_no}})</b><br>
                            <small>อาคารไชยยศสมบัติ 1 เลขที่ 254 ชั้นใต้ดิน ถนนพญาไท แขวงวังใหม่ เขตปทุมวัน กรุงเทพมหานคร 10330</small><br>
                            โทร. 0-2218-5746-9 โทรสาร. 0-2218-5762<br>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-3 px-0">
                <h6 style="text-align: right;">วันที่ _____/_____/_____</h6>
            </div>
        </div>
        
        <h4 class="mt-3 mb-2" style="text-align: center;"><b>ใบสำคัญรับเงิน<br><small>Receipt Voucher</small></b></h4>
        
        <hr>

        <div class="row px-2 mt-2">
            <div class="col-12 px-0">
                <h6 class="mt-4 mb-4" style="line-height:40px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ข้าพเจ้า <u>&nbsp;&nbsp;&nbsp;{{detail.payee_name}}&nbsp;&nbsp;&nbsp;&nbsp;</u> เลขประจำตัวประชาชน <u>&nbsp;&nbsp;&nbsp;{{detail.payee_id_no}}&nbsp;&nbsp;&nbsp;</u> ที่อยู่ <u>&nbsp;&nbsp;&nbsp;{{detail.payee_address}}&nbsp;&nbsp;&nbsp;</u></h6>
                <h6 class="mb-4" style="line-height:40px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ได้รับเงินจาก <b>ห้างหุ้นส่วนสามัญ บริษัทจำลองจุฬาลงกรณ์มหาวิทยาลัย 2563 (โครงการพิเศษ {{project_no}})</b> ดังรายการต่อไปนี้</h6>
            </div>
        </div>  

        <div class="row px-2 mt-2">
            <table style="border-collapse: collapse; width: 100%;">
                <tr>
                    <th style="width:8%;">ลำดับ</th>
                    <th style="width:50%;">รายการ</th>
                    <th style="width:17%;">จำนวนเงิน (บาท)</th>
                </tr>
                <tr>
                    <td style="text-align: center;">1</td>
                    <td style="text-align: left;">{{detail.account_name}}</td>
                    <td style="text-align: right;">{{detail.total_amount | number:2}}</td>
                </tr>
                <tr>
                    <th colspan="2" style="text-align: left;">จำนวนเงิน (ตัวอักษร) : {{total_amount_text}}</th>
                    <th style="text-align: right;">รวม {{detail.total_amount | number:2}}</th>
                </tr>
            </table>
        </div> 

        <div class="row mt-5" style="text-align: center;">
            <div class="col">
                ลงชื่อ .............................................<br>
                ผู้รับเงิน<br>
                วันที่ _____/_____/_____
            </div>
            <div class="col">
                ลงชื่อ .............................................<br>
                ผู้จ่ายเงิน<br>
                วันที่ _____/_____/_____
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