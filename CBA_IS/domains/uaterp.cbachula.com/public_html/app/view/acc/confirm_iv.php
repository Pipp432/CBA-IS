<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">Confirm IV</h2>
        
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- PO -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- DOCUMENT -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="mt-2 p-0">
            <table class="table table-hover my-1">
					<tr>
						<th style="text-align: center;">เลขที่ IV</th>
						<th style="text-align: center;">วันที่ที่ออก iv</th>
                        <th style="text-align: center;">ชื่อผู้ออก iv</th>
                        <th style="text-align: center;">ประเภท</th>
						<th style="text-align: center;">Vat type</th>
						<th style="text-align: center;">เอกสาร IV CBA</th>
                        <th style="text-align: center;">สถานะ</th>
					</tr>
					<tr ng-repeat="inv in invoice | unique:'invoice_no'" >
						<td ng-click="viewFile(invoice)" style="text-align: center;">{{invoice.invoice_no}}</td>
						<td style="text-align: center;">{{invoice.invoice_date}}</td>
                        <td style="text-align: center;">{{invoice.employee_id}}</td>
						<td style="text-align: center;">{{invoice.vat_type}}</td>
						<td style="text-align: center;">{{invoice.product_type}}</td>
                        <td style="text-align: center;"><button type="button" class="btn btn-info" ng-click = "getAddress(dashboard)">Print IV</button>
                        </td>
                        <input type="checkbox" id="confirmIV" name="confirmIV" value="confirm"><label for="Confirm_iv"> Confirm</label><br>
						<!--<td style="text-align: center;"><button type="button" class="btn btn-info" ng-click = "getSOXinIRD(dashboard)">Confirm</button>
                        </td>-->
						
					</tr>				
			</table>
            
            
        </div>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

    </div>

</body>

</html>

<style>
    td { border-bottom: 1px solid lightgray; }
    th { border-bottom: 1px solid lightgray; text-align: left; }
    .card:hover { transform: translate(0,-4px); box-shadow: 0 4px 8px lightgrey; }
</style>

<script>

    app.controller('moduleAppController', function($scope, $http, $compile) {
        
		$scope.confirm_iv = <?php echo $this->confirm_iv; ?>
        
        $scope.getAddress = function(file) {
			window.open('/acc/iv_download/' + file.iv_no);
		}

        $scope.viewFile = function(file) {
			window.open('/file/iv/' + file.iv_no);
		}
		
    });

</script>