<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">Confirm Invoice</h2>
 
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
					<tr ng-repeat="inv in invoices | unique:'invoice_no'" ng-click="viewFile(inv)">
						<td style="text-align: center;">{{inv.invoice_no}}</td>
						<td style="text-align: center;">{{inv.invoice_date}} {{inv.invoice_time}}</td>
                        <td style="text-align: center;">{{inv.approved_employee}}</td>
                        <td style="text-align: center;">{{inv.product_type}}</td>
						<td style="text-align: center;">{{inv.vat_type}}</td>
                        <td style="text-align: center;"><button type="button" class="btn btn-info" ng-click = "printIV(inv)">ดูเอกสาร</button>
                        </td>
                        <td style="text-align: center;"><input type="button" id="ConfirmIVbox" ng-click = "conivItems()"> Confirm</td>
                         

                        <!-- //example
                        <label for="poNoTextbox">เลขที่ PO</label>
                        <input type="text" class="form-control" id="poNoTextbox" ng-model="poNo" ng-change="getPo()" style="text-transform:uppercase"> -->
                    
                        <!-- <td input type="checkbox" id="confirmIV" name="confirmIV" value="confirm" > Confirm<br></td> -->
						<!--<td style="text-align: center;"><button type="button" class="btn btn-info" ng-click = "getSOXinIRD(dashboard)">Confirm</button>
                        </td>-->
						
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
        
        $scope.civItems = [];

		$scope.invoices = <?php echo $this->invoices; ?>

        $scope.conivItems = function() {
            console.log("check")
            if ($('#ConfirmIVbox').is(":checked"))
            {
                // $('#confirmModal').modal('hide');
                $.post("/acc/confirm_iv/conivItems", {
                    civItems : JSON.stringify(angular.toJson($scope.civItems))
				
				
                }, function(data) {
                    addModal('successModal', 'Confirm IV', 'Confirm IV successful');
                    $('#successModal').modal('toggle');
                    $('#successModal').on('hide.bs.modal', function (e) {
                        window.location.assign('/');
                });
            });
            }
        }

        // if ($('ConfirmIVbox').is(":checked"))
        // {
        //   // it is checked
        // }

        //console.log($scope.invoices);      
        
        // $scope.tickConfirm = function(file) {
		// 	window.open('/acc/iv_download/' + file.iv_no);
		// }

        

        $scope.viewFile = function(file) {
			window.open('/file/iv/' + file.iv_no);
		}

        //$scope.viewFile2 = function(file) {
		// 	window.open('/file/iv_rc/' + file.iv_no);
		// }

        $scope.printIV = function(file) {
		    window.open('/file/iv/'+ data.substring(0, 9));
            location.reload();
		}
        
		
    });

</script>