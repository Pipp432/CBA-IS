<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController" ng-init="getDetail()">

        <div class="row px-2 mt-2" ng-init="CombinePO()">
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

                    <tr ng-repeat="product in detail | unique : 'product_no'">
                        <td style="text-align: center;">{{product.product_no}}</td>
                        <td style="text-align: left;">{{product.product_description}}</td>
                        <td style="text-align: right;">{{quantity[product.product_no]}}</td>
                        <td style="text-align: left;">{{product.unit}}</td>
                        <td style="text-align: right;">{{product.purchase_price}}</td>
                        <td style="text-align: right;">{{total_purchase_price[product.product_no] | number:2}}</td>
                    </tr>
                    
                <tr>
                    <th colspan="3" rowspan="3">
                        โปรดนำสำเนาใบสั่งซื้อมาทุกครั้ง<br>ที่ส่งสินค้าและวางบิล<br>(จะชำระราคาตามใบสั่งซื้อเท่านั้น)
                    </th>
                    <th colspan="2" style="text-align: right;">ราคาสินค้า</th>
                    <th colspan="1" style="text-align: right;">{{detail[0].po_total_purchase_no_vat | number:2}}</th>
                </tr>
                <tr>
                    <th colspan="2" style="text-align: right;">ภาษีมูลค่าเพิ่ม 7%</th>
                    <th colspan="1" style="text-align: right;">{{detail[0].po_total_purchase_vat | number:2}}</th>
                </tr>
                <tr>
                    <th colspan="2" style="text-align: right;">ราคารวมทั้งสิ้น</th>
                    <th colspan="1" style="text-align: right;">{{detail[0].po_total_purchase_price | number:2}}</th>
                </tr>                    
	
            </table>
           <br>
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
        
        $scope.CombinePO = function() {
            $scope.ProductList = [];
            $scope.quantity = {};
            $scope.total_purchase_price = {}; 
                 
            angular.forEach($scope.detail, function (value, key) {
                if(!(value.product_no in $scope.ProductList)) {
                    $scope.ProductList.push(value.product_no);
                    $scope.quantity[value.product_no] = 0;
                    $scope.total_purchase_price[value.product_no] = 0;
                }
            });
                        
            angular.forEach($scope.detail, function (value, key) {
                    $scope.quantity[value.product_no] += Number(value.quantity);
                    $scope.total_purchase_price[value.product_no] += Number(value.total_purchase_price);                   
            });
		}
       
    });
    
</script>