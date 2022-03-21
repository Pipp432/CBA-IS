<!DOCTYPE html>
<html>
<script type="text/javascript">
	function Toggle() {
	var x = document.getElementById("printing");
	var y = document.getElementById("dot_printing");
	if (x.style.display === "none") {
		x.style.display = "block";
		y.style.display = "none";
	} else {
		y.style.display = "block";
		x.style.display = "none";
	 }
	}
	function Hide() {
	var z = document.getElementById("PrintMode");
		z.style.display = "none";
	}
	function Show() {
	var z = document.getElementById("PrintMode");
	if (z.style.display === "none") {
		z.style.display = "block";
	}else {
		z.style.display = "none";
	 }
	}
</script>
<body>
<!--<div class="container mt-3" id="PrintMode" style="width: 100%; text-align: right; padding-top: 20px;padding-bottom:20px">
    <button id="hide" type="button" class="btn btn-secondary" onclick="Hide()">Hide</button>
    <button id="PrintMode" type="button" class="btn btn-primary" onclick="Toggle()">Toggle Print Mode</button>
</div>-->
<div id="printing" onclick="Show()">
    <div class="container mt-3" ng-controller="moduleAppController" ng-init="getDetail()">

        <div class="row px-2 mt-2">
            <div class="col-4 pl-0 pr-3">
                <img src="/public/img/cba-logo.png" alt="cba-logo" style="width: 60%;">
            </div>
            <div class="col-4 px-0">
                <h3 style="text-align: center;"><b>ใบกำกับภาษี<br>Tax Invoice</b></h3>
            </div>
            <div class="col-4 px-0">
                <h5 style="text-align: right;">สำเนา</h5>
                <h5 style="text-align: right;"><b>เลขที่ {{detail[0].invoice_no}}</b></h5>
                <h6 style="text-align: right;">วันที่ {{detail[0].invoice_date}}</h6>
                <h6 style="text-align: right; font-size: 16px;">เอกสารออกเป็นชุด</h6>
            </div>
        </div>
        
        <div class="row px-2 mt-2">
            <div class="col-12 px-0">
                <p class="my-0">
                    <b>ห้างหุ้นส่วนสามัญ บริษัทจำลองจุฬาลงกรณ์มหาวิทยาลัย 2564 (โครงการ {{company}})</b><br>
                    อาคารไชยยศสมบัติ 1 เลขที่ 254 ชั้นใต้ดิน ถนนพญาไท แขวงวังใหม่ เขตปทุมวัน กรุงเทพมหานคร 10330<br>
                    โทร. 0-2218-5746-9 โทรสาร. 0-2218-5762<br>
					เลขประจำตัวผู้เสียภาษี {{company_id}} (สำนักงานใหญ่)
                </p>
            </div>
        </div>  
        
        <hr>

        <div class="row px-2 mt-2">
            <div class="col-12 px-0">
                <table style="border-collapse: collapse; width: 100%;">
                    <tr>
                        <td><b>ชื่อลูกค้า</b> {{detail[0].customer_name}}</td>
                        <td><b>รหัสพนักงาน</b> {{detail[0].employee_id}}</td>
                    </tr>
                    <tr>
                        <td colspan="2"><b>ที่อยู่</b> {{detail[0].customer_address}}</td>
                    </tr>
                    <tr>
                        <td colspan="2"><b>เลขประจำตัวผู้เสียภาษี</b> {{detail[0].id_no}}</td>
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
                    <th>จำนวน</th>
                    <th>หน่วย</th>
                    <th>ราคา/หน่วย<p>รวมภาษีมูลค่าเพิ่ม</p></th>
                    <th>จำนวนเงิน</th>
                </tr>
                <tr ng-repeat="item in detail">
                    <td style="text-align: center;">{{$index+1}}</td>
                    <td style="text-align: center;">{{item.product_no}}</td>
                    <td style="text-align: left;">{{item.product_name}}</td>
                    <td style="text-align: right;">{{item.quantity}}</td>
                    <td style="text-align: left;">{{item.unit}}</td>
                    <td style="text-align: right;">{{item.sales_price | number:2}}</td>
                    <td style="text-align: right;">{{item.total_sales_price | number:2}}</td>
                </tr>
                <tr>
                    <th colspan="2">
                        จำนวนเงิน (ตัวอักษร)
                    </th>
                    <th colspan="2" style="text-align: left;">
                        {{detail[0].sales_price_thai}}
                    </th>
                    <th colspan="2" style="text-align: right;">ส่วนลด</th>
                    <th colspan="1" style="text-align: right;">{{detail[0].discount | number:2}}</th>
                </tr>
                <tr>
                    <th colspan="4" rowspan="3">
                        <div class="row">
                            <div class="col-4">
                                <br>ได้รับสินค้าตามรายการ<br>ถูกต้องและเรียบร้อยแล้ว
                            </div>
                            <div class="col-4">
                                <div class="row">
									<div class="col">
										<img ng-src="/public/img/acc_sign.jpg" style="width: 30%; " />
									</div>
								</div>
								<div class="row" style="margin-top: -5%">
									<div class="col">
										__________________________<br>บัญชีผู้ออกเอกสาร<br>วันที่ {{day}}/{{month}}/{{year}}
									</div>
								</div>
                            </div>
                            <div class="col-4" style="margin-top: 3%">
                                __________________________<br>ผู้รับสินค้า<br>วันที่ _____/_____/_____ 
                            </div>
                        </div>
                    </th>
                    <th colspan="2" style="text-align: right;">มูลค่าสินค้า/บริการ</th>
                    <th colspan="1" style="text-align: right;">{{detail[0].invoice_total_purchase_no_vat | number:2}}</th>
                </tr>
                <tr>
                    <th colspan="2" style="text-align: right;">ภาษีมูลค่าเพิ่ม 7%</th>
                    <th colspan="1" style="text-align: right;">{{detail[0].invoice_total_sales_vat | number:2}}</th>
                </tr>
                <tr>
                    <th colspan="2" style="text-align: right;">จำนวนเงินรวม</th>
                    <th colspan="1" style="text-align: right;">{{detail[0].invoice_total_sales_price | number:2}}</th>
                </tr>
            </table>
        </div> 
    
    </div>
</div>

<div style="font-size:18px; margin-top: -20px; display:none" id="dot_printing" onclick="Show()">
<div class="container mt-3" ng-controller="moduleAppController" ng-init="getDetail()">
		<div class="row">
            <div class="col">
                <div style="text-align: right;">{{detail[0].file_no}}</div>
            </div>
        <div class="row" style="height: 100px"></div>
        </div>
        <div class="row">
			<div class="col-1"></div>
            <div class="col-6">{{detail[0].customer_name}}</div>
            <div class="col">{{detail[0].employee_id}}</div>
            <div class="col">
                <div style="text-align: right;">{{detail[0].invoice_no}}</div>
            </div>
        </div>
		<div class="row" style="height: 17px"></div>
        <div class="row">
			<div class="col-1"></div>
            <div class="col-8">{{detail[0].customer_address}}</div>
            <div class="col">
                <div style="text-align: right;">{{detail[0].invoice_date}}</div>
            </div>
        </div>
		<div class="row" style="height: 20px"></div>
		<div class="row">
			<div class="col-3"></div>
            <div class="col">{{detail[0].id_no}}</div>
            <div class="col"></div>
        </div>
		<div class="row" style="height: 70px"></div>
		<div class="row" style="width: 100%; height: 190px; vertical-align: text-top;">
			<div class="row" ng-repeat="item in detail" style="width: 100%">
				<div class="col" style="text-align: center;">{{$index+1}}</div>
				<div class="col-2" style="text-align: center;">{{item.product_no}}</div>
				<div class="col-5" style="text-align: left;">{{item.product_name}}</div>
				<div class="col" style="text-align: right;">{{item.quantity}}</div>
				<div class="col" style="text-align: left;">{{item.unit}}</div>
				<div class="col" style="text-align: right;"><div style="margin-right:-50px">{{item.sales_price | number:2}}</div></div>
				<div class="col-2" style="text-align: right;">{{item.total_sales_price | number:2}}</div>
			</div>
        </div>
		<div class="row" style="width: 100%">
			<div class="col-1"></div>
			<div class="col-6" style="text-align: left;">{{detail[0].sales_price_thai}}</div>
			<div class="col-3"></div>
			<div class="col" style="text-align: right;">{{detail[0].discount | number:2}}</div>
		</div>
		<div class="row" style="height: 15px"></div>
		<div class="row" style="width: 100%">
			<div class="col-2"></div>
			<div class="col-6" style="text-align: left;">วันที่ครบ</div>
			<div class="col-2"></div>
			<div class="col" style="text-align: right;">{{detail[0].invoice_total_purchase_no_vat | number:2}}</div>
		</div>
		<div class="row" style="height: 15px"></div>
		<div class="row" style="width: 100%">
			<div class="col-2"></div>
			<div class="col-5" style="text-align: left;"></div>
			<div class="col-3"></div>
			<div class="col" style="text-align: right;">{{detail[0].invoice_total_sales_vat | number:2}}</div>
		</div>
		<div class="row" style="height: 12px"></div>
		<div class="row" style="width: 100%">
			<div class="col-10"></div>
			<div class="col" style="text-align: right;">{{detail[0].invoice_total_sales_price | number:2}}</div>
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
            $scope.detail = <?php echo $this->iv; ?>;
            $scope.company = $scope.detail[0].invoice_no.substring(0,1);
			$scope.year = $scope.detail[0].invoice_date.substring(0,4);
			$scope.month = $scope.detail[0].invoice_date.substring(5,7);
			$scope.day = $scope.detail[0].invoice_date.substring(8,10);
            switch($scope.company) {
                case '1': $scope.company_id = '0-9920-04145-63-5'; break;
                case '2': $scope.company_id = '0-9920-04145-64-3'; break;
                case '3': $scope.company_id = '0-9920-04145-65-1'; break;
                default: $scope.company_id = 'XXX'; break;
            }
        }
    });
    
</script>