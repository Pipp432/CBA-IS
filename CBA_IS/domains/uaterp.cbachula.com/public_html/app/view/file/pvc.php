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
                <h5 style="text-align: right;">สำเนา</h5>
                <h5 style="text-align: right;"><b>เลขที่ {{detail[0].pv_no}}</b></h5>
                <h6 style="text-align: right;">วันที่ {{detail[0].pv_date}}</h6>
            </div>
        </div>
        
        <div class="row px-2 mt-2">
            <div class="col-12 px-0">
                <p class="my-0">
                    <b>ห้างหุ้นส่วนสามัญ บริษัทจำลองจุฬาลงกรณ์มหาวิทยาลัย 2564 (โครงการ {{company}})</b><br>
                    อาคารไชยยศสมบัติ 1 เลขที่ 254 ชั้นใต้ดิน ถนนพญาไท แขวงวังใหม่ เขตปทุมวัน กรุงเทพมหานคร 10330<br>
                    โทร. 0-2218-5746-9 โทรสาร. 0-2218-5762
                </p>
            </div>
        </div>  
        
        <hr>

        <div class="row px-2 mt-2">
            <div class="col-12 px-0">
                <table style="border-collapse: collapse; width: 100%;">
                    <tr>
                        <td><b>สั่งจ่าย</b> {{detail[0].pv_name}}</td>
                        <td><b>จ่ายเพื่อ</b> {{detail[0].pv_type}}</td>
                        <td><b>วันครบกำหนดจ่ายเงิน</b> {{detail[0].pv_due_date}}</td>
                    </tr>
                    <tr ng-show="detail[0].supplier_no != ''">
                        <td colspan="2"><b>ที่อยู่</b> {{detail[0].pv_address}}</td>
						<td><b>เลขที่ Supplier</b> {{detail[0].supplier_no}}</td>
                    </tr>
                    <tr ng-show="detail[0].supplier_no == ''">
                        <td colspan="3"><b>ที่อยู่</b> {{detail[0].pv_address}}</td>
                    </tr>
                </table>
            </div>
        </div>  

        <div class="row px-2 mt-2">
            <table style="border-collapse: collapse; width: 100%;">
                <tr>
                    <th>ใบสำคัญลงวันที่</th>
                    <th>เลขที่ใบสำคัญ/ใบรับวางบิล/<br>ใบกำกับภาษี/ใบเบิกค่าใช้จ่าย</th>
                    <th>รายละเอียด</th>
                    <th>เลขที่ RR/CI</th>
                    <th>จำนวน</th>
                    <th>หมายเหตุ</th>
                </tr>
              
                    <td style="text-align: left;">{{detail[0].pv_due_date}}</td>
                    <td style="text-align: left;">{{detail[0].ex_no}}</td>
                    <td style="text-align: left;">{{detail[0].pv_details}}</td>
                    <td style="text-align: left;">{{detail[0].re_req_no}}</td>
                    <td style="text-align: right;">{{item.paid_total | number:2}}</td>
                    <td style="text-align: left;">{{item.note}}</td>
                
                <tr>
                    <th colspan="1" style="text-align: center;">จำนวนเงิน</th>
                    <th colspan="2" style="text-align: left;">{{detail[0].total_paid_thai}}</th>
                    <th colspan="1" style="text-align: center;">รวม</th>
                    <th colspan="1" style="text-align: right;">{{detail[0].total_paid | number:2}}</th>
                </tr>
            </table>
        </div>  
        
        <div class="row px-2 mt-3">
            <p><i>หมายเหตุ : จ่าย {{detail[0].bank}}</i></p>
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
            $scope.detail = <?php echo $this->pvc; ?>;
            $scope.company = $scope.detail[0].pv_no.substring(0,1);
        }
    });
    
</script>