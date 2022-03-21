<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController" ng-init="getDetail()">

        <div class="row px-2 mt-2">
            <div class="col-4 pl-0 pr-3">
                <img src="/public/img/cba-logo.png" alt="cba-logo" style="width: 60%;">
            </div>
            <div class="col-4 px-0">
                <h3 style="text-align: center;"><b>ใบสั่งซื้อ<br>Purchase Order</b></h3>
            </div>
            <div class="col-4 px-0">
                <h5 style="text-align: right;">สำเนา</h5>
                <h5 style="text-align: right;"><b>เลขที่ {{detail[0].po_no}}</b></h5>
                <h6 style="text-align: right;">วันที่ {{detail[0].po_date}}</h6>
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
                        <td><b>ผู้ขาย</b> {{detail[0].supplier_name}}</td>
                        <td><b>รหัสผู้ขาย</b> {{detail[0].supplier_no}}</td>
                    </tr>
                    <tr>
                        <td colspan="2"><b>ที่อยู่</b> {{detail[0].address}}</td>
                    </tr>
                </table>
            </div>
        </div>  
        
        <div ng-repeat="customer in detail | unique : 'customer_tel'">
            
            <hr style="border-top: 1px dashed;">
            
            <div class="row px-2 mt-2">
                <div class="col-12 px-0">
                    <h4><b>ข้อมูลลูกค้า</b></h4>
                    <div class="p-3" style="border:1px solid black;">
                        <h5 class="my-0"><b>ชื่อ</b> {{customer.customer_name}} {{customer.customer_surname}} <b>เบอร์โทร</b> {{customer.customer_tel}}</h5>
                        <h5 class="mt-3 mb-0"><b>ที่อยู่</b> {{customer.customer_address}}</h5>
                    </div>
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
                    <tr ng-repeat="item in detail" ng-show="customer.customer_tel===item.customer_tel && item.total_purchase_price != 0">
                        <td style="text-align: center;">{{item.product_no}}</td>
                        <td style="text-align: left;">{{item.product_description}}</td>
                        <td style="text-align: right;">{{item.quantity}}</td>
                        <td style="text-align: left;">{{item.unit}}</td>
                        <td style="text-align: right;">{{item.purchase_price | number:2}}</td>
                        <td style="text-align: right;">{{item.total_purchase_price | number:2}}</td>
                    </tr>
					
                </table>
            </div> 
            
            <br>
        
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
            $scope.detail = <?php echo $this->po; ?>;
            $scope.company = $scope.detail[0].po_no.substring(0,1);
        }
    });
    
</script>