<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">Dashboard IRD</h2>
        
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- PO -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- DOCUMENT -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="mt-2 p-0">
            <table class="table table-hover my-1">
					<tr>
						<th style="text-align: center;">เลข IRD</th>
						<th style="text-align: center;">วันที่ IRD</th>
						<th style="text-align: center;">ผู้ออก IRD</th>
						<th style="text-align: center;">จำนวนกล่อง</th>
						<th style="text-align: center;">Download SOX</th>
						<th style="text-align: center;">Download address</th>
					</tr>
					<tr ng-repeat="dashboard in dashboards | unique:'ird_no'" >
						<td ng-click="viewFile(dashboard)" style="text-align: center;">{{dashboard.ird_no}}</td>
						<td style="text-align: center;">{{dashboard.ird_date}}</td>
						<td style="text-align: center;">{{dashboard.approved_employee}}</td>
						<td style="text-align: center;">{{dashboard.box_count}}</td>
						<td style="text-align: center;"><button type="button" class="btn btn-info" ng-click = "getSOXinIRD(dashboard)">SOX</button>
</td>
						<td style="text-align: center;"><button type="button" class="btn btn-info" ng-click = "getAddress(dashboard)">address</button>
</td>
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
        
		$scope.dashboards = <?php echo $this->dashboards; ?>
        
        
        $scope.viewFile = function(file) {
			window.open('/file/ird/' + file.ird_no);
		}
		
		$scope.getSOXinIRD = function(file) {
			window.open('/scm/sox_in_ird/' + file.ird_no);
		}
        
        $scope.getAddress = function(file) {
			window.open('/scm/ird_download/' + file.ird_no);
		}
		
    });

</script>