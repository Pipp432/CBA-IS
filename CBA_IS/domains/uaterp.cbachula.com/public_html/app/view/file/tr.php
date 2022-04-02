<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController" ng-init="getDetail()">

        <div class="row px-2 mt-2">
            <div class="col-4 pl-0 pr-3">
                <img src="/public/img/cba-logo.png" alt="cba-logo" style="width: 60%;">
            </div>
            <div class="col-4 px-0">
                <h3 style="text-align: center;"><b>รายการโอนเงิน<br>Transfer Report</b></h3>
            </div>
            <div class="col-4 px-0">
                <h5 style="text-align: right;"><b>เลขที่ {{tr_price[0].tr_no}}</b></h5>
                <h6 style="text-align: right;">วันที่ {{tr_price[0].tr_date}}</h6>
            </div>
        </div>
        
        <div class="row px-2 mt-2">
            <div class="col-12 px-0">
                <p class="my-0">
                    <b>ห้างหุ้นส่วนสามัญ บริษัทจำลองจุฬาลงกรณ์มหาวิทยาลัย 2565 </b><br>
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
                        <th>เริ่มที่ CR</th>
                        <th>สิ้นสุดที่ CR</th>
						<th rowspan="4"><b>ไปยัง</th>
						<th>บัญชี</th>
						<th>จำนวนเงิน</th>
                    </tr>
                    <tr>
                        <td>{{tr_range[0].min_cr}}</td>
						<td>{{tr_range[0].max_cr}}</td>
						<td>โครงการ1</td>
						<td>{{tr_price[0].tot1 | number:2}}</td>
                    </tr>
                    <tr>
                        <td>{{tr_range[1].min_cr}}</td>
						<td>{{tr_range[1].max_cr}}</td>
						<td>โครงการ2</td>
						<td>{{tr_price[0].tot2 | number:2}}</td>
                    </tr>
					<tr>
                        <td>{{tr_range[2].min_cr}}</td>
						<td>{{tr_range[2].max_cr}}</td>
						<td>โครงการ3</td>
						<td>{{tr_price[0].tot3 | number:2}}</td>
                    </tr>
                </table>
            </div>
        </div>  
		<h6 style="text-align: left;"><b>หมายเหตุ</b></h6>
        <div class="row px-2 mt-2">
            <table style="border-collapse: collapse; width: 100%;">
                <tr>
                    <th style="text-align: left;">CR</th>
                    <th>รายละเอียด</th>
                    <th style="text-align: right;">จำนวนเงิน</th>
                </tr>
                <tr ng-repeat="n in tr_note">
                    <td style="text-align: left;">{{n.cr_no}}</td>
                    <td style="text-align: center;">{{n.details}}</td>
                    <td style="text-align: right;">{{n.total_price | number:2}}</td>
                </tr>
                <tr>
                    <th colspan="2">รวม</th>
                    <th style="text-align: right;">{{tr_price[0].tot | number:2}}</th>
                </tr>
            </table>
			<hr>
			<div class="row" style="width: 100%">
				<div class="col" style="width: 100%; text-align: center;">
					<br>__________________________<br>ผู้อนุมัติ<br>วันที่ _____/_____/_____ 
				</div>
				<div class="col"  style="width: 100%; text-align: center;">
					<br>__________________________<br>ผู้นำฝาก<br>วันที่ _____/_____/_____ 
				</div>
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
			$scope.tr_price = <?php echo $this->tr_price; ?>;
			$scope.tr_range = <?php echo $this->tr_range; ?>;
			$scope.tr_note = <?php echo $this->tr_note; ?>;
        }
    });
    
</script>