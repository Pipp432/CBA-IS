<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">Print Invoice</h2>
 
        <div class="mt-2 p-0">
            <table class="table table-hover my-1">
					<tr>
						<th style="text-align: center;">เลขที่ IV</th>
						<th style="text-align: center;">วันที่ที่ออก iv</th>
                        <th style="text-align: center;">ผู้ออกเอกสาร</th>
                        <th style="text-align: center;">ประเภท</th>
                        <th style="text-align: center;">ชื่อลูกค้า</th>
                        <th style="text-align: center;">address</th>
                        <th style="text-align: center;">contact</th>
                        <th style="text-align: center;">e-mail</th>
                        <th style="text-align: center;">ลูกค้าขอ IV</th>
						<th style="text-align: center;">เอกสาร IV CBA</th>
                        <th style="text-align: center;">Print แล้ว</th>
					</tr>
					<tr ng-repeat="inv in invoicess | unique:'invoice_no'">
						<td style="text-align: center;">{{inv.invoice_no}}</td>
						<td style="text-align: center;">{{inv.invoice_date}} {{inv.invoice_time}}</td>
                        <td style="text-align: center;">{{inv.approved_employee}}</td>
                        <td style="text-align: center;">{{inv.product_type}}</td>
                        <td style="text-align: center;">{{inv.customer_name}}</td>
                        <td style="text-align: center;">{{inv.customer_address}}</td> 
                        <td style="text-align: center;">{{inv.customer_tel}}</td> 
                        <td style="text-align: center;">{{inv.email}}</td> 
                        <td style="text-align: center;">{{inv.fin_form}}</td> 
                        <td style="text-align: center;"><button type="button" class="btn btn-info" target="_blank" ng-click = "seeIV(inv)">IVCR</button></td>
                        <td style="text-align: center;"><button type="button" class="btn btn-info" id="checkPrintIVbox" ng-click = "printivItems(inv.invoice_no)">Check </button></td>
					</tr>				
			</table>
            
            
        </div>

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
        
        $scope.pivItems = [];

		$scope.invoicess = <?php echo $this->invoicess; ?>

        $scope.printivItems = function(invoice_no) {
            $.post("/acc/print_iv/printivItems", {
                invoice_no: invoice_no
            }, function(data) {
                addModal('successModal', 'Print IV', 'Print IV succesful');
                $('#successModal').modal('toggle');
                $('#successModal').on('hide.bs.modal', function (e) { location.assign('/acc/dashboard') });  
            });
        }
     
        $scope.seeIV = function(file) {
			window.open('/file/iv_cr/' + file.invoice_no);
		}

    });

</script>